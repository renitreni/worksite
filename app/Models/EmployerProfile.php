<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployerProfile extends Model
{
    protected $fillable = [
        'company_name',
        'company_address',
        'company_contact',
        'representative_name',
        'position',
        'status',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
