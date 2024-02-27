<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\EmailReportSchedule;

class EmailReportToCustomer extends Command
{

    protected $signature = 'command:email-report';

    protected $description = 'Email Report to Customers';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $emailSchedules = EmailReportSchedule::with('CustomerAdmin')
                ->where('active',true)
                ->get();
        foreach($emailSchedules as $emailSched){
            dd($emailSched->frequency);
            if($emailSched->frequency === 'DAILYAT'){
                
            }
        }

        return 0;
    }
}
