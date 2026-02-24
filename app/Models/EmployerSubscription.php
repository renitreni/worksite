<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployerSubscription extends Model
{
    protected $fillable = [
        'employer_profile_id',
        'plan',
        'subscription_status',
        'starts_at',
        'ends_at',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function employerProfile()
    {
        return $this->belongsTo(EmployerProfile::class);
    }
}