<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        'name',
        'code',
        'image',
        'is_active',
        'sort_order',
    ];

    public function cities()
    {
        return $this->hasMany(City::class);
    }
}

