<?php

namespace App\Console;

use App\Jobs\Reports\Closing\DispatchMonthlyUserClosingReports;
use App\Jobs\Reports\Closing\DispatchUserClosingReports;
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
        $schedule->command('telescope:prune --hours=72')->daily();
        $schedule->command('horizon:snapshot')->everyFiveMinutes();

        $schedule->job(new DispatchUserClosingReports)->dailyAt('03:00');
        $schedule->job(new DispatchMonthlyUserClosingReports)->monthlyOn(1, '03:00');
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
