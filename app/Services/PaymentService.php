<?php

namespace App\Services;

use App\Exceptions\PaymentFailedException;
use App\Models\Order;
use App\PaymentGateways\PaymentGatewayFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;

class PaymentService
{
    protected StripeClient $stripe;
    protected PaymentGatewayFactory $paymentGatewayFactory;

    public function __construct(PaymentGatewayFactory $paymentGatewayFactory)
    {
        $this->stripe = new StripeClient(config('services.stripe.secret'));
        $this->paymentGatewayFactory = $paymentGatewayFactory;
    }

    /**
     * Process payment for an order
     */
    public function process(int $orderId, string $method, float $amount): array
    {
        return DB::transaction(function () use ($orderId, $method, $amount) {
            try {
                $order = Order::findOrFail($orderId);
                $this->validateOrderForPayment($order, $amount);

                // Process payment based on method
                $result = match ($method) {
                    'card' => $this->processCardPayment($order, $amount),
                    'bank_transfer' => $this->processBankTransfer($order, $amount),
                    'paypal' => $this->processPaypalPayment($order, $amount),
                    default => $this->processViaGateway($order, $method, $amount),
                };

                $this->updateOrderAfterPayment($order, $result, $method);

                return $result;

            } catch (ApiErrorException $e) {
                throw $this->handleStripeError($e, $orderId, $method, $amount);
            } catch (\Exception $e) {
                throw $this->wrapException($e, $orderId, $method, $amount);
            }
        });
    }

    /**
     * Process refund for an order
     */
    public function refund(int $orderId, float $amount = null): bool
    {
        return DB::transaction(function () use ($orderId, $amount) {
            try {
                $order = Order::findOrFail($orderId);
                $amount = $amount ?? $order->total;
                
                $this->validateOrderForRefund($order, $amount);

                // Use appropriate refund method based on original payment
                $success = match ($order->payment_method) {
                    'card' => $this->processCardRefund($order, $amount),
                    'bank_transfer' => $this->processBankRefund($order, $amount),
                    'paypal' => $this->processPaypalRefund($order, $amount),
                    default => $this->processGatewayRefund($order, $amount),
                };

                if ($success) {
                    $order->update([
                        'payment_status' => $amount < $order->total ? 'partially_refunded' : 'refunded',
                        'refund_amount' => $amount,
                        'refunded_at' => now(),
                    ]);
                }

                return $success;

            } catch (\Exception $e) {
                $this->logPaymentError($e, $orderId, $order->payment_method ?? null, $amount);
                throw new PaymentFailedException(
                    "Refund failed: " . $e->getMessage(),
                    $e->getCode(),
                    $e,
                    $orderId,
                    $order->payment_method ?? null,
                    $amount
                );
            }
        });
    }

    /**
     * Process card payment via Stripe
     */
    protected function processCardPayment(Order $order, float $amount): array
    {
        $paymentIntent = $this->stripe->paymentIntents->create([
            'amount' => $this->convertToCents($amount),
            'currency' => strtolower($order->currency),
            'payment_method_types' => ['card'],
            'description' => "Payment for Order #{$order->id}",
            'metadata' => [
                'order_id' => $order->id,
                'customer_id' => $order->user_id,
            ],
            'receipt_email' => $order->user->email,
        ]);

        return [
            'status' => $paymentIntent->status,
            'transaction_id' => $paymentIntent->id,
            'client_secret' => $paymentIntent->client_secret,
            'requires_action' => $paymentIntent->status === 'requires_action',
            'payment_method' => 'card',
        ];
    }

    /**
     * Process bank transfer payment
     */
    protected function processBankTransfer(Order $order, float $amount): array
    {
        // Implement actual bank transfer logic here
        return [
            'status' => 'pending',
            'transaction_id' => 'bank_' . uniqid(),
            'payment_method' => 'bank_transfer',
            'instructions' => [
                'account_number' => config('payments.bank.account'),
                'reference' => "ORDER-{$order->id}",
            ],
        ];
    }

    /**
     * Process PayPal payment
     */
    protected function processPaypalPayment(Order $order, float $amount): array
    {
        // Implement actual PayPal logic here
        return [
            'status' => 'completed',
            'transaction_id' => 'paypal_' . uniqid(),
            'payment_method' => 'paypal',
            'redirect_url' => 'https://paypal.com/checkout/' . uniqid(),
        ];
    }

    /**
     * Process payment via gateway factory
     */
    protected function processViaGateway(Order $order, string $method, float $amount): array
    {
        $gateway = $this->paymentGatewayFactory->create($method);
        return $gateway->charge([
            'order_id' => $order->id,
            'amount' => $amount,
            'currency' => $order->currency,
            'customer' => $order->user->toArray(),
        ]);
    }

    /**
     * Process card refund via Stripe
     */
    protected function processCardRefund(Order $order, float $amount): bool
    {
        $refund = $this->stripe->refunds->create([
            'payment_intent' => $order->payment_reference,
            'amount' => $this->convertToCents($amount),
            'metadata' => [
                'order_id' => $order->id,
                'reason' => 'customer_request',
            ],
        ]);

        return $refund->status === 'succeeded';
    }

    /**
     * Process bank transfer refund
     */
    protected function processBankRefund(Order $order, float $amount): bool
    {
        // Implement actual bank refund logic
        return true; // Assuming manual processing
    }

    /**
     * Process PayPal refund
     */
    protected function processPaypalRefund(Order $order, float $amount): bool
    {
        // Implement actual PayPal refund logic
        return true; // Assuming successful for example
    }

    /**
     * Process refund via gateway factory
     */
    protected function processGatewayRefund(Order $order, float $amount): bool
    {
        $gateway = $this->paymentGatewayFactory->create($order->payment_method);
        return $gateway->refund($order->payment_reference, $amount);
    }

    /**
     * Validate order can be paid
     */
    protected function validateOrderForPayment(Order $order, float $amount): void
    {
        if ($order->payment_status === 'paid') {
            throw new \Exception("Order #{$order->id} has already been paid");
        }

        if (abs($order->total - $amount) > 0.01) {
            throw new \Exception(sprintf(
                "Payment amount (%.2f) does not match order total (%.2f)",
                $amount,
                $order->total
            ));
        }
    }

    /**
     * Validate order can be refunded
     */
    protected function validateOrderForRefund(Order $order, float $amount): void
    {
        if (!in_array($order->payment_status, ['paid', 'partially_refunded'])) {
            throw new \Exception("Only paid or partially refunded orders can be refunded");
        }

        $alreadyRefunded = $order->refund_amount ?? 0;
        $refundableAmount = $order->total - $alreadyRefunded;

        if ($amount > $refundableAmount) {
            throw new \Exception(sprintf(
                "Refund amount (%.2f) exceeds available amount (%.2f)",
                $amount,
                $refundableAmount
            ));
        }
    }

    /**
     * Update order after payment
     */
    protected function updateOrderAfterPayment(Order $order, array $result, string $method): void
    {
        $status = match($result['status']) {
            'succeeded', 'completed' => 'paid',
            'pending', 'requires_action' => 'pending',
            default => 'failed',
        };

        $updateData = [
            'payment_status' => $status,
            'payment_method' => $method,
            'payment_reference' => $result['transaction_id'],
            'payment_details' => json_encode($result),
        ];

        if ($status === 'paid') {
            $updateData['paid_at'] = now();
        }

        $order->update($updateData);
    }

    /**
     * Handle Stripe API errors
     */
    protected function handleStripeError(ApiErrorException $e, int $orderId, string $method, float $amount): PaymentFailedException
    {
        $error = $e->getError();
        
        Log::error('Stripe Payment Error', [
            'order_id' => $orderId,
            'method' => $method,
            'amount' => $amount,
            'error_type' => $error->type ?? null,
            'code' => $error->code ?? null,
            'decline_code' => $error->decline_code ?? null,
            'message' => $error->message ?? $e->getMessage(),
        ]);

        return new PaymentFailedException(
            $error->message ?? 'Payment processing failed',
            $e->getCode(),
            $e,
            $orderId,
            $method,
            $amount
        );
    }

    /**
     * Log payment errors
     */
    protected function logPaymentError(\Exception $e, int $orderId, ?string $method, ?float $amount): void
    {
        Log::error('Payment processing failed', [
            'order_id' => $orderId,
            'method' => $method,
            'amount' => $amount,
            'error' => $e->getMessage(),
            'exception' => get_class($e),
            'trace' => $e->getTraceAsString(),
        ]);
    }

    /**
     * Wrap exception in PaymentFailedException
     */
    protected function wrapException(\Exception $e, int $orderId, string $method, float $amount): PaymentFailedException
    {
        $this->logPaymentError($e, $orderId, $method, $amount);
        
        return new PaymentFailedException(
            $e->getMessage(),
            $e->getCode(),
            $e,
            $orderId,
            $method,
            $amount
        );
    }

    /**
     * Convert amount to cents for Stripe
     */
    protected function convertToCents(float $amount): int
    {
        return (int) round($amount * 100);
    }
}