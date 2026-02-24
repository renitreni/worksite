<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployerIndustry extends Model
{
    protected $fillable = [
        'employer_profile_id',
        'industry_id',
    ];

    public function employerProfile()
    {
        return $this->belongsTo(EmployerProfile::class);
    }

    public function industry()
    {
        return $this->belongsTo(Industry::class);
    }
}
