<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use App\Custom\SendExcelReportMail;
use Laravel\Nova\Fields\Select;

class SendReport extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        //

        foreach ($models as $model) {
            (new SendExcelReportMail($model, $fields->report_type))->handle();
            // only run the first action for now.
            break;
        }
        return Action::message('Shipment Report Mail Sent.');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Select::make('Report by', 'report_type')
                ->options(\App\EmailReportSchedule::$report_by_option)
                ->rules('required')
        ];
    }
}
