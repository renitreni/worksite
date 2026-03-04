<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\CandidateProfile;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    use Notifiable;

    /**
     * Spatie permissions are guard-specific.
     * Since your admin routes use auth:admin, keep this as 'admin'.
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'name',
        'email',
        'phone',
        'password',
        'role',
        'account_status', // active | disabled | hold
        'archived_at',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'archived_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function candidateProfile()
    {
        return $this->hasOne(\App\Models\CandidateProfile::class);
    }

    public function employerProfile()
    {
        return $this->hasOne(\App\Models\EmployerProfile::class);
    }

    public function savedJobs()
    {
        return $this->hasMany(\App\Models\SavedJob::class);
    }

    public function savedJobPosts()
    {
        return $this->belongsToMany(\App\Models\JobPost::class, 'saved_jobs', 'user_id', 'job_post_id')
            ->withTimestamps();
    }

    public function jobReports()
    {
        return $this->hasMany(\App\Models\JobReport::class);
    }

    public function candidateResume()
    {
        return $this->hasOne(\App\Models\CandidateResume::class, 'user_id', 'id');
    }
}