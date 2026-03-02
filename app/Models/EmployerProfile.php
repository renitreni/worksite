<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\FeatureAccess;

class EmployerProfile extends Model
{
    use FeatureAccess;

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

    public function subscriptions()
    {
        return $this->hasMany(\App\Models\EmployerSubscription::class, 'employer_profile_id');
    }

    public function subscription() // keep this name if you already use it
    {
        return $this->hasOne(\App\Models\EmployerSubscription::class, 'employer_profile_id')
            ->latestOfMany('id'); // ✅ always latest row
    }
    public function industries()
    {
        return $this->belongsToMany(Industry::class, 'employer_industries')->withTimestamps();
    }

    public function jobPosts()
    {
        return $this->hasMany(JobPost::class);
    }

    public function activeSubscription()
    {
        return $this->hasOne(\App\Models\EmployerSubscription::class)
            ->where('subscription_status', \App\Models\EmployerSubscription::STATUS_ACTIVE)
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            })
            ->latestOfMany();
    }

    public function canFeature(string $key): bool|string|null
    {
        $subscription = $this->activeSubscription()->with('plan.featureValues.definition')->first();

        if (!$subscription) return false;

        $features = $subscription->activeFeatures();

        return $features->get($key, false);
    }
}
