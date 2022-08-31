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
        $ext_name = request("$field.$index.ext_name");
        $full_name_array = [
            $first_name,
            $middle_name,
            $last_name,
            $ext_name,
        ];
        $full_name = trim(implode(" ",$full_name_array));
        $full_name = trim(preg_replace("/\s+/", " ", $full_name));


        $full_name_mi_array = [
            $first_name,
            ($middle_name ? substr($middle_name, 0, 1) : ""),
            $last_name,
            $ext_name,
        ];
        $full_name_mi = trim(implode(" ",$full_name_mi_array));
        $full_name_mi = trim(preg_replace("/\s+/", " ", $full_name_mi));


        $id = request("$field.$index.id");

        // $beneficiary_query = Beneficiary::where('full_name', $full_name);
        $beneficiary_query = Beneficiary::where('full_name_mi', $full_name_mi);
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
        return 'The beneficiary exist.<br>'."<a target='_blank' href='".route('encoding', $this->composition->uuid)."'>View composition</a>";
        // return 'The beneficiary exist.'."<a target='_blank' href='".route('encoding', $this->composition->uuid)."'>".$this->composition->uuid."</a>";
    }
}
