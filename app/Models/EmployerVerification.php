<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployerVerification extends Model
{
    protected $fillable = [
        'employer_profile_id',
        'status',
        'suspended_reason',
        'rejection_reason',
        'rejected_at',
        'approved_at',
    ];

    protected $casts = [
        'rejected_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function employerProfile()
    {
        return $this->belongsTo(EmployerProfile::class);
    }
}
