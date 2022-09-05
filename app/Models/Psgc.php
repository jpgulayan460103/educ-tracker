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

    //update districs

    /* 
    
    update psgcs set `district` = "Lone" WHERE `province_psgc` = '112400000' and `district` = "";
    update psgcs set `district` = "Lone" WHERE `province_psgc` = '118600000' and `district` = "";

    */
}
