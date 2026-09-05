<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEnrollmentRequest extends FormRequest
{
    /**
     * Tentukan apakah user diizinkan melakukan request ini.
     */
    public function authorize(): bool
    {
        // Set ke true karena perlindungan sudah dilakukan oleh middleware 'auth' di web.php
        return true; 
    }

    /**
     * Aturan validasi untuk pendaftaran tabungan.
     */
    public function rules(): array
    {
        return [
            // Memastikan paket yang dipilih benar-benar ada di database
            'travel_package_id' => ['required', 'exists:travel_packages,id'],
            'notes'             => ['nullable', 'string', 'max:1000'],
        ];
    }
}