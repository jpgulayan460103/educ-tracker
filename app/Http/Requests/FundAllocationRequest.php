<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FundAllocationRequest extends FormRequest
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
        $payout_id = request('payout_id');
        $id = request('id');
        $swad_office_id = request('swad_office_id');
        $school_level_id = request('school_level_id');
        return [
            'swad_office_id' => ['required'],
            'school_level_id' => ['required'],
            'allocated_amount' => ['required', 'numeric', 'min:0'],
            'payout_id' => [
                'required',
                Rule::unique('fund_allocations')->where(function ($query) use ($payout_id, $swad_office_id, $school_level_id, $id)  {
                    $query
                    ->where('payout_id', $payout_id)
                    ->where('swad_office_id', $swad_office_id)
                    ->where('school_level_id', $school_level_id)
                    ->where('id', '<>', $id);
                }),
            ],
        ];
    }
}
