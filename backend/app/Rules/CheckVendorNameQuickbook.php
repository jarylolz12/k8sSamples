<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

class CheckVendorNameQuickbook implements Rule
{
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

        $names = [];
        $vendor_obj = $qb->getDataService()->Query("select * from Vendor MAXRESULTS 800");
        
        if (count($vendor_obj) > 0) {
            foreach ($vendor_obj as $key => $v) {
                if (isset($v->DisplayName)) {
                    array_push($names, $v->DisplayName);
                }
            }
        }

        if (in_array($value, $names))
            $valid = false;

        return $valid;

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Vendor name already exists in the Quickbooks.';
    }
}