<?php

namespace App\Transformers;

use App\Models\SwadOffice;
use League\Fractal\TransformerAbstract;

class SwadOfficeTransformer extends TransformerAbstract
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
    public function transform(SwadOffice $swadOffice)
    {
        return [
            'id' => $swadOffice->id,
            'key' => $swadOffice->id,
            'name' => $swadOffice->name,
        ];
    }
}
