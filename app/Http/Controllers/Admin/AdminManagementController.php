<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminManagementController extends Controller
{
    public function index()
    {
        // Ambil semua user yang rolenya 'admin' beserta jabatan (roles) dari Spatie
        $admins = User::where('role', 'admin')->with('roles')->latest()->get();
        
        // Ambil semua daftar jabatan (Super Admin, Staff Keuangan, dll) untuk di dropdown
        $roles = Role::all();
        
        return view('admin.management.index', compact('admins', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role_name' => 'required|exists:roles,name',
        ]);

        // 1. Buat akun Admin di tabel users
        $admin = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin', // Gerbang hybrid tetap 'admin'
            'status' => 'active',
        ]);

        // 2. Berikan "Kekuatan/Jabatan" via Spatie
        $admin->assignRole($request->role_name);

        return back()->with('success', 'Akun staff/admin berhasil ditambahkan!');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'role_name' => 'required|exists:roles,name',
        ]);

        // Ganti jabatan (syncRoles akan menghapus jabatan lama dan mengganti yang baru)
        $user->syncRoles([$request->role_name]);

        return back()->with('success', 'Jabatan staff berhasil diubah!');
    }

    public function destroy(User $user)
    {
        // Jangan biarkan Super Admin menghapus dirinya sendiri
        if (auth()->id() === $user->id) {
            return back()->withErrors(['error' => 'Anda tidak bisa menghapus akun Anda sendiri bosku!']);
        }

        // Kita ubah statusnya jadi inactive agar histori relasi (seperti verifikasi payment) tidak error
        $user->update(['status' => 'inactive']);
        // Cabut semua kekuatannya
        $user->syncRoles([]); 

        return back()->with('success', 'Akses staff berhasil dicabut!');
    }

    public function resetPassword(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->withErrors(['error' => 'Anda tidak bisa mereset password sendiri dari sini bosku!']);
        }

        $user->update([
            'password' => \Illuminate\Support\Facades\Hash::make('hanania123')
        ]);

        return back()->with('success', "Sukses! Password untuk akun {$user->email} berhasil direset menjadi: hanania123");
    }
}