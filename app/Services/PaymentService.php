<?php

namespace App\Services;

use App\Models\Enrollment;
use App\Models\PaymentPlan;
use App\Models\PaymentTransaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Str;

class PaymentService
{
    /**
     * Menentukan aturan setoran berikutnya untuk jamaah (Otomatis)
     */
    public function getNextPaymentRules(Enrollment $enrollment): array
    {
        if (!$enrollment->paymentPlan) {
            return [
                'is_first_payment' => true,
                'min_amount'       => 1000000,
                'payment_type'     => 'initial_deposit',
                'label_saran'      => 'Wajib Setoran Awal Min. Rp 1.000.000'
            ];
        }

        // 💡 PERBAIKAN: Gembok hanya terbuka jika statusnya WAJIB 'verified'
        $hasVerifiedInitialDeposit = $enrollment->paymentPlan->transactions()
            ->where('type', 'initial_deposit')
            ->where('status', 'verified') // Hapus kata 'pending' dari sini
            ->exists();

        return [
            'is_first_payment' => !$hasVerifiedInitialDeposit,
            'min_amount'       => $hasVerifiedInitialDeposit ? 500000 : 1000000,
            'payment_type'     => $hasVerifiedInitialDeposit ? 'additional_payment' : 'initial_deposit',
            'label_saran'      => $hasVerifiedInitialDeposit ? 'Min. Rp 500.000' : 'Wajib Setoran Awal Min. Rp 1.000.000'
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
        ?string $proofFile = null
    ): PaymentTransaction {
        // 🛡️ Bungkus dengan Database Transaction & Row Locking untuk mencegah Race Condition
        return DB::transaction(function () use ($enrollment, $amount, $type, $paymentMethod, $referenceNumber, $proofFile) {
            
            // Kunci baris payment_plan agar aman dari double-submission bersamaan
            $paymentPlan = $enrollment->paymentPlan()->lockForUpdate()->first();

            if (!$paymentPlan) {
                throw new \RuntimeException('Payment plan tidak ditemukan.');
            }

            if ($amount <= 0) {
                throw new \InvalidArgumentException('Nominal pembayaran harus lebih dari Rp0.');
            }

            // ==========================================
            // 🛡️ VALIDASI MINIMAL DEPOSIT & SETORAN
            // ==========================================
            $rules = $this->getNextPaymentRules($enrollment);
            
            if ($amount < $rules['min_amount']) {
                $formatRupiah = 'Rp ' . number_format($rules['min_amount'], 0, ',', '.');
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

                if (!$hasVerifiedInitial) {
                    throw new \RuntimeException('Pembayaran ditolak! Anda wajib melunasi Setoran Awal terlebih dahulu.');
                }
            }

            // 1. Buat Data Transaksi di Database
            $transaction = PaymentTransaction::create([
                'payment_plan_id'    => $paymentPlan->id,
                'transaction_number' => $this->generateTransactionNumber(),
                'type'               => $type,
                'amount'             => $amount,
                'payment_method'     => $paymentMethod,
                'reference_number'   => $referenceNumber,
                'proof_file'         => $proofFile,
                'status'             => 'pending',
            ]);

            // 2. JIKA METODE MIDTRANS
            if ($paymentMethod === 'midtrans') {
                Config::$serverKey = env('MIDTRANS_SERVER_KEY');
                Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
                Config::$isSanitized = true;
                Config::$is3ds = true;

                $urlNgrokAktif = 'https://baton-drizzle-tameness.ngrok-free.dev'; 
                $notificationUrl = $urlNgrokAktif . '/api/midtrans/notification';
                
                Config::$curlOptions = [
                    CURLOPT_HTTPHEADER => [
                        'X-Override-Notification: ' . $notificationUrl
                    ]
                ];
                
                $params = [
                    'transaction_details' => [
                        'order_id'     => $transaction->transaction_number, 
                        'gross_amount' => (int) $transaction->amount,
                    ],
                    'customer_details' => [
                        'first_name' => $enrollment->customer->name ?? 'Jamaah',
                        'phone'      => $enrollment->customer->phone ?? '08123456789',
                        'email'      => $enrollment->customer->email ?? 'jamaah@hananiatravel.com', 
                    ],
                    'expiry' => [
                        'unit'     => 'minute',
                        'duration' => 5,
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
            if ($transaction->status !== 'pending') {
                throw new \RuntimeException('Pembayaran tidak berada dalam status pending.');
            }

            $transaction->update([
                'status'      => 'verified',
                'paid_at'     => $transaction->paid_at ?? now(),
                'verified_at' => now(),
                'verified_by' => $admin->id,
            ]);

            // Pemicu otomatis pembaruan status enrollment
            $this->refreshEnrollmentStatus($transaction->paymentPlan->enrollment);

            return $transaction->fresh(['paymentPlan']);
        });
    }

    /**
     * Admin menolak pembayaran.
     */
    public function rejectPayment(PaymentTransaction $transaction, User $admin, string $reason): PaymentTransaction
    {
        if ($transaction->status !== 'pending') {
            throw new \RuntimeException('Pembayaran tidak berada dalam status pending.');
        }

        if (trim($reason) === '') {
            throw new \InvalidArgumentException('Alasan penolakan wajib diisi.');
        }

        $transaction->update([
            'status'           => 'rejected',
            'verified_by'      => $admin->id,
            'verified_at'      => now(),
            'rejection_reason' => $reason,
        ]);

        return $transaction->fresh();
    }

    public function verifiedTotal(PaymentPlan $paymentPlan): float
    {
        return (float) $paymentPlan->transactions()->where('status', 'verified')->sum('amount');
    }

    public function enrollmentPaidTotal(Enrollment $enrollment): float
    {
        if (!$enrollment->paymentPlan) return 0;
        return $this->verifiedTotal($enrollment->paymentPlan);
    }

    public function outstandingAmount(Enrollment $enrollment): float
    {
        $paymentPlan = $enrollment->paymentPlan;
        if (!$paymentPlan) return 0;

        $target = $paymentPlan->final_target_amount ?? $paymentPlan->estimated_target_amount;
        if ($target === null) return 0;

        $paid = $this->verifiedTotal($paymentPlan);
        return max((float) $target - $paid, 0);
    }

    /**
     * 🚀 MEMPERBARUI STATUS ENROLLMENT SECARA OTOMATIS
     */
    public function refreshEnrollmentStatus(Enrollment $enrollment): Enrollment
    {
        $paymentPlan = $enrollment->paymentPlan;
        if (!$paymentPlan) return $enrollment;

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
            
            $number = 'PUH-' . $date . '-' . $random;
        } while (PaymentTransaction::where('transaction_number', $number)->exists());

        return $number;
    }

    /**
     * Memproses notifikasi webhook dari Midtrans
     */
    public function handleMidtransWebhook(array $payload): void
    {
        $orderId = $payload['order_id'] ?? null;
        $status  = $payload['transaction_status'] ?? null;
        $fraud   = $payload['fraud_status'] ?? null;

        if (!$orderId) {
            throw new \InvalidArgumentException('No order ID provided');
        }

        $transaction = \App\Models\PaymentTransaction::where('transaction_number', $orderId)->first();

        if (!$transaction) {
            throw new \RuntimeException('Transaction not found');
        }

        // Jika sudah diproses, abaikan
        if (in_array($transaction->status, ['verified', 'expired', 'rejected'])) {
            return;
        }

        DB::transaction(function () use ($transaction, $status, $fraud) {
            if ($status == 'capture') {
                if ($fraud == 'challenge') {
                    $transaction->update(['status' => 'pending']);
                } else if ($fraud == 'accept') {
                    $this->markAsVerified($transaction);
                }
            } else if ($status == 'settlement') {
                $this->markAsVerified($transaction);
            } else if (in_array($status, ['deny', 'expire', 'cancel'])) {
                $transaction->update(['status' => 'expired']);
            }
        });
    }

    /**
     * Memverifikasi transaksi dan mengirim WA
     */
    protected function markAsVerified(\App\Models\PaymentTransaction $transaction): void
    {
        $transaction->update([
            'status'      => 'verified',
            'paid_at'     => now(),
            'verified_at' => now(),
        ]);

        // Refresh total saldo tabungan jamaah
        $this->refreshEnrollmentStatus($transaction->paymentPlan->enrollment);

        // --- FITUR NOTIFIKASI WA GRATIS ---
        try {
            $enrollment = $transaction->paymentPlan->enrollment ?? null;
            $jamaah = $enrollment ? ($enrollment->user ?? $enrollment->customer) : null;
            
            $phone = null;
            $nama  = 'Jamaah';

            if ($jamaah) {
                $nama = $jamaah->name ?? $jamaah->nama ?? 'Jamaah';
                $phone = $jamaah->phone ?? $jamaah->phone_number ?? null;
                
                if (!$phone && isset($jamaah->customer)) {
                    $phone = $jamaah->customer->phone ?? $jamaah->customer->phone_number ?? null;
                }
            }

            if ($phone) {
                $nominal = number_format($transaction->amount, 0, ',', '.');
                $pesan  = "Assalamu'alaikum Bpk/Ibu *{$nama}*,\n\n";
                $pesan .= "Alhamdulillah, setoran tabungan Umroh Anda sebesar *Rp {$nominal}* telah TERVERIFIKASI.\n\n";
                $pesan .= "Terima kasih telah mempercayakan perjalanan ibadah Anda bersama Hanania Travel. 🤲✨";

                // \App\Services\WhatsAppService::sendMessage($phone, $pesan);
            } else {
                \Illuminate\Support\Facades\Log::warning("Notif WA Batal: Gagal menemukan nomor HP untuk transaksi " . $transaction->transaction_number);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Notif WA gagal: " . $e->getMessage());
        }
    }
}