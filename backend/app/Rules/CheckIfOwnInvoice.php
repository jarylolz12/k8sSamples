<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use App\Invoice;

class CheckIfOwnInvoice implements Rule
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
        
        $check_qbo_customers = Auth::user()->customersApi->pluck('qbo_customer');
        $final_qbo_customers = [];

        if (count($check_qbo_customers)>0) {
            
            foreach($check_qbo_customers as $check_qbo_customer) {

                if ($check_qbo_customer!==null && $check_qbo_customer!=='') {
                    $obj = json_decode($check_qbo_customer);
                    if (isset($obj->customer)) {
                        if (isset($obj->customer->Id)) {
                            array_push($final_qbo_customers, intval($obj->customer->Id));
                        }
                    }
                }
            }
        }

        $invoice = Invoice::whereIn('qbo_customer_id', $final_qbo_customers)
                            ->where('id', $value)
                            ->first();

        return (isset($invoice->id));

    }


    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Please make sure you that you are allowed to view that invoice.';
    }
}