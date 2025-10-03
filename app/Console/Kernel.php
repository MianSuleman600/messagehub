<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

// Jobs
use App\Domain\Messaging\Jobs\DeliverScheduledMessageJob;
use App\Jobs\PruneOldMessages;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Deliver scheduled messages every minute on the "outbound" queue
        $schedule->job(new DeliverScheduledMessageJob)
                 ->everyMinute()
                 ->onQueue('outbound')
                 ->runInBackground() // optional if you want it to run asynchronously
                 ->withoutOverlapping(); // prevent multiple instances overlapping

        // Maintenance job: prune old messages daily at 2 AM on the "maintenance" queue
        $schedule->job(new PruneOldMessages)
                 ->dailyAt('02:00')
                 ->onQueue('maintenance')
                 ->runInBackground()
                 ->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
