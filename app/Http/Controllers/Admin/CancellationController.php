<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CancellationRequest;
use App\Services\CancellationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CancellationController extends Controller
{
    public function index()
    {
        $cancellations = CancellationRequest::with(['enrollment.customer.user', 'enrollment.travelPackage'])
            ->latest()
            ->get();

        return view('admin.cancellations.index', compact('cancellations'));
    }

    public function approve(Request $request, CancellationRequest $cancellation, CancellationService $cancellationService)
    {
        $request->validate([
            'refund_amount' => 'required|numeric|min:0',
            'admin_acc_document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'admin_transfer_proof' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'penalty_amount' => 'nullable|numeric|min:0',
        ], [
            'refund_amount.min' => 'Nominal pencairan tidak boleh negatif.',
            'admin_acc_document.mimes' => 'Surat ACC harus berformat PDF, JPG, atau PNG.',
            'admin_transfer_proof.mimes' => 'Bukti transfer harus berformat PDF, JPG, atau PNG.',
        ]);

        // 🔒 Penyimpanan File Lokal (Aman dari akses publik langsung)
        $accPath = $request->file('admin_acc_document')->store('dokumen_refund/acc', 'local');
        $transferPath = $request->file('admin_transfer_proof')->store('dokumen_refund/transfer', 'local');

        $data = [
            'refund_amount' => $request->refund_amount,
            'penalty_amount' => $request->penalty_amount ?? 0,
            'acc_path' => $accPath,
            'transfer_path' => $transferPath,
        ];

        try {
            // Langsung eksekusi pencairan sesuai alur form
            $cancellationService->processAdminRefund($cancellation, auth()->user(), $data);

            return back()->with('success', 'Refund berhasil diproses! Bukti transfer tersimpan dan saldo jamaah otomatis berkurang.');

        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage()); // Error domain (nominal negatif/saldo kurang)
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage()); // Error workflow state
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan sistem saat memproses refund.');
        }
    }

    public function document(CancellationRequest $cancellation, string $type): BinaryFileResponse
    {
        $path = match ($type) {
            'supporting' => $cancellation->supporting_document,
            'admin_acc' => $cancellation->admin_acc_document,
            'admin_transfer' => $cancellation->admin_transfer_proof,
            default => abort(404, 'Jenis dokumen tidak ditemukan.'),
        };

        if (! $path || ! Storage::disk('local')->exists($path)) {
            abort(404, 'Dokumen tidak ditemukan.');
        }

        return response()->file(Storage::disk('local')->path($path));
    }
}
