<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    protected $fillable = [
        'job_post_id',
        'candidate_id',
        'full_name',
        'email',
        'phone',
        'cover_letter',
        'cover_letter_file_path',
        'status',
    ];

    public function jobPost()
    {
        return $this->belongsTo(\App\Models\JobPost::class, 'job_post_id');
    }

    public function candidateProfile()
    {
        return $this->belongsTo(\App\Models\CandidateProfile::class, 'candidate_id');
    }
}
