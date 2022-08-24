<?php

namespace App\Transformers;

use App\Models\Sector;
use League\Fractal\TransformerAbstract;

class SectorTransformer extends TransformerAbstract
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
    public function transform(Sector $sector)
    {
        return [
            'id' => $sector->id,
            'key' => $sector->id,
            'name' => $sector->name,
        ];
    }
}
