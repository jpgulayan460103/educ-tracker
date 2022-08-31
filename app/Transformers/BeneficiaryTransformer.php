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
        'composition',
        'school_level',
        'sector',
        'payout',
        'swad_office',
    ];
    
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Beneficiary $beneficiary)
    {
        $data = [
            'id' => $beneficiary->id,
            'key' => $beneficiary->id,
            'last_name' => $beneficiary->last_name,
            'first_name' => $beneficiary->first_name,
            'middle_name' => $beneficiary->middle_name,
            'ext_name' => $beneficiary->ext_name,
            'full_name' => $beneficiary->full_name,
            'school_level_id' => $beneficiary->school_level_id,
            'mobile_number' => $beneficiary->mobile_number,
            'school_name' => $beneficiary->school_name,
            'birth_date' => $beneficiary->birth_date,
            'age' => $beneficiary->age,
            'gender' => $beneficiary->gender,
            'occupation' => $beneficiary->occupation,
            'monthly_salary' => $beneficiary->monthly_salary,
            'composition_id' => $beneficiary->composition_id,
            'sector_id' => $beneficiary->sector_id,
            'remarks' => $beneficiary->remarks,
            'status' => $beneficiary->status,
            'status_date' => $beneficiary->status_date,
            'school_name' => $beneficiary->school_name,
            'amount_granted' => $beneficiary->amount_granted,
            'payout_id' => $beneficiary->payout_id,
            'swad_office_id' => $beneficiary->swad_office_id,
            'uuid' => $beneficiary->uuid,
            'control_number' => $beneficiary->control_number,
            'created_at' => $beneficiary->created_at->toDateString(),
        ];

        if($data['birth_date'] == null){
            unset($data['birth_date']);
        }
        return $data;
    }

    public function includeComposition(Beneficiary $beneficiary)
    {
        if($beneficiary->composition){
            return $this->item($beneficiary->composition, new CompositionTransformer);
        }
    }
    public function includeSchoolLevel(Beneficiary $beneficiary)
    {
        if($beneficiary->school_level){
            return $this->item($beneficiary->school_level, new SchoolLevelTransformer);
        }
    }
    public function includeSector(Beneficiary $beneficiary)
    {
        if($beneficiary->sector){
            return $this->item($beneficiary->sector, new SectorTransformer);
        }
    }
    public function includePayout(Beneficiary $beneficiary)
    {
        if($beneficiary->payout){
            return $this->item($beneficiary->payout, new PayoutTransformer);
        }
    }
    public function includeSwadOffice(Beneficiary $beneficiary)
    {
        if($beneficiary->swad_office){
            return $this->item($beneficiary->swad_office, new SwadOfficeTransformer);
        }
    }
}
