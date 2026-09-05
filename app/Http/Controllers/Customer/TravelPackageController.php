<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\TravelPackage;
use App\Models\Testimonial; // 👈 JANGAN LUPA IMPORT INI BOSKU

class TravelPackageController extends Controller
{
    public function index()
    {
        // Mengambil semua paket travel yang statusnya aktif
        $packages = TravelPackage::where('status', 'active')->get();
        
        // Melempar data ke view resources/views/jamaah/packages/index.blade.php
        return view('packages.index', compact('packages'));
    }

    public function show(TravelPackage $travelPackage)
    {
        // 1. Ambil 3 ulasan terbaru yang sudah di-ACC Admin
        $testimonials = Testimonial::with('user')
                        ->where('is_approved', true)
                        ->latest()
                        ->take(3)
                        ->get();

        // 2. Lempar data paket dan testimoni ke view menggunakan compact
        return view('packages.show', compact('travelPackage', 'testimonials'));
    }
}