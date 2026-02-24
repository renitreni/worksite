<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployerProfile extends Model
{

    protected $fillable = [
        'user_id',
        'company_name',
        'company_address',
        'company_contact',
        'company_website',
        'description',
        'logo_path',
        'cover_path',
        'total_profile_views',
        'representative_name',
        'position',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function verification()
    {
        return $this->hasOne(EmployerVerification::class);
    }

    public function subscription()
    {
        return $this->hasOne(EmployerSubscription::class);
    }

    public function industries()
    {
        return $this->belongsToMany(Industry::class, 'employer_industries')->withTimestamps();
    }

    public function jobPosts()
    {
        return $this->hasMany(JobPost::class);
    }

    /**
     * Subscription helpers (reads from employer_subscriptions table)
     */
    public function isExpired(): bool
    {
        $sub = $this->subscription;

        if (!$sub || !$sub->ends_at) {
            return false;
        }

        return now()->greaterThan($sub->ends_at);
    }

    public function effectivePlan(): string
    {
        $sub = $this->subscription;

        // No subscription row yet → basic
        if (!$sub) {
            return 'basic';
        }

        // Not active → basic
        if ($sub->subscription_status !== 'active') {
            return 'basic';
        }

        // Expired → basic
        if ($this->isExpired()) {
            return 'basic';
        }

        return $sub->plan ?: 'basic';
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
