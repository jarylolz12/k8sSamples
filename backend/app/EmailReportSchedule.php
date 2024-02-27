<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Exception;

class EmailReportSchedule extends Model
{
    protected $table = 'email_report_schedules';

    protected $fillable = [
        'customer_admin_id',
        'frequency','day',
        'time','active',
        'report_type',
        'month_day',
        'report',
        'report_columns',
        'selected_customer',
        'report_recipients',
        // 'report_name'
    ];

    protected $casts = [
        'month_day' => 'date:F j',
        'report_recipients' => 'array'
    ];
    
    private static $report_limits = 10;

    public static $days_of_the_week = [
        1 => 'Monday',
        2 => 'Tuesday',
        3 => 'Wednesday',
        4 => 'Thursday',
        5 => 'Friday',
        6 => 'Saturday',
        7 => 'Sunday'
    ];

    public static $frequency = [
        'DAILYAT' => 'Daily',
        'WEEKLYON' => 'Weekly',
        // 'MONTHLYON' => 'Monthly',
        // 'YEARLYON' => 'Yearly'
    ];

    public static $report_by_option = [
        'BYREFERENCE' => 'Shipment by Shifl Reference Number', 
        'BYREFERENCEADV' => 'Shipment by Shifl Reference Number Advanced', 
        'BYCONTAINER' => 'Shipment by Container Number', 
        'ACHREPORT' => 'ACH Statement - Monthly',  
    ];

    public static $report_by_option_customer_view = [
        'BYREFERENCEADV' => 'Shipment by Shifl Reference Number', 
        'BYCONTAINER' => 'Shipment by Container'
    ];

    public static $report = [
        1 => 'Company Report',
        2 => 'Personalized Report'
    ];

    public static function boot()
    {
        parent::boot();
        static::saving(function (EmailReportSchedule $scheduleReport){
            if($scheduleReport->frequency === 'YEARLYON'){
                $month_day = Carbon::parse($scheduleReport->month_day);
                $scheduleReport->month_day = $month_day->format('Y-m-d');
            }
        });

        static::created(function (EmailReportSchedule $scheduleReport){
            $sched = (new self)->where('customer_admin_id', $scheduleReport->customer_admin_id)->get();
            if(count($sched) > self::$report_limits){
                throw new Exception("Exceeds allowable email report schedule for this customer admin");
            }
        });

    }
    public function getDaysInMonths()
    {
        $days_in_months = [];
        $days = cal_days_in_month(CAL_GREGORIAN, 3, 2022);
        for($i=1; $i<=$days; $i++){
            $days_in_months[$i] = 'Day '.$i;
        }
        return $days_in_months;
    }

    public function getMonthsInYear()
    {
        $months_in_year = [];
        for($m=1; $m<=12; ++$m){
            $month = date('F', mktime(0, 0, 0, $m, 1)) . '<br/>';
            $months_in_year[$month] = $month;
        }
    }

    public function CustomerAdmin(){
        return $this->belongsTo(CustomerAdmin::class);
    }

    public static function getReportTypeByCustomerAdminId($id)
    {
        return self::where('customer_admin_id', $id)
             ->pluck('report_type', 'id');
                
    }

    public function getEmailReportWithUsersByUserId($id)
    {
        return self::join('users', 'users.id', '=', 'email_report_schedules.customer_admin_id')
                ->select('email_report_schedules.*', 'users.name', 'users.report_recipients')
                ->where('users.id', $id);
    }


}
