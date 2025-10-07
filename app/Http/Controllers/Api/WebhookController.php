<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WebhookEndpoint;
use App\Models\WebhookEvent;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    /**
     * Register a webhook endpoint
     * POST /api/webhooks/register
     */
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'url' => 'required|url',
            'events' => 'required|array',
            'events.*' => 'in:product.created,product.updated,product.deleted,product.stock_updated,tracking.created,tracking.updated,tracking.delivered,order.status_updated',
            'secret' => 'nullable|string',
            'description' => 'nullable|string'
        ]);

        $webhook = WebhookEndpoint::create([
            'url' => $validated['url'],
            'events' => $validated['events'],
            'secret' => $validated['secret'] ?? null,
            'description' => $validated['description'] ?? null,
            'is_active' => true
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Webhook registered successfully',
            'data' => $webhook
        ], 201);
    }

    /**
     * Send webhook event
     */
    public static function sendWebhook($event, $data, $sellerId = null)
    {
        $webhooks = WebhookEndpoint::where('is_active', true)
            ->whereJsonContains('events', $event)
            ->get();

        foreach ($webhooks as $webhook) {
            try {
                $payload = [
                    'event' => $event,
                    'timestamp' => now()->toISOString(),
                    'data' => $data
                ];

                $headers = [
                    'Content-Type' => 'application/json',
                    'User-Agent' => 'Ecommerce-Platform-Webhook/1.0'
                ];

                if ($webhook->secret) {
                    $signature = hash_hmac('sha256', json_encode($payload), $webhook->secret);
                    $headers['X-Webhook-Signature'] = 'sha256=' . $signature;
                }

                $response = Http::timeout(30)
                    ->withHeaders($headers)
                    ->post($webhook->url, $payload);

                // Log webhook event
                WebhookEvent::create([
                    'webhook_endpoint_id' => $webhook->id,
                    'event' => $event,
                    'payload' => $payload,
                    'response_status' => $response->status(),
                    'response_body' => $response->body(),
                    'attempted_at' => now()
                ]);

                if ($response->successful()) {
                    Log::info('Webhook sent successfully', [
                        'webhook_id' => $webhook->id,
                        'event' => $event,
                        'url' => $webhook->url
                    ]);
                } else {
                    Log::warning('Webhook failed', [
                        'webhook_id' => $webhook->id,
                        'event' => $event,
                        'url' => $webhook->url,
                        'status' => $response->status(),
                        'response' => $response->body()
                    ]);
                }

            } catch (\Exception $e) {
                Log::error('Webhook exception', [
                    'webhook_id' => $webhook->id,
                    'event' => $event,
                    'url' => $webhook->url,
                    'error' => $e->getMessage()
                ]);

                WebhookEvent::create([
                    'webhook_endpoint_id' => $webhook->id,
                    'event' => $event,
                    'payload' => $payload,
                    'response_status' => 0,
                    'response_body' => $e->getMessage(),
                    'attempted_at' => now()
                ]);
            }
        }
    }
}
