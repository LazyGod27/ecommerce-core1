<?php

namespace App\Services;

use App\Models\Order;
use App\Services\CoreTransaction1WebhookSender;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderAutoConfirmed;

class AutoOrderCompletionService
{
    protected $webhookSender;

    public function __construct(CoreTransaction1WebhookSender $webhookSender)
    {
        $this->webhookSender = $webhookSender;
    }

    /**
     * Process orders that have passed their confirmation deadline
     */
    public function processExpiredOrders(): int
    {
        $expiredOrders = Order::deadlinePassed()->get();
        $processedCount = 0;

        foreach ($expiredOrders as $order) {
            try {
                $this->autoConfirmOrder($order);
                $processedCount++;
            } catch (\Exception $e) {
                Log::error("Failed to auto-confirm order {$order->id}: " . $e->getMessage());
            }
        }

        Log::info("Auto-order completion processed {$processedCount} orders");
        return $processedCount;
    }

    /**
     * Auto-confirm a specific order
     */
    public function autoConfirmOrder(Order $order): void
    {
        // Mark order as auto-confirmed
        $order->autoConfirm();

        // Send notification email to customer
        $this->sendAutoConfirmationEmail($order);

        // Send webhook notification
        $this->sendAutoConfirmationWebhook($order);

        // Log the action
        Log::info("Order {$order->id} auto-confirmed after deadline", [
            'order_number' => $order->order_number,
            'customer_id' => $order->user_id,
            'deadline' => $order->delivery_confirmation_deadline,
            'confirmed_at' => now()
        ]);
    }

    /**
     * Send auto-confirmation email to customer
     */
    protected function sendAutoConfirmationEmail(Order $order): void
    {
        try {
            Mail::to($order->user->email)->send(new OrderAutoConfirmed($order));
        } catch (\Exception $e) {
            Log::error("Failed to send auto-confirmation email for order {$order->id}: " . $e->getMessage());
        }
    }

    /**
     * Send webhook notification for auto-confirmation
     */
    protected function sendAutoConfirmationWebhook(Order $order): void
    {
        try {
            $this->webhookSender->sendOrderStatusChanged(
                $order,
                'delivered',
                'completed'
            );
        } catch (\Exception $e) {
            Log::error("Failed to send auto-confirmation webhook for order {$order->id}: " . $e->getMessage());
        }
    }

    /**
     * Get orders that will expire soon (within 24 hours)
     */
    public function getOrdersExpiringSoon(): \Illuminate\Database\Eloquent\Collection
    {
        return Order::waitingForResponse()
            ->where('delivery_confirmation_deadline', '<=', now()->addDay())
            ->where('delivery_confirmation_deadline', '>', now())
            ->with(['user', 'items.product'])
            ->get();
    }

    /**
     * Send reminder emails for orders expiring soon
     */
    public function sendExpirationReminders(): int
    {
        $expiringOrders = $this->getOrdersExpiringSoon();
        $sentCount = 0;

        foreach ($expiringOrders as $order) {
            try {
                $this->sendExpirationReminderEmail($order);
                $sentCount++;
            } catch (\Exception $e) {
                Log::error("Failed to send expiration reminder for order {$order->id}: " . $e->getMessage());
            }
        }

        return $sentCount;
    }

    /**
     * Send expiration reminder email
     */
    protected function sendExpirationReminderEmail(Order $order): void
    {
        try {
            // You can create a specific mail class for this
            Mail::to($order->user->email)->send(new \App\Mail\OrderExpirationReminder($order));
        } catch (\Exception $e) {
            Log::error("Failed to send expiration reminder email for order {$order->id}: " . $e->getMessage());
        }
    }

    /**
     * Get statistics for auto-completion
     */
    public function getStatistics(): array
    {
        return [
            'waiting_for_response' => Order::waitingForResponse()->count(),
            'expired_orders' => Order::deadlinePassed()->count(),
            'auto_confirmed_today' => Order::where('delivery_status', 'auto_confirmed')
                ->whereDate('customer_response_at', today())
                ->count(),
            'return_requests_today' => Order::where('return_status', 'requested')
                ->whereDate('return_requested_at', today())
                ->count(),
            'expiring_soon' => $this->getOrdersExpiringSoon()->count()
        ];
    }

    /**
     * Process a single order manually (for testing or manual intervention)
     */
    public function processOrder(Order $order): bool
    {
        if (!$order->hasDeadlinePassed()) {
            return false;
        }

        try {
            $this->autoConfirmOrder($order);
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to process order {$order->id}: " . $e->getMessage());
            return false;
        }
    }
}
