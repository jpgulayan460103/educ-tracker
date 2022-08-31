<?php

namespace App\Transformers;

use App\Models\Client;
use League\Fractal\TransformerAbstract;

class ClientTransformer extends TransformerAbstract
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
        'psgc',
        'sector',
        'sector_other',
        'client_sector',
    ];
    
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Client $client)
    {
        $data = [
            'id' => $client->id,
            'key' => $client->id,
            'last_name' => $client->last_name,
            'first_name' => $client->first_name,
            'middle_name' => $client->middle_name,
            'ext_name' => $client->ext_name,
            'full_name' => $client->full_name,
            'street_number' => $client->street_number,
            'psgc_id' => $client->psgc_id,
            'mobile_number' => $client->mobile_number,
            'birth_date' => $client->birth_date,
            'age' => $client->age,
            'gender' => $client->gender,
            'occupation' => $client->occupation,
            'monthly_salary' => $client->monthly_salary,
            'relationship_beneficiary' => $client->relationship_beneficiary,
            'sector_id' => $client->sector_id,
            'sector_other_id' => $client->sector_other_id,
            'client_sector_id' => $client->client_sector_id,
        ];

        if($data['birth_date'] == null){
            unset($data['birth_date']);
        }
        return $data;
    }
    public function includePsgc(Client $client)
    {
        if($client->psgc){
            return $this->item($client->psgc, new PsgcTransformer);
        }
    }
    public function includeSector(Client $client)
    {
        if($client->sector){
            return $this->item($client->sector, new SectorTransformer);
        }
    }
    public function includeSectorOther(Client $client)
    {
        if($client->sector_other){
            return $this->item($client->sector_other, new SectorOtherTransformer);
        }
    }
    public function includeClientSector(Client $client)
    {
        if($client->client_sector){
            return $this->item($client->client_sector, new ClientSectorTransformer);
        }
    }
}
