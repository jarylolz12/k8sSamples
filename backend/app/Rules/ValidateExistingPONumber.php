<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\PurchaseOrder;

class ValidateExistingPONumber implements Rule
{
    public function __construct()
    {
        //
    }

    public function passes($attribute, $value)
    {
        if(PurchaseOrder::where('po_num',$value)->count() > 0){
            return false;
        }
        return true;
    }

    public function message()
    {
        return 'P.O. Number already exists.';
    }
}
