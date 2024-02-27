<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class FindDuplicateSupplier implements Rule
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
        $getSupplierGroup = json_decode($value);
        $tempArray = [];
        $errorKeys = [];

        if (count($getSupplierGroup)>0) {
            foreach ($getSupplierGroup as $key => $supplier) {
                if (!in_array($supplier->supplier_id, $tempArray)) {
                    array_push($tempArray, $supplier->supplier_id);
                } else {
                    array_push($errorKeys, $supplier->supplier_id);
                    $hasError = true;
                }
            }

            if ($hasError) {
                $this->errorMessages['supplier_id'] = $errorKeys;
            }
        }
        //$hasError = true;

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
