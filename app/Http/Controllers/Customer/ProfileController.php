<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ProfileService; // 🪄 Panggil Service-nya

class ProfileController extends Controller
{
    // ==========================================
    // BAGIAN PROFIL & KONTAK
    // ==========================================
    public function index()
    {
        $user = auth()->user();
        $customer = $user->customer;
        return view('customer.profile.index', compact('user', 'customer'));
    }

    public function edit()
    {
        $user = auth()->user();
        $customer = $user->customer;
        return view('customer.profile.edit', compact('user', 'customer'));
    }

    public function update(Request $request, ProfileService $profileService)
    {
        $request->validate([
            'phone'   => ['required', 'string', 'max:20'],
            'address' => ['required', 'string'],
            'profile_image'   => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $profileService->updateContactInfo(auth()->user(), $request->all(), $request->file('profile_image'));

        return redirect()->route('customer.profile.index')->with('success', 'Alhamdulillah, data kontak & foto berhasil diperbarui!');
    }

    // ==========================================
    // BAGIAN PASSWORD
    // ==========================================
    public function editPassword()
    {
        return view('customer.profile.password'); // View baru untuk password
    }

    public function updatePassword(Request $request, ProfileService $profileService)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'new_password'     => ['required', 'min:8', 'confirmed'], // Wajib diulang (new_password_confirmation)
        ]);

        $profileService->updatePassword(auth()->user(), $request->new_password);

        return redirect()->route('customer.profile.index')->with('success', 'Keamanan terjaga! Password Anda berhasil diubah.');
    }

    // ==========================================
    // BAGIAN REKENING BANK
    // ==========================================
    public function editBankAccount()
    {
        $user = auth()->user();
        $customer = $user->customer;
        
        return view('customer.profile.bank', compact('user', 'customer'));
    }

    public function updateBankAccount(Request $request, ProfileService $profileService)
    {
        $request->validate([
            'bank_name'           => ['required', 'string', 'max:100'],
            'bank_account_number' => ['required', 'string', 'max:50'],
            'bank_account_name'   => ['required', 'string', 'max:255'],
        ]);

        $profileService->updateBankAccount(auth()->user(), $request->all());

        return redirect()->route('customer.profile.index')->with('success', 'Alhamdulillah, informasi rekening bank berhasil diperbarui!');
    }
}