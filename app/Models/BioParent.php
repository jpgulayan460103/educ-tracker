<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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


            $model->full_name = trim($full_name);
        });
    }

    public function composition()
    {
        return $this->belongsTo(Composition::class);
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
