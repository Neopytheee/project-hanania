<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Customer;
use App\Models\TravelPackage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
// 💡 TAMBAHKAN CLASS ROLE DARI SPATIE DI SINI
use Spatie\Permission\Models\Role; 

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ==========================================
        // 0. BUAT JABATAN (ROLES) SPATIE
        // ==========================================
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);
        Role::firstOrCreate(['name' => 'Admin Operasional']);
        Role::firstOrCreate(['name' => 'Admin Keuangan']);

        // ==========================================
        // 1. DATA USER & CUSTOMER
        // ==========================================
        
        // Akun Admin (Ditampung di variabel $adminDewa)
        $adminDewa = User::create([
            'email'    => 'admin@hanania.test',
            'password' => Hash::make('password'),
            'role'     => 'admin',
            'status'   => 'active',
        ]);

        // 💡 OTOMATIS BERIKAN KEKUATAN SUPER DEWA
        $adminDewa->assignRole($superAdminRole);

        // Akun Jamaah 1 (Budi)
        $jamaah1 = User::create([
            'email'    => 'viaryhouse@gmail.com',
            'password' => Hash::make('password'),
            'role'     => 'customer',
            'status'   => 'active',
        ]);
        Customer::create([
            'user_id'                 => $jamaah1->id,
            'customer_number'         => 'CUS-20240101-0001',
            'name'                    => 'Viary House',
            'nik'                     => '3201234567890001',
            'phone'                   => '081234567891',
            'email'                   => 'viaryhouse@gmail.com',
            'birth_date'              => '1990-01-01',
            'gender'                  => 'male',
            'address'                 => 'Jl. Raya Cisarua No. 10, Bogor',
            'emergency_contact_name'  => 'Siti (Istri)',
            'emergency_contact_phone' => '081987654321',
            'status'                  => 'active',
        ]);

        // Akun Jamaah 2 
        $jamaah2 = User::create([
            'email'    => 'mumut@hanania.com',
            'password' => Hash::make('password'),
            'role'     => 'customer',
            'status'   => 'active',
        ]);
        Customer::create([
            'user_id'                 => $jamaah2->id,
            'customer_number'         => 'CUS-20240101-0002',
            'name'                    => 'Mumut sarimut',
            'nik'                     => '3201234567890002',
            'phone'                   => '085975409429',
            'email'                   => 'mumut@hanania.com',
            'birth_date'              => '1995-05-15',
            'gender'                  => 'female',
            'address'                 => 'Jl. Pajajaran No. 45, Bogor',
            'emergency_contact_name'  => 'Ahmad (Suami)',
            'emergency_contact_phone' => '081987654322',
            'status'                  => 'active',
        ]);


        // ==========================================
        // 2. DATA PAKET TRAVEL
        // ==========================================
        
        TravelPackage::create([
            'code'            => 'PKG-REG-01',
            'name'            => 'Paket Umroh Reguler Bintang 4',
            'description'     => 'Paket Umroh Reguler selama 9 hari dengan fasilitas hotel Bintang 4 (Makkah: Rayyana / Madinah: Concorde). Penerbangan direct menggunakan Saudia Airlines. Cocok untuk jamaah yang ingin fokus beribadah dengan harga terjangkau.',
            'duration_days'   => 9,
            'estimated_price' => 28500000,
            'status'          => 'active',
        ]);

        TravelPackage::create([
            'code'            => 'PKG-PLUS-TRK',
            'name'            => 'Paket Umroh Plus Turki (Cappadocia)',
            'description'     => 'Perjalanan spiritual Umroh 12 Hari yang dipadukan dengan city tour eksklusif ke Istanbul dan Cappadocia, Turki. Nikmati pengalaman naik balon udara (opsional) dan menelusuri jejak peradaban Islam.',
            'duration_days'   => 12,
            'estimated_price' => 37000000,
            'status'          => 'active',
        ]);

        TravelPackage::create([
            'code'            => 'PKG-RAMADHAN',
            'name'            => 'Paket Umroh Ramadhan Lailatul Qadar',
            'description'     => 'Raih pahala setara haji bersama Rasulullah dengan beribadah di 10 malam terakhir bulan Ramadhan di Masjidil Haram. Kuota sangat terbatas. Durasi 15 Hari (Full Iktikaf).',
            'duration_days'   => 15,
            'estimated_price' => 45000000,
            'status'          => 'active',
        ]);
    }
}