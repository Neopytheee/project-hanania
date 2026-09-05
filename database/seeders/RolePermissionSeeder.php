<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Role Dewa (Super Admin)
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);
        
        // Buat role bawahan sebagai contoh (nanti bisa ditambah via web)
        Role::firstOrCreate(['name' => 'Staff Keuangan']);
        Role::firstOrCreate(['name' => 'Staff Pendaftaran']);

        // 2. Cari akun admin utama bosku (Asumsi ID = 1 atau sesuaikan emailnya)
        // Kita pakai first() untuk mengambil user pertama yang rolenya 'admin'
        $adminDewa = User::where('role', 'admin')->first();

        // 3. Nobatkan akun tersebut jadi Super Admin
        if ($adminDewa) {
            $adminDewa->assignRole($superAdminRole);
            echo "Sukses! Akun {$adminDewa->email} telah dinobatkan sebagai Super Admin (Dewa).\n";
        } else {
            echo "Perhatian: Belum ada akun dengan role 'admin' di database.\n";
        }
    }
}