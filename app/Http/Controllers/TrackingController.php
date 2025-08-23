<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Tracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class TrackingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $orders = Order::with(['tracking', 'items.product'])
            ->where('user_id', $user->id)
            ->where('status', '!=', 'cancelled')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('tracking', compact('orders'));
    }

    public function show($orderId)
    {
        $user = Auth::user();
        $order = Order::with(['tracking', 'items.product', 'user'])
            ->where('id', $orderId)
            ->where('user_id', $user->id)
            ->firstOrFail();
            
        return view('tracking.show', compact('order'));
    }

    public function track(Request $request)
    {
        $request->validate([
            'tracking_number' => 'required|string|max:50'
        ]);

        $tracking = Tracking::with(['order.items.product', 'order.user'])
            ->where('tracking_number', $request->tracking_number)
            ->first();
        
        if (!$tracking) {
            return back()->with('error', 'Tracking number not found. Please check and try again.');
        }

        // Check if user is authorized to view this order
        if (Auth::check() && $tracking->order->user_id !== Auth::id()) {
            return back()->with('error', 'You are not authorized to view this order.');
        }

        return view('tracking.show', compact('tracking'));
    }

    public function trackByOrderNumber(Request $request)
    {
        $request->validate([
            'order_number' => 'required|string|max:50'
        ]);

        $order = Order::with(['tracking', 'items.product', 'user'])
            ->where('order_number', $request->order_number)
            ->first();
        
        if (!$order) {
            return back()->with('error', 'Order number not found. Please check and try again.');
        }

        // Check if user is authorized to view this order
        if (Auth::check() && $order->user_id !== Auth::id()) {
            return back()->with('error', 'You are not authorized to view this order.');
        }

        return view('tracking.show', compact('order'));
    }

    public function getTrackingUpdates($trackingId)
    {
        $tracking = Tracking::findOrFail($trackingId);
        
        // Check if user is authorized
        if (Auth::check() && $tracking->order->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Simulate real-time updates (in production, this would integrate with shipping APIs)
        $updates = $this->simulateTrackingUpdates($tracking);
        
        return response()->json([
            'tracking' => $tracking,
            'updates' => $updates,
            'last_updated' => now()->toISOString()
        ]);
    }

    public function updateTrackingStatus(Request $request, $trackingId)
    {
        $request->validate([
            'status' => 'required|string|in:pending,processing,shipped,in_transit,out_for_delivery,delivered,failed',
            'location' => 'required|string|max:255',
            'description' => 'required|string|max:500'
        ]);

        $tracking = Tracking::findOrFail($trackingId);
        
        // Add new tracking detail
        $trackingDetails = $tracking->tracking_details ?? [];
        $trackingDetails[] = [
            'status' => $request->status,
            'location' => $request->location,
            'timestamp' => now()->toISOString(),
            'description' => $request->description
        ];

        $tracking->update([
            'status' => $request->status,
            'current_location' => $request->location,
            'tracking_details' => $trackingDetails
        ]);

        // Update order status if delivered
        if ($request->status === 'delivered') {
            $tracking->order->update([
                'status' => 'completed',
                'delivered_at' => now()
            ]);
        }

        // Clear cache
        Cache::forget("tracking_{$trackingId}");

        return response()->json([
            'success' => true,
            'message' => 'Tracking status updated successfully'
        ]);
    }

    public function getOrderHistory()
    {
        $user = Auth::user();
        $orders = Order::with(['tracking', 'items.product'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return response()->json($orders);
    }

    public function downloadInvoice($orderId)
    {
        $user = Auth::user();
        $order = Order::with(['items.product', 'user'])
            ->where('id', $orderId)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Generate PDF invoice (you would implement this)
        // For now, return a simple response
        return response()->json([
            'message' => 'Invoice download feature will be implemented',
            'order' => $order
        ]);
    }

    private function simulateTrackingUpdates($tracking)
    {
        $statuses = [
            'pending' => ['icon' => '📋', 'color' => 'gray'],
            'processing' => ['icon' => '⚙️', 'color' => 'blue'],
            'shipped' => ['icon' => '📦', 'color' => 'green'],
            'in_transit' => ['icon' => '🚚', 'color' => 'orange'],
            'out_for_delivery' => ['icon' => '🛵', 'color' => 'purple'],
            'delivered' => ['icon' => '✅', 'color' => 'green'],
            'failed' => ['icon' => '❌', 'color' => 'red']
        ];

        $currentStatus = $tracking->status;
        $updates = [];

        foreach ($statuses as $status => $info) {
            $isCompleted = array_search($status, array_keys($statuses)) <= array_search($currentStatus, array_keys($statuses));
            $updates[] = [
                'status' => $status,
                'icon' => $info['icon'],
                'color' => $info['color'],
                'completed' => $isCompleted,
                'current' => $status === $currentStatus
            ];
        }

        return $updates;
    }

    public function getEstimatedDelivery($trackingId)
    {
        $tracking = Tracking::findOrFail($trackingId);
        
        $estimatedDelivery = $tracking->estimated_delivery;
        $now = now();
        
        if ($estimatedDelivery->isPast()) {
            $status = 'overdue';
            $message = 'Delivery is overdue. Please contact customer service.';
        } elseif ($estimatedDelivery->diffInDays($now) <= 1) {
            $status = 'today';
            $message = 'Expected delivery today!';
        } else {
            $status = 'upcoming';
            $message = "Expected delivery in {$estimatedDelivery->diffInDays($now)} days";
        }

        return response()->json([
            'estimated_delivery' => $estimatedDelivery->toISOString(),
            'status' => $status,
            'message' => $message,
            'days_remaining' => max(0, $estimatedDelivery->diffInDays($now))
        ]);
    }
}
