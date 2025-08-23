<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GcashService
{
    protected $apiKey;
    protected $secretKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.gcash.api_key');
        $this->secretKey = config('services.gcash.secret_key');
        $this->baseUrl = config('services.gcash.base_url', 'https://api.gcash.com');
    }

    public function createPayment(Order $order, string $callbackUrl): string
    {
        try {
            $payload = [
                'amount' => $order->total * 100, // Convert to centavos
                'currency' => 'PHP',
                'description' => "Order #{$order->order_number}",
                'reference_number' => $order->order_number,
                'callback_url' => $callbackUrl,
                'customer' => [
                    'name' => $order->user->name,
                    'email' => $order->email,
                    'phone' => $order->contact_number
                ]
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json'
            ])->post($this->baseUrl . '/payments', $payload);

            if ($response->successful()) {
                $data = $response->json();
                return $data['redirect_url'] ?? $this->baseUrl . '/payment/' . $data['payment_id'];
            }

            throw new \Exception('Failed to create GCash payment: ' . $response->body());

        } catch (\Exception $e) {
            Log::error('GCash payment creation error: ' . $e->getMessage());
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

            $paymentId = $payload['payment_id'] ?? null;
            $status = $payload['status'] ?? null;

            if (!$paymentId || !$status) {
                throw new \Exception('Invalid callback payload');
            }

            return [
                'success' => $status === 'success',
                'payment_id' => $paymentId,
                'message' => $status === 'success' ? 'Payment successful' : 'Payment failed'
            ];

        } catch (\Exception $e) {
            Log::error('GCash callback error: ' . $e->getMessage());
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

            $paymentId = $payload['payment_id'] ?? null;
            $status = $payload['status'] ?? null;

            if ($paymentId && $status) {
                // Update payment record
                $payment = \App\Models\Payment::where('payment_id', $paymentId)->first();
                if ($payment) {
                    $payment->update([
                        'status' => $status === 'success' ? 'completed' : 'failed',
                        'paid_at' => $status === 'success' ? now() : null,
                        'transaction_data' => $payload
                    ]);
                }
            }

            return ['success' => true];

        } catch (\Exception $e) {
            Log::error('GCash webhook error: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function processRefund(string $paymentId, float $amount): bool
    {
        try {
            $payload = [
                'payment_id' => $paymentId,
                'amount' => $amount * 100, // Convert to centavos
                'reason' => 'Customer request'
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json'
            ])->post($this->baseUrl . '/refunds', $payload);

            if ($response->successful()) {
                $data = $response->json();
                return $data['status'] === 'success';
            }

            throw new \Exception('Failed to process GCash refund: ' . $response->body());

        } catch (\Exception $e) {
            Log::error('GCash refund error: ' . $e->getMessage());
            return false;
        }
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