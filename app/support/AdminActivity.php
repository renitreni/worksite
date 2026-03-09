<?php

namespace App\Support;

use App\Models\AdminActivityLog;
use Illuminate\Support\Facades\Auth;

class AdminActivity
{
    public static function log($action, $target = null, $meta = [])
    {
        $admin = Auth::guard('admin')->user();

        if (!$admin) {
            return;
        }

        AdminActivityLog::create([
            'admin_id' => $admin->id,
            'action' => $action,
            'target_type' => $target ? get_class($target) : null,
            'target_id' => $target?->id,
            'meta' => $meta,
        ]);
    }
}