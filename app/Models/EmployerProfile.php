<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployerProfile extends Model
{
    protected $fillable = [
        'user_id',
        'industry_id',
        'company_name',
        'company_address',
        'company_contact',
        'company_website',
        'description',
        'industries',
        'logo_path',
        'cover_path',
        'total_profile_views',
        'representative_name',
        'position',
        'status', // pending/approved/rejected
        'rejection_reason',
        'rejected_at',
        'approved_at',
    ];

    protected $casts = [
        'industries' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jobPosts()
    {
        return $this->hasMany(JobPost::class);
    }

    public function industry()
    {
        return $this->belongsTo(\App\Models\Industry::class);
    }
}
