<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    // 1. Tampilkan form masukin email
    public function create()
    {
        return view('auth.forgot-password');
    }

    // 2. Proses kirim link ke email
    public function store(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        
        // Meminta Laravel mengirim email reset password bawaan
        $status = Password::sendResetLink($request->only('email'));
        
        if ($status == Password::RESET_LINK_SENT) {
            return back()->with('status', 'Link reset password sudah terbang ke email bosku! Cek kotak masuk atau folder Spam ya.');
        }
        
        return back()->withErrors(['email' => 'Waduh, email ini tidak ditemukan di sistem kami.']);
    }

    // 3. Tampilkan form ubah password (setelah klik link di email)
    public function edit(Request $request, $token)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    // 4. Proses simpan password baru ke database
    public function update(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed', // Harus ada field password_confirmation
        ]);

        $status = Password::reset($request->only('email', 'password', 'password_confirmation', 'token'), function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->setRememberToken(Str::random(60));

            $user->save();
        });

        if ($status == Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('success', 'Sukses! Password berhasil diubah. Silakan login dengan kunci baru bosku.');
        }

        return back()->withErrors(['email' => 'Token tidak valid atau sudah kadaluarsa. Coba request link baru lagi.']);
    }
}