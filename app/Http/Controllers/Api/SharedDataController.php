<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DataSyncService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SharedDataController extends Controller
{
    protected $dataSyncService;

    public function __construct(DataSyncService $dataSyncService)
    {
        $this->dataSyncService = $dataSyncService;
    }

    /**
     * Get shared data for both Core Transaction systems
     */
    public function getSharedData(Request $request): JsonResponse
    {
        try {
            $type = $request->input('type');
            $filters = $request->except(['type']);

            if (!$type) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data type is required',
                    'error' => 'missing_type'
                ], 400);
            }

            $data = $this->dataSyncService->getSharedData($type, $filters);

            return response()->json([
                'success' => true,
                'data' => $data,
                'timestamp' => now()->toISOString()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve shared data',
                'error' => 'data_retrieval_failed',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Sync seller data between systems
     */
    public function syncSellerData(Request $request, $sellerId): JsonResponse
    {
        try {
            $data = $request->all();
            $sellerData = $this->dataSyncService->syncSellerData($sellerId, $data);

            return response()->json([
                'success' => true,
                'message' => 'Seller data synced successfully',
                'data' => $sellerData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to sync seller data',
                'error' => 'sync_failed',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Sync product data between systems
     */
    public function syncProductData(Request $request, $productId): JsonResponse
    {
        try {
            $data = $request->all();
            $productData = $this->dataSyncService->syncProductData($productId, $data);

            return response()->json([
                'success' => true,
                'message' => 'Product data synced successfully',
                'data' => $productData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to sync product data',
                'error' => 'sync_failed',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Sync order data between systems
     */
    public function syncOrderData(Request $request, $orderId): JsonResponse
    {
        try {
            $data = $request->all();
            $orderData = $this->dataSyncService->syncOrderData($orderId, $data);

            return response()->json([
                'success' => true,
                'message' => 'Order data synced successfully',
                'data' => $orderData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to sync order data',
                'error' => 'sync_failed',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Sync earnings data between systems
     */
    public function syncEarningsData(Request $request, $sellerId): JsonResponse
    {
        try {
            $period = $request->input('period', 'month');
            $earningsData = $this->dataSyncService->syncEarningsData($sellerId, $period);

            return response()->json([
                'success' => true,
                'message' => 'Earnings data synced successfully',
                'data' => $earningsData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to sync earnings data',
                'error' => 'sync_failed',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Sync platform settings between systems
     */
    public function syncPlatformSettings(Request $request): JsonResponse
    {
        try {
            $platformData = $this->dataSyncService->syncPlatformSettings();

            return response()->json([
                'success' => true,
                'message' => 'Platform settings synced successfully',
                'data' => $platformData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to sync platform settings',
                'error' => 'sync_failed',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get real-time data updates via webhook
     */
    public function getRealtimeUpdates(Request $request): JsonResponse
    {
        try {
            $lastUpdate = $request->input('last_update');
            $events = $request->input('events', ['*']);

            // Get recent webhook events
            $query = \App\Models\WebhookEvent::with('endpoint')
                ->where('created_at', '>', $lastUpdate ? now()->parse($lastUpdate) : now()->subHour());

            if (!in_array('*', $events)) {
                $query->whereIn('event', $events);
            }

            $webhookEvents = $query->orderBy('created_at', 'desc')->limit(100)->get();

            $updates = $webhookEvents->map(function($event) {
                return [
                    'id' => $event->id,
                    'event' => $event->event,
                    'data' => $event->payload['data'] ?? null,
                    'timestamp' => $event->created_at->toISOString(),
                    'status' => $event->status
                ];
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'updates' => $updates,
                    'last_update' => now()->toISOString(),
                    'count' => $updates->count()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve real-time updates',
                'error' => 'updates_failed',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Register webhook endpoint for real-time updates
     */
    public function registerWebhook(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'url' => 'required|url',
                'events' => 'required|array',
                'events.*' => 'string',
                'secret' => 'nullable|string',
                'description' => 'nullable|string',
                'timeout' => 'nullable|integer|min:5|max:300',
                'headers' => 'nullable|array'
            ]);

            $webhook = \App\Models\WebhookEndpoint::create([
                'name' => $validated['name'],
                'url' => $validated['url'],
                'events' => $validated['events'],
                'secret' => $validated['secret'] ?? \Illuminate\Support\Str::random(32),
                'description' => $validated['description'],
                'timeout' => $validated['timeout'] ?? 30,
                'headers' => $validated['headers'] ?? [],
                'is_active' => true
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Webhook endpoint registered successfully',
                'data' => [
                    'id' => $webhook->id,
                    'name' => $webhook->name,
                    'url' => $webhook->url,
                    'events' => $webhook->events,
                    'secret' => $webhook->secret
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'error' => 'validation_failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to register webhook',
                'error' => 'registration_failed',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test webhook endpoint
     */
    public function testWebhook(Request $request, $webhookId): JsonResponse
    {
        try {
            $webhook = \App\Models\WebhookEndpoint::findOrFail($webhookId);

            $testData = [
                'event' => 'test',
                'timestamp' => now()->toISOString(),
                'data' => [
                    'message' => 'This is a test webhook',
                    'webhook_id' => $webhook->id
                ]
            ];

            $client = new \GuzzleHttp\Client();
            $response = $client->post($webhook->url, [
                'json' => $testData,
                'headers' => [
                    'X-Webhook-Signature' => hash_hmac('sha256', json_encode($testData), $webhook->secret),
                    'Content-Type' => 'application/json'
                ],
                'timeout' => $webhook->timeout ?? 30
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Webhook test successful',
                'data' => [
                    'status_code' => $response->getStatusCode(),
                    'response' => $response->getBody()->getContents()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Webhook test failed',
                'error' => 'test_failed',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
