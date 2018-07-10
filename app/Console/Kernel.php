<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
/**
 * Commands kernel.
 *
 * @author VLA & Code for Australia
 * @version Christian Arevalo
 * @see  ConsoleKernel
 */
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands.
     *
     * @var array
     */
    protected $commands = [
        Commands\SendReminders::class,
        Commands\UpdatePanelLawyersGEO::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        $schedule->command('sms:reminders')
         ->dailyAt('09:00')
         ->timezone('Australia/Melbourne')
         ->emailOutputTo('christian@codeforaustralia.org');

         $schedule ->command('panelLawyers:updateGEO')
         ->weekly()
         ->saturdays()
         ->at('09:00')
         ->timezone('Australia/Melbourne')
         ->emailOutputTo('sebastian.currea@vla.vic.gov.au');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
