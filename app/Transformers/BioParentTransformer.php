<?php

namespace App\Transformers;

use App\Models\BioParent;
use League\Fractal\TransformerAbstract;

class BioParentTransformer extends TransformerAbstract
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
    public function transform(BioParent $bioParent)
    {
        $data = [
            'id' => $bioParent->id,
            'key' => $bioParent->id,
            'full_name' => $bioParent->full_name,
            'last_name' => $bioParent->last_name,
            'first_name' => $bioParent->first_name,
            'middle_name' => $bioParent->middle_name,
            'ext_name' => $bioParent->ext_name,
            'birth_date' => $bioParent->birth_date,
            'relationship_beneficiary' => $bioParent->relationship_beneficiary,
            'composition_id' => $bioParent->composition_id,
            'uuid' => $bioParent->uuid,
        ];
        if($data['birth_date'] == null){
            unset($data['birth_date']);
        }
        return $data;
    }
}
