<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Testimonial;

class TestimonialController extends Controller
{
    // Tampilkan daftar testimoni
    public function index()
    {
        // Ambil semua testimoni beserta nama user-nya, urutkan dari yang terbaru
        $testimonials = Testimonial::with('user')->latest()->get();
        return view('admin.testimonials.index', compact('testimonials'));
    }

    // Fungsi untuk ACC (Tampilkan di web)
    public function approve(Testimonial $testimonial)
    {
        $testimonial->update(['is_approved' => true]);
        return back()->with('success', 'Testimoni berhasil di-ACC dan sudah tayang di halaman depan!');
    }

    // Fungsi untuk Hapus / Tolak
    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();
        return back()->with('success', 'Testimoni berhasil dihapus.');
    }
}