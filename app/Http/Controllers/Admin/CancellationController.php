<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CancellationRequest;
use App\Services\CancellationService; // 🪄 PANGGIL SERVICE-NYA

class CancellationController extends Controller
{
    // 1. Menampilkan daftar semua pengajuan refund
    public function index()
    {
        $cancellations = CancellationRequest::with(['enrollment.customer.user', 'enrollment.travelPackage'])
                            ->latest()
                            ->get();
                            
        return view('admin.cancellations.index', compact('cancellations'));
    }

    // 2. Memproses ACC & Upload Bukti dari Admin (Kini Super Clean!)
    public function approve(Request $request, CancellationRequest $cancellation, CancellationService $cancellationService)
    {
        // A. Pelayan memvalidasi form
        $request->validate([
            'refund_amount'        => 'required|numeric|min:0',
            'admin_acc_document'   => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'admin_transfer_proof' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'penalty_amount'       => 'nullable|numeric|min:0'
        ]);

        // B. Pelayan mengurus file upload (Ini memang tugas Controller)
        $accPath = $request->file('admin_acc_document')->store('dokumen_refund/acc', 'public');
        $transferPath = $request->file('admin_transfer_proof')->store('dokumen_refund/transfer', 'public');

        // C. Bungkus data untuk dikirim ke Koki
        $data = [
            'refund_amount'  => $request->refund_amount,
            'penalty_amount' => $request->penalty_amount,
            'acc_path'       => $accPath,
            'transfer_path'  => $transferPath,
        ];

        // D. Pelayan menyuruh Koki (Service) memproses Database!
        try {
            $cancellationService->processAdminRefund($cancellation, auth()->user(), $data);

            return back()->with('success', 'Refund berhasil diproses! Bukti transfer tersimpan dan saldo jamaah otomatis berkurang.');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan sistem saat memproses refund: ' . $e->getMessage());
        }
    }
}