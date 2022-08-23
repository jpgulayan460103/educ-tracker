<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Composition extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
        self::updating(function($model) {

        });
    }

    public function beneficiaries()
    {
        return $this->hasMany(Beneficiary::class);
    }

    public function father()
    {
        return $this->hasOne(BioParent::class)->where('relationship_beneficiary', 'father');
    }
    public function mother()
    {
        return $this->hasOne(BioParent::class)->where('relationship_beneficiary', 'mother');
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
