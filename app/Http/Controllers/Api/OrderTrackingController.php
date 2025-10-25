<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Tracking;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class OrderTrackingController extends Controller
{
    /**
     * Create tracking for an order
     * POST /api/external/orders/{orderId}/tracking
     */
    public function createTracking(Request $request, $orderId): JsonResponse
    {
        $order = Order::findOrFail($orderId);

        $validator = Validator::make($request->all(), [
            'tracking_number' => 'required|string|max:100',
            'carrier' => 'required|string|max:100',
            'carrier_code' => 'nullable|string|max:50',
            'status' => 'required|in:pending,shipped,in_transit,out_for_delivery,delivered,delivery_attempted,returned,cancelled',
            'status_description' => 'nullable|string|max:255',
            'estimated_delivery' => 'nullable|date',
            'shipping_address' => 'nullable|array',
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|array',
            'shipping_cost' => 'nullable|numeric|min:0',
            'insurance_amount' => 'nullable|numeric|min:0',
            'special_instructions' => 'nullable|string',
            'signature_required' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Check if tracking already exists
            if ($order->tracking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tracking already exists for this order',
                    'error' => 'tracking_exists'
                ], 409);
            }

            $trackingData = $request->all();
            $trackingData['order_id'] = $orderId;
            $trackingData['shipped_at'] = $request->get('status') === 'shipped' ? now() : null;
            $trackingData['updated_by'] = 'external_api';

            $tracking = Tracking::create($trackingData);

            // Add initial history entry
            $tracking->addHistoryEntry(
                $tracking->status,
                $tracking->status_description ?? 'Tracking created',
                null,
                now()
            );

            DB::commit();

            Log::info('Tracking created via external API', [
                'tracking_id' => $tracking->id,
                'order_id' => $orderId,
                'tracking_number' => $tracking->tracking_number,
                'carrier' => $tracking->carrier,
                'created_by' => 'external_api'
            ]);

            // Trigger webhook
            \App\Http\Controllers\Api\WebhookController::sendWebhook('tracking.created', [
                'tracking_id' => $tracking->id,
                'order_id' => $orderId,
                'tracking_number' => $tracking->tracking_number,
                'carrier' => $tracking->carrier,
                'status' => $tracking->status
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tracking created successfully',
                'data' => $tracking->load('order')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to create tracking via external API', [
                'order_id' => $orderId,
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create tracking',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update tracking status
     * PATCH /api/external/tracking/{trackingId}/status
     */
    public function updateTrackingStatus(Request $request, $trackingId): JsonResponse
    {
        $tracking = Tracking::findOrFail($trackingId);

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,shipped,in_transit,out_for_delivery,delivered,delivery_attempted,returned,cancelled',
            'status_description' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'estimated_delivery' => 'nullable|date',
            'actual_delivery' => 'nullable|date',
            'delivery_notes' => 'nullable|string',
            'delivery_attempts' => 'nullable|integer|min:0',
            'signature_received' => 'nullable|boolean',
            'delivery_photo' => 'nullable|url'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $oldStatus = $tracking->status;
            $newStatus = $request->get('status');

            // Update tracking status
            $tracking->updateStatus(
                $newStatus,
                $request->get('status_description'),
                $request->get('location')
            );

            // Update additional fields
            $updateData = $request->only([
                'estimated_delivery', 'actual_delivery', 'delivery_notes',
                'delivery_attempts', 'signature_received', 'delivery_photo'
            ]);

            if (!empty($updateData)) {
                $tracking->update($updateData);
            }

            DB::commit();

            Log::info('Tracking status updated via external API', [
                'tracking_id' => $trackingId,
                'order_id' => $tracking->order_id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'updated_by' => 'external_api'
            ]);

            // Trigger webhook
            \App\Http\Controllers\Api\WebhookController::sendWebhook('tracking.updated', [
                'tracking_id' => $trackingId,
                'order_id' => $tracking->order_id,
                'tracking_number' => $tracking->tracking_number,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'carrier' => $tracking->carrier
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tracking status updated successfully',
                'data' => $tracking->fresh()
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to update tracking status via external API', [
                'tracking_id' => $trackingId,
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update tracking status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add tracking history entry
     * POST /api/external/tracking/{trackingId}/history
     */
    public function addTrackingHistory(Request $request, $trackingId): JsonResponse
    {
        $tracking = Tracking::findOrFail($trackingId);

        $validator = Validator::make($request->all(), [
            'status' => 'required|string|max:100',
            'description' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'timestamp' => 'nullable|date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $tracking->addHistoryEntry(
                $request->get('status'),
                $request->get('description'),
                $request->get('location'),
                $request->get('timestamp')
            );

            Log::info('Tracking history added via external API', [
                'tracking_id' => $trackingId,
                'order_id' => $tracking->order_id,
                'status' => $request->get('status'),
                'added_by' => 'external_api'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tracking history added successfully',
                'data' => $tracking->fresh()
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to add tracking history via external API', [
                'tracking_id' => $trackingId,
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to add tracking history',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get tracking information
     * GET /api/external/tracking/{trackingId}
     */
    public function getTracking($trackingId): JsonResponse
    {
        try {
            $tracking = Tracking::with(['order.user', 'order.items.product'])
                              ->findOrFail($trackingId);

            return response()->json([
                'success' => true,
                'data' => $tracking
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tracking not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Get tracking by tracking number
     * GET /api/external/tracking/number/{trackingNumber}
     */
    public function getTrackingByNumber($trackingNumber): JsonResponse
    {
        try {
            $tracking = Tracking::with(['order.user', 'order.items.product'])
                              ->where('tracking_number', $trackingNumber)
                              ->firstOrFail();

            return response()->json([
                'success' => true,
                'data' => $tracking
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tracking not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Get order tracking
     * GET /api/external/orders/{orderId}/tracking
     */
    public function getOrderTracking($orderId): JsonResponse
    {
        try {
            $order = Order::with(['tracking', 'user', 'items.product'])->findOrFail($orderId);

            if (!$order->tracking) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tracking information available for this order'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $order->tracking
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Bulk update tracking statuses
     * POST /api/external/tracking/bulk-update
     */
    public function bulkUpdateTracking(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'updates' => 'required|array|min:1|max:100',
            'updates.*.tracking_id' => 'required|exists:trackings,id',
            'updates.*.status' => 'required|in:pending,shipped,in_transit,out_for_delivery,delivered,delivery_attempted,returned,cancelled',
            'updates.*.status_description' => 'nullable|string|max:255',
            'updates.*.location' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $updatedTrackings = [];
            $failedUpdates = [];

            foreach ($request->updates as $index => $updateData) {
                try {
                    $tracking = Tracking::findOrFail($updateData['tracking_id']);
                    $oldStatus = $tracking->status;

                    $tracking->updateStatus(
                        $updateData['status'],
                        $updateData['status_description'] ?? null,
                        $updateData['location'] ?? null
                    );

                    $updatedTrackings[] = [
                        'tracking_id' => $tracking->id,
                        'order_id' => $tracking->order_id,
                        'old_status' => $oldStatus,
                        'new_status' => $updateData['status']
                    ];

                } catch (\Exception $e) {
                    $failedUpdates[] = [
                        'index' => $index,
                        'tracking_id' => $updateData['tracking_id'],
                        'error' => $e->getMessage()
                    ];
                }
            }

            DB::commit();

            Log::info('Bulk tracking update via external API', [
                'updated_count' => count($updatedTrackings),
                'failed_count' => count($failedUpdates),
                'updated_by' => 'external_api'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Bulk tracking update completed',
                'data' => [
                    'updated_trackings' => $updatedTrackings,
                    'failed_updates' => $failedUpdates,
                    'summary' => [
                        'total_requested' => count($request->updates),
                        'successfully_updated' => count($updatedTrackings),
                        'failed' => count($failedUpdates)
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to bulk update tracking via external API', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to bulk update tracking',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get tracking statistics
     * GET /api/external/tracking/stats
     */
    public function getTrackingStats(Request $request): JsonResponse
    {
        try {
            $query = Tracking::query();

            // Apply filters
            if ($request->get('carrier')) {
                $query->where('carrier', $request->get('carrier'));
            }

            if ($request->get('status')) {
                $query->where('status', $request->get('status'));
            }

            if ($request->get('date_from')) {
                $query->where('created_at', '>=', $request->get('date_from'));
            }

            if ($request->get('date_to')) {
                $query->where('created_at', '<=', $request->get('date_to'));
            }

            $stats = [
                'total_trackings' => $query->count(),
                'by_status' => $query->groupBy('status')->selectRaw('status, count(*) as count')->get(),
                'by_carrier' => $query->groupBy('carrier')->selectRaw('carrier, count(*) as count')->get(),
                'delivered_count' => $query->where('status', 'delivered')->count(),
                'in_transit_count' => $query->inTransit()->count(),
                'pending_delivery_count' => $query->pendingDelivery()->count()
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to get tracking stats via external API', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to get tracking statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}


























