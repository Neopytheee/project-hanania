<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentWebhookController extends Controller
{
    public function handle(Request $request, PaymentService $paymentService)
    {
        Log::info('=== WEBHOOK MIDTRANS MASUK ===', $request->all());

        // =================================================================
        // 🔒 SISTEM KEAMANAN: VALIDASI SIGNATURE KEY MIDTRANS (ANTI-TIMING ATTACK)
        // =================================================================
        $serverKey = config('services.midtrans.server_key');

        $orderId = $request->order_id;
        $statusCode = $request->status_code;
        $grossAmount = $request->gross_amount;
        $incomingSignature = $request->signature_key;

        $mySignature = hash('sha512', $orderId.$statusCode.$grossAmount.$serverKey);

        // 🔒 PERBAIKAN: Menggunakan hash_equals() untuk mencegah Timing Attack
        if (! hash_equals($mySignature, (string) $incomingSignature)) {
            Log::warning('⚠️ SERANGAN DITOLAK: Signature Webhook Palsu/Tidak Valid!', [
                'ip' => $request->ip(),
                'order_id' => $orderId,
            ]);

            return response()->json(['message' => 'Forbidden: Invalid Signature Key'], 403);
        }
        // =================================================================

        try {
            $paymentService->handleMidtransWebhook($request->all());

            Log::info('Webhook SUKSES diproses.');

            return response()->json(['message' => 'Webhook success'], 200);

        } catch (\InvalidArgumentException $e) {
            Log::error('Webhook ERROR (Bad Request): '.$e->getMessage());

            return response()->json(['message' => $e->getMessage()], 400);

        } catch (\RuntimeException $e) {
            Log::error('Webhook ERROR (Not Found): '.$e->getMessage());

            return response()->json(['message' => $e->getMessage()], 404);

        } catch (\Exception $e) {
            Log::error('Webhook ERROR (Server): '.$e->getMessage());

            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }
}
