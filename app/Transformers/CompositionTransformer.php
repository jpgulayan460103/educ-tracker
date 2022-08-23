<?php

namespace App\Transformers;

use App\Models\Composition;
use League\Fractal\TransformerAbstract;

class CompositionTransformer extends TransformerAbstract
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
        'beneficiaries',
        'father',
        'mother',
        'client',
    ];
    
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Composition $composition)
    {
        return [
            'id' => $composition->id,
            'key' => $composition->id,
            'uuid' => $composition->uuid,
            'client_id' => $composition->client_id,
        ];
    }

    public function includeBeneficiaries(Composition $composition)
    {
        if($composition->beneficiaries){
            return $this->collection($composition->beneficiaries, new BeneficiaryTransformer);
        }
    }
    public function includeFather(Composition $composition)
    {
        if($composition->father){
            return $this->item($composition->father, new BioParentTransformer);
        }
    }
    public function includeMother(Composition $composition)
    {
        if($composition->mother){
            return $this->item($composition->mother, new BioParentTransformer);
        }
    }
    public function includeClient(Composition $composition)
    {
        if($composition->client){
            return $this->item($composition->client, new ClientTransformer);
        }
    }
}
