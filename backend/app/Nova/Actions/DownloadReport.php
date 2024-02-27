<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Support\Facades\Storage;
use App\Custom\SendExcelReportMail;
use Laravel\Nova\Fields\Select;

class DownloadReport extends Action
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
        $fileName = '';
        foreach ($models as $model) {
            $fileName = (new SendExcelReportMail($model, $fields->report_type))->store();

            // only run the first action for now.
            break;
        }
        return Action::redirect("/download/excel/".$fileName);
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
