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

            // 🔒 PERBAIKAN: Gunakan config() yang aman untuk deployment (Cache-safe)
            $token = config('services.fonnte.token');

            if (! $token) {
                Log::error('Gagal kirim WA: FONNTE_TOKEN belum dikonfigurasi!');

                return false;
            }

            // 🔒 PERBAIKAN: Tambahkan timeout(10) untuk mencegah Server Hang (Resource Exhaustion)
            $response = Http::asForm()->timeout(10)->withHeaders([
                'Authorization' => $token,
            ])->post(config('services.fonnte.endpoint'), [
                'target' => $formattedTarget,
                'message' => $message,
            ]);

            $result = $response->json();

            if ($response->successful() && isset($result['status']) && $result['status'] === true) {
                Log::info("WA SUKSES (Fonnte): Pesan terkirim ke {$formattedTarget}");

                return true;
            }

            Log::error('Fonnte Menolak Pesan: '.$response->body());

            return false;

        } catch (\Exception $e) {
            Log::error('Error WA Service: '.$e->getMessage());

            return false;
        }
    }

    private static function formatPhoneNumber(string $number): string
    {
        $number = preg_replace('/[^0-9]/', '', $number);
        if (str_starts_with($number, '0')) {
            return '62'.substr($number, 1);
        }

        return $number;
    }
}
