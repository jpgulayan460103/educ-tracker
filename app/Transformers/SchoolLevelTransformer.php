<?php

namespace App\Transformers;

use App\Models\SchoolLevel;
use League\Fractal\TransformerAbstract;

class SchoolLevelTransformer extends TransformerAbstract
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
    public function transform(SchoolLevel $schoolLevel)
    {
        return [
            'id' => $schoolLevel->id,
            'key' => $schoolLevel->id,
            'name' => $schoolLevel->name,
            'amount' => $schoolLevel->amount,
        ];
    }
}
