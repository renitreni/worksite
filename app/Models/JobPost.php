<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\JobApplication;

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
    ];

    protected $casts = [
        'posted_at' => 'datetime',
        'apply_until' => 'date',
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
}
