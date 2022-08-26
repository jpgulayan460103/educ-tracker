<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Psgc extends Model
{
    use HasFactory;

    public function swad_office()
    {
        return $this->belongsTo(SwadOffice::class);
    }
}
