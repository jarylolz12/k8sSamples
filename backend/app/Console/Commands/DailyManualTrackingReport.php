<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\BasicMail;
use Illuminate\Support\Facades\Mail;

class DailyManualTrackingReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manual-tracking:report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily manual tracking report';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $fileName = 'Shifl_Shipment_Manual_Tracking_Report_' . date('Y_m_d') . '.xlsx';

        \Excel::store(new \App\Console\Commands\Report\ManualTrackingExport(), 'reports/excel/manualTracking/'.$fileName, 'public');

        $attachment = [];

        $path = storage_path('/app/public/reports/excel/manualTracking/'.$fileName);

        if (file_exists($path)) {
            array_push($attachment, $path);
        }

        $subject = "Manual Tracking Daily Report - " . date('m/d/Y');
        $content = "Please See Attached.";
        $markdown = 'mails.basic';
        $to = ['jayakumar@shifl.com'];
        $cc = ['tanvir@shifl.com', 'chana@shifl.com', 'shia@shifl.com', 'vivek@shifl.com'];
        
        //check usa office
        $check_office = \App\ShiflOffice::where('name', 'Shifl USA')->first();

        if ( $check_office->id ) {
            $check_office_email = \App\ShiflOfficeEmail::where('type', 'all')
                                                ->where('office_id', $check_office->id)
                                                ->first();

            if ( isset($check_office_email->id)) {
                $dynamic_emails = $check_office_email->manual_tracking_report_emails;
                if ( $dynamic_emails !=null ) {

                    $dynamic_emails = ( is_array($dynamic_emails)) ? $dynamic_emails : json_decode($dynamic_emails);
                    $dynamic_emails = ($dynamic_emails == '') ? [] : $dynamic_emails;

                    foreach($dynamic_emails as $dynamic_email ) {
                        if ( !in_array($dynamic_email, $cc)) {
                            array_push($cc, $dynamic_email);
                        }
                    }
                }
            }
        }

        Mail::to($to)->cc($cc)->send(new \App\Mail\BasicMail($subject,$content,$attachment,$markdown));

        return 0;
    }
}
