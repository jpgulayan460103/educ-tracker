<?php

namespace App\Rules;

use App\Models\Beneficiary;
use App\Models\Client;
use App\Models\Composition;
use Illuminate\Contracts\Validation\Rule;

class ClientExist implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public $uuids;
    public function __construct()
    {
        $this->uuids = [];
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
        $last_name = request("$field.last_name");
        $first_name = request("$field.first_name");
        $middle_name = request("$field.middle_name");
        $ext_name = request("$field.ext_name");

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

        $id = request("$field.id");

        // $client_query = Client::where('full_name', $full_name);
        $client_query = Client::where('full_name_mi', $full_name_mi);
        if($id && $id != ""){
            $client_query->where('id', '<>', $id);
        }
        $client_ids = $client_query->pluck('id');

        $beneficiary_query = Beneficiary::whereHas("composition", function($q) use ($client_ids){
            $q->whereIn("compositions.id", $client_ids);
        })->where('beneficiaries.status', "Claimed");
        $beneficiary_count = $beneficiary_query->count();
        $form_data_beneficiaries = request('beneficiaries');
        $total_beneficiaries = $beneficiary_count + count($form_data_beneficiaries);
        if($total_beneficiaries > 3){
            $this->uuids = Composition::whereIn('client_id', $client_ids)->pluck('uuid');
            return false;
        }else{
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
        $links = [];
        foreach ($this->uuids as $uuid) {
            $uuid_array = explode("-", $uuid);
            $last_uuid = end($uuid_array);
            $last_uuid = "View composition";
            $links[] = "<a target='_blank' href='".route('encoding', $uuid)."'>".$last_uuid."</a>";
        }
        return 'This client has reached its maximum beneficiaries.<br>'. implode("<br>", $links);
    }
}
