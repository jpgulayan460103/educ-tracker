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
        'id_number',
        'last_name',
        'first_name',
        'middle_name',
        'ext_name',
        'username',
        'password',
        'user_role',
        'psgc_scope',
        'is_active',
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
            $model->is_active = 0;
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
}
