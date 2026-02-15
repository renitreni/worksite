<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CandidateProfile extends Model
{
    protected $fillable = [
        'user_id',
        'contact_number',
        'contact_e164',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
