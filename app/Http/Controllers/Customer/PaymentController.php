<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\AppInformation;
use App\Models\Enrollment;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PaymentController extends Controller
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function store(Request $request, Enrollment $enrollment)
    {
        $customer = auth()->user()->customer;
        if (! $customer || $enrollment->customer_id !== $customer->id) {
            abort(403, 'Akses Ditolak!.');
        }

        $rules = $this->paymentService->getNextPaymentRules($enrollment);
        $midtransEnabled = AppInformation::isPaymentMidtransEnabled();
        $allowedMethods = $midtransEnabled ? ['midtrans', 'manual_transfer'] : ['manual_transfer'];

        $request->validate([
            'amount' => 'required|numeric|min:'.$rules['min_amount'],
            'payment_method' => ['required', Rule::in($allowedMethods)],
            'proof_file' => 'required_if:payment_method,manual_transfer|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        if (! $midtransEnabled && $request->payment_method === 'midtrans') {
            return redirect()->route('customer.enrollments.show', $enrollment->id)
                ->with('error', 'Pembayaran otomatis saat ini dinonaktifkan oleh owner. Silakan pilih transfer manual.');
        }

        try {
            $proofPath = null;

            if ($request->payment_method === 'manual_transfer' && $request->hasFile('proof_file')) {
                // 🔒 PERBAIKAN: Ubah parameter 'public' menjadi 'local'
                $proofPath = $request->file('proof_file')->store('bukti_transfer', 'local');
            }

            $transaction = $this->paymentService->createPayment(
                $enrollment,
                $request->amount,
                $rules['payment_type'],
                $request->payment_method,
                null,
                $proofPath,
                null
            );

            if ($request->payment_method === 'manual_transfer') {
                return redirect()->route('customer.enrollments.show', $enrollment->id)
                    ->with('success', 'Bukti transfer berhasil diunggah! Menunggu verifikasi Admin.');
            }

            return view('customer.enrollments.snap', compact('enrollment', 'transaction'));

        } catch (\Exception $e) {
            return redirect()->route('customer.enrollments.show', $enrollment->id)
                ->with('error', $e->getMessage());
        }
    }
}
