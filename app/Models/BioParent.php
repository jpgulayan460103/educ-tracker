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
            $full_name = implode(" ",$full_name_array);
            $model->full_name = trim($full_name);
            $model->uuid = (string) Str::uuid();
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
        return $this->belongsTo(Composition::class);
    }
}
