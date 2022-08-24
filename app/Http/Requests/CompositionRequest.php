<?php

namespace App\Http\Requests;

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
            'client.last_name' => ['required', 'string', 'max:255', new ValidStringName],
            'client.middle_name' => ['required', 'string', 'max:255', new ValidStringName],
            'client.first_name' => ['required', 'string', 'max:255', new ValidStringName],
            'client.ext_name' => ['string', 'max:255', new ValidStringName],
            'client.street_number' => ['required', 'string', 'max:255'],
            // 'client.psgc_id' => ['required'],
            'client.mobile_number' => [new ValidCellphoneNumber],
            'client.birth_date' => ['required', 'date' , 'before:'.Carbon::now()->toDateString()],
            'client.age' => ['required', 'integer'],
            'client.gender' => ['required', 'string', 'max:5'],
            'client.occupation' => ['required', 'string', 'max:255'],
            'client.monthly_salary' => ['required', 'string', 'max:255'],
            'client.relationship_beneficiary' => ['required', 'string', 'max:255'],

            'beneficiaries.*.last_name' => ['required', 'string', 'max:255', new ValidStringName],
            'beneficiaries.*.first_name' => ['required', 'string', 'max:255', new ValidStringName],
            'beneficiaries.*.middle_name' => ['required', 'string', 'max:255', new ValidStringName],
            'beneficiaries.*.ext_name' => ['string', 'max:255', new ValidStringName],
            // 'beneficiaries.*.street_number' => ['required', 'string', 'max:255'],
            'beneficiaries.*.school_level_id' => ['required'],
            'beneficiaries.*.sector_id' => ['required'],
            'beneficiaries.*.status' => ['required'],
            'beneficiaries.*.mobile_number' => [new ValidCellphoneNumber],
            'beneficiaries.*.birth_date' => ['required', 'date' , 'before:'.Carbon::now()->toDateString()],
            // 'beneficiaries.*.age' => ['required', 'integer'],
            // 'beneficiaries.*.gender' => ['required', 'string', 'max:5'],
            // 'beneficiaries.*.occupation' => ['required', 'string', 'max:255'],
            // 'beneficiaries.*.monthly_salary' => ['required', 'string', 'max:255'],

            'father.last_name' => ['required', 'string', 'max:255', new ValidStringName],
            'father.first_name' => ['required', 'string', 'max:255', new ValidStringName],
            'father.middle_name' => ['required', 'string', 'max:255', new ValidStringName],
            'father.ext_name' => ['string', 'max:255', new ValidStringName],
            'father.birth_date' => ['required', 'date' , 'before:'.Carbon::now()->toDateString()],
            // 'father.relationship_beneficiary' => ['required', 'string', 'max:255'],

            'mother.last_name' => ['required', 'string', 'max:255', new ValidStringName],
            'mother.first_name' => ['required', 'string', 'max:255', new ValidStringName],
            'mother.middle_name' => ['required', 'string', 'max:255', new ValidStringName],
            'mother.ext_name' => ['string', 'max:255', new ValidStringName],
            'mother.birth_date' => ['required', 'date' , 'before:'.Carbon::now()->toDateString()],
            // 'mother.relationship_beneficiary' => ['required', 'string', 'max:255'],
        ];
    }
}
