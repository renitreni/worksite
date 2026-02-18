<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    protected $fillable = [
        'job_post_id', // foreign key to JobPost
        'candidate_id', // foreign key to candidate/user
        'cover_letter',
        'status', // e.g., applied, shortlisted, rejected
    ];

    public function jobPost()
    {
        return $this->belongsTo(JobPost::class);
    }

    public function candidate()
    {
        return $this->belongsTo(User::class, 'candidate_id');
    }
}