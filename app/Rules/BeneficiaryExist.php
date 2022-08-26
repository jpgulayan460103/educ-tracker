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
    public $composition;
    public function __construct()
    {
        
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
        $index = $att_split[1];
        $last_name = request("$field.$index.last_name");
        $first_name = request("$field.$index.first_name");
        $middle_name = request("$field.$index.middle_name");
        $id = request("$field.$index.id");

        $beneficiary_query = Beneficiary::where('last_name', $last_name)->where('first_name', $first_name)->where('middle_name', $middle_name);
        if($id && $id != ""){
            $beneficiary_query->where('id', '<>', $id);
        }
        $beneficiary_count = $beneficiary_query->count();
        if($beneficiary_count != 0){
            $beneficiary = $beneficiary_query->first();
            $this->composition = $beneficiary->composition;
        }
        return $beneficiary_count == 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $uuid = $this->composition->uuid;
        return 'The beneficiary exist.'."<a target='_blank' href='".route('encoding', $this->composition->uuid)."'>".$this->composition->uuid."</a>";
    }
}
