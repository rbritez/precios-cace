<?php

namespace App\Console;

use App\Console\Commands\CheckPrices;
use App\Console\Commands\NotifyAlterations;
use App\Console\Commands\updateStatusEvent;
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
        updateStatusEvent::class,
        CheckPrices::class,
        NotifyAlterations::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('event:updateStatus')->everyFiveMinutes();
        $schedule->command('check:prices')->dailyAt('01:00');
        $schedule->command('mo:verify')->dailyAt('08:30');
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