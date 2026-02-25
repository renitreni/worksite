<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployerProfileView extends Model
{
    protected $table = 'employer_profile_views';

    protected $fillable = [
        'employer_profile_id',
        'user_id',
        'viewed_on',
    ];

    protected $casts = [
        'viewed_on' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function employerProfile()
    {
        return $this->belongsTo(EmployerProfile::class, 'employer_profile_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}