<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AutoOrderCompletionService;

class ProcessAutoOrderCompletion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:auto-complete 
                            {--reminders : Send expiration reminders for orders expiring soon}
                            {--stats : Show statistics about order completion}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process automatic order completion for orders that have passed their confirmation deadline';

    protected AutoOrderCompletionService $autoCompletionService;

    public function __construct(AutoOrderCompletionService $autoCompletionService)
    {
        parent::__construct();
        $this->autoCompletionService = $autoCompletionService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Starting automatic order completion process...');

        // Process expired orders
        $processedCount = $this->autoCompletionService->processExpiredOrders();
        
        if ($processedCount > 0) {
            $this->info("✅ Successfully auto-confirmed {$processedCount} orders");
        } else {
            $this->info('ℹ️  No orders found that need auto-confirmation');
        }

        // Send reminders if requested
        if ($this->option('reminders')) {
            $this->info('📧 Sending expiration reminders...');
            $reminderCount = $this->autoCompletionService->sendExpirationReminders();
            $this->info("📧 Sent {$reminderCount} expiration reminders");
        }

        // Show statistics if requested
        if ($this->option('stats')) {
            $this->showStatistics();
        }

        $this->info('🎉 Automatic order completion process completed!');
    }

    /**
     * Show statistics about order completion
     */
    protected function showStatistics()
    {
        $stats = $this->autoCompletionService->getStatistics();

        $this->info('📊 Order Completion Statistics:');
        $this->table(
            ['Metric', 'Count'],
            [
                ['Orders waiting for response', $stats['waiting_for_response']],
                ['Orders with passed deadline', $stats['expired_orders']],
                ['Auto-confirmed today', $stats['auto_confirmed_today']],
                ['Return requests today', $stats['return_requests_today']],
                ['Orders expiring soon', $stats['expiring_soon']]
            ]
        );
    }
}
