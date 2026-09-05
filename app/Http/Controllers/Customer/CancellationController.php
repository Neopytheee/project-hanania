<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Models\CancellationRequest;
use App\Services\CancellationService; // 🪄 WAJIB PANGGIL KOKI-NYA!

class CancellationController extends Controller
{
    // Halaman Form Pengajuan Refund
    public function create(Enrollment $enrollment)
    {
        // Pastikan tabungan ini milik customer yang sedang login
        if ($enrollment->customer_id !== auth()->user()->customer->id) {
            abort(403, 'Akses ditolak.');
        }

        // Cek apakah sudah pernah mengajukan refund
        $existingRequest = CancellationRequest::where('enrollment_id', $enrollment->id)->first();
        
        // 🪄 CEK STATUS KEBERANGKATAN JAMAAH
        $activeMembership = $enrollment->groupMemberships()->where('status', 'active')->first();
        $departure = $activeMembership ? $activeMembership->group->departure : null;
        
        return view('customer.cancellations.create', compact('enrollment', 'existingRequest', 'departure'));
    }

    // ==========================================
    // PROSES PENGAJUAN REFUND (Kini Pakai Service!)
    // ==========================================
    public function store(Request $request, Enrollment $enrollment, CancellationService $cancellationService)
    {
        // 1. Pelayan Validasi Input
        $request->validate([
            'cancellation_type'   => 'required|in:voluntary_withdrawal,medical,death,force_majeure,other',
            'reason'              => 'required|string|min:10',
            'requested_amount'    => 'nullable|numeric|min:10000',
            'supporting_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120'
        ]);

        $filePath = null;
        if ($request->hasFile('supporting_document')) {
            $filePath = $request->file('supporting_document')->store('dokumen_refund', 'public');
        }

        // 🪄 TRIK SAKTI: Gabungkan nominal ke dalam alasan jika user mengisi form nominal
        $finalReason = $request->reason;
        if ($request->filled('requested_amount')) {
            $nominal = 'Rp ' . number_format($request->requested_amount, 0, ',', '.');
            $finalReason = "TARIK DANA SEBAGIAN (TIDAK BATAL).\nNominal Diajukan: " . $nominal . "\n\nDetail Alasan:\n" . $request->reason;
        }

        // 2. Pelayan Menyuruh Koki (Service) Bekerja & Menangani Jika Ada Error
        try {
            $cancellationService->request(
                $enrollment, 
                $request->cancellation_type, 
                $finalReason, 
                $filePath
            );

            return back()->with('success', 'Pengajuan berhasil dikirim. Tim Admin kami akan segera memproses dan menghubungi Anda untuk pencairan dana.');

        } catch (\RuntimeException $e) {
            // Jika ditolak oleh aturan Service (misal sudah pernah ngajuin batal), tampilkan pesan error
            return back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            // Jika ada error sistem lainnya
            return back()->with('error', 'Terjadi kesalahan sistem saat mengajukan pembatalan.');
        }
    }
}