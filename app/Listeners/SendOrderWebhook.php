<?php

namespace App\Listeners;

use App\Services\CoreTransaction1WebhookSender;
use App\Events\OrderCreated;
use App\Events\OrderUpdated;
use App\Events\OrderStatusChanged;
use App\Events\PaymentStatusChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendOrderWebhook implements ShouldQueue
{
    use InteractsWithQueue;

    protected $webhookSender;

    public function __construct(CoreTransaction1WebhookSender $webhookSender)
    {
        $this->webhookSender = $webhookSender;
    }

    /**
     * Handle order created event.
     */
    public function handleOrderCreated(OrderCreated $event): void
    {
        try {
            $this->webhookSender->sendOrderCreated($event->order);
        } catch (\Exception $e) {
            Log::error('Failed to send order created webhook: ' . $e->getMessage());
        }
    }

    /**
     * Handle order updated event.
     */
    public function handleOrderUpdated(OrderUpdated $event): void
    {
        try {
            $this->webhookSender->sendOrderUpdated($event->order);
        } catch (\Exception $e) {
            Log::error('Failed to send order updated webhook: ' . $e->getMessage());
        }
    }

    /**
     * Handle order status changed event.
     */
    public function handleOrderStatusChanged(OrderStatusChanged $event): void
    {
        try {
            $this->webhookSender->sendOrderStatusChanged(
                $event->order,
                $event->oldStatus,
                $event->newStatus
            );
        } catch (\Exception $e) {
            Log::error('Failed to send order status changed webhook: ' . $e->getMessage());
        }
    }

    /**
     * Handle payment status changed event.
     */
    public function handlePaymentStatusChanged(PaymentStatusChanged $event): void
    {
        try {
            $this->webhookSender->sendPaymentStatusChanged(
                $event->order,
                $event->oldStatus,
                $event->newStatus
            );
        } catch (\Exception $e) {
            Log::error('Failed to send payment status changed webhook: ' . $e->getMessage());
        }
    }
}
