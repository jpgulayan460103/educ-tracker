<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidCellphoneNumber implements Rule
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
        if(trim($value) == ""){
            return true;
        }
        if(strlen(trim($value)) != 11){
            return false;
        }
        if(substr($value, 0, 2) != "09"){
            return false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "The invalid mobile number format";
    }
}
