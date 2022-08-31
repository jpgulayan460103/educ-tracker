<?php

namespace App\Rules;

use App\Models\BioParent;
use Illuminate\Contracts\Validation\Rule;

class BioParentExist implements Rule
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
        $ext_name = request("$field.ext_name");

        $full_name_array = [
            $first_name,
            $middle_name,
            $last_name,
            $ext_name,
        ];
        $full_name = trim(implode(" ",$full_name_array));
        $full_name = trim(preg_replace("/\s+/", " ", $full_name));
        
        $id = request("$field.id");

        $bio_parent_query = BioParent::where('full_name', $full_name);
        if($id && $id != ""){
            $bio_parent_query->where('id', '<>', $id);
        }
        $bio_parent_count = $bio_parent_query->count();
        if($bio_parent_count != 0){
            $bio_parent = $bio_parent_query->first();
            $this->composition = $bio_parent->composition;
        }
        return $bio_parent_count == 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $uuid = $this->composition->uuid;
        return 'The parent exist.'."<a target='_blank' href='".route('encoding', $this->composition->uuid)."'>".$this->composition->uuid."</a>";
    }
}
