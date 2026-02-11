<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployerProfile extends Model
{
    protected $fillable = [
        'user_id',
        'company_name',
        'company_email',
        'company_address',
        'company_contact',
        'representative_name',
        'position',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
