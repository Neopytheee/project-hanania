<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappService
{
    /**
     * Mengirim pesan WA Gratis via Fonnte API
     */
    public static function sendMessage(string $targetNumber, string $message): bool
    {
        try {
            $formattedTarget = self::formatPhoneNumber($targetNumber);
            $token = env('FONNTE_TOKEN');

            if (!$token) {
                Log::error("Gagal kirim WA: FONNTE_TOKEN belum diisi di file .env!");
                return false;
            }

            // Gunakan asForm() karena Fonnte mewajibkan format Form Data
            $response = Http::asForm()->withHeaders([
                'Authorization' => $token,
            ])->post('https://api.fonnte.com/send', [
                'target'  => $formattedTarget,
                'message' => $message,
            ]);

            // Baca balasan asli dari Fonnte
            $result = $response->json();

            // Cek apakah balasan Fonnte benar-benar berstatus "true"
            if ($response->successful() && isset($result['status']) && $result['status'] === true) {
                Log::info("WA SUKSES (Fonnte): Pesan terkirim ke {$formattedTarget}");
                return true;
            }

            // Jika gagal, catat alasan penolakan dari Fonnte
            Log::error("Fonnte Menolak Pesan: " . $response->body());
            return false;

        } catch (\Exception $e) {
            Log::error("Error WA Service: " . $e->getMessage());
            return false;
        }
    }

    private static function formatPhoneNumber(string $number): string
    {
        $number = preg_replace('/[^0-9]/', '', $number);
        if (str_starts_with($number, '0')) {
            return '62' . substr($number, 1);
        }
        return $number;
    }
}