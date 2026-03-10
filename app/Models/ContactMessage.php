<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = [
        'role',
        'name',
        'email',
        'phone',
        'message',
        'is_read',
        'is_starred'
    ];
}