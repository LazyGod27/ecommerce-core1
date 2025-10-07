<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ShippingService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ShippingWebhookController extends Controller
{
    protected $shippingService;

    public function __construct(ShippingService $shippingService)
    {
        $this->shippingService = $shippingService;
    }

    /**
     * Handle J&T Express webhook
     */
    public function handleJntWebhook(Request $request): JsonResponse
    {
        return $this->handleProviderWebhook('jnt', $request);
    }

    /**
     * Handle Ninja Van webhook
     */
    public function handleNinjaVanWebhook(Request $request): JsonResponse
    {
        return $this->handleProviderWebhook('ninjavan', $request);
    }

    /**
     * Handle LBC Express webhook
     */
    public function handleLbcWebhook(Request $request): JsonResponse
    {
        return $this->handleProviderWebhook('lbc', $request);
    }

    /**
     * Handle Flash Express webhook
     */
    public function handleFlashWebhook(Request $request): JsonResponse
    {
        return $this->handleProviderWebhook('flash', $request);
    }

    /**
     * Handle provider webhook
     */
    private function handleProviderWebhook(string $provider, Request $request): JsonResponse
    {
        try {
            // Verify webhook signature if configured
            if (!$this->verifyWebhookSignature($provider, $request)) {
                Log::warning('Invalid webhook signature', [
                    'provider' => $provider,
                    'ip' => $request->ip()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Invalid signature'
                ], 401);
            }

            $webhookData = $request->all();
            
            Log::info('Received shipping webhook', [
                'provider' => $provider,
                'data' => $webhookData
            ]);

            $result = $this->shippingService->handleProviderWebhook($provider, $webhookData);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'Webhook processed successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to process webhook',
                    'error' => $result['error']
                ], 400);
            }

        } catch (\Exception $e) {
            Log::error('Webhook processing failed', [
                'provider' => $provider,
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify webhook signature
     */
    private function verifyWebhookSignature(string $provider, Request $request): bool
    {
        $secret = config("shipping.{$provider}.webhook_secret");
        
        if (!$secret) {
            // If no secret is configured, allow the webhook
            return true;
        }

        $signature = $request->header('X-Webhook-Signature');
        $payload = $request->getContent();
        $expectedSignature = hash_hmac('sha256', $payload, $secret);

        return hash_equals($expectedSignature, $signature);
    }
}











