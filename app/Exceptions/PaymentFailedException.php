<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class PaymentFailedException extends Exception
{
    protected $orderId;
    protected $paymentMethod;
    protected $amount;

    public function __construct(
        string $message = "Payment processing failed",
        int $code = 0,
        ?Throwable $previous = null,
        ?int $orderId = null,
        ?string $paymentMethod = null,
        ?float $amount = null
    ) {
        $this->orderId = $orderId;
        $this->paymentMethod = $paymentMethod;
        $this->amount = $amount;

        parent::__construct($message, $code, $previous);
    }

    public function getOrderId(): ?int
    {
        return $this->orderId;
    }

    public function getPaymentMethod(): ?string
    {
        return $this->paymentMethod;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function context(): array
    {
        return [
            'order_id' => $this->orderId,
            'payment_method' => $this->paymentMethod,
            'amount' => $this->amount,
        ];
    }
}