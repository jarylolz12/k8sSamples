<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class IsArrayRule implements Rule
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
        //
        if (isset($value))
            return (is_array($value) && count($value) > 0);
        else {
            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This field must be a valid array and has an array length of at least 1.';
    }
}
