<?php

namespace App\Transformers;

use App\Models\SectorOther;
use League\Fractal\TransformerAbstract;

class SectorOtherTransformer extends TransformerAbstract
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
    public function transform(SectorOther $sectorOther)
    {
        return [
            'id' => $sectorOther->id,
            'key' => $sectorOther->id,
            'name' => $sectorOther->name,
        ];
    }
}
