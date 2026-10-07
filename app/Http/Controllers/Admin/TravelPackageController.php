<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TravelPackage;
use App\Services\TravelPackageService;
use Illuminate\Http\Request;

class TravelPackageController extends Controller
{
    // 1. Tampilkan Daftar Paket
    public function index()
    {
        $packages = TravelPackage::orderBy('created_at', 'desc')->get();

        return view('admin.travel_packages.index', compact('packages'));
    }

    // 2. Form Tambah Paket
    public function create()
    {
        return view('admin.travel_packages.create');
    }

    // 3. Simpan Paket Baru (SUPER CLEAN)
    public function store(Request $request, TravelPackageService $packageService)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:UMROH,HAJI',
            'description' => 'required|string',
            'facilities' => 'required|string',
            'airline' => 'nullable|string|max:255', // DITAMBAHKAN
            'hotel_mekkah' => 'nullable|string|max:255', // DITAMBAHKAN
            'duration_days' => 'required|integer|min:1',
            'estimated_price' => 'required|numeric|min:0',
            'status' => 'required|in:draft,active,inactive,archived',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Suruh Koki Masak (Proses generate kode & upload ada di dalam sini)
        $package = $packageService->createPackage($validated, $request->file('image'));

        return redirect()->route('admin.travel_packages.index')
            ->with('success', 'Paket Travel berhasil ditambahkan dengan Kode: '.$package->code);
    }

    // 4. Form Edit Paket
    public function edit(TravelPackage $travelPackage)
    {
        return view('admin.travel_packages.edit', compact('travelPackage'));
    }

    // 5. Simpan Perubahan Paket (SUPER CLEAN)
    public function update(Request $request, TravelPackage $travelPackage, TravelPackageService $packageService)
    {
        $validated = $request->validate([
            'code' => 'required|max:50|unique:travel_packages,code,'.$travelPackage->id,
            'name' => 'required|string|max:255',
            'category' => 'required|in:UMROH,HAJI', // DITAMBAHKAN
            'description' => 'required|string',
            'facilities' => 'required|string',        // DITAMBAHKAN
            'airline' => 'nullable|string|max:255', // DITAMBAHKAN
            'hotel_mekkah' => 'nullable|string|max:255', // DITAMBAHKAN
            'duration_days' => 'required|integer|min:1',
            'estimated_price' => 'required|numeric|min:0',
            'status' => 'required|in:draft,active,inactive,archived',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Suruh Koki Update Data
        $packageService->updatePackage($travelPackage, $validated, $request->file('image'));

        return redirect()->route('admin.travel_packages.index')
            ->with('success', 'Paket Travel berhasil diperbarui!');
    }

    // 6. Hapus Paket (SUPER CLEAN)
    public function destroy(TravelPackage $travelPackage, TravelPackageService $packageService)
    {
        try {
            $packageService->deletePackage($travelPackage);

            return redirect()->route('admin.travel_packages.index')
                ->with('success', 'Paket Travel berhasil dihapus!');

        } catch (\RuntimeException $e) {
            // Tangkap error jika paket sudah dipakai jamaah
            return redirect()->route('admin.travel_packages.index')
                ->with('error', $e->getMessage());
        }
    }
}
