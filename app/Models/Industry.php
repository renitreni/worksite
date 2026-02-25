<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Industry extends Model
{
    protected $fillable = ['name', 'image', 'is_active', 'sort_order'];

    public function employerProfiles()
    {
        return $this->belongsToMany(EmployerProfile::class, 'employer_industries')
            ->withTimestamps();
    }

    public function skills()
    {
        return $this->hasMany(Skill::class);
    }
}
