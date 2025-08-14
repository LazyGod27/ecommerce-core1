<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmation;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Exception\ApiErrorException;

class PaymentController extends Controller
{
    /**
     * Create a payment intent for Stripe
     */
    public function createPaymentIntent(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.5',
            'order_id' => 'required|exists:orders,id'
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));
        
        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $this->convertToCents($request->amount),
                'currency' => 'usd',
                'metadata' => [
                    'order_id' => $request->order_id,
                    'user_id' => auth()->id()
                ],
                'receipt_email' => auth()->user()->email,
                'setup_future_usage' => 'off_session'
            ]);
            
            return response()->json([
                'clientSecret' => $paymentIntent->client_secret,
                'paymentIntentId' => $paymentIntent->id
            ]);
            
        } catch (ApiErrorException $e) {
            return $this->handleStripeError($e);
        }
    }
    
    /**
     * Process payment based on selected method
     */
    public function processPayment(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'payment_method' => 'required|in:card,paypal',
            'payment_method_id' => 'required_if:payment_method,card'
        ]);

        $order = Order::findOrFail($request->order_id);
        $this->authorize('pay', $order);
        
        if ($order->payment_status === 'paid') {
            return response()->json(['error' => 'Order already paid'], 400);
        }

        return $request->payment_method === 'card' 
            ? $this->processCardPayment($order, $request)
            : $this->processPaypalPayment($order, $request);
    }
    
    /**
     * Process credit card payment via Stripe
     */
    protected function processCardPayment(Order $order, Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));
        
        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $this->convertToCents($order->total),
                'currency' => 'usd',
                'payment_method' => $request->payment_method_id,
                'confirmation_method' => 'manual',
                'confirm' => true,
                'metadata' => [
                    'order_id' => $order->id,
                    'user_id' => $order->user_id
                ],
                'receipt_email' => $order->user->email,
                'return_url' => config('app.url') . '/order-complete/' . $order->id
            ]);
            
            if ($paymentIntent->status === 'succeeded') {
                $this->markOrderAsPaid($order, $paymentIntent);
                return response()->json(['success' => true]);
            }
            
            if ($paymentIntent->status === 'requires_action') {
                return response()->json([
                    'requires_action' => true,
                    'client_secret' => $paymentIntent->client_secret
                ]);
            }
            
            return response()->json(['error' => 'Payment failed'], 400);
            
        } catch (ApiErrorException $e) {
            return $this->handleStripeError($e);
        }
    }
    
    /**
     * Process PayPal payment
     */
    protected function processPaypalPayment(Order $order, Request $request)
    {
        // Implement PayPal integration here
        // This is a placeholder implementation
        
        try {
            // Simulate PayPal payment processing
            $paymentId = 'PAYPAL-' . uniqid();
            
            $this->markOrderAsPaid($order, [
                'payment_id' => $paymentId,
                'payment_method' => 'paypal'
            ]);
            
            return response()->json(['success' => true]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'PayPal payment failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Handle Stripe webhook events
     */
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');
        
        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sigHeader, $endpointSecret
            );
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }
        
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $this->handlePaymentSucceeded($event->data->object);
                break;
                
            case 'payment_intent.payment_failed':
                $this->handlePaymentFailed($event->data->object);
                break;
                
            // Add more event types as needed
        }
        
        return response()->json(['status' => 'success']);
    }
    
    /**
     * Mark order as paid and trigger related actions
     */
    protected function markOrderAsPaid(Order $order, $paymentData)
    {
        $order->update([
            'payment_status' => 'paid',
            'payment_method' => $paymentData instanceof PaymentIntent ? 'card' : $paymentData['payment_method'],
            'payment_id' => $paymentData instanceof PaymentIntent ? $paymentData->id : $paymentData['payment_id'],
            'paid_at' => now()
        ]);
        
        // Clear cart
        $order->user->cart()->delete();
        
        // Send confirmation email
        Mail::to($order->user->email)->send(new OrderConfirmation($order));
        
        // Trigger any other post-payment actions
        event(new OrderPaid($order));
    }
    
    /**
     * Handle successful payment
     */
    protected function handlePaymentSucceeded($paymentIntent)
    {
        $order = Order::find($paymentIntent->metadata->order_id);
        
        if ($order && $order->payment_status !== 'paid') {
            $this->markOrderAsPaid($order, $paymentIntent);
        }
    }
    
    /**
     * Handle failed payment
     */
    protected function handlePaymentFailed($paymentIntent)
    {
        $order = Order::find($paymentIntent->metadata->order_id);
        
        if ($order) {
            $order->update([
                'payment_status' => 'failed',
                'payment_error' => $paymentIntent->last_payment_error->message ?? 'Payment failed'
            ]);
            
            // Notify user about failed payment
            Mail::to($order->user->email)->send(new PaymentFailed($order));
        }
    }
    
    /**
     * Convert amount to cents for Stripe
     */
    protected function convertToCents($amount)
    {
        return (int) round($amount * 100);
    }
    
    /**
     * Handle Stripe API errors consistently
     */
    protected function handleStripeError(ApiErrorException $e)
    {
        $error = $e->getError();
        
        return response()->json([
            'error' => $error->message ?? 'Payment processing failed',
            'code' => $error->code ?? 'stripe_error',
            'type' => $error->type ?? 'api_error'
        ], 500);
    }
}