<?php

namespace App\Console;

use App\Jobs\PurgeCompletedContentReports;
use App\Jobs\SendEventReminders;
use App\Jobs\UnpublishPassedEvents;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->job(new UnpublishPassedEvents)->hourly();
        $schedule->job(new PurgeCompletedContentReports)->daily();
        $schedule->job(new SendEventReminders)
            ->dailyAt('08:30')
            ->timezone('Europe/London');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
