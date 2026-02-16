<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CandidateResume extends Model
{
    protected $fillable = [
        'user_id',
        'resume_path','resume_original_name','resume_mime','resume_size',
    ];

    public function experiences()
    {
        return $this->hasMany(ResumeExperience::class, 'resume_id')->latest();
    }

    public function educations()
    {
        return $this->hasMany(ResumeEducation::class, 'resume_id')->latest();
    }

    public function attachments()
    {
        return $this->hasMany(ResumeAttachment::class, 'resume_id')->latest();
    }
}
