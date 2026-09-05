<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gallery;
use App\Models\TravelPackage;
use App\Models\Testimonial;

class HomeController extends Controller
{
    // Fungsi untuk Halaman Pertama (Welcome)
    public function index()
    {
     // Mengambil foto galeri terbaru beserta kategorinya, misal dibatasi 8 foto
        $galleries = Gallery::with('category')->latest()->take(8)->get();
        $testimonials = Testimonial::with('user')
                        ->where('is_approved', true)
                        ->latest()
                        ->take(6)
                        ->get();

        // Hanya ambil paket yang statusnya 'active'
    $featuredPackages = TravelPackage::where('status', 'active')
                                     ->latest()
                                     ->take(3)
                                     ->get();
        
        return view('welcome', compact('galleries', 'testimonials', 'featuredPackages'));
    }
}