<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function registerCustomer(array $data): User
    {
        return DB::transaction(function () use ($data) {

            // 🔒 PERBAIKAN: Validasi Unique menggunakan Blind Index (nik_hash)
            // hash_hmac akan menghasilkan string yang selalu sama untuk NIK yang sama
            $nikHash = hash_hmac('sha256', $data['nik'], config('app.key'));

            if (Customer::where('nik_hash', $nikHash)->exists()) {
                throw new \Exception('Registrasi Gagal: NIK tersebut sudah terdaftar di sistem.');
            }

            $user = User::create([
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => 'customer',
                'status' => 'active',
            ]);

            do {
                $customerNumber = 'CUS-'.date('Ymd').'-'.random_int(100000, 999999);
            } while (Customer::where('customer_number', $customerNumber)->exists());

            Customer::create([
                'user_id' => $user->id,
                'customer_number' => $customerNumber,
                'name' => $data['name'],
                'nik' => $data['nik'],
                'nik_hash' => $nikHash, // 🔒 Simpan Hash-nya ke DB
                'phone' => $data['phone'],
                'email' => $data['email'],
                'birth_date' => $data['birth_date'],
                'gender' => $data['gender'],
                'address' => $data['address'],
                'emergency_contact_name' => $data['emergency_contact_name'],
                'emergency_contact_phone' => $data['emergency_contact_phone'],
                'status' => 'active',
            ]);

            return $user;
        });
    }
}
