<?php

namespace App\Rules;

use App\Models\Beneficiary;
use Illuminate\Contracts\Validation\Rule;

class BeneficiaryExist implements Rule
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
        $att_split = explode('.',$attribute);
        $field = $att_split[0];
        $index = $att_split[0];
        $last_name = request("$field.$index.last_name");
        $first_name = request("$field.$index.first_name");
        $middle_name = request("$field.$index.middle_name");
        $id = request("$field.$index.id");

        $beneficiary_count = Beneficiary::where('last_name', $last_name)->where('first_name', $first_name)->where('middle_name', $middle_name);
        if($id && $id != ""){
            $beneficiary_count->where('id', '<>', $id);
        }
        return $beneficiary_count->count() == 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The beneficiary exist.';
    }
}
