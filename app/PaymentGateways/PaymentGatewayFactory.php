<?php

namespace App\PaymentGateways;

use InvalidArgumentException;

class PaymentGatewayFactory
{
    public function create(string $method): PaymentGatewayInterface
    {
        $method = strtolower($method);
        
        $gateways = [
            'card' => StripeGateway::class,
            'stripe' => StripeGateway::class,
            'paypal' => PaypalGateway::class,
            'bank_transfer' => BankTransferGateway::class,
            'bank' => BankTransferGateway::class,
        ];

        if (!array_key_exists($method, $gateways)) {
            throw new InvalidArgumentException("Unsupported payment method: {$method}");
        }

        return app($gateways[$method]);
    }
}