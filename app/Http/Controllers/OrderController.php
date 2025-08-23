<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function confirmation($orderId)
    {
        $user = Auth::user();
        $order = Order::with(['tracking', 'items.product', 'user'])
            ->where('id', $orderId)
            ->where('user_id', $user->id)
            ->firstOrFail();
            
        return view('order-confirmation', compact('order'));
    }

    public function cancel(Request $request, $orderId)
    {
        $user = Auth::user();
        $order = Order::where('id', $orderId)
            ->where('user_id', $user->id)
            ->firstOrFail();

        if (!$order->canBeCancelled()) {
            return back()->with('error', 'This order cannot be cancelled.');
        }

        $request->validate([
            'cancellation_reason' => 'required|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            // Update order status
            $order->update([
                'status' => 'cancelled',
                'notes' => 'Cancelled: ' . $request->cancellation_reason
            ]);

            // Restore product stock
            foreach ($order->items as $item) {
                $product = $item->product;
                $product->increment('stock', $item->quantity);
            }

            // Update tracking status
            if ($order->tracking) {
                $trackingDetails = $order->tracking->tracking_details ?? [];
                $trackingDetails[] = [
                    'status' => 'Order Cancelled',
                    'location' => 'System',
                    'timestamp' => now()->toISOString(),
                    'description' => 'Order cancelled by customer: ' . $request->cancellation_reason
                ];

                $order->tracking->update([
                    'status' => 'cancelled',
                    'tracking_details' => $trackingDetails
                ]);
            }

            DB::commit();

            return redirect()->route('tracking')->with('success', 'Order cancelled successfully. Your refund will be processed within 3-5 business days.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order cancellation error: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while cancelling the order. Please try again.');
        }
    }

    public function edit(Request $request, $orderId)
    {
        $user = Auth::user();
        $order = Order::where('id', $orderId)
            ->where('user_id', $user->id)
            ->firstOrFail();

        if (!$order->canBeEdited()) {
            return back()->with('error', 'This order cannot be edited.');
        }

        $request->validate([
            'shipping_address' => 'required|string|max:500',
            'contact_number' => 'required|string|max:20',
            'email' => 'required|email'
        ]);

        try {
            DB::beginTransaction();

            // Update order details
            $order->update([
                'shipping_address' => $request->shipping_address,
                'contact_number' => $request->contact_number,
                'email' => $request->email
            ]);

            // Update tracking details
            if ($order->tracking) {
                $trackingDetails = $order->tracking->tracking_details ?? [];
                $trackingDetails[] = [
                    'status' => 'Order Updated',
                    'location' => 'System',
                    'timestamp' => now()->toISOString(),
                    'description' => 'Order details updated by customer'
                ];

                $order->tracking->update([
                    'tracking_details' => $trackingDetails
                ]);
            }

            DB::commit();

            return redirect()->route('order.confirmation', $order->id)->with('success', 'Order updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order edit error: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while updating the order. Please try again.');
        }
    }
}
