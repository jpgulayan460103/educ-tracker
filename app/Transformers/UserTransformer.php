<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
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
        'swad_office'
    ];
    
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'id' => $user->id,
            'key' => $user->id,
            'full_name' => $user->full_name,
            'last_name' => $user->last_name,
            'first_name' => $user->first_name,
            'middle_name' => $user->middle_name,
            'ext_name' => $user->ext_name,
            'username' => $user->username,
            'user_role' => $user->user_role,
            'psgc_scope' => $user->psgc_scope,
            'is_active' => $user->is_active,
            'swad_office_id' => $user->swad_office_id,
        ];
    }
    public function includeSwadOffice(User $user)
    {
        if($user->swad_office){
            return $this->item($user->swad_office, new SwadOfficeTransformer);
        }
    }
}
