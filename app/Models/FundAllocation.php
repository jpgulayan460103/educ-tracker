<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FundAllocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'payout_id',
        'swad_office_id',
        'school_level_id',
        'allocated_amount',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function payout()
    {
        return $this->belongsTo(Payout::class);
    }
    public function swad_office()
    {
        return $this->belongsTo(SwadOffice::class);
    }
    public function school_level()
    {
        return $this->belongsTo(SchoolLevel::class);
    }
}
