<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    // 1. TAMBAHKAN BARIS INI (Daftar Putih / Gembok Dibuka)
    protected $fillable = [
        'user_id', 
        'rating', 
        'content', 
        'is_approved'
    ];

    // 2. Relasi ke tabel users (Siapa yang nulis testimoni)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}