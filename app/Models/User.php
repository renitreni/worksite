<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\CandidateProfile;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
    'first_name',
    'last_name',
    'name',
    'email',
    'phone',
    'password',
    'role',
    'is_active',
];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function candidateProfile()
    {
        return $this->hasOne(CandidateProfile::class);
    }

    public function employerProfile()
    {
        return $this->hasOne(\App\Models\EmployerProfile::class);
    }
}
