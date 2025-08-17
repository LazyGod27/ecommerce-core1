<?php

namespace App\PaymentGateways;

interface PaymentGatewayInterface
{
    /**
     * Process a payment charge
     * @param array $data Should include order_id, amount, currency
     * @return array Transaction details
     */
    public function charge(array $data): array;

    /**
     * Process a refund
     * @param string $transactionId Original transaction ID
     * @param float $amount Amount to refund
     * @return bool Success status
     */
    public function refund(string $transactionId, float $amount): bool;
}