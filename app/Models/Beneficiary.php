<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beneficiary extends Model
{
    use HasFactory;

    protected $fillable = [
        'last_name',
        'first_name',
        'middle_name',
        'ext_name',
        'full_name',
        'street_number',
        'school_level_id',
        'mobile_number',
        'birth_date',
        'age',
        'gender',
        'occupation',
        'monthly_salary',
        'composition_id',
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $full_name_array = [
                $model->last_name,
                $model->first_name,
                $model->middle_name,
                $model->ext_name,
            ];
            $full_name = implode(" ",$full_name_array);
            $model->full_name = trim($full_name);
        });
        self::updating(function($model) {
            $full_name_array = [
                $model->last_name,
                $model->first_name,
                $model->middle_name,
                $model->ext_name,
            ];
            $full_name = implode(" ",$full_name_array);
            $model->full_name = trim($full_name);
        });
    }
}
