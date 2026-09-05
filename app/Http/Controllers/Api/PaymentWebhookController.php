<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Log;

class PaymentWebhookController extends Controller
{
    public function handle(Request $request, PaymentService $paymentService)
    {
        // 1. CCTV: Rekam payload yang masuk dari Midtrans
        Log::info('=== WEBHOOK MIDTRANS MASUK ===', $request->all());

        try {
            // 2. Pelayan lempar data ke Koki (Service)
            $paymentService->handleMidtransWebhook($request->all());

            // 3. Pelayan bilang OK ke Midtrans
            Log::info("Webhook SUKSES diproses.");
            return response()->json(['message' => 'Webhook success'], 200);

        } catch (\InvalidArgumentException $e) {
            Log::error("Webhook ERROR (Bad Request): " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);

        } catch (\RuntimeException $e) {
            Log::error("Webhook ERROR (Not Found): " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 404);

        } catch (\Exception $e) {
            Log::error("Webhook ERROR (Server): " . $e->getMessage());
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }
}