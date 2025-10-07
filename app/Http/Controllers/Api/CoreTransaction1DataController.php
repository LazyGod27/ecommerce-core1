<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CoreTransaction1DataExporter;
use App\Services\CoreTransaction1WebhookSender;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CoreTransaction1DataController extends Controller
{
    protected $dataExporter;
    protected $webhookSender;

    public function __construct(CoreTransaction1DataExporter $dataExporter, CoreTransaction1WebhookSender $webhookSender)
    {
        $this->dataExporter = $dataExporter;
        $this->webhookSender = $webhookSender;
    }

    /**
     * Export customer data for Core Transaction 2 & 3
     */
    public function exportCustomers(Request $request): JsonResponse
    {
        try {
            $filters = $request->only([
                'date_from', 'date_to', 'has_orders', 'per_page'
            ]);

            $data = $this->dataExporter->exportCustomerData(null, $filters);

            return response()->json([
                'success' => true,
                'data' => $data,
                'exported_at' => now()->toISOString()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to export customer data',
                'error' => 'export_failed',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export specific customer data
     */
    public function exportCustomer(Request $request, $customerId): JsonResponse
    {
        try {
            $filters = $request->only(['date_from', 'date_to']);
            $data = $this->dataExporter->exportCustomerData($customerId, $filters);

            return response()->json([
                'success' => true,
                'data' => $data['customers'][0] ?? null,
                'exported_at' => now()->toISOString()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to export customer data',
                'error' => 'export_failed',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export order data for Core Transaction 2 & 3
     */
    public function exportOrders(Request $request): JsonResponse
    {
        try {
            $filters = $request->only([
                'customer_id', 'status', 'payment_status', 'date_from', 'date_to', 'seller_id', 'per_page'
            ]);

            $data = $this->dataExporter->exportOrderData(null, $filters);

            return response()->json([
                'success' => true,
                'data' => $data,
                'exported_at' => now()->toISOString()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to export order data',
                'error' => 'export_failed',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export specific order data
     */
    public function exportOrder(Request $request, $orderId): JsonResponse
    {
        try {
            $filters = $request->only(['date_from', 'date_to']);
            $data = $this->dataExporter->exportOrderData($orderId, $filters);

            return response()->json([
                'success' => true,
                'data' => $data['orders'][0] ?? null,
                'exported_at' => now()->toISOString()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to export order data',
                'error' => 'export_failed',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export product data for Core Transaction 2 & 3
     */
    public function exportProducts(Request $request): JsonResponse
    {
        try {
            $filters = $request->only([
                'seller_id', 'category', 'status', 'in_stock', 'search', 'per_page'
            ]);

            $data = $this->dataExporter->exportProductData(null, $filters);

            return response()->json([
                'success' => true,
                'data' => $data,
                'exported_at' => now()->toISOString()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to export product data',
                'error' => 'export_failed',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export specific product data
     */
    public function exportProduct(Request $request, $productId): JsonResponse
    {
        try {
            $filters = $request->only(['date_from', 'date_to']);
            $data = $this->dataExporter->exportProductData($productId, $filters);

            return response()->json([
                'success' => true,
                'data' => $data['products'][0] ?? null,
                'exported_at' => now()->toISOString()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to export product data',
                'error' => 'export_failed',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export review data for Core Transaction 2 & 3
     */
    public function exportReviews(Request $request): JsonResponse
    {
        try {
            $filters = $request->only([
                'product_id', 'user_id', 'rating', 'status', 'date_from', 'date_to', 'per_page'
            ]);

            $data = $this->dataExporter->exportReviewData(null, $filters);

            return response()->json([
                'success' => true,
                'data' => $data,
                'exported_at' => now()->toISOString()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to export review data',
                'error' => 'export_failed',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export marketplace analytics for Core Transaction 2 & 3
     */
    public function exportAnalytics(Request $request): JsonResponse
    {
        try {
            $filters = $request->only(['date_from', 'date_to']);
            $data = $this->dataExporter->exportMarketplaceAnalytics($filters);

            return response()->json([
                'success' => true,
                'data' => $data,
                'exported_at' => now()->toISOString()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to export analytics data',
                'error' => 'export_failed',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get real-time events for Core Transaction 2 & 3
     */
    public function getRealTimeEvents(Request $request): JsonResponse
    {
        try {
            $lastEventId = $request->input('last_event_id', 0);
            $eventTypes = $request->input('event_types', []);

            $events = $this->dataExporter->exportRealTimeEvents($lastEventId, $eventTypes);

            return response()->json([
                'success' => true,
                'data' => [
                    'events' => $events,
                    'last_event_id' => $events->isNotEmpty() ? $events->last()['id'] : $lastEventId,
                    'count' => $events->count()
                ],
                'exported_at' => now()->toISOString()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to export real-time events',
                'error' => 'export_failed',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send webhook test to Core Transaction 2 & 3
     */
    public function testWebhooks(Request $request): JsonResponse
    {
        try {
            $system = $request->input('system', 'both');
            $results = [];

            if ($system === 'both' || $system === 'core_transaction_2') {
                $results['core_transaction_2'] = $this->webhookSender->testWebhookConnectivity('core_transaction_2');
            }

            if ($system === 'both' || $system === 'core_transaction_3') {
                $results['core_transaction_3'] = $this->webhookSender->testWebhookConnectivity('core_transaction_3');
            }

            return response()->json([
                'success' => true,
                'data' => $results,
                'tested_at' => now()->toISOString()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to test webhooks',
                'error' => 'test_failed',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get webhook status for both systems
     */
    public function getWebhookStatus(Request $request): JsonResponse
    {
        try {
            $status = $this->webhookSender->getWebhookStatus();

            return response()->json([
                'success' => true,
                'data' => $status,
                'checked_at' => now()->toISOString()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get webhook status',
                'error' => 'status_failed',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send manual webhook event
     */
    public function sendWebhookEvent(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'event' => 'required|string',
                'data' => 'required|array',
                'systems' => 'array',
                'systems.*' => 'in:core_transaction_2,core_transaction_3'
            ]);

            $systems = $validated['systems'] ?? ['core_transaction_2', 'core_transaction_3'];
            
            $this->webhookSender->sendWebhook($validated['event'], $validated['data'], $systems);

            return response()->json([
                'success' => true,
                'message' => 'Webhook event sent successfully',
                'data' => [
                    'event' => $validated['event'],
                    'systems' => $systems,
                    'sent_at' => now()->toISOString()
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
                'message' => 'Failed to send webhook event',
                'error' => 'send_failed',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get data export statistics
     */
    public function getExportStats(Request $request): JsonResponse
    {
        try {
            $stats = [
                'customers' => [
                    'total' => \App\Models\User::count(),
                    'with_orders' => \App\Models\User::has('orders')->count(),
                    'new_this_month' => \App\Models\User::whereMonth('created_at', now()->month)->count()
                ],
                'orders' => [
                    'total' => \App\Models\Order::count(),
                    'pending' => \App\Models\Order::where('status', 'pending')->count(),
                    'completed' => \App\Models\Order::where('status', 'completed')->count(),
                    'this_month' => \App\Models\Order::whereMonth('created_at', now()->month)->count()
                ],
                'products' => [
                    'total' => \App\Models\Product::count(),
                    'active' => \App\Models\Product::where('status', 'active')->count(),
                    'out_of_stock' => \App\Models\Product::where('stock', '<=', 0)->count()
                ],
                'reviews' => [
                    'total' => \App\Models\Review::count(),
                    'this_month' => \App\Models\Review::whereMonth('created_at', now()->month)->count(),
                    'average_rating' => \App\Models\Review::avg('rating') ?? 0
                ],
                'webhooks' => $this->webhookSender->getWebhookStatus()
            ];

            return response()->json([
                'success' => true,
                'data' => $stats,
                'generated_at' => now()->toISOString()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get export statistics',
                'error' => 'stats_failed',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
