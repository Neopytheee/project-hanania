<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentTransaction;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PaymentVerificationController extends Controller
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    // Menampilkan daftar pembayaran yang masih 'pending'
    public function index()
    {
        $pendingTransactions = PaymentTransaction::with([
            'paymentPlan.enrollment.customer',
            'paymentPlan.enrollment.travelPackage',
        ])
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc') // Yang bayar duluan, diurus duluan (antrean)
            ->get();

        return view('admin.payments.index', compact('pendingTransactions'));
    }

    // Aksi Verifikasi (Uang Sah Masuk)
    public function verify(Request $request, PaymentTransaction $transaction)
    {
        try {
            $this->paymentService->verifyPayment($transaction, $request->user());

            return redirect()->back()->with('success', 'Alhamdulillah, pembayaran berhasil diverifikasi!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // Aksi Tolak (Misal: Bukti transfer palsu / buram)
    public function reject(Request $request, PaymentTransaction $transaction)
    {
        $request->validate(['reason' => 'required|string|max:255']);

        try {
            $this->paymentService->rejectPayment($transaction, $request->user(), $request->reason);

            return redirect()->back()->with('success', 'Pembayaran telah ditolak.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function proof(PaymentTransaction $transaction): BinaryFileResponse
    {
        if (! $transaction->proof_file || ! Storage::disk('local')->exists($transaction->proof_file)) {
            abort(404, 'Bukti transfer tidak ditemukan.');
        }

        return response()->file(Storage::disk('local')->path($transaction->proof_file));
    }
}
