<?php

namespace App\Console;

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
        $hour = date('h');
        if($hour%2 == 0)
            $exec = 1;
        else
            $exec = 0;

        switch ($exec) {
            case 1:
                $schedule->call('App\Http\Controllers\CronController@getRefreshAccessToken')->hourly();
                $schedule->call('App\Http\Controllers\CronController@projects')->hourly();
                break;
            case 0:
                $schedule->call('App\Http\Controllers\CronController@getRefreshAccessToken')->hourly();
                $schedule->call('App\Http\Controllers\CronController@subTasks')->hourly();
                break;
            default:
                break;
        }

        $schedule->call('App\Http\Controllers\CronController@sendWeeklyMail')->saturdays()->at('04:30');
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
