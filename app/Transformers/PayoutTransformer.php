<?php

namespace App\Transformers;

use App\Models\Payout;
use League\Fractal\TransformerAbstract;

class PayoutTransformer extends TransformerAbstract
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
    public function transform(Payout $payout)
    {
        return [
            'id' => $payout->id,
            'key' => $payout->id,
            'payout_date' => $payout->payout_date,
        ];
    }
}
