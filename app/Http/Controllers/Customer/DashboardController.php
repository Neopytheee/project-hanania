<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Ambil customer ID dari user yang sedang login
        $customer = $request->user()->customer;

        // Cari apakah dia punya pendaftaran yang MASIH AKTIF (Belum selesai / batal)
        $activeEnrollment = null;
        if ($customer) {
            $activeEnrollments = \App\Models\Enrollment::with('travelPackage')
                                ->where('customer_id', $customer->id)
                                ->whereNotIn('status', ['completed', 'cancelled'])
                                ->get();
        }

        // Ambil daftar paket yang aktif untuk ditampilkan di bawah
        $travelPackages = \App\Models\TravelPackage::where('status', 'active')->get();

        return view('customer.dashboard', compact('travelPackages', 'activeEnrollments'));
    }
}