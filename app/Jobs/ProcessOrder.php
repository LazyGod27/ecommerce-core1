<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $order;
    public $timeout = 300; // 5 minutes

    /**
     * Create a new job instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Log::info('Processing order', ['order_id' => $this->order->id]);

            // Update order status to processing
            $this->order->update(['status' => 'processing']);

            // Simulate order processing steps
            $this->processInventory();
            $this->processShipping();
            $this->sendNotifications();

            // Mark order as completed
            $this->order->update(['status' => 'completed']);

            Log::info('Order processed successfully', ['order_id' => $this->order->id]);

        } catch (\Exception $e) {
            Log::error('Order processing failed', [
                'order_id' => $this->order->id,
                'error' => $e->getMessage()
            ]);

            // Mark order as failed
            $this->order->update(['status' => 'failed']);

            // Re-throw the exception to mark the job as failed
            throw $e;
        }
    }

    private function processInventory(): void
    {
        // Update product stock
        foreach ($this->order->items as $item) {
            $product = $item->product;
            $product->decrement('stock', $item->quantity);
        }
    }

    private function processShipping(): void
    {
        // Create tracking information
        $this->order->tracking()->create([
            'tracking_number' => 'TRK-' . strtoupper(uniqid()),
            'carrier' => 'Standard Shipping',
            'status' => 'shipped',
            'estimated_delivery' => now()->addDays(5),
            'current_location' => 'Warehouse',
            'tracking_details' => [
                'shipped_at' => now()->toISOString(),
                'shipping_method' => 'Standard Ground'
            ]
        ]);
    }

    private function sendNotifications(): void
    {
        // Send order confirmation email
        // Mail::to($this->order->user->email)->send(new OrderShipped($this->order));

        // Send SMS notification if phone number exists
        // if ($this->order->user->phone) {
        //     // SMS notification logic
        // }
    }
}
