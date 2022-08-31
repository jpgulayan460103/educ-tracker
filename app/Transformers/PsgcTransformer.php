<?php

namespace App\Transformers;

use App\Models\Psgc;
use League\Fractal\TransformerAbstract;

class PsgcTransformer extends TransformerAbstract
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
    public function transform(Psgc $psgc)
    {
        return [
            'id' => $psgc->id,
            'key' => $psgc->id,
            'region_name' => $psgc->region_name,
            'region_psgc' => $psgc->region_psgc,
            'province_name' => $psgc->province_name,
            'province_psgc' => $psgc->province_psgc,
            'city_name' => $psgc->city_name,
            'city_psgc' => $psgc->city_psgc,
            'brgy_name' => $psgc->brgy_name,
            'brgy_psgc' => $psgc->brgy_psgc,
            'district' => $psgc->district,
            'subdistrict' => $psgc->subdistrict,
            'swad_office_id' => $psgc->swad_office_id,
            'full_address' => $psgc->brgy_name.", ".$psgc->city_name.", ".$psgc->province_name
        ];
    }
}
