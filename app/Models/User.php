<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'last_name',
        'first_name',
        'middle_name',
        'ext_name',
        'username',
        'password',
        'user_role',
        'is_active',
        'swad_office_id',
        'office_id',
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
            $model->is_active = 1;
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

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function swad_office()
    {
        return $this->belongsTo(SwadOffice::class);
    }
    public function office()
    {
        return $this->belongsTo(Office::class);
    }
}
