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
        'remarks',
        'client_sector_id',
        'sector_id',
        'sector_other_id',
        'swad_office_id',
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
            $full_name = trim(implode(" ",$full_name_array));
            $full_name = trim(preg_replace("/\s+/", " ", $full_name));
            $model->full_name = $full_name;

            $full_name_mi_array = [
                $model->first_name,
                ($model->middle_name ? substr($model->middle_name, 0, 1) : ""),
                $model->last_name,
                $model->ext_name,
            ];
            $full_name_mi = trim(implode(" ",$full_name_mi_array));
            $full_name_mi = trim(preg_replace("/\s+/", " ", $full_name_mi));
            $model->full_name_mi = $full_name_mi;

        });
        self::updating(function($model) {
            $full_name_array = [
                $model->first_name,
                $model->middle_name,
                $model->last_name,
                $model->ext_name,
            ];
            $full_name = trim(implode(" ",$full_name_array));
            $full_name = trim(preg_replace("/\s+/", " ", $full_name));
            $model->full_name = $full_name;

            $full_name_mi_array = [
                $model->first_name,
                ($model->middle_name ? substr($model->middle_name, 0, 1) : ""),
                $model->last_name,
                $model->ext_name,
            ];
            $full_name_mi = trim(implode(" ",$full_name_mi_array));
            $full_name_mi = trim(preg_replace("/\s+/", " ", $full_name_mi));
            $model->full_name_mi = $full_name_mi;
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
    public function swad_office()
    {
        return $this->belongsTo(SwadOffice::class);
    }

    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = strtoupper($value);
    }
    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = strtoupper($value);
    }
    public function setMiddleNameAttribute($value)
    {
        $this->attributes['middle_name'] = strtoupper($value);
    }
    public function setExtNameAttribute($value)
    {
        $this->attributes['ext_name'] = strtoupper($value);
    }
}
