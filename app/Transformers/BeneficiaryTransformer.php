<?php

namespace App\Transformers;

use App\Models\Beneficiary;
use League\Fractal\TransformerAbstract;

class BeneficiaryTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        //
    ];
    
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        //
    ];
    
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Beneficiary $beneficiary)
    {
        return [
            'id' => $beneficiary->id,
            'key' => $beneficiary->id,
            'last_name' => $beneficiary->last_name,
            'first_name' => $beneficiary->first_name,
            'middle_name' => $beneficiary->middle_name,
            'ext_name' => $beneficiary->ext_name,
            'full_name' => $beneficiary->full_name,
            'street_number' => $beneficiary->street_number,
            'school_level_id' => $beneficiary->school_level_id,
            'mobile_number' => $beneficiary->mobile_number,
            'birth_date' => $beneficiary->birth_date,
            'age' => $beneficiary->age,
            'gender' => $beneficiary->gender,
            'occupation' => $beneficiary->occupation,
            'monthly_salary' => $beneficiary->monthly_salary,
            'composition_id' => $beneficiary->composition_id,
        ];
    }
}
