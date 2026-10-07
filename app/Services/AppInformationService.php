<?php

namespace App\Services;

use App\Models\AppInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AppInformationService
{
    public function updateSettings(array $data, Request $request): void
    {
        DB::transaction(function () use ($data, $request) {
            // 🔒 ALLOW-LIST KEY: Hanya izinkan kunci resmi yang boleh di-update ke DB
            $allowedKeys = [
                'app_name',
                'company_name',
                'company_tagline',
                'sk_kemenag_number',
                'bank_name',
                'bank_account_number',
                'bank_account_name',
                'whatsapp_number',
                'phone_number',
                'whatsapp_message',
                'email_address',
                'office_address',
                'operational_hours',
                'google_maps_link',
                'instagram_link',
                'facebook_link',
                'tiktok_link',
                'youtube_link',
                'payment_midtrans_enabled',
            ];

            foreach ($allowedKeys as $key) {
                if (array_key_exists($key, $data)) {
                    AppInformation::updateOrCreate(
                        ['key' => $key],
                        ['value' => $data[$key]]
                    );
                }
            }

            // Tangani file logo secara aman
            if ($request->hasFile('company_logo')) {
                $oldFile = AppInformation::getValue('company_logo');
                if ($oldFile && Storage::disk('public')->exists($oldFile)) {
                    Storage::disk('public')->delete($oldFile);
                }
                $path = $request->file('company_logo')->store('app_settings', 'public');
                AppInformation::updateOrCreate(['key' => 'company_logo'], ['value' => $path]);
            }

            // Tangani file favicon secara aman
            if ($request->hasFile('favicon')) {
                $oldFile = AppInformation::getValue('favicon');
                if ($oldFile && Storage::disk('public')->exists($oldFile)) {
                    Storage::disk('public')->delete($oldFile);
                }
                $path = $request->file('favicon')->store('app_settings', 'public');
                AppInformation::updateOrCreate(['key' => 'favicon'], ['value' => $path]);
            }

            for ($i = 1; $i <= 5; $i++) {
                $key = "carousel_image_{$i}";
                if ($request->hasFile($key)) {
                    $oldFile = AppInformation::getValue($key);
                    // Hapus gambar lama jika ada
                    if ($oldFile && Storage::disk('public')->exists($oldFile)) {
                        Storage::disk('public')->delete($oldFile);
                    }
                    // Simpan gambar baru ke folder app_settings/carousel
                    $path = $request->file($key)->store('app_settings/carousel', 'public');
                    AppInformation::updateOrCreate(['key' => $key], ['value' => $path]);
                }
            }
        });
    }
}
