<?php

namespace App\Services\Employer;

use App\Models\AdminManagementRequest;

class AdminManagementRequestService
{
    public function createRequest($profile, $message = null)
    {
        // prevent duplicate pending request
        $existing = AdminManagementRequest::where('employer_profile_id', $profile->id)
            ->where('status', AdminManagementRequest::STATUS_PENDING)
            ->first();

        if ($existing) {
            return $existing;
        }

        return AdminManagementRequest::create([
            'employer_profile_id' => $profile->id,
            'message' => $message,
            'status' => AdminManagementRequest::STATUS_PENDING,
        ]);
    }
}