<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BioParent extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'last_name',
        'first_name',
        'middle_name',
        'ext_name',
        'birth_date',
        'relationship_beneficiary',
        'composition_id',
    ];

    public function composition()
    {
        return $this->belongsTo(Composition::class);
    }
}
