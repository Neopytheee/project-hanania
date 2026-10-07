<?php

namespace App\Services;

// 💡 PERBAIKAN: Gunakan PaymentTransaction, bukan Payment
use App\Models\PaymentTransaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    /**
     * Mengambil rekap kartu metrik (Total Pemasukan, dll)
     */
    public function getSummaryMetrics(array $filters = []): array
    {
        $query = PaymentTransaction::query();
        $this->applyFilters($query, $filters);

        return [
            // Hitung total uang yang statusnya verified (Sukses)
            'total_income' => (clone $query)->where('status', 'verified')->sum(DB::raw('COALESCE(net_amount, amount)')),

            // Hitung total uang yang masih pending
            'total_pending' => (clone $query)->where('status', 'pending')->sum(DB::raw('COALESCE(net_amount, amount)')),

            // Hitung jumlah transaksinya
            'count_success' => (clone $query)->where('status', 'verified')->count(),
            'count_rejected' => (clone $query)->where('status', 'rejected')->count(),
        ];
    }

    /**
     * Mengambil daftar riwayat transaksi untuk tabel (dengan Paginate)
     */
    public function getHistoryPaginated(array $filters = [], int $perPage = 15)
    {
        // 💡 Asumsi Relasi: PaymentTransaction -> PaymentPlan -> Enrollment
        // Pastikan relasi ini sesuai dengan yang ada di model bosku
        $query = PaymentTransaction::with(['paymentPlan.enrollment']);

        $this->applyFilters($query, $filters);

        // Urutkan dari yang paling baru
        return $query->latest('created_at')->paginate($perPage);
    }

    /**
     * Logic filter tanggal & status (DRY Code)
     */
    private function applyFilters($query, array $filters)
    {
        if (! empty($filters['start_date']) && ! empty($filters['end_date'])) {
            $query->whereBetween('created_at', [
                Carbon::parse($filters['start_date'])->startOfDay(),
                Carbon::parse($filters['end_date'])->endOfDay(),
            ]);
        }

        if (! empty($filters['status']) && $filters['status'] !== 'all') {
            $query->where('status', $filters['status']);
        }

        // TAMBAHAN: Filter Pencarian Nama / Nomor Transaksi
        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('transaction_number', 'like', "%{$search}%")
                    ->orWhereHas('paymentPlan.enrollment', function ($q2) use ($search) {
                        $q2->where('passenger_name', 'like', "%{$search}%");
                    });
            });
        }
    }
}
