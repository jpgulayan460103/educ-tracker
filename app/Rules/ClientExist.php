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

        $client_count = Client::where('last_name', $last_name)->where('first_name', $first_name)->where('middle_name', $middle_name);
        if($id && $id != ""){
            $client_count->where('id', '<>', $id);
        }
        return $client_count->count() == 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The client exist message.';
    }
}
