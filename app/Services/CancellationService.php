<?php

namespace App\Services;

use App\Models\CancellationRequest;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CancellationService
{
    public function request(
        Enrollment $enrollment,
        string $type,
        ?string $reason = null,
        ?string $supportingDocument = null
    ): CancellationRequest {
        if (in_array(
            $enrollment->status,
            ['departed', 'completed', 'cancelled'],
            true
        )) {
            throw new \RuntimeException(
                'Enrollment tidak dapat dibatalkan pada status ini.'
            );
        }

        $activeRequest = $enrollment
            ->cancellationRequests()
            ->whereIn('status', [
                'requested',
                'under_review',
                'refund_processing',
            ])
            ->exists();

        if ($activeRequest) {
            throw new \RuntimeException(
                'Sudah ada proses pembatalan yang sedang berjalan.'
            );
        }

        return CancellationRequest::create([
            'enrollment_id' => $enrollment->id,
            'cancellation_type' => $type,
            'reason' => $reason,
            'status' => 'requested',
            'supporting_document' => $supportingDocument,
        ]);
    }

    public function approve(
        CancellationRequest $request,
        User $admin,
        float $penaltyAmount,
        float $refundAmount,
        float $creditAmount = 0
    ): CancellationRequest {
        return DB::transaction(function () use (
            $request,
            $admin,
            $penaltyAmount,
            $refundAmount,
            $creditAmount
        ) {
            if ($request->status !== 'under_review') {
                throw new \RuntimeException(
                    'Cancellation belum berada dalam tahap review.'
                );
            }

            $request->update([
                'status' => $refundAmount > 0
                    ? 'refund_processing'
                    : ($creditAmount > 0
                        ? 'credited'
                        : 'approved'),
                'penalty_amount' => $penaltyAmount,
                'refund_amount' => $refundAmount,
                'credit_amount' => $creditAmount,
                'reviewed_at' => now(),
                'reviewed_by' => $admin->id,
            ]);

            $request->enrollment->update([
                'status' => 'cancelled',
            ]);

            return $request->fresh();
        });
    }

    public function reject(
        CancellationRequest $request,
        User $admin,
        string $reason
    ): CancellationRequest {
        $request->update([
            'status' => 'rejected',
            'reviewed_at' => now(),
            'reviewed_by' => $admin->id,
            'notes' => $reason,
        ]);

        return $request->fresh();
    }

    /**
     * Memproses ACC Pembatalan oleh Admin (Upload Bukti & Potong Saldo)
     */
    public function processAdminRefund(CancellationRequest $cancellation, User $admin, array $data): CancellationRequest
    {
        return DB::transaction(function () use ($cancellation, $admin, $data) {
            
            // 1. Hitung total tabungan saat ini sebelum ditarik
            $paymentPlan = $cancellation->enrollment->paymentPlan;
            $totalSaved = $paymentPlan ? (float) $paymentPlan->transactions()->where('status', 'verified')->sum('amount') : 0;
            
            $refundAmount = (float) $data['refund_amount'];

            // 💡 DETEKSI CERDAS: Apakah ini penarikan sebagian atau pembatalan total?
            // Dicek dari teks alasan (apakah ada kata TARIK DANA SEBAGIAN) atau jika nominal tarik < total saldo
            $isPartialWithdrawal = str_contains($cancellation->reason, 'TARIK DANA SEBAGIAN') || ($refundAmount < $totalSaved);

            // 2. Update status pengajuan beserta file bukti
            $cancellation->update([
                'status'               => 'refunded',
                'refund_amount'        => $refundAmount,
                'penalty_amount'       => $data['penalty_amount'] ?? 0,
                'admin_acc_document'   => $data['acc_path'],
                'admin_transfer_proof' => $data['transfer_path'],
                'reviewed_at'          => now(),
                'reviewed_by'          => $admin->id,
            ]);

            // 3. TRIK SAKTI: Transaksi Negatif untuk memotong saldo tabungan
            if ($paymentPlan) {
                \App\Models\PaymentTransaction::create([
                    'payment_plan_id'    => $paymentPlan->id,
                    'transaction_number' => 'TRX-REF-' . time() . '-' . rand(100, 999), 
                    'amount'             => -abs($refundAmount), // Nilai minus
                    'type'               => 'refund',
                    'status'             => 'verified',
                    'payment_method'     => 'manual_transfer',
                ]);
            }

            // 4. ATUR STATUS ENROLLMENT BERDASARKAN JENIS PENARIKAN
            if ($isPartialWithdrawal) {
                // JIKA TARIK SEBAGIAN: Status enrollment TETAP 'saving', jamaah bisa lanjut menabung!
                $cancellation->enrollment->update([
                    'status' => 'saving'
                ]);
            } else {
                // JIKA TARIK SEMUA / BATAL TOTAL: Baru ubah status jadi 'cancelled'
                $cancellation->enrollment->update([
                    'status' => 'cancelled'
                ]);
            }

            return $cancellation;
        });
    }
}