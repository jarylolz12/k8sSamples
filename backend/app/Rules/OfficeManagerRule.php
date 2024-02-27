<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class OfficeManagerRule implements Rule
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

        $hasErrors = false;

        //check all offices
        $offices = \App\ShiflOffice::all();

        if (count($offices) > 0) {
            $getValue = json_decode($value, true);

            if (count($getValue)>0) {

                $notAssigned = [];
                foreach ($offices as $key => $value) {

                    $hasMatch = false;
                    foreach($getValue as $k => $v) {
                        if (isset($v['office_id']) && $v['office_id'] == $value->id) {
                            $hasMatch = true;
                        }
                    }
                    if ($hasMatch==false) {
                        array_push($notAssigned, intval($value->id));
                    }
                }

                if (count($notAssigned) > 0) {
                    $hasErrors = true;
                    $this->errorMessages = $notAssigned;
                }

            } else {
                $notAssigned = [];
                foreach ($offices as $key => $value) {
                    array_push($notAssigned, intval($value->id));
                }
                $hasErrors = true;
                $this->errorMessages = $notAssigned;
            }

        }

        return (!$hasErrors) ? true : false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->errorMessages;
    }
}
