<?php

namespace App\Services;

use App\Models\AppInformation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class AppInformationService
{
    public function updateSettings(array $data): void
    {
        DB::transaction(function () use ($data) {
            foreach ($data as $key => $value) {
                
                // 🪄 LOGIKA MAGIC: Cek apakah inputan ini berupa File Gambar
                if ($value instanceof UploadedFile) {
                    
                    // Hapus file logo lama di server agar tidak jadi sampah
                    $oldFile = AppInformation::getValue($key);
                    if ($oldFile && Storage::disk('public')->exists($oldFile)) {
                        Storage::disk('public')->delete($oldFile);
                    }
                    
                    // Simpan file baru dan ubah $value menjadi alamat path-nya
                    $value = $value->store('app_settings', 'public');
                }

                // Simpan ke database (berlaku untuk teks maupun path file)
                AppInformation::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value]
                );
            }
        });
    }
}