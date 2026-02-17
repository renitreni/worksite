<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Industry extends Model
{
    protected $fillable = ['name', 'is_active', 'sort_order'];

    public function employerProfiles()
    {
        return $this->hasMany(\App\Models\EmployerProfile::class);
    }
}
