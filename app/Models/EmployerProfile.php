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

    public function activeSubscription()
    {
        return $this->hasOne(\App\Models\EmployerSubscription::class)
            ->where('subscription_status', \App\Models\EmployerSubscription::STATUS_ACTIVE)
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            })
            ->latestOfMany();
    }
}
