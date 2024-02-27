<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Date;
use Carbon\Carbon;
use Fourstacks\NovaRepeatableFields\Repeater;

use DigitalCreative\ConditionalContainer\ConditionalContainer;
use DigitalCreative\ConditionalContainer\HasConditionalContainer;
use Michielfb\Time\Time;

use Gale\ScheduleTime\ScheduleTime;

class EmailReportSchedule extends Resource
{
    use HasConditionalContainer;

    public static $model = \App\EmailReportSchedule::class;

    public static $title = 'Email Report Schedules';

    public static $search = [
        'customer_admin_id',
    ];

    public function fields(Request $request)
    {
        return [
            BelongsTo::make('Customer Admin','CustomerAdmin')
                ->sortable()
                ->hideWhenUpdating(),

            Boolean::make('Active')->default(true),

            Select::make('Report Type', 'report_type')
                ->options(\App\EmailReportSchedule::$report_by_option)
                ->displayUsingLabels(),

            /**
             * For now this will be hidden waiting for further instruction
              */    
            // Select::make('Report', 'report')
            //     ->options(\App\EmailReportSchedule::$report)
            //     ->displayUsingLabels(), 

            // ConditionalContainer::make([

            //     Repeater::make('Report Recipients', 'report_recipients')->addField([
            //         'name' => 'report_recipients',
            //         'type' => 'email',
            //         'placeholder' => 'Report Recipients',
            //     ])->addButtonText('Add Report Recipient')->hideFromIndex(),    
            // ])->if('report = 1'),       


            ConditionalContainer::make([
                Select::make('Frequency')
                    ->options(\App\EmailReportSchedule::$frequency)
                    ->displayUsingLabels(),

                ConditionalContainer::make([
                    Select::make('Day')
                    ->options(\App\EmailReportSchedule::$days_of_the_week)
                    ->displayUsingLabels(),
                ])->if('frequency = WEEKLYON'),
    
                //Make conditional container for MONTHLYON
                // ConditionalContainer::make([
                //     Select::make('Day')
                //     ->options($this->getDaysInMonths())
                //     ->displayUsingLabels(),
                // ])->if('frequency = MONTHLYON'),
            ])->if('report_type = BYCONTAINER OR report_type = BYREFERENCE'),

            ConditionalContainer::make([
                Select::make('Frequency')
                    ->options(['MONTHLYON' => 'Monthly'])
                    ->displayUsingLabels(),

                Select::make('Day')
                    ->options($this->getDaysInMonths())
                    ->displayUsingLabels(),
            ])->if('report_type = ACHREPORT'),

            //Day field to display in index
            Text::make('Day', function(){
                if($this->frequency === 'DAILYAT'){
                    return "--";
                }else if($this->frequency === 'WEEKLYON'){
                    return $this->day ? \App\EmailReportSchedule::$days_of_the_week[$this->day] : "--";
                }else if($this->frequency === 'MONTHLYON'){
                    return 'Day '.$this->day;
                }
                // else if($this->frequency === 'YEARLYON'){
                //     $this->month_day = Carbon::parse($this->month_day);
                //     return $this->month_day->format('F j');
                // }
            })->onlyOnIndex(),
            //Time field to display in index
            // Time::make('Time')
            //     ->format('HH:mm')
            //     ->onlyOnIndex(),

            //Make conditional container for YEARLYON
            // ConditionalContainer::make([
            //     Date::make('Month/Day', 'month_day', function(){
            //         $this->month_day = Carbon::parse($this->month_day);
            //         return $this->month_day->format('F j');
            //     })
            //     ->format('MMM DD')
            //     ->pickerFormat('F j')
            //     ->firstDayOfWeek(1),
            // ])->if('frequency === YEARLYON'),


            // Time::make('Time')
            //      ->format('hh:mm A'),

            // Time::make('Time')
            //     ->format('HH:mm'),
                
            ScheduleTime::make('Time', 'time')
                 ->initFields(['time' => $this->time])

               
        ];
    }

    public function cards(Request $request)
    {
        return [];
    }

    public function filters(Request $request)
    {
        return [];
    }

    public function lenses(Request $request)
    {
        return [];
    }

    public function actions(Request $request)
    {
        return [];
    }
}
