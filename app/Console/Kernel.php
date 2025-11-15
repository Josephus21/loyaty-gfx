<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Register the application commands.
     */
    protected $commands = [
        \App\Console\Commands\SyncMemberPoints::class,
    ];

    /**
     * Define the application's scheduled tasks.
     */
    protected function schedule(Schedule $schedule)
{
    // Run every 1 hour
    $schedule->command('sync:memberpoints')->hourly();
}

    protected function commands()
    {
        // Load all files inside app/Console/Commands automatically
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
