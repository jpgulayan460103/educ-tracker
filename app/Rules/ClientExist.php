<?php

namespace App\Rules;

use App\Models\Client;
use Illuminate\Contracts\Validation\Rule;

class ClientExist implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public $composition;
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
        $last_name = request("$field.last_name");
        $first_name = request("$field.first_name");
        $middle_name = request("$field.middle_name");
        $id = request("$field.id");

        $client_query = Client::where('last_name', $last_name)->where('first_name', $first_name)->where('middle_name', $middle_name);
        if($id && $id != ""){
            $client_query->where('id', '<>', $id);
        }
        $client_count = $client_query->count();
        if($client_count != 0){
            $client = $client_query->first();
            $this->composition = $client->composition;
        }
        return $client_count == 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $uuid = $this->composition->uuid;
        return 'The client exist.'."<a @click='viewBeneficiary(".'"'.$uuid.'"'.")' href='".route('encoding', $this->composition->uuid)."'>".$this->composition->uuid."</a>";
    }
}
