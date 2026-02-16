<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResumeAttachment extends Model
{
    protected $fillable = [
        'resume_id','category','file_path','original_name','mime','size'
    ];

    public function resume()
    {
        return $this->belongsTo(CandidateResume::class, 'resume_id');
    }
}
