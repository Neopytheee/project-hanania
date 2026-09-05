<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\TransactionService;

class TransactionController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function history(Request $request)
    {
        // Tangkap input filter dari bosku (kalau ada)
        $filters = [
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'status' => $request->input('status', 'all'),
        ];

        // Minta Service untuk hitung rekapan & ambil data
        $metrics = $this->transactionService->getSummaryMetrics($filters);
        $transactions = $this->transactionService->getHistoryPaginated($filters);

        return view('admin.transactions.history', compact('metrics', 'transactions', 'filters'));
    }

    public function print(Request $request)
    {
        $filters = $request->only(['start_date', 'end_date', 'status', 'search']);
        
        // Kita ambil semua data (tanpa di-paginate) karena untuk di-print
        $query = \App\Models\PaymentTransaction::with(['paymentPlan.enrollment']);
        
        // Panggil filter dari class Controller itu sendiri kalau bosku pindahkan logika filternya, 
        // atau supaya cepat, panggil logika filter dari service (harus di-public-kan dulu fungsi applyFilters-nya).
        // 
        // Opsi cepat:
        $transactions = $this->transactionService->getHistoryPaginated($filters, 1000); // Paksa paginate besar untuk print

        return view('admin.transactions.print', compact('transactions', 'filters'));
    }
}