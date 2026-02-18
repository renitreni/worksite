<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = [
        'country_id',
        'name',
        'is_active',
        'sort_order',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function areas()
    {
        return $this->hasMany(Area::class);
    }
}
