<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminManagementRequest extends Model
{
    protected $fillable = [
        'employer_profile_id',
        'message',
        'status',
    ];

    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_DECLINED = 'declined';

    public function employer()
    {
        return $this->belongsTo(EmployerProfile::class, 'employer_profile_id');
    }
}