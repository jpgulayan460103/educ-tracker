<?php

namespace App\Transformers;

use App\Models\ClientSector;
use League\Fractal\TransformerAbstract;

class ClientSectorTransformer extends TransformerAbstract
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
    public function transform(ClientSector $clientSector)
    {
        return [
            'id' => $clientSector->id,
            'key' => $clientSector->id,
            'name' => $clientSector->id,
        ];
    }
}
