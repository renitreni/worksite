<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\JobApplication;

class JobPost extends Model
{
    protected $fillable = [
        'employer_profile_id',
        'title',
        'description',
        'location',
        'salary',
        'job_type',
        'required_skills',
        'status',
    ];

    public function employerProfile()
    {
        return $this->belongsTo(EmployerProfile::class);
    }
    
    public function applications()
    {
        return $this->hasMany(JobApplication::class, 'job_post_id');
    }
}