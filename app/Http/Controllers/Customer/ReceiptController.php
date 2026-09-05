<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\PaymentTransaction;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReceiptController extends Controller
{
    public function download(Request $request, PaymentTransaction $transaction)
    {
        // 1. Keamanan: Pastikan transaksi ini milik jamaah yang sedang login
        $customer = $request->user()->customer;
        $belongsToCustomer = $transaction->paymentPlan->enrollment->customer_id === $customer->id;

        if (!$belongsToCustomer) {
            abort(403, 'Akses ditolak. Anda tidak berhak mengunduh kuitansi ini.');
        }

        // 2. Keamanan: Kuitansi hanya bisa didownload jika transaksinya sudah 'verified' (sah)
        if ($transaction->status !== 'verified') {
            return back()->with('error', 'Kuitansi hanya tersedia untuk transaksi yang sudah disetujui (Sah).');
        }

        // 3. Load data relasi yang dibutuhkan untuk dicetak ke PDF
        $transaction->load(['paymentPlan.enrollment.travelPackage', 'paymentPlan.enrollment.customer']);

        // 4. Render file Blade menjadi PDF
        $pdf = Pdf::loadView('customer.receipts.pdf', compact('transaction'));
        
        // Atur ukuran kertas (A5 landscape atau A4 portrait, kita pakai A4 portrait standar)
        $pdf->setPaper('a4', 'portrait');

        // 5. Download file dengan nama yang rapi
        $fileName = 'Kuitansi-' . $transaction->invoice_number . '.pdf';
        return $pdf->download($fileName);
    }
}