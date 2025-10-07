<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CustomerOrderController extends Controller
{
    /**
     * Display orders waiting for customer response
     */
    public function waitingForResponse()
    {
        $orders = Order::where('user_id', Auth::id())
            ->waitingForResponse()
            ->with(['items.product', 'tracking'])
            ->orderBy('delivery_confirmation_deadline', 'asc')
            ->get();

        return view('customer.orders.waiting-response', compact('orders'));
    }

    /**
     * Display order details for response
     */
    public function showResponseForm(Order $order)
    {
        // Ensure the order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to order');
        }

        // Ensure the order is waiting for response
        if (!$order->isWaitingForCustomerResponse()) {
            return redirect()->route('orders.show', $order)
                ->with('error', 'This order is not waiting for your response.');
        }

        $order->load(['items.product', 'tracking', 'user']);

        return view('customer.orders.response-form', compact('order'));
    }

    /**
     * Customer confirms item received
     */
    public function confirmReceived(Request $request, Order $order): JsonResponse
    {
        // Validate the order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to order'
            ], 403);
        }

        // Validate the order is waiting for response
        if (!$order->isWaitingForCustomerResponse()) {
            return response()->json([
                'success' => false,
                'message' => 'This order is not waiting for your response'
            ], 400);
        }

        try {
            // Confirm the order as received
            $order->confirmReceived();

            // Log the action
            Log::info("Order {$order->id} confirmed as received by customer", [
                'order_number' => $order->order_number,
                'customer_id' => $order->user_id,
                'confirmed_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Order confirmed as received successfully!',
                'order' => [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'status' => $order->status,
                    'delivery_status' => $order->delivery_status
                ]
            ]);

        } catch (\Exception $e) {
            Log::error("Failed to confirm order {$order->id} as received: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to confirm order. Please try again.'
            ], 500);
        }
    }

    /**
     * Customer requests return/refund
     */
    public function requestReturn(Request $request, Order $order): JsonResponse
    {
        // Validate the order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to order'
            ], 403);
        }

        // Validate the order is waiting for response
        if (!$order->isWaitingForCustomerResponse()) {
            return response()->json([
                'success' => false,
                'message' => 'This order is not waiting for your response'
            ], 400);
        }

        // Validate the request
        $request->validate([
            'reason' => 'required|string|min:10|max:1000'
        ], [
            'reason.required' => 'Please provide a reason for the return request',
            'reason.min' => 'Reason must be at least 10 characters long',
            'reason.max' => 'Reason must not exceed 1000 characters'
        ]);

        try {
            // Request return for the order
            $order->requestReturn($request->reason);

            // Log the action
            Log::info("Return requested for order {$order->id}", [
                'order_number' => $order->order_number,
                'customer_id' => $order->user_id,
                'reason' => $request->reason,
                'requested_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Return request submitted successfully! We will review your request and get back to you soon.',
                'order' => [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'status' => $order->status,
                    'delivery_status' => $order->delivery_status,
                    'return_status' => $order->return_status
                ]
            ]);

        } catch (\Exception $e) {
            Log::error("Failed to request return for order {$order->id}: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit return request. Please try again.'
            ], 500);
        }
    }

    /**
     * Get order status for AJAX requests
     */
    public function getOrderStatus(Order $order): JsonResponse
    {
        // Validate the order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to order'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'order' => [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'status' => $order->status,
                'delivery_status' => $order->delivery_status,
                'return_status' => $order->return_status,
                'delivery_confirmation_deadline' => $order->delivery_confirmation_deadline?->toISOString(),
                'is_waiting_for_response' => $order->isWaitingForCustomerResponse(),
                'has_deadline_passed' => $order->hasDeadlinePassed()
            ]
        ]);
    }

    /**
     * Display return request form
     */
    public function showReturnForm(Order $order)
    {
        // Ensure the order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to order');
        }

        // Ensure the order is waiting for response
        if (!$order->isWaitingForCustomerResponse()) {
            return redirect()->route('orders.show', $order)
                ->with('error', 'This order is not waiting for your response.');
        }

        $order->load(['items.product', 'tracking', 'user']);

        return view('customer.orders.return-form', compact('order'));
    }

    /**
     * Display orders with return requests
     */
    public function returnRequests()
    {
        $orders = Order::where('user_id', Auth::id())
            ->withReturnRequests()
            ->with(['items.product', 'tracking'])
            ->orderBy('return_requested_at', 'desc')
            ->get();

        return view('customer.orders.return-requests', compact('orders'));
    }

    /**
     * Get return request details
     */
    public function getReturnDetails(Order $order): JsonResponse
    {
        // Validate the order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to order'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'return_request' => [
                'reason' => $order->return_reason,
                'status' => $order->return_status,
                'requested_at' => $order->return_requested_at?->toISOString(),
                'processed_at' => $order->return_processed_at?->toISOString(),
                'status_badge_class' => $order->getReturnStatusBadgeClass()
            ]
        ]);
    }
}
