<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GcashService
{
    protected $apiKey;
    protected $apiEndpoint;

    public function __construct()
    {
        $this->apiKey = config('services.gcash.api_key');
        $this->apiEndpoint = config('services.gcash.api_endpoint');
    }

    public function createPayment(Order $order, string $callbackUrl): string
    {
        try {
            $payload = [
                'amount' => $order->total,
                'currency' => 'PHP',
                'reference' => 'ORDER-' . $order->id,
                'description' => 'Payment for Order #' . $order->id,
                'callback_url' => $callbackUrl,
                'metadata' => [
                    'order_id' => $order->id,
                    'user_id' => $order->user_id
                ]
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json'
            ])->post($this->apiEndpoint . '/payments', $payload);

            if ($response->successful()) {
                $data = $response->json();
                return $data['redirect_url'] ?? $data['payment_url'];
            }

            throw new \Exception('Failed to create GCash payment: ' . $response->body());

        } catch (\Exception $e) {
            Log::error('GCash payment creation failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function verifyPayment(string $paymentId): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey
            ])->get($this->apiEndpoint . '/payments/' . $paymentId);

            if ($response->successful()) {
                return $response->json();
            }

            throw new \Exception('Failed to verify GCash payment: ' . $response->body());

        } catch (\Exception $e) {
            Log::error('GCash payment verification failed', [
                'payment_id' => $paymentId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function refundPayment(string $paymentId, float $amount, string $reason = ''): array
    {
        try {
            $payload = [
                'amount' => $amount,
                'reason' => $reason
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json'
            ])->post($this->apiEndpoint . '/payments/' . $paymentId . '/refund', $payload);

            if ($response->successful()) {
                return $response->json();
            }

            throw new \Exception('Failed to refund GCash payment: ' . $response->body());

        } catch (\Exception $e) {
            Log::error('GCash payment refund failed', [
                'payment_id' => $paymentId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}