<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('employer.{id}', function ($user, $id) {
    return $user->id === (int) $id && $user->role === 'employer';
});