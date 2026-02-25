<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployerVerification extends Model
{
    protected $fillable = ['employer_profile_id', 'status'];

    public function employerProfile()
    {
        return $this->belongsTo(EmployerProfile::class);
    }
}
