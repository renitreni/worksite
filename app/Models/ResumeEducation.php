<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResumeEducation extends Model
{
    protected $table = 'resume_educations'; 

    protected $fillable = [
        'resume_id','degree','school','year','notes'
    ];

    public function resume()
    {
        return $this->belongsTo(CandidateResume::class, 'resume_id');
    }
}
