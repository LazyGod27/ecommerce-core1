<?php

namespace App\PaymentGateways;

use Stripe\StripeClient;
use Stripe\Exception\ApiErrorException;
use App\Exceptions\PaymentFailedException;

class StripeGateway implements PaymentGatewayInterface
{
    protected StripeClient $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('services.stripe.secret'));
    }

    public function charge(array $data): array
    {
        try {
            $paymentIntent = $this->stripe->paymentIntents->create([
                'amount' => $this->formatAmount($data['amount']),
                'currency' => $data['currency'],
                'payment_method_types' => ['card'],
                'description' => 'Order #' . $data['order_id'],
                'metadata' => [
                    'order_id' => $data['order_id']
                ],
            ]);

            return $this->formatResponse($paymentIntent);
            
        } catch (ApiErrorException $e) {
            throw $this->handleStripeError($e, $data);
        }
    }

    public function refund(string $transactionId, float $amount): bool
    {
        try {
            $refund = $this->stripe->refunds->create([
                'payment_intent' => $transactionId,
                'amount' => $this->formatAmount($amount),
            ]);
            return $refund->status === 'succeeded';
        } catch (ApiErrorException $e) {
            throw new PaymentFailedException(
                "Stripe refund failed: " . $e->getMessage(),
                $e->getCode(),
                $e,
                null,
                'stripe',
                $amount
            );
        }
    }

    protected function formatAmount(float $amount): int
    {
        return (int)($amount * 100); // Convert to cents
    }

    protected function formatResponse($paymentIntent): array
    {
        return [
            'transaction_id' => $paymentIntent->id,
            'status' => $paymentIntent->status,
            'amount' => $paymentIntent->amount / 100,
            'currency' => $paymentIntent->currency,
            'payment_method' => 'stripe',
            'client_secret' => $paymentIntent->client_secret,
            'requires_action' => $paymentIntent->status === 'requires_action',
        ];
    }

    protected function handleStripeError(ApiErrorException $e, array $data): PaymentFailedException
    {
        return new PaymentFailedException(
            "Stripe payment failed: " . $e->getMessage(),
            $e->getCode(),
            $e,
            $data['order_id'],
            'stripe',
            $data['amount']
        );
    }
}