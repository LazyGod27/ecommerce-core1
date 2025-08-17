<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;

class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent(
                $payload, $sigHeader, $endpointSecret
            );
        } catch (SignatureVerificationException $e) {
            Log::error('Stripe webhook signature verification failed: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid signature'], 403);
        } catch (\Exception $e) {
            Log::error('Stripe webhook error: ' . $e->getMessage());
            return response()->json(['error' => 'Webhook error'], 400);
        }

        // Handle the event
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $this->handlePaymentSucceeded($event->data->object);
                break;
                
            case 'payment_intent.payment_failed':
                $this->handlePaymentFailed($event->data->object);
                break;
                
            case 'charge.refunded':
                $this->handleRefundProcessed($event->data->object);
                break;
                
            default:
                Log::info('Unhandled Stripe event: ' . $event->type);
        }

        return response()->json(['status' => 'success']);
    }

    protected function handlePaymentSucceeded($paymentIntent)
    {
        $orderId = $paymentIntent->metadata->order_id ?? null;
        
        if (!$orderId) {
            Log::warning('Payment succeeded but no order_id in metadata', ['payment_intent' => $paymentIntent->id]);
            return;
        }

        $order = Order::find($orderId);
        if (!$order) {
            Log::warning('Order not found for payment intent', ['order_id' => $orderId, 'payment_intent' => $paymentIntent->id]);
            return;
        }

        $order->update([
            'payment_status' => 'paid',
            'payment_id' => $paymentIntent->id,
            'paid_at' => now()
        ]);

        Log::info('Order marked as paid', ['order_id' => $orderId, 'payment_intent' => $paymentIntent->id]);
    }

    protected function handlePaymentFailed($paymentIntent)
    {
        $orderId = $paymentIntent->metadata->order_id ?? null;
        
        if (!$orderId) {
            Log::warning('Payment failed but no order_id in metadata', ['payment_intent' => $paymentIntent->id]);
            return;
        }

        $order = Order::find($orderId);
        if (!$order) {
            Log::warning('Order not found for failed payment intent', ['order_id' => $orderId, 'payment_intent' => $paymentIntent->id]);
            return;
        }

        $order->update([
            'payment_status' => 'failed',
            'payment_error' => $paymentIntent->last_payment_error->message ?? 'Payment failed'
        ]);

        Log::info('Order marked as payment failed', ['order_id' => $orderId, 'payment_intent' => $paymentIntent->id]);
    }

    protected function handleRefundProcessed($charge)
    {
        $orderId = $charge->metadata->order_id ?? null;
        
        if (!$orderId) {
            Log::warning('Refund processed but no order_id in metadata', ['charge' => $charge->id]);
            return;
        }

        $order = Order::find($orderId);
        if (!$order) {
            Log::warning('Order not found for refund', ['order_id' => $orderId, 'charge' => $charge->id]);
            return;
        }

        $order->update([
            'payment_status' => 'refunded',
            'refunded_at' => now()
        ]);

        Log::info('Order marked as refunded', ['order_id' => $orderId, 'charge' => $charge->id]);
    }
}