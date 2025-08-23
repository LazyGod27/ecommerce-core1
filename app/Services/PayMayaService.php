<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PayMayaService
{
    protected $publicKey;
    protected $secretKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->publicKey = config('services.paymaya.public_key');
        $this->secretKey = config('services.paymaya.secret_key');
        $this->baseUrl = config('services.paymaya.base_url', 'https://api.paymaya.com');
    }

    public function createPayment(Order $order, string $callbackUrl): string
    {
        try {
            $payload = [
                'totalAmount' => [
                    'amount' => $order->total,
                    'currency' => 'PHP'
                ],
                'requestReferenceNumber' => $order->order_number,
                'items' => $this->formatOrderItems($order),
                'redirectUrl' => [
                    'success' => $callbackUrl,
                    'failure' => $callbackUrl,
                    'cancel' => $callbackUrl
                ],
                'buyer' => [
                    'firstName' => $order->user->name,
                    'lastName' => '',
                    'contact' => [
                        'phone' => $order->contact_number,
                        'email' => $order->email
                    ]
                ]
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . base64_encode($this->publicKey . ':' . $this->secretKey),
                'Content-Type' => 'application/json'
            ])->post($this->baseUrl . '/payments/v1/links', $payload);

            if ($response->successful()) {
                $data = $response->json();
                return $data['redirectUrl'] ?? $data['links'][0]['href'] ?? '';
            }

            throw new \Exception('Failed to create PayMaya payment: ' . $response->body());

        } catch (\Exception $e) {
            Log::error('PayMaya payment creation error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function handleCallback(array $payload, Order $order): array
    {
        try {
            // Verify webhook signature
            if (!$this->verifySignature($payload)) {
                throw new \Exception('Invalid webhook signature');
            }

            $paymentId = $payload['paymentId'] ?? null;
            $status = $payload['status'] ?? null;

            if (!$paymentId || !$status) {
                throw new \Exception('Invalid callback payload');
            }

            return [
                'success' => $status === 'COMPLETED',
                'payment_id' => $paymentId,
                'message' => $status === 'COMPLETED' ? 'Payment successful' : 'Payment failed'
            ];

        } catch (\Exception $e) {
            Log::error('PayMaya callback error: ' . $e->getMessage());
            return [
                'success' => false,
                'payment_id' => null,
                'message' => $e->getMessage()
            ];
        }
    }

    public function handleWebhook($request): array
    {
        try {
            $payload = $request->all();
            
            // Verify webhook signature
            if (!$this->verifySignature($payload)) {
                throw new \Exception('Invalid webhook signature');
            }

            $paymentId = $payload['paymentId'] ?? null;
            $status = $payload['status'] ?? null;

            if ($paymentId && $status) {
                // Update payment record
                $payment = \App\Models\Payment::where('payment_id', $paymentId)->first();
                if ($payment) {
                    $payment->update([
                        'status' => $status === 'COMPLETED' ? 'completed' : 'failed',
                        'paid_at' => $status === 'COMPLETED' ? now() : null,
                        'transaction_data' => $payload
                    ]);
                }
            }

            return ['success' => true];

        } catch (\Exception $e) {
            Log::error('PayMaya webhook error: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function processRefund(string $paymentId, float $amount): bool
    {
        try {
            $payload = [
                'amount' => [
                    'amount' => $amount,
                    'currency' => 'PHP'
                ],
                'reason' => 'Customer request'
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . base64_encode($this->publicKey . ':' . $this->secretKey),
                'Content-Type' => 'application/json'
            ])->post($this->baseUrl . '/payments/v1/payments/' . $paymentId . '/refunds', $payload);

            if ($response->successful()) {
                $data = $response->json();
                return $data['status'] === 'COMPLETED';
            }

            throw new \Exception('Failed to process PayMaya refund: ' . $response->body());

        } catch (\Exception $e) {
            Log::error('PayMaya refund error: ' . $e->getMessage());
            return false;
        }
    }

    protected function formatOrderItems(Order $order): array
    {
        $items = [];
        foreach ($order->items as $item) {
            $items[] = [
                'name' => $item->product->name,
                'quantity' => $item->quantity,
                'code' => $item->product->id,
                'amount' => [
                    'value' => $item->price,
                    'currency' => 'PHP'
                ],
                'totalAmount' => [
                    'value' => $item->total,
                    'currency' => 'PHP'
                ]
            ];
        }
        return $items;
    }

    protected function verifySignature(array $payload): bool
    {
        // In production, implement proper signature verification
        // This is a simplified version
        $signature = $payload['signature'] ?? '';
        $expectedSignature = hash_hmac('sha256', json_encode($payload), $this->secretKey);
        
        return hash_equals($expectedSignature, $signature);
    }
}
