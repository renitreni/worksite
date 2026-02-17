<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidateProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'photo_path',
        'address',
        'birth_date',
        'bio',
        'experience_years',
        'whatsapp',
        'facebook',
        'linkedin',
        'telegram',
        'highest_qualification',
        'current_salary',
        'contact_number',
        'contact_e164',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
