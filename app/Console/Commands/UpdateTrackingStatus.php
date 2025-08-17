<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TrackingService;
class UpdateTrackingStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tracking:update';

    public function handle()
    {
        $this->info('Updating tracking statuses...');
        app(TrackingService::class)->updateTrackingStatus();
        $this->info('Completed!');
    }

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
}
