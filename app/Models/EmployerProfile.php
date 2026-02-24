<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployerProfile extends Model
{
    protected $table = 'employer_profiles';
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
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'rejected_at' => 'datetime',
        'approved_at' => 'datetime',
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
    public function isExpired(): bool
    {
        return $this->ends_at && now()->greaterThan($this->ends_at);
    }

    public function effectivePlan(): string
    {
        // If not active or expired → basic/free
        if ($this->subscription_status !== 'active') {
            return 'basic';
        }

        if ($this->isExpired()) {
            return 'basic';
        }

        return $this->plan ?: 'basic';
    }

    public function can(string $feature): bool
    {
        $plan = $this->effectivePlan();

        $matrix = [
            'basic' => [
                'post_job' => false,
                'view_candidate_full' => false,
                'download_cv' => false,
                'message_candidate' => false,
            ],
            'standard' => [
                'post_job' => true,
                'view_candidate_full' => false,
                'download_cv' => false,
                'message_candidate' => false,
            ],
            'gold' => [
                'post_job' => true,
                'view_candidate_full' => true,
                'download_cv' => false,
                'message_candidate' => false,
            ],
            'platinum' => [
                'post_job' => true,
                'view_candidate_full' => true,
                'download_cv' => true,
                'message_candidate' => true,
            ],
        ];

        return (bool) data_get($matrix, "{$plan}.{$feature}", false);
    }
}
