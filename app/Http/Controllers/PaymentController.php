<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\GcashService;
use App\Services\PayMayaService;
use App\Services\StripeService;

class PaymentController extends Controller
{
    public function process($orderId)
    {
        $order = Order::with(['items.product', 'user'])
            ->where('id', $orderId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if ($order->payment_status === 'paid') {
            return redirect()->route('tracking.show', $order->id)
                ->with('info', 'This order has already been paid.');
        }

        return view('payment.process', compact('order'));
    }

    public function gcash(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id'
        ]);

        $order = Order::findOrFail($request->order_id);
        
        // Verify user owns this order
        if ($order->user_id !== auth()->id()) {
            return back()->with('error', 'Unauthorized access to this order.');
        }

        try {
            $gcashService = app(GcashService::class);
            $redirectUrl = $gcashService->createPayment(
                $order, 
                route('payment.callback', ['method' => 'gcash', 'order_id' => $order->id])
            );
            
            return redirect($redirectUrl);
            
        } catch (\Exception $e) {
            Log::error('GCash payment error: ' . $e->getMessage());
            return back()->with('error', 'Failed to initiate GCash payment. Please try again.');
        }
    }

    public function paymaya(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id'
        ]);

        $order = Order::findOrFail($request->order_id);
        
        if ($order->user_id !== auth()->id()) {
            return back()->with('error', 'Unauthorized access to this order.');
        }

        try {
            $paymayaService = app(PayMayaService::class);
            $redirectUrl = $paymayaService->createPayment(
                $order,
                route('payment.callback', ['method' => 'paymaya', 'order_id' => $order->id])
            );
            
            return redirect($redirectUrl);
            
        } catch (\Exception $e) {
            Log::error('PayMaya payment error: ' . $e->getMessage());
            return back()->with('error', 'Failed to initiate PayMaya payment. Please try again.');
        }
    }

    public function stripe(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'stripe_token' => 'required|string'
        ]);

        $order = Order::findOrFail($request->order_id);
        
        if ($order->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $stripeService = app(StripeService::class);
            $result = $stripeService->processPayment($order, $request->stripe_token);
            
            if ($result['success']) {
                $this->markOrderAsPaid($order, 'stripe', $result['payment_id']);
                return response()->json([
                    'success' => true,
                    'redirect_url' => route('payment.success', $order->id)
                ]);
            } else {
                return response()->json(['error' => $result['message']], 400);
            }
            
        } catch (\Exception $e) {
            Log::error('Stripe payment error: ' . $e->getMessage());
            return response()->json(['error' => 'Payment processing failed. Please try again.'], 500);
        }
    }

    public function callback(Request $request, $method, $orderId)
    {
        $order = Order::findOrFail($orderId);
        
        try {
            switch ($method) {
                case 'gcash':
                    $gcashService = app(GcashService::class);
                    $result = $gcashService->handleCallback($request->all(), $order);
                    break;
                    
                case 'paymaya':
                    $paymayaService = app(PayMayaService::class);
                    $result = $paymayaService->handleCallback($request->all(), $order);
                    break;
                    
                default:
                    throw new \Exception('Invalid payment method');
            }
            
            if ($result['success']) {
                $this->markOrderAsPaid($order, $method, $result['payment_id']);
                return redirect()->route('order.confirmation', $order->id);
            } else {
                return redirect()->route('payment.failed', $order->id)
                    ->with('error', $result['message']);
            }
            
        } catch (\Exception $e) {
            Log::error("Payment callback error ({$method}): " . $e->getMessage());
            return redirect()->route('payment.failed', $order->id)
                ->with('error', 'Payment verification failed. Please contact support.');
        }
    }

    public function success($orderId)
    {
        $order = Order::with(['items.product', 'tracking'])
            ->where('id', $orderId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('payment.success', compact('order'));
    }

    public function failed($orderId)
    {
        $order = Order::with(['items.product'])
            ->where('id', $orderId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('payment.failed', compact('order'));
    }

    public function webhook(Request $request, $method)
    {
        try {
            switch ($method) {
                case 'stripe':
                    $stripeService = app(StripeService::class);
                    $result = $stripeService->handleWebhook($request);
                    break;
                    
                case 'gcash':
                    $gcashService = app(GcashService::class);
                    $result = $gcashService->handleWebhook($request);
                    break;
                    
                case 'paymaya':
                    $paymayaService = app(PayMayaService::class);
                    $result = $paymayaService->handleWebhook($request);
                    break;
                    
                default:
                    return response()->json(['error' => 'Invalid payment method'], 400);
            }
            
            return response()->json(['status' => 'success']);
            
        } catch (\Exception $e) {
            Log::error("Payment webhook error ({$method}): " . $e->getMessage());
            return response()->json(['error' => 'Webhook processing failed'], 500);
        }
    }

    public function refund(Request $request, $orderId)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        $order = Order::where('id', $orderId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if ($order->payment_status !== 'paid') {
            return back()->with('error', 'This order has not been paid yet.');
        }

        if ($order->status === 'delivered') {
            return back()->with('error', 'Cannot refund delivered orders. Please contact customer service.');
        }

        try {
            DB::beginTransaction();

            // Process refund based on payment method
            $payment = Payment::where('order_id', $order->id)->first();
            
            if ($payment) {
                switch ($payment->payment_method) {
                    case 'stripe':
                        $stripeService = app(StripeService::class);
                        $stripeService->processRefund($payment->payment_id, $order->total);
                        break;
                        
                    case 'gcash':
                        $gcashService = app(GcashService::class);
                        $gcashService->processRefund($payment->payment_id, $order->total);
                        break;
                        
                    case 'paymaya':
                        $paymayaService = app(PayMayaService::class);
                        $paymayaService->processRefund($payment->payment_id, $order->total);
                        break;
                }
            }

            // Update order status
            $order->update([
                'status' => 'refunded',
                'refunded_at' => now(),
                'refund_reason' => $request->reason
            ]);

            // Restore product stock
            foreach ($order->items as $item) {
                $item->product->increment('stock', $item->quantity);
            }

            DB::commit();

            return redirect()->route('tracking.show', $order->id)
                ->with('success', 'Refund request processed successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Refund error: ' . $e->getMessage());
            return back()->with('error', 'Failed to process refund. Please contact support.');
        }
    }

    private function markOrderAsPaid($order, $method, $paymentId)
    {
        DB::transaction(function () use ($order, $method, $paymentId) {
            // Create payment record
            Payment::create([
                'order_id' => $order->id,
                'payment_method' => $method,
                'payment_id' => $paymentId,
                'amount' => $order->total,
                'status' => 'completed',
                'paid_at' => now()
            ]);

            // Update order
            $order->update([
                'payment_status' => 'paid',
                'status' => 'processing',
                'paid_at' => now()
            ]);

            // Update tracking status
            if ($order->tracking) {
                $trackingDetails = $order->tracking->tracking_details ?? [];
                $trackingDetails[] = [
                    'status' => 'processing',
                    'location' => 'Processing Center',
                    'timestamp' => now()->toISOString(),
                    'description' => 'Payment received. Order is being processed.'
                ];

                $order->tracking->update([
                    'status' => 'processing',
                    'tracking_details' => $trackingDetails
                ]);
            }
        });
    }

    public function getPaymentMethods()
    {
        return response()->json([
            'methods' => [
                [
                    'id' => 'gcash',
                    'name' => 'GCash',
                    'description' => 'Pay using your GCash wallet',
                    'icon' => 'gcash-icon.png',
                    'enabled' => true
                ],
                [
                    'id' => 'paymaya',
                    'name' => 'PayMaya',
                    'description' => 'Pay using your PayMaya wallet',
                    'icon' => 'paymaya-icon.png',
                    'enabled' => true
                ],
                [
                    'id' => 'stripe',
                    'name' => 'Credit/Debit Card',
                    'description' => 'Pay using your credit or debit card',
                    'icon' => 'card-icon.png',
                    'enabled' => true
                ],
                [
                    'id' => 'cod',
                    'name' => 'Cash on Delivery',
                    'description' => 'Pay when you receive your order',
                    'icon' => 'cod-icon.png',
                    'enabled' => true
                ]
            ]
        ]);
    }
}
