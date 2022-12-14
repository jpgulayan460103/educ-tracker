<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'last_name',
        'first_name',
        'middle_name',
        'ext_name',
        'full_name',
        'street_number',
        'psgc_id',
        'mobile_number',
        'birth_date',
        'age',
        'gender',
        'occupation',
        'monthly_salary',
        'relationship_beneficiary',
        'client_sector_id',
        'sector_id',
        'sector_other_id',
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
            $full_name_array = [
                $model->first_name,
                $model->middle_name,
                $model->last_name,
                $model->ext_name,
            ];
            $full_name = implode(" ",$full_name_array);
            $model->full_name = trim($full_name);
        });
        self::updating(function($model) {
            $full_name_array = [
                $model->first_name,
                $model->middle_name,
                $model->last_name,
                $model->ext_name,
            ];
            $full_name = implode(" ",$full_name_array);
            $model->full_name = trim($full_name);
        });
    }

    public function composition()
    {
        return $this->hasOne(Composition::class);
    }
    public function psgc()
    {
        return $this->belongsTo(Psgc::class);
    }

    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }
    public function client_sector()
    {
        return $this->belongsTo(ClientSector::class);
    }

    public function sector_other()
    {
        return $this->belongsTo(SectorOther::class);
    }
}
