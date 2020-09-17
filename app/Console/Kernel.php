<?php

namespace App\Console;

use App\Console\Commands\BackUpLogs;
use App\Console\Commands\RetrieveBill;
use App\Console\Commands\RetrieveProduct;
use App\Console\Commands\SyncBillState;
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
        BackUpLogs::class,
        RetrieveBill::class,
        SyncBillState::class,
        RetrieveProduct::class,
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
//        $schedule->command('backup:log')->hourly();
        $schedule->command('retrieve:bill')->hourlyAt('0');
        $schedule->command('sync:bill')->hourlyAt('30');
        $schedule->command('retrieve:product')->hourly();

        $schedule->command('backup:clean')->daily()->at('01:00');
        $schedule->command('backup:run')->daily()->at('02:00');
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
