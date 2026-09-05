<?php

namespace App\Services;

use App\Models\TravelPackage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class TravelPackageService
{
    /**
     * Menyimpan paket baru beserta Auto-Generate Kode
     */
    public function createPackage(array $data, ?UploadedFile $imageFile): TravelPackage
    {
        // --- AUTO GENERATE KODE PAKET ---
        $prefix = ($data['category'] === 'HAJI') ? 'HJI' : 'UMR';
        
        $lastPackage = TravelPackage::where('code', 'like', $prefix . '-%')
            ->orderBy('id', 'desc')
            ->first();
            
        $nextNumber = 1;
        if ($lastPackage) {
            $lastCode = explode('-', $lastPackage->code);
            $nextNumber = isset($lastCode[1]) ? ((int) $lastCode[1]) + 1 : 1;
        }

        $data['code'] = $prefix . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        // ---------------------------------

        // Proses simpan gambar jika ada
        if ($imageFile) {
            $data['image'] = $imageFile->store('travel_packages', 'public');
        }

        return TravelPackage::create($data);
    }

    /**
     * Memperbarui paket dan mengganti foto lama jika ada
     */
    public function updatePackage(TravelPackage $package, array $data, ?UploadedFile $imageFile): TravelPackage
    {
        if ($imageFile) {
            // Hapus foto lama di server jika ada
            if ($package->image && Storage::disk('public')->exists($package->image)) {
                Storage::disk('public')->delete($package->image);
            }
            // Simpan foto baru
            $data['image'] = $imageFile->store('travel_packages', 'public');
        }

        $package->update($data);

        return $package->fresh();
    }

    /**
     * Menghapus paket dengan aman
     */
    public function deletePackage(TravelPackage $package): void
    {
        // Cegah penghapusan jika sudah ada jamaah yang mendaftar paket ini
        if ($package->enrollments()->exists()) {
            throw new \RuntimeException('Paket tidak bisa dihapus karena sudah ada jamaah yang mendaftar!');
        }

        // Hapus file fisik gambar jika ada
        if ($package->image && Storage::disk('public')->exists($package->image)) {
            Storage::disk('public')->delete($package->image);
        }

        $package->delete();
    }
}