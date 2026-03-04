<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $fillable = ['job_application_id', 'sender_id', 'message'];

    public function sender()
    {
        return $this->belongsTo(\App\Models\User::class, 'sender_id');
    }

    public function application()
    {
        return $this->belongsTo(\App\Models\JobApplication::class, 'job_application_id');
    }
}