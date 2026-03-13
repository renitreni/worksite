<?php

namespace App\Services\Admin;

use App\Models\User;
use App\Models\JobPost;
use App\Models\JobApplication;
use App\Models\Payment;
use App\Models\EmployerSubscription;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardAnalyticsService
{
    public function analytics(string $range = '7d'): array
    {
        [$mode, $start, $end, $labels] = $this->rangeToWindow($range);

        return [
            'range' => $range,
            'kpis' => $this->kpis($start, $end),
            'series' => [
                'users' => $this->countSeriesUsers($mode, $start, $end, $labels),
                'jobs' => $this->countSeries($mode, $start, $end, $labels, JobPost::query(), 'created_at'),
                'applications' => $this->countSeries($mode, $start, $end, $labels, JobApplication::query(), 'created_at'),
                'revenue' => $this->sumRevenueSeries($mode, $start, $end, $labels),
            ],
            'donuts' => [
                'payments_status' => $this->paymentsStatusDonut($start, $end),
                'jobs_status' => $this->jobsStatusDonut(),
            ],
        ];
    }

    private function kpis(Carbon $start, Carbon $end): array
    {
        // Totals in selected range
        $jobsCount = JobPost::query()->whereBetween('created_at', [$start, $end])->count();
        $appsCount = JobApplication::query()->whereBetween('created_at', [$start, $end])->count();

        $appsPerJob = $jobsCount > 0 ? round($appsCount / $jobsCount, 2) : 0.0;

        // Hire rate (if status constant exists)
        $hireRate = 0.0;
        $hiredStatus = defined(JobApplication::class . '::STATUS_HIRED') ? JobApplication::STATUS_HIRED : null;

        if ($hiredStatus !== null) {
            $hired = JobApplication::query()
                ->whereBetween('created_at', [$start, $end])
                ->where('status', $hiredStatus)
                ->count();

            $hireRate = $appsCount > 0 ? round(($hired / $appsCount) * 100, 1) : 0.0;
        }

        // Expiring soon (next 7 days from now) — this KPI is intentionally NOT tied to range
        $expiringSoon7d = EmployerSubscription::query()
            ->whereNotNull('ends_at')
            ->whereBetween('ends_at', [now(), now()->copy()->addDays(7)])
            ->count();

        return [
            'apps_per_job' => $appsPerJob,
            'hire_rate' => $hireRate,
            'expiring_soon_7d' => (int)$expiringSoon7d,
        ];
    }

    private function rangeToWindow(string $range): array
    {
        $range = strtolower(trim($range));

        if (!in_array($range, ['7d', '30d', 'monthly'], true)) {
            $range = '7d';
        }

        if ($range === 'monthly') {
            $mode = 'monthly';
            $months = 6;

            $start = now()->copy()->subMonths($months - 1)->startOfMonth();
            $end   = now()->copy()->endOfMonth();

            $labels = [];
            for ($i = 0; $i < $months; $i++) {
                $labels[] = $start->copy()->addMonths($i)->format('Y-m');
            }

            return [$mode, $start, $end, $labels];
        }

        $days = $range === '30d' ? 30 : 7;
        $mode = 'daily';

        $start = now()->copy()->subDays($days - 1)->startOfDay();
        $end   = now()->copy()->endOfDay();

        $labels = [];
        for ($i = 0; $i < $days; $i++) {
            $labels[] = $start->copy()->addDays($i)->format('Y-m-d');
        }

        return [$mode, $start, $end, $labels];
    }

    private function countSeriesUsers(string $mode, Carbon $start, Carbon $end, array $labels): array
    {
        // match your dashboard totals (employer + candidate only)
        $q = User::query()
            ->whereIn('role', ['employer', 'candidate']);

        return $this->countSeries($mode, $start, $end, $labels, $q, 'created_at');
    }

    private function countSeries(string $mode, Carbon $start, Carbon $end, array $labels, Builder $query, string $dateCol): array
    {
        $groupExpr = $this->groupKey($mode, $dateCol);

        $rows = $query
            ->whereBetween($dateCol, [$start, $end])
            ->selectRaw("$groupExpr as k, COUNT(*) as v")
            ->groupBy('k')
            ->orderBy('k')
            ->pluck('v', 'k')
            ->toArray();

        $values = array_map(static fn ($k) => (int)($rows[$k] ?? 0), $labels);

        return ['labels' => $labels, 'values' => $values];
    }

    private function sumRevenueSeries(string $mode, Carbon $start, Carbon $end, array $labels): array
    {
        $groupExpr = $this->groupKey($mode, 'created_at');

        $completedStatus = defined(Payment::class . '::STATUS_COMPLETED') ? Payment::STATUS_COMPLETED : 'completed';

        $rows = Payment::query()
            ->where('status', $completedStatus)
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw("$groupExpr as k, COALESCE(SUM(amount),0) as v")
            ->groupBy('k')
            ->orderBy('k')
            ->pluck('v', 'k')
            ->toArray();

        $values = array_map(static fn ($k) => (float)($rows[$k] ?? 0), $labels);

        return ['labels' => $labels, 'values' => $values];
    }

    private function paymentsStatusDonut(Carbon $start, Carbon $end): array
    {
        // Do it in ONE query
        $completedStatus = defined(Payment::class . '::STATUS_COMPLETED') ? Payment::STATUS_COMPLETED : 'completed';
        $pendingStatus   = defined(Payment::class . '::STATUS_PENDING') ? Payment::STATUS_PENDING : 'pending';
        $failedStatus    = defined(Payment::class . '::STATUS_FAILED') ? Payment::STATUS_FAILED : null;

        $row = Payment::query()
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw(
                "SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as completed,
                 SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as pending" .
                ($failedStatus !== null ? ", SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as failed" : ""),
                $failedStatus !== null
                    ? [$completedStatus, $pendingStatus, $failedStatus]
                    : [$completedStatus, $pendingStatus]
            )
            ->first();

        $completed = (int)($row->completed ?? 0);
        $pending   = (int)($row->pending ?? 0);
        $failed    = (int)($row->failed ?? 0);

        return [
            'labels' => ['Completed', 'Pending', 'Failed'],
            'values' => [$completed, $pending, $failed],
        ];
    }

    private function jobsStatusDonut(): array
    {
        // One query, snapshot (not range-based)
        $row = JobPost::query()
            ->selectRaw("
                SUM(CASE WHEN status = 'open' AND is_held = 0 AND is_disabled = 0 THEN 1 ELSE 0 END) as active,
                SUM(CASE WHEN is_held = 1 THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN is_disabled = 1 THEN 1 ELSE 0 END) as disabled,
                SUM(CASE WHEN status = 'closed' THEN 1 ELSE 0 END) as closed
            ")
            ->first();

        return [
            'labels' => ['Active', 'Pending', 'Disabled', 'Closed'],
            'values' => [
                (int)($row->active ?? 0),
                (int)($row->pending ?? 0),
                (int)($row->disabled ?? 0),
                (int)($row->closed ?? 0),
            ],
        ];
    }

    /**
     * DB-driver friendly group key expression.
     * - MySQL/MariaDB: DATE_FORMAT
     * - PostgreSQL: to_char
     * - SQLite: strftime
     */
    private function groupKey(string $mode, string $dateCol): string
    {
        $driver = DB::getDriverName();
        $monthly = $mode === 'monthly';

        if ($driver === 'pgsql') {
            // Postgres: to_char(timestamp, 'YYYY-MM') / 'YYYY-MM-DD'
            return $monthly
                ? "to_char($dateCol, 'YYYY-MM')"
                : "to_char($dateCol, 'YYYY-MM-DD')";
        }

        if ($driver === 'sqlite') {
            // SQLite: strftime('%Y-%m', col) / '%Y-%m-%d'
            return $monthly
                ? "strftime('%Y-%m', $dateCol)"
                : "strftime('%Y-%m-%d', $dateCol)";
        }

        // Default: MySQL/MariaDB
        return $monthly
            ? "DATE_FORMAT($dateCol, '%Y-%m')"
            : "DATE_FORMAT($dateCol, '%Y-%m-%d')";
    }
}