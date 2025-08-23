<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Refund;

class StripeService
{
    protected $secretKey;
    protected $publicKey;

    public function __construct()
    {
        $this->secretKey = config('services.stripe.secret');
        $this->publicKey = config('services.stripe.key');
        Stripe::setApiKey($this->secretKey);
    }

    public function processPayment(Order $order, string $token): array
    {
        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => (int)($order->total * 100), // Convert to cents
                'currency' => 'php',
                'payment_method_data' => [
                    'type' => 'card',
                    'card' => [
                        'token' => $token
                    ]
                ],
                'confirm' => true,
                'return_url' => route('payment.success', $order->id),
                'metadata' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'user_id' => $order->user_id
                ]
            ]);

            if ($paymentIntent->status === 'succeeded') {
                return [
                    'success' => true,
                    'payment_id' => $paymentIntent->id,
                    'message' => 'Payment successful'
                ];
            } elseif ($paymentIntent->status === 'requires_action') {
                return [
                    'success' => false,
                    'payment_id' => $paymentIntent->id,
                    'message' => 'Payment requires additional authentication',
                    'requires_action' => true,
                    'client_secret' => $paymentIntent->client_secret
                ];
            } else {
                return [
                    'success' => false,
                    'payment_id' => $paymentIntent->id,
                    'message' => 'Payment failed: ' . $paymentIntent->last_payment_error->message ?? 'Unknown error'
                ];
            }

        } catch (\Exception $e) {
            Log::error('Stripe payment error: ' . $e->getMessage());
            return [
                'success' => false,
                'payment_id' => null,
                'message' => 'Payment processing failed: ' . $e->getMessage()
            ];
        }
    }

    public function handleWebhook($request): array
    {
        try {
            $payload = $request->getContent();
            $sigHeader = $request->header('Stripe-Signature');
            $endpointSecret = config('services.stripe.webhook_secret');

            $event = \Stripe\Webhook::constructEvent(
                $payload, $sigHeader, $endpointSecret
            );

            switch ($event->type) {
                case 'payment_intent.succeeded':
                    $paymentIntent = $event->data->object;
                    $this->handlePaymentSuccess($paymentIntent);
                    break;

                case 'payment_intent.payment_failed':
                    $paymentIntent = $event->data->object;
                    $this->handlePaymentFailure($paymentIntent);
                    break;

                case 'charge.refunded':
                    $charge = $event->data->object;
                    $this->handleRefund($charge);
                    break;

                default:
                    Log::info('Unhandled Stripe event: ' . $event->type);
            }

            return ['success' => true];

        } catch (\UnexpectedValueException $e) {
            Log::error('Stripe webhook error - Invalid payload: ' . $e->getMessage());
            return ['success' => false, 'error' => 'Invalid payload'];
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::error('Stripe webhook error - Invalid signature: ' . $e->getMessage());
            return ['success' => false, 'error' => 'Invalid signature'];
        } catch (\Exception $e) {
            Log::error('Stripe webhook error: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function processRefund(string $paymentId, float $amount): bool
    {
        try {
            $refund = Refund::create([
                'payment_intent' => $paymentId,
                'amount' => (int)($amount * 100), // Convert to cents
                'reason' => 'requested_by_customer'
            ]);

            return $refund->status === 'succeeded';

        } catch (\Exception $e) {
            Log::error('Stripe refund error: ' . $e->getMessage());
            return false;
        }
    }

    public function createPaymentIntent(Order $order): array
    {
        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => (int)($order->total * 100),
                'currency' => 'php',
                'metadata' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'user_id' => $order->user_id
                ]
            ]);

            return [
                'success' => true,
                'client_secret' => $paymentIntent->client_secret,
                'payment_intent_id' => $paymentIntent->id
            ];

        } catch (\Exception $e) {
            Log::error('Stripe payment intent creation error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to create payment intent'
            ];
        }
    }

    protected function handlePaymentSuccess($paymentIntent): void
    {
        $orderId = $paymentIntent->metadata->order_id ?? null;
        
        if ($orderId) {
            $payment = \App\Models\Payment::where('payment_id', $paymentIntent->id)->first();
            if ($payment) {
                $payment->update([
                    'status' => 'completed',
                    'paid_at' => now(),
                    'transaction_data' => $paymentIntent->toArray()
                ]);
            }
        }
    }

    protected function handlePaymentFailure($paymentIntent): void
    {
        $orderId = $paymentIntent->metadata->order_id ?? null;
        
        if ($orderId) {
            $payment = \App\Models\Payment::where('payment_id', $paymentIntent->id)->first();
            if ($payment) {
                $payment->update([
                    'status' => 'failed',
                    'transaction_data' => $paymentIntent->toArray()
                ]);
            }
        }
    }

    protected function handleRefund($charge): void
    {
        $payment = \App\Models\Payment::where('payment_id', $charge->payment_intent)->first();
        if ($payment) {
            $payment->update([
                'status' => 'refunded',
                'refunded_at' => now(),
                'refund_amount' => $charge->amount_refunded / 100
            ]);
        }
    }

    public function getPublicKey(): string
    {
        return $this->publicKey;
    }
}
