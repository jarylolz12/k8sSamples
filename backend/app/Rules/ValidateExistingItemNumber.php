<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Item;

class ValidateExistingItemNumber implements Rule
{

    public function __construct()
    {
        //
    }


    public function passes($attribute, $value)
    {
        if(Item::where('item_num',$value)->count() > 0){
            return false;
        }
        return true;
    }

    public function message()
    {
        return 'Item Number already exists.';
    }
}
