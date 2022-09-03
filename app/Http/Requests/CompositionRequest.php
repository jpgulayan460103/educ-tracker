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
        $rules = [
            'client.last_name' => ['required', 'string', 'max:255', new ValidStringName],
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
            'client.client_sector_id' => ['required'],
            'client.sector_other_id' => ['required_if:client.sector_name,Others'],
            'client.mobile_number' => [new ValidCellphoneNumber],
            // 'client.birth_date' => ['required', 'date' , 'before:'.Carbon::now()->toDateString()],
            // 'client.age' => ['required', 'integer'],
            'client.gender' => ['required'],
            // 'client.swad_office_name' => ['required'],
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
            // 'beneficiaries.*.mobile_number' => [new ValidCellphoneNumber],
            'beneficiaries.*.birth_date' => ['date' , 'before:'.Carbon::now()->toDateString()],
            // 'beneficiaries.*.age' => ['required', 'integer'],
            // 'beneficiaries.*.gender' => ['required', 'string', 'max:10'],
            // 'beneficiaries.*.occupation' => ['required', 'string', 'max:255'],
            // 'beneficiaries.*.monthly_salary' => ['required', 'string', 'max:255'],

            'father.last_name' => ['max:255', new ValidStringName],
            'father.first_name' => ['max:255', new ValidStringName],
            'father.middle_name' => ['required_if:father.has_middle_name,false', 'max:255', new ValidStringName],
            'father.ext_name' => ['max:255', new ValidStringName],
            'father.birth_date' => ['date' , 'before:'.Carbon::now()->toDateString()],
            // 'father.relationship_beneficiary' => ['required', 'string', 'max:255'],

            'mother.last_name' => ['max:255', new ValidStringName],
            'mother.first_name' => ['max:255', new ValidStringName],
            'mother.middle_name' => ['required_if:mother.has_middle_name,false', 'max:255', new ValidStringName],
            'mother.ext_name' => ['max:255', new ValidStringName],
            'mother.birth_date' => ['date' , 'before:'.Carbon::now()->toDateString()],
            'payout_id' => ['required'],
        ];

        if(request()->has('id') && request('id') != ""){
            unset($rules['client.mobile_number']);
            unset($rules['client.last_name']);
            unset($rules['client.middle_name']);
            unset($rules['client.first_name']);
            unset($rules['client.ext_name']);
            unset($rules['client.street_number']);
            // unset($rules['client.brgy']);
            // unset($rules['client.city']);
            // unset($rules['client.province']);
            // unset($rules['client.psgc_id']);
            // unset($rules['client.swad_office_id']);
            unset($rules['client.sector_id']);
            unset($rules['client.client_sector_id']);
            unset($rules['client.sector_other_id']);
            unset($rules['client.mobile_number']);
            unset($rules['client.birth_date']);
            unset($rules['client.age']);
            unset($rules['client.gender']);
            unset($rules['client.swad_office_name']);
            unset($rules['client.occupation']);
            unset($rules['client.monthly_salary']);
            unset($rules['client.relationship_beneficiary']);
            unset($rules['beneficiaries.*.gender']);
            unset($rules['beneficiaries.*.school_name']);
            unset($rules['client.street_number']);
            unset($rules['father.birth_date']);
            unset($rules['mother.birth_date']);
        }
        return $rules;
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
                // $school_level_amounts = request('school_level_amounts');
                // foreach ($school_level_amounts as $key => $school_level_amount) {
                //     if($school_level_amount['total_amount'] == 0){
                //         continue;
                //     }
                //     $school_level_id = $school_level_amount['id'];
                //     $total_amount = $school_level_amount['total_amount'];
                //     $payout_id = request('payout_id');
                //     $swad_office_id = request('client.swad_office_id');
                //     $amount_granted = Beneficiary::where('payout_id', $payout_id)->where('swad_office_id', $swad_office_id)->sum('amount_granted');
                //     $allocated_amount = FundAllocation::where('payout_id', $payout_id)->where('swad_office_id', $swad_office_id)->sum('allocated_amount');
                //     // $amount_granted = Beneficiary::where('school_level_id', $school_level_id)->where('payout_id', $payout_id)->sum('amount_granted');
                //     // $allocated_amount = FundAllocation::where('school_level_id', $school_level_id)->where('payout_id', $payout_id)->sum('allocated_amount');

                //     $remaining = $allocated_amount - $amount_granted;
                //     // dd([
                //     //     'allocated_amount' => $allocated_amount,
                //     //     'amount_granted' => $amount_granted,
                //     //     'total_amount' => $total_amount,
                //     //     'remaining' => $remaining,
                //     //     $remaining < $total_amount
                //     // ]);
                //     if($remaining < $total_amount || $remaining == 0){
                //         $validator->errors()->add("school_level_amount", "Not enough allocation.");
                //     }

                // }
                
                $total_amount = request('totalSchoolLevelAmounts');
                $payout_id = request('payout_id');
                $swad_office_id = request('client.swad_office_id');
                $beneficiaries = collect(request('beneficiaries'));
                $beneficiary_ids = $beneficiaries->pluck('id')->toArray();
                if(request()->has('id')){
                    $amount_granted = Beneficiary::where('payout_id', $payout_id)
                        ->where('swad_office_id', $swad_office_id)
                        ->whereNotIn('id', $beneficiary_ids)
                        ->sum('amount_granted');
                    $allocated_amount = FundAllocation::where('payout_id', $payout_id)
                        ->where('swad_office_id', $swad_office_id)
                        ->sum('allocated_amount');
                }else{
                    $amount_granted = Beneficiary::where('payout_id', $payout_id)
                        ->where('swad_office_id', $swad_office_id)
                        ->sum('amount_granted');
                    $allocated_amount = FundAllocation::where('payout_id', $payout_id)
                        ->where('swad_office_id', $swad_office_id)
                        ->sum('allocated_amount');
                }
                // $amount_granted = Beneficiary::where('school_level_id', $school_level_id)->where('payout_id', $payout_id)->sum('amount_granted');
                // $allocated_amount = FundAllocation::where('school_level_id', $school_level_id)->where('payout_id', $payout_id)->sum('allocated_amount');

                $remaining = $allocated_amount - $amount_granted - $total_amount;
                // dd([
                //     'allocated_amount' => $allocated_amount,
                //     'amount_granted' => $amount_granted,
                //     'total_amount' => $total_amount,
                //     'remaining' => $remaining,
                //     $remaining < 0 && $total_amount != 0
                // ]);
                if($remaining < 0 && $total_amount != 0){
                    $validator->errors()->add("school_level_amount", "Not enough allocation.");
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
