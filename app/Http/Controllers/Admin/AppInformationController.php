<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AppInformationService; // 🪄 Panggil Kokinya!

class AppInformationController extends Controller
{
    // Menampilkan halaman form pengaturan
    public function index()
    {
        return view('admin.informations.index');
    }

    // Proses menyimpan data dari form (Super Clean!)
    public function update(Request $request, AppInformationService $infoService)
    {
        // 1. Ambil semua input form, kecuali token bawaan Laravel
        $data = $request->except(['_token', '_method']);

        // 2. Suruh Koki (Service) yang ngelooping dan masukin ke Database
        $infoService->updateSettings($data);

        // 3. Kembalikan respon sukses
        return back()->with('success', 'Informasi Aplikasi berhasil diperbarui!');
    }
}