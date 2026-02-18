<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocationSuggestion extends Model
{
    protected $fillable = [
        'country',
        'city',
        'area',
        'count',
        'status',
    ];
}
