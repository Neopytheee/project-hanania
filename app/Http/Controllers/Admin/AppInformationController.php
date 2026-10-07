<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AppInformationService;
use Illuminate\Http\Request;

class AppInformationController extends Controller
{
    public function index()
    {
        return view('admin.informations.index');
    }

    public function update(Request $request, AppInformationService $infoService)
    {
        // 🔒 CRITICAL FIX: Validasi ketat Allow-List & Tipe File (Mencegah RCE / Web Shell)
        $validated = $request->validate([
            'app_name' => 'nullable|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'company_tagline' => 'nullable|string|max:255',
            'sk_kemenag_number' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:100',
            'bank_account_name' => 'nullable|string|max:255',
            'whatsapp_number' => 'nullable|string|max:20',
            'phone_number' => 'nullable|string|max:50',
            'whatsapp_message' => 'nullable|string|max:1000',
            'email_address' => 'nullable|email|max:255',
            'office_address' => 'nullable|string|max:1000',
            'operational_hours' => 'nullable|string|max:1000',
            'payment_midtrans_enabled' => 'nullable|in:1,0',
            'google_maps_link' => ['nullable', 'url', 'max:1000', 'regex:/^https?:\\/\\//i'],
            'instagram_link' => ['nullable', 'url', 'max:1000', 'regex:/^https?:\\/\\//i'],
            'facebook_link' => ['nullable', 'url', 'max:1000', 'regex:/^https?:\\/\\//i'],
            'tiktok_link' => ['nullable', 'url', 'max:1000', 'regex:/^https?:\\/\\//i'],
            'youtube_link' => ['nullable', 'url', 'max:1000', 'regex:/^https?:\\/\\//i'],
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
            'carousel_image_1' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'carousel_image_2' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'carousel_image_3' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'carousel_image_4' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'carousel_image_5' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        $infoService->updateSettings($validated, $request);

        return back()->with('success', 'Informasi Aplikasi berhasil diperbarui dengan aman!');
    }
}
