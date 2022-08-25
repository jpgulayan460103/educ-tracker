<?php

namespace App\Http\Requests;

use App\Rules\BeneficiaryExist;
use App\Rules\BioParentExist;
use App\Rules\ClientExist;
use App\Rules\ValidCellphoneNumber;
use App\Rules\ValidString;
use App\Rules\ValidStringName;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class CompositionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'client.last_name' => ['required', 'string', 'max:255', new ValidStringName, new ClientExist],
            'client.middle_name' => ['required_if:client.has_middle_name,false', 'max:255', new ValidStringName],
            'client.first_name' => ['required', 'string', 'max:255', new ValidStringName],
            'client.ext_name' => ['max:255', new ValidStringName],
            'client.street_number' => ['required', 'string', 'max:255'],
            // 'client.psgc_id' => ['required'],
            'client.mobile_number' => [new ValidCellphoneNumber],
            'client.birth_date' => ['required', 'date' , 'before:'.Carbon::now()->toDateString()],
            'client.age' => ['required', 'integer'],
            'client.gender' => ['required'],
            'client.occupation' => ['required', 'string', 'max:255'],
            'client.monthly_salary' => ['required', 'string', 'max:255'],
            'client.relationship_beneficiary' => ['required', 'string', 'max:255'],

            'beneficiaries.*.last_name' => ['required', 'string', 'max:255', new ValidStringName, new BeneficiaryExist],
            'beneficiaries.*.first_name' => ['required', 'string', 'max:255', new ValidStringName],
            'beneficiaries.*.middle_name' => ['required_if:beneficiaries.*.has_middle_name,false', 'max:255', new ValidStringName],
            'beneficiaries.*.ext_name' => ['max:255', new ValidStringName],
            // 'beneficiaries.*.street_number' => ['required', 'string', 'max:255'],
            'beneficiaries.*.school_level_id' => ['required'],
            // 'beneficiaries.*.sector_id' => ['required'],
            'beneficiaries.*.status' => ['required'],
            'beneficiaries.*.payout_id' => ['required_if:beneficiaries.*.status,Claimed'],
            'beneficiaries.*.gender' => ['required'],
            'beneficiaries.*.school_name' => ['required'],
            'beneficiaries.*.mobile_number' => [new ValidCellphoneNumber],
            'beneficiaries.*.birth_date' => ['required', 'date' , 'before:'.Carbon::now()->toDateString()],
            // 'beneficiaries.*.age' => ['required', 'integer'],
            // 'beneficiaries.*.gender' => ['required', 'string', 'max:10'],
            // 'beneficiaries.*.occupation' => ['required', 'string', 'max:255'],
            // 'beneficiaries.*.monthly_salary' => ['required', 'string', 'max:255'],

            'father.last_name' => ['required', 'string', 'max:255', new ValidStringName, new BioParentExist],
            'father.first_name' => ['required', 'string', 'max:255', new ValidStringName],
            'father.middle_name' => ['required_if:father.has_middle_name,false', 'max:255', new ValidStringName],
            'father.ext_name' => ['max:255', new ValidStringName],
            'father.birth_date' => ['required', 'date' , 'before:'.Carbon::now()->toDateString()],
            // 'father.relationship_beneficiary' => ['required', 'string', 'max:255'],

            'mother.last_name' => ['required', 'string', 'max:255', new ValidStringName, new BioParentExist],
            'mother.first_name' => ['required', 'string', 'max:255', new ValidStringName],
            'mother.middle_name' => ['required_if:mother.has_middle_name,false', 'max:255', new ValidStringName],
            'mother.ext_name' => ['max:255', new ValidStringName],
            'mother.birth_date' => ['required', 'date' , 'before:'.Carbon::now()->toDateString()],
            // 'mother.relationship_beneficiary' => ['required', 'string', 'max:255'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $this->validateBeneficaryFullNames($validator);
        });
    }

    public function validateBeneficaryFullNames($validator)
    {
        if(request()->has('beneficiaries')){
            if(request('beneficiaries') == array()){
                $validator->errors()->add("beneficiary", "No beneficiary added.");
            }else{
                $beneficiaries = request('beneficiaries');
                $full_names = [];
                foreach ($beneficiaries as $beneficiary) {
                    $full_name_array = [
                        (isset($beneficiary['first_name']) ? $beneficiary['first_name'] : ""),
                        (isset($beneficiary['middle_name']) ? $beneficiary['middle_name'] : ""),
                        (isset($beneficiary['last_name']) ? $beneficiary['last_name'] : ""),
                    ];
                    $full_name = implode(" ",$full_name_array);
                    $full_names[] = trim($full_name);
                }

                $full_names = array_unique($full_names);
                if(count($full_names) != count($beneficiaries)){
                    $validator->errors()->add("beneficiary", "Beneficiary has duplicate entries.");
                }
            }
        }
    }
}
