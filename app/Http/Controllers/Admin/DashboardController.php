<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CancellationRequest;
use App\Models\Customer;
use App\Models\Departure;
use App\Models\Document;
use App\Models\Enrollment;
use App\Models\PaymentTransaction; // 🪄 Tambahan Model Refund
use App\Models\Testimonial;       // 🪄 Tambahan Model Testimoni
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Data Keuangan
        $totalRevenue = PaymentTransaction::where('status', 'verified')->sum(DB::raw('COALESCE(net_amount, amount)'));

        // 2. HITUNGAN ALARM PEMBAYARAN MANUAL
        $pendingPayments = PaymentTransaction::where('payment_method', 'manual_transfer')
            ->whereIn('status', ['pending', 'pending_verification', 'unverified'])
            ->count();

        // 3. Data Jamaah
        $totalCustomers = Customer::count();
        $jamaahLunas = Enrollment::whereIn('status', ['funds_sufficient', 'waiting_schedule'])->count();
        $activeSaving = Enrollment::where('status', 'saving')->count();

        // 4. Data Dokumen
        $pendingDocuments = Document::where('status', 'submitted')->count();

        // 5. 🪄 TAMBAHAN ALARM BARU: Refund & Testimoni
        $pendingCancellations = CancellationRequest::where('status', 'requested')->count();
        $pendingTestimonials = Testimonial::where('is_approved', false)->count();

        // 6. Tabel Keberangkatan & Pendaftaran Baru
        $upcomingDepartures = Departure::with('travelPackage')
            ->where('departure_date', '>=', now())
            ->orderBy('departure_date', 'asc')
            ->take(4)
            ->get();

        $recentEnrollments = Enrollment::with(['customer', 'travelPackage'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalRevenue', 'pendingPayments', 'totalCustomers',
            'jamaahLunas', 'activeSaving', 'pendingDocuments',
            'pendingCancellations', 'pendingTestimonials', // 🪄 Masukkan ke compact
            'upcomingDepartures', 'recentEnrollments'
        ));
    }
}
