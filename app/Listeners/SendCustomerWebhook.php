<?php

namespace App\Listeners;

use App\Services\CoreTransaction1WebhookSender;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendCustomerWebhook implements ShouldQueue
{
    use InteractsWithQueue;

    protected $webhookSender;

    public function __construct(CoreTransaction1WebhookSender $webhookSender)
    {
        $this->webhookSender = $webhookSender;
    }

    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        try {
            $this->webhookSender->sendCustomerCreated($event->user);
        } catch (\Exception $e) {
            Log::error('Failed to send customer created webhook: ' . $e->getMessage());
        }
    }
}
