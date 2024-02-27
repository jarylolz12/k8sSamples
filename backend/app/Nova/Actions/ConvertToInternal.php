<?php

namespace App\Nova\Actions;

use App\Mail\BasicMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class ConvertToInternal extends Action
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
        foreach ($models as $key => $model) {
            // code...
            $model->is_tracking_shipment = 0;
            $model->save();

            $content['ref_no'] = $model->shifl_ref;
            $content['from'] = 'External';
            $content['to'] = 'Internal';
            $content['user'] = Auth::user()->name;
            $markdown = "mails.shipment.shipment_convert_notification";

            Mail::to(config('mail-settings.shipment_convert_notification_email'))->send(new BasicMail('Shipment Converted', $content, [], $markdown));

            return Action::push('/resources/shipments/'.$model->id);
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [];
    }
}
