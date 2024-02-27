<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

class CheckVendorQuickbook implements Rule
{

    private $error_message = '';

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


        $qb = app('QuickBooks');
        $valid = true;

        $emails = [];
        $vendor_obj = $qb->getDataService()->Query("select * from Vendor MAXRESULTS 800");
        
        if (count($vendor_obj) > 0) {
            foreach ($vendor_obj as $key => $v) {
                if (isset($v->PrimaryEmailAddr) && isset($v->PrimaryEmailAddr->Address)) {
                    array_push($emails, $v->PrimaryEmailAddr->Address);
                }
            }
        }

        if (in_array($value, $emails))
            $valid = false;

        $this->error_message = 'Vendor email already exists in the Quickbooks.';

        if ( $valid ) {
            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $this->error_message = 'Please enter a valid email address.';
                $valid = false;
            }
        }
        
        return $valid;

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->error_message;
    }
}