<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CancellationRequest;
use App\Models\Enrollment;
use App\Services\CancellationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CancellationController extends Controller
{
    public function create(Enrollment $enrollment)
    {
        $customer = auth()->user()->customer;
        if (! $customer || $enrollment->customer_id !== $customer->id) {
            abort(403, 'Akses Ditolak!.');
        }

        $existingRequest = CancellationRequest::where('enrollment_id', $enrollment->id)->first();

        $activeMembership = $enrollment->groupMemberships()->where('status', 'active')->first();
        $departure = $activeMembership ? $activeMembership->group->departure : null;

        return view('customer.cancellations.create', compact('enrollment', 'existingRequest', 'departure'));
    }

    public function store(Request $request, Enrollment $enrollment, CancellationService $cancellationService)
    {
        $customer = auth()->user()->customer;
        if (! $customer || $enrollment->customer_id !== $customer->id) {
            Log::warning('IDOR Attempt (Refund)', [
                'user_id' => auth()->id(),
                'target_enrollment_id' => $enrollment->id,
            ]);
            // 🔒 Perbaikan Minor: Mengubah 403 menjadi 404 untuk mencegah ID Enumeration (Sesuai Audit Fase 2)
            abort(404, 'Data tidak ditemukan.');
        }

        // Tambahkan array kedua untuk meng-custom pesan error
        $request->validate([
            'cancellation_type' => 'required|in:voluntary_withdrawal,medical,death,force_majeure,other',
            'reason' => 'required|string|min:10',
            'requested_amount' => 'nullable|numeric|min:500000',
            'supporting_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ], [
            // Kustomisasi pesan error dalam Bahasa Indonesia
            'cancellation_type.required' => 'Kategori alasan pembatalan wajib dipilih.',
            'cancellation_type.in' => 'Pilihan kategori alasan tidak valid.',
            'reason.required' => 'Detail alasan wajib diisi.',
            'reason.min' => 'Detail alasan terlalu singkat, minimal 10 karakter.',
            'requested_amount.numeric' => 'Nominal pencairan harus berupa angka tanpa titik/koma.',
            'requested_amount.min' => 'Nominal pencairan sebagian minimal adalah Rp 500.000.', // 👈 INI PESAN BARUNYA
            'supporting_document.mimes' => 'Dokumen pendukung harus berformat PDF, JPG, JPEG, atau PNG.',
            'supporting_document.max' => 'Ukuran file maksimal adalah 5MB.',
        ]);

        $filePath = null;
        if ($request->hasFile('supporting_document')) {
            // 🔒 Penyimpanan lokal yang aman (Private)
            $filePath = $request->file('supporting_document')->store('dokumen_refund', 'local');
        }

        $finalReason = $request->reason;
        if ($request->filled('requested_amount')) {
            $nominal = 'Rp '.number_format($request->requested_amount, 0, ',', '.');
            $finalReason = "TARIK DANA SEBAGIAN (TIDAK BATAL).\nNominal Diajukan: ".$nominal."\n\nDetail Alasan:\n".$request->reason;
        }

        try {
            $cancellationService->request(
                $enrollment,
                $request->cancellation_type,
                $finalReason,
                $filePath,
                $request->requested_amount // 👈 INI KUNCI UTAMANYA: Parameter ke-5
            );

            return back()->with('success', 'Pengajuan berhasil dikirim. Tim Admin kami akan segera memproses dan menghubungi Anda untuk pencairan dana.');

        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan sistem saat mengajukan pembatalan.');
        }
    }

    public function document(Enrollment $enrollment, string $type): BinaryFileResponse
    {
        $customer = auth()->user()->customer;
        if (! $customer || $enrollment->customer_id !== $customer->id) {
            abort(403, 'Akses Ditolak.');
        }

        $cancellation = CancellationRequest::where('enrollment_id', $enrollment->id)->firstOrFail();
        $path = match ($type) {
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
