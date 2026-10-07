<?php

namespace App\Services;

use App\Models\CancellationRequest;
use App\Models\Enrollment;
use App\Models\PaymentPlan;
use App\Models\PaymentTransaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CancellationService
{
    // 🚀 SINKRONISASI BARU: Menambahkan penangkap $requestedAmount dari Form Customer
    public function request(Enrollment $enrollment, string $type, ?string $reason = null, ?string $supportingDoc = null, ?float $requestedAmount = null): CancellationRequest
    {
        return DB::transaction(function () use ($enrollment, $type, $reason, $supportingDoc, $requestedAmount) {
            // 🔒 ROW LOCK ENROLLMENT
            $lockedEnrollment = Enrollment::where('id', $enrollment->id)->lockForUpdate()->first();

            if (in_array($lockedEnrollment->status, ['departed', 'completed', 'cancelled'], true)) {
                throw new \RuntimeException('Enrollment tidak dapat dibatalkan pada status ini.');
            }

            if ($lockedEnrollment->cancellationRequests()->whereIn('status', ['requested', 'under_review', 'refund_processing'])->exists()) {
                throw new \RuntimeException('Sudah ada proses pembatalan berjalan.');
            }

            return CancellationRequest::create([
                'enrollment_id' => $lockedEnrollment->id,
                'cancellation_type' => $type,
                'reason' => $reason,
                'status' => 'requested',
                'supporting_document' => $supportingDoc,
                'requested_amount' => $requestedAmount, // 👈 SIMPAN KE DATABASE
            ]);
        });
    }

    public function approve(
        CancellationRequest $request,
        User $admin,
        float $penaltyAmount,
        float $refundAmount,
        float $creditAmount = 0
    ): CancellationRequest {

        // 🔒 Domain-level Validation untuk menolak angka negatif di Service Level
        if ($penaltyAmount < 0 || $refundAmount < 0 || $creditAmount < 0) {
            throw new \InvalidArgumentException('Nominal tidak boleh bernilai negatif.');
        }

        return DB::transaction(function () use ($request, $admin, $penaltyAmount, $refundAmount, $creditAmount) {
            if ($request->status !== 'under_review') {
                throw new \RuntimeException('Cancellation belum berada dalam tahap review.');
            }

            $request->update([
                'status' => $refundAmount > 0 ? 'refund_processing' : ($creditAmount > 0 ? 'credited' : 'approved'),
                'penalty_amount' => $penaltyAmount,
                'refund_amount' => $refundAmount,
                'credit_amount' => $creditAmount,
                'reviewed_at' => now(),
                'reviewed_by' => $admin->id,
            ]);

            $request->enrollment->update(['status' => 'cancelled']);

            return $request->fresh();
        });
    }

    public function reject(CancellationRequest $request, User $admin, string $reason): CancellationRequest
    {
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

            // 1. ROW LOCKING CANCELLATION
            $lockedCancellation = CancellationRequest::where('id', $cancellation->id)
                ->lockForUpdate()
                ->first();

            // 🔒 EXPLICIT TRANSITION WORKFLOW (Whitelist)
            $allowedWorkflowStates = ['requested', 'under_review', 'approved', 'refund_processing'];
            if (! in_array($lockedCancellation->status, $allowedWorkflowStates, true)) {
                throw new \RuntimeException("Transition Workflow Error: Pengajuan dengan status '{$lockedCancellation->status}' tidak valid untuk dieksekusi pencairan.");
            }

            // 3. ROW LOCKING PAYMENT PLAN
            $paymentPlan = $lockedCancellation->enrollment->paymentPlan;
            if ($paymentPlan) {
                $paymentPlan = PaymentPlan::where('id', $paymentPlan->id)
                    ->lockForUpdate()
                    ->first();
            }

            $totalSaved = $paymentPlan ? (float) $paymentPlan->transactions()
                ->where('status', 'verified')
                ->sum(DB::raw('COALESCE(net_amount, amount)')) : 0;

            $refundAmount = (float) $data['refund_amount'];
            $penaltyAmount = (float) ($data['penalty_amount'] ?? 0);

            // 💡 LOGIKA FINANSIAL: Total saldo yang akan dipotong = Uang ditransfer + Potongan Admin
            $totalDeductionAmount = $refundAmount + $penaltyAmount;

            // 🔒 Menolak input negatif secara independen di luar Controller
            if ($refundAmount < 0 || $penaltyAmount < 0) {
                throw new \InvalidArgumentException('Domain Logic Error: Nominal pencairan (refund) dan penalti tidak boleh bernilai negatif.');
            }

            // 4. VALIDASI SALDO
            if ($totalDeductionAmount > $totalSaved) {
                throw new \InvalidArgumentException('Total potongan saldo (Transfer Rp'.number_format($refundAmount, 0, ',', '.').' + Penalti Rp'.number_format($penaltyAmount, 0, ',', '.').') melebihi total saldo jamaah saat ini (Rp'.number_format($totalSaved, 0, ',', '.').').');
            }

            $isPartialWithdrawal = $totalDeductionAmount < $totalSaved;

            $lockedCancellation->update([
                'status' => 'refunded',
                'refund_amount' => $refundAmount,
                'penalty_amount' => $penaltyAmount,
                'admin_acc_document' => $data['acc_path'],
                'admin_transfer_proof' => $data['transfer_path'],
                'reviewed_at' => now(),
                'reviewed_by' => $admin->id,
            ]);

            if ($paymentPlan && $totalDeductionAmount > 0) {
                PaymentTransaction::create([
                    'payment_plan_id' => $paymentPlan->id,
                    'transaction_number' => 'TRX-REF-'.time().'-'.random_int(100, 999),
                    // 💡 BUG FIXED: Memotong saldo sesuai total deduksi (20 Juta), bukan cuma yang ditransfer (15 Juta)
                    'amount' => -abs($totalDeductionAmount),
                    'type' => 'refund',
                    'status' => 'verified',
                    'payment_method' => 'manual_transfer',
                ]);
            }

            if ($isPartialWithdrawal) {
                $lockedCancellation->enrollment->update(['status' => 'saving']);
            } else {
                $lockedCancellation->enrollment->update(['status' => 'cancelled']);
            }

            return $lockedCancellation;
        });
    }
}
