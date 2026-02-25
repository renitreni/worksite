<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $fillable = [
        'industry_id',
        'name',
        'is_active',
        'sort_order',
    ];

    public function industry()
    {
        return $this->belongsTo(Industry::class);
    }
}
