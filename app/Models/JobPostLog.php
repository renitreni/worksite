<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobPostLog extends Model
{
    protected $fillable = [
        'job_post_id',
        'admin_id',
        'action',
        'description'
    ];

    public function admin()
    {
        return $this->belongsTo(User::class,'admin_id');
    }

    public function jobPost()
    {
        return $this->belongsTo(JobPost::class);
    }
}