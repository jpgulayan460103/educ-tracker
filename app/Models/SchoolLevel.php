<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'amount',
    ];

    public function beneficiaries()
    {
        return $this->hasMany(Beneficiary::class);
    }
}
