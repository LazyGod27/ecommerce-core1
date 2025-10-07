<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('tracking:update')->hourly();
        $schedule->command('reviews:summarize')->dailyAt('3:00');
        
        // Auto-complete orders that have passed their confirmation deadline
        $schedule->command('orders:auto-complete')->hourly();
        
        // Send expiration reminders for orders expiring soon (daily at 9 AM)
        $schedule->command('orders:auto-complete --reminders')->dailyAt('9:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}