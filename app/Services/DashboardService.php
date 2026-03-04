<?php

namespace App\Services;

use App\Models\User;
use App\Models\EmployerProfile;
use App\Models\JobPost;
use App\Models\Payment;
use App\Models\EmployerSubscription;

class DashboardService
{
    public function overview(): array
    {
        // Users (scoped to employer + candidate = "employees")
        $userRoles = ['employer', 'candidate'];

        $totalEmployers  = User::where('role', 'employer')->count();
        $totalEmployees  = User::where('role', 'candidate')->count();

        $usersNew7d = User::whereIn('role', $userRoles)
            ->where('created_at', '>=', now()->subDays(7))
            ->count();

        // Jobs
        $jobsActive = JobPost::where('status', 'open')
            ->where('is_held', false)
            ->where('is_disabled', false)
            ->count();

        $jobsPending = JobPost::where('is_held', true)->count();
        $jobsClosed  = JobPost::where('status', 'closed')->count();

        $jobsNew7d = JobPost::where('created_at', '>=', now()->subDays(7))->count();

        // Active employers (paid vs free)
        $paidEmployers = EmployerProfile::whereHas('activeSubscription')->count();
        $freeEmployers = EmployerProfile::whereDoesntHave('activeSubscription')->count();

        // Revenue + payments
        $revenue = Payment::where('status', Payment::STATUS_COMPLETED)->sum('amount');
        $pendingPaymentsCount = Payment::where('status', Payment::STATUS_PENDING)->count();

        // Expired subscriptions
        $expiredSubs = EmployerSubscription::whereNotNull('ends_at')
            ->where('ends_at', '<', now())
            ->count();

        return [
            'users' => [
                'total' => $totalEmployers + $totalEmployees,
                'employers' => $totalEmployers,
                'employees' => $totalEmployees,
                'new_7d' => $usersNew7d,
            ],
            'jobs' => [
                'active' => $jobsActive,
                'pending' => $jobsPending,
                'closed' => $jobsClosed,
                'new_7d' => $jobsNew7d,
            ],
            'employers' => [
                'paid_active' => $paidEmployers,
                'free_active' => $freeEmployers,
            ],
            'payments' => [
                'revenue_completed' => (float) $revenue,
                'pending_payments' => $pendingPaymentsCount,
                'expired_subscriptions' => $expiredSubs,
            ],
        ];
    }
}