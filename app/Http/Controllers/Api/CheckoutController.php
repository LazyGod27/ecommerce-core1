<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\OrderResource;
use App\Jobs\ProcessOrder;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        // Validate the request
        $request->validate([
            'shipping_address' => 'required|string',
            'payment_method' => 'required|in:credit_card,paypal,bank_transfer'
        ]);

        // Get the authenticated user
        $user = $request->user();

        // Retrieve user's cart with product details
        $cart = Cart::with(['items.product'])
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Check if cart has any items
        if ($cart->items->isEmpty()) {
            return response()->json(['message' => 'Your cart is empty'], 400);
        }

        return DB::transaction(function () use ($user, $cart, $request) {
            // Calculate subtotal
            $subtotal = $cart->items->sum(function ($item) {
                return $item->quantity * $item->product->price;
            });

            // Optional: set tax/shipping cost here
            $tax = 0;
            $shipping = 0;

            // Create the order
            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'shipping_address' => $request->shipping_address,
                'payment_method' => $request->payment_method,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping_cost' => $shipping,
                'total' => $subtotal + $tax + $shipping,
            ]);

            // Create each order item from cart
            foreach ($cart->items as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);
            }

            // Clear cart after order is placed
            $cart->items()->delete();

            // Process payment (simulated)
            $this->processPayment($order, $request);

            // Return formatted order as JSON
            return new OrderResource($order);
        });
    }

    protected function processPayment($order, $request)
    {
        // In a real application, integrate with Stripe, PayPal, etc.
        $order->update(['status' => 'processing']);

        // Optionally dispatch background job for fulfillment
        ProcessOrder::dispatch($order)->delay(now()->addMinutes(5));
    }
}
