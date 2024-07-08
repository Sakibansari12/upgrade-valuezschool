<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\ResetOtpStudent;
class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */

     protected  $commands = [
        \App\Console\Commands\StudentSubscription::class,
        \App\Console\Commands\StudentSubscriptionNotification::class,
       // \App\Console\Commands\TestDalyOpt::class,
       // \App\Console\Commands\TestDalyMail::class,
        \App\Console\Commands\TestDalyPaymentlink::class,
     ];
     

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('student:subscription')->everyMinute();
        $schedule->command('student:notification')->everyMinute();
        //$schedule->command('student:otp')->everyMinute();
       // $schedule->command('test:email')->everyMinute();
        $schedule->command('test:paymentlink')->everyMinute();
        // everyMinute daily  $schedule->command('inspire')->hourly();
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
