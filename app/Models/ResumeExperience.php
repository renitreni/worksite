<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResumeExperience extends Model
{
    protected $fillable = [
        'resume_id','role','company','start','end','description'
    ];

    public function resume()
    {
        return $this->belongsTo(CandidateResume::class, 'resume_id');
    }
}
