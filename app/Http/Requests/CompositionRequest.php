<?php

namespace App\Http\Requests;

use App\Models\Beneficiary;
use App\Models\FundAllocation;
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
            'client.brgy' => ['required'],
            'client.city' => ['required'],
            'client.province' => ['required'],
            'client.psgc_id' => ['required'],
            'client.swad_office_id' => ['required'],
            'client.sector_id' => ['required'],
            'client.sector_other_id' => ['required_if:client.sector_name,Others'],
            'client.mobile_number' => [new ValidCellphoneNumber],
            'client.birth_date' => ['required', 'date' , 'before:'.Carbon::now()->toDateString()],
            'client.age' => ['required', 'integer'],
            'client.gender' => ['required'],
            'client.swad_office_name' => ['required'],
            // 'client.occupation' => ['required', 'string', 'max:255'],
            // 'client.monthly_salary' => ['required', 'string', 'max:255'],
            'client.relationship_beneficiary' => ['required', 'string', 'max:255'],

            'beneficiaries.*.last_name' => ['required', 'string', 'max:255', new ValidStringName, new BeneficiaryExist],
            'beneficiaries.*.first_name' => ['required', 'string', 'max:255', new ValidStringName],
            'beneficiaries.*.middle_name' => ['required_if:beneficiaries.*.has_middle_name,false', 'max:255', new ValidStringName],
            'beneficiaries.*.ext_name' => ['max:255', new ValidStringName],
            // 'beneficiaries.*.street_number' => ['required', 'string', 'max:255'],
            'beneficiaries.*.school_level_id' => ['required'],
            // 'beneficiaries.*.sector_id' => ['required'],
            'beneficiaries.*.status' => ['required'],
            'beneficiaries.*.swad_office_id' => ['required'],
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
            'payout_id' => ['required'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $this->validateBeneficaryFullNames($validator);
            $this->validateFundAllocated($validator);
        });
    }

    public function validateFundAllocated($validator)
    {
        if(request()->has('school_level_amounts')){
            if(request('school_level_amounts') == array()){
                $validator->errors()->add("school_level_amounts", "No Amounts .");
            }else{
                $school_level_amounts = request('school_level_amounts');
                foreach ($school_level_amounts as $key => $school_level_amount) {
                    if($school_level_amount['total_amount'] == 0){
                        continue;
                    }
                    $school_level_id = $school_level_amount['id'];
                    $payout_id = 2;
                    $amount_granted = Beneficiary::where('school_level_id', $school_level_id)->where('payout_id', $payout_id)->sum('amount_granted');
                    $allocated_amount = FundAllocation::where('school_level_id', $school_level_id)->where('payout_id', $payout_id)->sum('allocated_amount');
                    // dd([
                    //     'allocated_amount' => $allocated_amount,
                    //     'amount_granted' => $amount_granted,
                    // ]);
                    if($allocated_amount < $amount_granted || $allocated_amount == 0){
                        $validator->errors()->add("school_level_amount.$key", "Not enough allocation.");
                    }

                }
            }
        }
    }

    public function validateBeneficaryFullNames($validator)
    {
        if(request()->has('beneficiaries')){
            if(request('beneficiaries') == array()){
                $validator->errors()->add("beneficiary", "No beneficiary added.");
            }else{
                $beneficiaries = request('beneficiaries');
                $full_names = [];
                $claimed = [];
                foreach ($beneficiaries as $beneficiary) {
                    $full_name_array = [
                        (isset($beneficiary['first_name']) ? $beneficiary['first_name'] : ""),
                        (isset($beneficiary['middle_name']) ? $beneficiary['middle_name'] : ""),
                        (isset($beneficiary['last_name']) ? $beneficiary['last_name'] : ""),
                    ];
                    $full_name = implode(" ",$full_name_array);
                    $full_names[] = trim($full_name);
                    if(isset($beneficiary['status']) && $beneficiary['status'] == "Claimed"){
                        $claimed[] = $beneficiary['status'];
                    }
                }

                $full_names = array_unique($full_names);
                if(count($full_names) != count($beneficiaries)){
                    $validator->errors()->add("beneficiary", "The beneficiary has duplicate entries.");
                }
                if(count($claimed) > 3){
                    $validator->errors()->add("beneficiary", "The beneficiary has 4 or more claimed status.");
                }
            }
        }
    }
}
