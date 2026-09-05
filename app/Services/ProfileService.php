<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProfileService
{
    /**
     * Memperbarui Foto, Nomor HP, dan Alamat Domisili
     */
    public function updateContactInfo(User $user, array $data, ?UploadedFile $profile_image): void
    {
        DB::transaction(function () use ($user, $data, $profile_image) {
            $customer = $user->customer;

            // Kita siapkan variabel penampung path foto lama
            $imagePath = $customer->profile_image;

            // Jika ada foto baru yang diupload
            if ($profile_image) {
                // Hapus foto lama dari Storage jika ada, supaya hosting nggak bengkak
                if ($customer->profile_image && Storage::disk('public')->exists($customer->profile_image)) {
                    Storage::disk('public')->delete($customer->profile_image);
                }
                
                // Simpan foto baru dan timpa variabel path-nya
                $imagePath = $profile_image->store('customer_photos', 'public');
            }

            // Update tabel customer
            if ($customer) {
                $customer->update([
                    'phone'         => $data['phone'] ?? $customer->phone,
                    'address'       => $data['address'] ?? $customer->address,
                    'profile_image' => $imagePath, // <-- Sesuai dengan kolom DB bosku
                ]);
            }
        });
    }

    /**
     * Memperbarui Password Akun
     */
    public function updatePassword(User $user, string $newPassword): void
    {
        $user->update([
            'password' => Hash::make($newPassword)
        ]);
    }
}