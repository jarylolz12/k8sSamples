<?php

namespace App\Listeners;

use App\Events\UpdateCustomerEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateCustomerEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SendBookingEmailEvent  $event
     * @return void
     */
    public function handle(UpdateCustomerEvent $event)
    {
        //\Debugbar::info('hello');
        $customer = $event->resource;

        if (isset($customer->offices_managers)) {


            $offices_managers = ($customer->offices_managers!=='') ? json_decode($customer->offices_managers) : [];

            if (count($offices_managers) > 0) {
                $checkValues = [];
                foreach ($offices_managers as $key => $value) {
                    array_push($checkValues, $value->office_id);
                }
                $checkNewOffices = \DB::table('shifl_offices_managers')
                                         ->whereNotIn('shifl_office_id', $checkValues)
                                         ->get();

                if (count($checkNewOffices)>0) {
                    foreach ($checkNewOffices as $key => $value) {
                        array_push($offices_managers, [
                            'office_id' => $value->shifl_office_id,
                            'manager_id' => $value->manager_id
                        ]);
                    }
                }
            } else {
                $checkNewOffices = \DB::table('shifl_offices_managers')
                                         ->get();

                if (count($checkNewOffices)>0) {
                    foreach ($checkNewOffices as $key => $value) {
                        array_push($offices_managers, [
                            'office_id' => $value->shifl_office_id,
                            'manager_id' => $value->manager_id
                        ]);
                    }
                }
            }
            $customer->offices_managers = json_encode($offices_managers);
            $customer->save();

        }


        
    }
}
