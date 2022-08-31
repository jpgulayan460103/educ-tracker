<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Beneficiary extends Model
{
    use HasFactory;

    protected $fillable = [
        'last_name',
        'first_name',
        'middle_name',
        'ext_name',
        'full_name',
        'school_level_id',
        'birth_date',
        'age',
        'gender',
        'composition_id',
        'status',
        'sector_id',
        'swad_office_id',
        'payout_id',
        'sector_others',
        'school_name',
        'amount_granted',
        'remarks',
        'control_number',
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
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
            $model->uuid = (string) Str::uuid();
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
        return $this->belongsTo(Composition::class);
    }

    public function school_level()
    {
        return $this->belongsTo(SchoolLevel::class);
    }

    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }

    public function payout()
    {
        return $this->belongsTo(Payout::class);
    }

    public function swad_office()
    {
        return $this->belongsTo(SwadOffice::class);
    }

    public function total_allocated($school_level_id, $payout_id)
    {
        # code...
    }
}
