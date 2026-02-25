<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\JobApplication;
use Illuminate\Database\Eloquent\Builder;

class JobPost extends Model
{
    protected $fillable = [
        'employer_profile_id',

        'title',
        'industry',
        'skills',

        'country',
        'city',
        'area',

        'min_experience_years',
        'education_level',

        'salary_min',
        'salary_max',
        'salary_currency',

        'gender',
        'age_min',
        'age_max',

        'posted_at',
        'apply_until',

        'job_description',
        'job_qualifications',
        'additional_information',

        'principal_employer',
        'dmw_registration_no',
        'principal_employer_address',

        'placement_fee',
        'placement_fee_currency',

        'status',
        'is_held',
        'held_at',
        'hold_reason',
        'held_by_user_id',

        'is_disabled',
        'disabled_at',
        'disabled_reason',
        'disabled_by_user_id',

        'admin_notes',
        'notes_updated_at',
    ];

    protected $casts = [
        'posted_at' => 'datetime',
        'apply_until' => 'date',
         'is_held' => 'boolean',
        'held_at' => 'datetime',

        'is_disabled' => 'boolean',
        'disabled_at' => 'datetime',

        'notes_updated_at' => 'datetime',

        'posted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];



    public function employerProfile()
    {
        return $this->belongsTo(EmployerProfile::class);
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class, 'job_post_id');
    }

    public function candidatesProfile()
    {
        return $this->hasManyThrough(CandidateProfile::class, JobApplication::class, 'job_post_id', 'id', 'id', 'candidate_profile_id');
    }
    

    

    public function saves()
    {
        return $this->hasMany(\App\Models\SavedJob::class, 'job_post_id');
    }

    public function reports()
    {
        return $this->hasMany(\App\Models\JobReport::class, 'job_post_id');
    }

    public function scopeNotDisabled(Builder $q): Builder
    {
        return $q->where('is_disabled', false);
    }

    public function scopeNotHeld(Builder $q): Builder
    {
        return $q->where('is_held', false);
    }

    public function scopeOpen(Builder $q): Builder
    {
        return $q->where('status', 'open');
    }

    public function scopeClosed(Builder $q): Builder
    {
        return $q->where('status', 'closed');
    }

    public function heldBy()
    {
        return $this->belongsTo(User::class, 'held_by_user_id');
    }

    public function disabledBy()
    {
        return $this->belongsTo(User::class, 'disabled_by_user_id');
    }
}
