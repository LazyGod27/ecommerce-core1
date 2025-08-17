<?php

namespace App\PaymentGateways;

use App\Exceptions\PaymentFailedException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaypalGateway implements PaymentGatewayInterface
{
    protected string $clientId;
    protected string $clientSecret;
    protected string $baseUrl;

    public function __construct()
    {
        $this->clientId = config('services.paypal.client_id');
        $this->clientSecret = config('services.paypal.client_secret');
        $this->baseUrl = config('services.paypal.mode') === 'sandbox'
            ? 'https://api.sandbox.paypal.com'
            : 'https://api.paypal.com';
    }

    public function charge(array $data): array
    {
        try {
            // 1. Get access token
            $token = $this->getAccessToken();
            
            // 2. Create PayPal order
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/v2/checkout/orders", [
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    [
                        'reference_id' => $data['order_id'],
                        'amount' => [
                            'currency_code' => $data['currency'],
                            'value' => number_format($data['amount'], 2),
                        ],
                    ],
                ],
            ]);

            if ($response->failed()) {
                throw new \Exception("PayPal order creation failed: " . $response->body());
            }

            return $this->formatPaypalResponse($response->json(), $data);

        } catch (\Exception $e) {
            Log::error('PayPal payment error', [
                'order_id' => $data['order_id'],
                'error' => $e->getMessage(),
            ]);
            
            throw new PaymentFailedException(
                "PayPal payment failed: " . $e->getMessage(),
                $e->getCode(),
                $e,
                $data['order_id'],
                'paypal',
                $data['amount']
            );
        }
    }

    public function refund(string $transactionId, float $amount): bool
    {
        // Implement refund logic similar to charge
        // Would need to first capture payment if not already captured
        return false;
    }

    protected function getAccessToken(): string
    {
        $response = Http::asForm()
            ->withBasicAuth($this->clientId, $this->clientSecret)
            ->post("{$this->baseUrl}/v1/oauth2/token", [
                'grant_type' => 'client_credentials'
            ]);

        if ($response->failed()) {
            throw new \Exception("PayPal authentication failed");
        }

        return $response->json()['access_token'];
    }

    protected function formatPaypalResponse(array $data, array $chargeData): array
    {
        return [
            'transaction_id' => $data['id'],
            'status' => strtolower($data['status']),
            'amount' => $chargeData['amount'],
            'currency' => $chargeData['currency'],
            'payment_method' => 'paypal',
            'approval_url' => collect($data['links'])
                ->where('rel', 'approve')
                ->first()['href'] ?? null,
        ];
    }
}