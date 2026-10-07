<?php

namespace App\Services;

use App\Models\Enrollment;
use App\Models\PaymentPlan;
use App\Models\PaymentTransaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentService
{
    /**
     * Menentukan aturan setoran berikutnya untuk jamaah (Otomatis)
     */
    public function getNextPaymentRules(Enrollment $enrollment): array
    {
        if (! $enrollment->paymentPlan) {
            return [
                'is_first_payment' => true,
                'min_amount' => 1000000,
                'payment_type' => 'initial_deposit',
                'label_saran' => 'Wajib Setoran Awal Min. Rp 1.000.000',
            ];
        }

        // 💡 PERBAIKAN: Gembok hanya terbuka jika statusnya WAJIB 'verified'
        $hasVerifiedInitialDeposit = $enrollment->paymentPlan->transactions()
            ->where('type', 'initial_deposit')
            ->where('status', 'verified') // Hapus kata 'pending' dari sini
            ->exists();

        return [
            'is_first_payment' => ! $hasVerifiedInitialDeposit,
            'min_amount' => $hasVerifiedInitialDeposit ? 500000 : 1000000,
            'payment_type' => $hasVerifiedInitialDeposit ? 'additional_payment' : 'initial_deposit',
            'label_saran' => $hasVerifiedInitialDeposit ? 'Min. Rp 500.000' : 'Wajib Setoran Awal Min. Rp 1.000.000',
        ];
    }

    public function calculateMidtransFee(float $amount, ?string $midtransChannel = null): array
    {
        $feeRate = (float) config('services.midtrans.default_fee_rate', 0.008);

        if ($feeRate <= 0) {
            $feeRate = 0.008;
        }

        $feeAmount = round((float) $amount * $feeRate, 2);
        $netAmount = round((float) $amount, 2);
        $grossAmount = round((float) $amount + $feeAmount, 2);

        return [
            'fee_rate' => $feeRate,
            'fee_amount' => $feeAmount,
            'net_amount' => $netAmount,
            'gross_amount' => $grossAmount,
        ];
    }

    /**
     * Membuat transaksi pembayaran & Request Token Midtrans
     */
    public function createPayment(
        Enrollment $enrollment,
        float $amount,
        string $type,
        string $paymentMethod = 'manual_transfer',
        ?string $referenceNumber = null,
        ?string $proofFile = null,
        ?string $midtransChannel = null
    ): PaymentTransaction {
        return DB::transaction(function () use ($enrollment, $amount, $type, $paymentMethod, $referenceNumber, $proofFile, $midtransChannel) {

            $paymentPlan = $enrollment->paymentPlan()->lockForUpdate()->first();

            if (! $paymentPlan) {
                throw new \RuntimeException('Payment plan tidak ditemukan.');
            }

            if ($amount <= 0) {
                throw new \InvalidArgumentException('Nominal pembayaran harus lebih dari Rp0.');
            }

            $rules = $this->getNextPaymentRules($enrollment);

            if ($amount < $rules['min_amount']) {
                $formatRupiah = 'Rp '.number_format($rules['min_amount'], 0, ',', '.');
                $pesanError = $rules['is_first_payment']
                    ? "Pembayaran ditolak! Setoran awal (DP) wajib minimal {$formatRupiah}."
                    : "Nominal kurang! Setoran tabungan lanjutan minimal adalah {$formatRupiah}.";

                throw new \InvalidArgumentException($pesanError);
            }

            if ($type === 'initial_deposit') {
                $hasInitialDeposit = $paymentPlan->transactions()
                    ->where('type', 'initial_deposit')
                    ->whereIn('status', ['pending', 'verified'])
                    ->exists();

                if ($hasInitialDeposit) {
                    throw new \RuntimeException('Setoran awal sedang diproses admin atau sudah lunas. Harap tunggu verifikasi selesai.');
                }
            }

            if ($type === 'additional_payment') {
                $hasVerifiedInitial = $paymentPlan->transactions()
                    ->where('type', 'initial_deposit')
                    ->where('status', 'verified')
                    ->exists();

                if (! $hasVerifiedInitial) {
                    throw new \RuntimeException('Pembayaran ditolak! Anda wajib melunasi Setoran Awal terlebih dahulu.');
                }
            }

            $feeDetails = [
                'fee_rate' => 0.0,
                'fee_amount' => 0.0,
                'net_amount' => (float) $amount,
                'gross_amount' => (float) $amount,
            ];

            if ($paymentMethod === 'midtrans') {
                $feeDetails = $this->calculateMidtransFee($amount, $midtransChannel);
            }

            $transaction = PaymentTransaction::create([
                'payment_plan_id' => $paymentPlan->id,
                'transaction_number' => $this->generateTransactionNumber(),
                'type' => $type,
                'amount' => (float) $amount,
                'payment_method' => $paymentMethod,
                'midtrans_channel' => $paymentMethod === 'midtrans' ? strtolower(trim((string) ($midtransChannel ?? 'bank_transfer'))) : null,
                'fee_amount' => $feeDetails['fee_amount'],
                'fee_rate' => $feeDetails['fee_rate'],
                'net_amount' => $feeDetails['net_amount'],
                'reference_number' => $referenceNumber,
                'proof_file' => $proofFile,
                'status' => 'pending',
            ]);

            if ($paymentMethod === 'midtrans') {
                Config::$serverKey = config('services.midtrans.server_key');
                Config::$isProduction = config('services.midtrans.is_production', false);
                Config::$isSanitized = true;
                Config::$is3ds = true;

                // Each website can override the shared Midtrans account's webhook URL.
                $notificationUrl = config('services.midtrans.notification_url')
                    ?: route('api.midtrans.notification');

                Config::$curlOptions = [
                    CURLOPT_HTTPHEADER => [
                        'X-Override-Notification: '.$notificationUrl,
                    ],
                ];

                $grossAmount = (int) round((float) ($transaction->net_amount + $transaction->fee_amount), 0);

                $params = [
                    'transaction_details' => [
                        'order_id' => $transaction->transaction_number,
                        'gross_amount' => $grossAmount,
                    ],
                    'customer_details' => [
                        'first_name' => $enrollment->customer->name ?? 'Jamaah',
                        'phone' => $enrollment->customer->phone,
                        'email' => $enrollment->customer->email,
                    ],
                    'expiry' => [
                        'unit' => config('services.midtrans.expiry_unit'),
                        'duration' => config('services.midtrans.expiry_duration'),
                    ],
                ];

                $snapToken = Snap::getSnapToken($params);
                $transaction->snap_token = $snapToken;
                $transaction->save();
            }

            return $transaction;
        });
    }

    /**
     * Admin melakukan verifikasi pembayaran.
     */
    public function verifyPayment(PaymentTransaction $transaction, User $admin): PaymentTransaction
    {
        return DB::transaction(function () use ($transaction, $admin) {

            // 🔒 ROW LOCK: Gunakan $lockedTx untuk SEMUA pengecekan dan update
            $lockedTx = PaymentTransaction::where('id', $transaction->id)->lockForUpdate()->first();

            if ($lockedTx->status !== 'pending') {
                throw new \RuntimeException('Pembayaran tidak berada dalam status pending.');
            }

            $lockedTx->update([
                'status' => 'verified',
                'paid_at' => $lockedTx->paid_at ?? now(),
                'verified_at' => now(),
                'verified_by' => $admin->id,
            ]);

            // Pemicu otomatis pembaruan status enrollment
            $this->refreshEnrollmentStatus($lockedTx->paymentPlan->enrollment);

            return $lockedTx->fresh(['paymentPlan']);
        });
    }

    /**
     * Admin menolak pembayaran.
     */
    public function rejectPayment(PaymentTransaction $transaction, User $admin, string $reason): PaymentTransaction
    {
        // 🔒 ROW LOCK: Harus dibungkus di dalam DB::transaction agar tidak error
        return DB::transaction(function () use ($transaction, $admin, $reason) {

            $lockedTx = PaymentTransaction::where('id', $transaction->id)->lockForUpdate()->first();

            if ($lockedTx->status !== 'pending') {
                throw new \RuntimeException('Pembayaran tidak berada dalam status pending.');
            }

            if (trim($reason) === '') {
                throw new \InvalidArgumentException('Alasan penolakan wajib diisi.');
            }

            $lockedTx->update([
                'status' => 'rejected',
                'verified_by' => $admin->id,
                'verified_at' => now(),
                'rejection_reason' => $reason,
            ]);

            return $lockedTx->fresh();
        });
    }

    public function verifiedTotal(PaymentPlan $paymentPlan): float
    {
        return (float) $paymentPlan->transactions()
            ->where('status', 'verified')
            ->sum(DB::raw('COALESCE(net_amount, amount)'));
    }

    public function enrollmentPaidTotal(Enrollment $enrollment): float
    {
        if (! $enrollment->paymentPlan) {
            return 0;
        }

        return $this->verifiedTotal($enrollment->paymentPlan);
    }

    public function outstandingAmount(Enrollment $enrollment): float
    {
        $paymentPlan = $enrollment->paymentPlan;
        if (! $paymentPlan) {
            return 0;
        }

        $target = $paymentPlan->final_target_amount ?? $paymentPlan->estimated_target_amount;
        if ($target === null) {
            return 0;
        }

        $paid = $this->verifiedTotal($paymentPlan);

        return max((float) $target - $paid, 0);
    }

    /**
     * 🚀 MEMPERBARUI STATUS ENROLLMENT SECARA OTOMATIS
     */
    public function refreshEnrollmentStatus(Enrollment $enrollment): Enrollment
    {
        $paymentPlan = $enrollment->paymentPlan;
        if (! $paymentPlan) {
            return $enrollment;
        }

        $totalPaid = $this->verifiedTotal($paymentPlan);
        $target = $paymentPlan->final_target_amount ?? $paymentPlan->estimated_target_amount;

        if ($target !== null && $totalPaid >= (float) $target) {
            if ($paymentPlan->final_target_amount === null) {
                if (in_array($enrollment->status, ['enrolled', 'saving'], true)) {
                    $enrollment->update([
                        'status' => 'funds_sufficient',
                        'funds_sufficient_at' => now(),
                    ]);
                }
            } else {
                if (in_array($enrollment->status, ['price_confirmed', 'payment_due', 'overdue'], true)) {
                    $enrollment->update(['status' => 'fully_paid']);
                    $paymentPlan->update(['status' => 'fully_paid']);
                }
            }
        } else {
            // 💡 PERBAIKAN DI SINI:
            // Status hanya berubah ke 'saving' JIKA sudah ada uang masuk (> 0)
            if ($totalPaid > 0 && in_array($enrollment->status, ['enrolled', 'funds_sufficient'], true)) {
                $enrollment->update(['status' => 'saving']);
            }

            if ($paymentPlan->final_target_amount !== null) {
                $paymentPlan->update(['status' => 'finalized']);
            }
        }

        return $enrollment->fresh();
    }

    protected function generateTransactionNumber(): string
    {
        do {
            $date = now()->format('ymd');
            $random = random_int(1000, 9999);

            $number = 'PUH-'.$date.'-'.$random;
        } while (PaymentTransaction::where('transaction_number', $number)->exists());

        return $number;
    }

    /**
     * Memproses notifikasi webhook dari Midtrans
     */
    public function handleMidtransWebhook(array $payload): void
    {
        $orderId = $payload['order_id'] ?? null;
        $status = $payload['transaction_status'] ?? null;
        $fraud = $payload['fraud_status'] ?? null;
        $grossAmount = $payload['gross_amount'] ?? null;

        if (! $orderId) {
            throw new \InvalidArgumentException('No order ID provided');
        }

        // 🔒 PERBAIKAN: Bungkus SELURUH proses webhook ke dalam DB Transaction & Lock
        DB::transaction(function () use ($orderId, $status, $fraud, $grossAmount) {

            // 🔒 ROW LOCK: Kunci transaksi agar tidak dieksekusi ganda oleh retry webhook
            $transaction = PaymentTransaction::where('transaction_number', $orderId)
                ->lockForUpdate()
                ->first();

            if (! $transaction) {
                throw new \RuntimeException('Transaction not found');
            }

            // 🔒 PRESISI DECIMAL ABSOLUT (Mencegah floating-point bypass)
            if ($grossAmount !== null) {
                $expectedGrossAmount = bcadd((string) $transaction->net_amount, (string) $transaction->fee_amount, 2);

                if (bccomp($expectedGrossAmount, (string) $grossAmount, 2) !== 0) {
                    Log::critical('⚠️ UPAYA MANIPULASI NOMINAL', [
                        'order_id' => $orderId,
                        'db' => $transaction->net_amount,
                        'fee' => $transaction->fee_amount,
                        'midtrans' => $grossAmount,
                    ]);
                    throw new \InvalidArgumentException('Pembayaran ditolak: Nominal tidak sesuai.');
                }
            }

            // Jika sudah diproses, hentikan tanpa error
            if (in_array($transaction->status, ['verified', 'expired', 'rejected'])) {
                return;
            }

            if ($status == 'capture') {
                if ($fraud == 'challenge') {
                    $transaction->update(['status' => 'pending']);
                } elseif ($fraud == 'accept') {
                    $this->markAsVerified($transaction);
                }
            } elseif ($status == 'settlement') {
                $this->markAsVerified($transaction);
            } elseif (in_array($status, ['deny', 'expire', 'cancel'])) {
                $transaction->update(['status' => 'expired']);
            }
        });
    }

    /**
     * Memverifikasi transaksi dan mengirim WA
     */
    protected function markAsVerified(PaymentTransaction $transaction): void
    {
        $transaction->update([
            'status' => 'verified',
            'paid_at' => now(),
            'verified_at' => now(),
        ]);

        // Refresh total saldo tabungan jamaah
        $this->refreshEnrollmentStatus($transaction->paymentPlan->enrollment);

        // --- FITUR NOTIFIKASI WA GRATIS ---
        try {
            $enrollment = $transaction->paymentPlan->enrollment ?? null;
            $jamaah = $enrollment ? ($enrollment->user ?? $enrollment->customer) : null;

            $phone = null;
            $nama = 'Jamaah';

            if ($jamaah) {
                $nama = $jamaah->name ?? $jamaah->nama ?? 'Jamaah';
                $phone = $jamaah->phone ?? $jamaah->phone_number ?? null;

                if (! $phone && isset($jamaah->customer)) {
                    $phone = $jamaah->customer->phone ?? $jamaah->customer->phone_number ?? null;
                }
            }

            if ($phone) {
                $nominal = number_format($transaction->amount, 0, ',', '.');
                $pesan = "Assalamu'alaikum Bpk/Ibu *{$nama}*,\n\n";
                $pesan .= "Alhamdulillah, setoran tabungan Umroh Anda sebesar *Rp {$nominal}* telah TERVERIFIKASI.\n\n";
                $pesan .= 'Terima kasih telah mempercayakan perjalanan ibadah Anda bersama Hanania. 🤲✨';

                // \App\Services\WhatsAppService::sendMessage($phone, $pesan);
            } else {
                Log::warning('Notif WA Batal: Gagal menemukan nomor HP untuk transaksi '.$transaction->transaction_number);
            }
        } catch (\Exception $e) {
            Log::error('Notif WA gagal: '.$e->getMessage());
        }
    }
}
