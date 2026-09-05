<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Testimonial;

class TestimonialController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi inputan dari form
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'content' => 'required|string|min:10|max:1000',
        ]);

        // 2. Simpan ke database dengan status is_approved = false (menunggu ACC Admin)
        Testimonial::create([
            'user_id' => auth()->id(),
            'rating' => $request->rating,
            'content' => $request->content,
            'is_approved' => false 
        ]);

        // 3. Kembalikan ke halaman tadi dengan pesan sukses
        return back()->with('success', 'Alhamdulillah! Ulasan Anda berhasil dikirim dan sedang menunggu moderasi Admin.');
    }
}