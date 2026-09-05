<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    // ========================================================================
    // 2. FUNGSI UNTUK MENYIMPAN TRANSAKSI DARI HALAMAN BAYAR
    // ========================================================================
    public function store(Request $request, Enrollment $enrollment)
    {
        // Ambil aturan pembayaran (minimal DP, dll)
        $rules = $this->paymentService->getNextPaymentRules($enrollment);

        // Validasi Input
        $request->validate([
            'amount'         => 'required|numeric|min:' . $rules['min_amount'],
            'payment_method' => 'required|in:midtrans,manual_transfer',
            'proof_file'     => 'required_if:payment_method,manual_transfer|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        try {
            $proofPath = null;

            if ($request->payment_method === 'manual_transfer' && $request->hasFile('proof_file')) {
                $proofPath = $request->file('proof_file')->store('bukti_transfer', 'public');
            }

            // Panggil Service untuk buat transaksi ke DB dan Token Midtrans
            $transaction = $this->paymentService->createPayment(
                $enrollment, 
                $request->amount, 
                $rules['payment_type'], 
                $request->payment_method, 
                null, 
                $proofPath
            );

            // JIKA MANUAL: Redirect ke halaman tabungan
            if ($request->payment_method === 'manual_transfer') {
                return redirect()->route('customer.enrollments.show', $enrollment->id)
                    ->with('success', 'Bukti transfer berhasil diunggah! Menunggu verifikasi Admin.');
            }

            // JIKA MIDTRANS: Bawa token ke halaman pop-up (snap.blade.php)
            return view('customer.enrollments.snap', compact('enrollment', 'transaction'));

        } catch (\Exception $e) {
            return redirect()->route('customer.enrollments.show', $enrollment->id)
                             ->with('error', $e->getMessage());
        }
    }
}