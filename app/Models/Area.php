<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $fillable = [
        'city_id',
        'name',
        'is_active',
        'sort_order',
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
