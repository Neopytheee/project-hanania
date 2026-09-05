<?php

namespace App\Services;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    /**
     * Menyimpan data registrasi jamaah ke tabel users dan customers
     */
    public function registerCustomer(array $data): User
    {
        // Bungkus pakai DB Transaction biar aman
        return DB::transaction(function () use ($data) {
            
            // A. Simpan ke tabel users
            $user = User::create([
                'email'    => $data['email'],
                'password' => Hash::make($data['password']),
                'role'     => 'customer',
                'status'   => 'active',
            ]);

            // B. Generate Customer Number (CUS-YYYYMMDD-XXXX)
            $customerNumber = 'CUS-' . date('Ymd') . '-' . rand(1000, 9999);

            // C. Simpan ke tabel customers dengan mengaitkan user_id
            Customer::create([
                'user_id'                 => $user->id,
                'customer_number'         => $customerNumber,
                'name'                    => $data['name'],
                'nik'                     => $data['nik'],
                'phone'                   => $data['phone'],
                'email'                   => $data['email'],
                'birth_date'              => $data['birth_date'],
                'gender'                  => $data['gender'],
                'address'                 => $data['address'],
                'emergency_contact_name'  => $data['emergency_contact_name'],
                'emergency_contact_phone' => $data['emergency_contact_phone'],
                'status'                  => 'active',
            ]);

            // Kembalikan data user agar Controller bisa langsung me-login-kan
            return $user;
        });
    }
}