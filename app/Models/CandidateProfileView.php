<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CandidateProfileView extends Model
{
    protected $fillable = [
        'employer_profile_id',
        'candidate_profile_id',
        'job_application_id',   // ✅ add
        'view_date',
        'viewed_at',
    ];

    protected $casts = [
        'viewed_at' => 'datetime',
        'view_date' => 'date',
    ];

    public function jobApplication(): BelongsTo
    {
        return $this->belongsTo(\App\Models\JobApplication::class);
    }

    public function employerProfile(): BelongsTo
    {
        return $this->belongsTo(EmployerProfile::class);
    }

    public function candidateProfile(): BelongsTo
    {
        return $this->belongsTo(CandidateProfile::class);
    }
}