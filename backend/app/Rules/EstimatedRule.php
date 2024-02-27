<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class EstimatedRule implements Rule
{

    private $errorMessages = [];
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $hasError = false;
        //$checkShipmentStatus = (isset($_POST['shipment_status'])) ? $_POST['shipment_status'] : '';
        $checkShipmentStatus = (isset($_POST['booking_confirmed'])) ? $_POST['booking_confirmed'] : 0;

        if ($checkShipmentStatus==1) {
          //get the etd and eta
            $getScheduleGroup = json_decode($value);

            if (count($getScheduleGroup)==0) {
                $this->errorMessages['etd'] = 'Estimated Time of Departure is required.';
                $this->errorMessages['eta'] = 'Estimated Time of Arrival is required';
               // $this->errorMessage = 'Please make sure to add schedule and fill up field for etd and eta as the shipment status was selected as approved.';
                $hasError = true;
            } else {
                if ($getScheduleGroup[0]->etd=='' || $getScheduleGroup[0]->etd==null) {
                    $hasError = true;
                    $this->errorMessages['etd'] = 'Estimated Time of Departure is required.';
                }

                if ($getScheduleGroup[0]->eta=='' || $getScheduleGroup[0]->eta==null) {
                    $hasError = true;
                    $this->errorMessages['eta'] = 'Estimated Time of Arrival is required.';
                }
            }
        }
        /*if($checkShipmentStatus=='Approved') {
            if($checkShipmentStatus!='') {


                //get the etd and eta
                $getScheduleGroup = json_decode($value);

                if(count($getScheduleGroup)==0) {
                    $this->errorMessages['etd'] = 'Estimated Time of Departure is required.';
                    $this->errorMessages['eta'] = 'Estimated Time of Arrival is required';
                   // $this->errorMessage = 'Please make sure to add schedule and fill up field for etd and eta as the shipment status was selected as approved.';
                    $hasError = true;
                }
                else
                {
                    if($getScheduleGroup[0]->etd=='' || $getScheduleGroup[0]->etd==null) {
                        $hasError = true;
                        $this->errorMessages['etd'] = 'Estimated Time of Departure is required.';
                    }

                    if($getScheduleGroup[0]->eta=='' || $getScheduleGroup[0]->eta==null) {
                        $hasError = true;
                        $this->errorMessages['eta'] = 'Estimated Time of Arrival is required.';
                    }

                }

            }
        }*/

        return (!$hasError);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return json_encode($this->errorMessages);
        //return 'Make sure to add etd and eta as well.';
    }
}
