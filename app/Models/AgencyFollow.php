<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgencyFollow extends Model
{
    protected $fillable = [
        'user_id',
        'employer_profile_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function employerProfile()
    {
        return $this->belongsTo(EmployerProfile::class);
    }
}
