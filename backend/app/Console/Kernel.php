<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\EmailReportSchedule;
use App\Custom\SendExcelReportMail;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\CheckEtd::class,
    ];

    protected function schedule(Schedule $schedule)
    {

        // send daily report
        $schedule->command('manual-tracking:report')
                 ->dailyAt('04:55')
                 ->emailOutputOnFailure('tanvir@shifl.com');
        // Sync Shipping Lines
        $schedule->command('sync:shipping_lines')
                 ->weekly()
                 ->emailOutputOnFailure('tanvir@shifl.com');

        // resync
        $schedule->command('resync --frequency=twiceDaily')
                 ->twiceDaily(1, 13)
                 ->emailOutputOnFailure('tanvir@shifl.com');

        $schedule->command('sync:manual-tracking')
                 ->daily()
                 ->emailOutputOnFailure('tanvir@shifl.com');

        //
        $schedule->command('resync --frequency=everyTwoHours')
                  ->everyTwoHours()
                  ->emailOutputOnFailure('tanvir@shifl.com');
        //
        $schedule->command('tr:retry')
                 ->daily()
                 ->emailOutputOnFailure('tanvir@shifl.com');

        //
        $schedule->command('t49:sync-status')
                 ->weekly()
                 ->emailOutputOnFailure('tanvir@shifl.com');
        //
        $schedule->command('sync:lfd-tool')
                 ->weekly()
                 ->emailOutputOnFailure('tanvir@shifl.com');

        // discontinuing the daily lfd report.
        // $schedule->command('check:lfd')
        //          ->daily()
        //          ->emailOutputOnFailure('tanvir@shifl.com');

        $schedule->command('check:etd')->dailyAt('2:46');

        $emailSchedules = EmailReportSchedule::with('CustomerAdmin')->where('active', true)->get();
        foreach ($emailSchedules as $emailSched) {
            $arSched = [];
            if ($emailSched->frequency === "DAILYAT") {
                $schedule->call(function () use ($emailSched) {
                    $arSched = $emailSched->toArray();
                    unset($arSched['customer_admin']);
                    (new SendExcelReportMail($emailSched->CustomerAdmin, $emailSched->report_type, $arSched))->handle();
                })->dailyAt(substr($emailSched->time, 0, 5));
            }
            if ($emailSched->frequency === 'WEEKLYON') {
                $schedule->call(function () use ($emailSched) {
                    $arSched = $emailSched->toArray();
                    unset($arSched['customer_admin']);
                    (new SendExcelReportMail($emailSched->CustomerAdmin, $emailSched->report_type, $arSched))->handle();
                })->weeklyOn($emailSched->day, substr($emailSched->time, 0, 5));
            }
        }

        //update invoices every 15 minutes
        //we will only process every hour since there is already webhook for invoice
        //this is just fallback in case webhooks is down
        //process only in live site(https://beta.shifl.com)
        //this won't work in staging since the shabsie is not authenticated in staging
        //need to setup environment variable to determine if it is staging or production so that we only
        //send an email to production
        $schedule->command('update:invoices-status')
                ->everyFifteenMinutes();

        //shipment optimization process
        //process shipments statuses fallback
        $schedule->command('update:shipment-status')
                ->everyTwoMinutes()
                ->emailOutputOnFailure('kenneth@shifl.com');

        $schedule->command('update:shipment-pending-docs-status')
                ->everyMinute()
                ->emailOutputOnFailure('kenneth@shifl.com');
        /*
        $schedule->command('update:shipment-status-minutes')
                ->everyFiveMinutes()
                ->emailOutputOnFailure('kenneth@shifl.com');*/
        /*
        //update shipment queue
        $schedule->command('check:shipment-queue')
                ->everyFiveMinutes()
                ->emailOutputOnFailure('kenneth@shifl.com');*/

        // get customer statement automation settings
        $sts = \Juliverdevshifl\CustomerStatement\Models\CustomerStatementAutomation::first();

        if( !empty($sts) && $sts->sched_enabled == 1 ){
            $sched = $schedule->command('create:latest_customer_statement');
            $sched = $sched->withoutOverlapping();

            $day = 1;
            $time = '4:00';

            if( !empty($sts->sched_day) ){
                $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
                $day = array_search($sts->sched_day,$days)+1;
            }

            if( !empty($sts->sched_time) ){
                $time = $sts->sched_time;
            }

            if( $sts->sched_type === 'Yearly' ){
                $sched = $sched->cron('0 0 '.$day.' 1 *');
            }elseif( $sts->sched_type === 'Monthy' ){
                $sched = $sched->monthlyOn($day, $time);
            }elseif($sts->sched_type === 'Weekly'){
                $sched = $sched->weeklyOn($day, $time);
            }elseif($sts->sched_type === 'Daily'){
                $sched = $sched->dailyAt($time);
            }else{
                $sched = $sched->weeklyOn($day, $time);
            }
             
            $sched->emailOutputOnFailure('juliver@shifl.com');
        }
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
