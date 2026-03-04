@extends('adminpage.layout')
@section('title','Dashboard')
@section('page_title','Dashboard Overview')

@section('content')
<div class="space-y-6">

    {{-- ROW 1: KPI Cards + Useful Extras --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-8 gap-4">

        {{-- Total Users -> Users page --}}
        <a wire:navigate href="{{ route('admin.users.index') }}"
           class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 block hover:shadow-md hover:-translate-y-0.5 transition">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs text-gray-500">Total Users</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" data-metric="users.total">
                        {{ $dashboard['users']['total'] ?? 0 }}
                    </p>
                    <p class="text-xs text-gray-500 mt-2">
                        Employers:
                        <span class="font-medium text-gray-700" data-metric="users.employers">{{ $dashboard['users']['employers'] ?? 0 }}</span>
                        • Employees:
                        <span class="font-medium text-gray-700" data-metric="users.employees">{{ $dashboard['users']['employees'] ?? 0 }}</span>
                    </p>
                </div>

                <span class="inline-flex items-center justify-center w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </span>
            </div>
        </a>

        {{-- NEW: New Users (7d) -> Users page --}}
        <a wire:navigate href="{{ route('admin.users.index') }}"
           class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 block hover:shadow-md hover:-translate-y-0.5 transition">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs text-gray-500">New Users (7 days)</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" data-metric="users.new_7d">
                        {{ $dashboard['users']['new_7d'] ?? 0 }}
                    </p>
                    <p class="text-xs text-gray-500 mt-2">Recent registrations</p>
                </div>

                <span class="inline-flex items-center justify-center w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 5v14"/>
                        <path d="M5 12h14"/>
                    </svg>
                </span>
            </div>
        </a>

        {{-- Jobs (Active) -> Job postings --}}
        <a wire:navigate href="{{ route('admin.job-posts.index') }}"
           class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 block hover:shadow-md hover:-translate-y-0.5 transition">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs text-gray-500">Jobs (Active)</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" data-metric="jobs.active">
                        {{ $dashboard['jobs']['active'] ?? 0 }}
                    </p>
                    <p class="text-xs text-gray-500 mt-2">
                        Closed:
                        <span class="font-medium text-gray-700" data-metric="jobs.closed">{{ $dashboard['jobs']['closed'] ?? 0 }}</span>
                    </p>
                </div>

                <span class="inline-flex items-center justify-center w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
                        <path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/>
                        <path d="M2 13h20"/>
                    </svg>
                </span>
            </div>
        </a>

        {{-- Jobs (Pending) -> Job postings --}}
        <a wire:navigate href="{{ route('admin.job-posts.index') }}"
           class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 block hover:shadow-md hover:-translate-y-0.5 transition">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs text-gray-500">Jobs (Pending)</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" data-metric="jobs.pending">
                        {{ $dashboard['jobs']['pending'] ?? 0 }}
                    </p>
                    <p class="text-xs text-gray-500 mt-2">Held for review</p>
                </div>

                <span class="inline-flex items-center justify-center w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M12 6v6l4 2"/>
                    </svg>
                </span>
            </div>
        </a>

        {{-- Jobs (Closed) -> Job postings --}}
        <a wire:navigate href="{{ route('admin.job-posts.index') }}"
           class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 block hover:shadow-md hover:-translate-y-0.5 transition">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs text-gray-500">Jobs (Closed)</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" data-metric="jobs.closed">
                        {{ $dashboard['jobs']['closed'] ?? 0 }}
                    </p>
                    <p class="text-xs text-gray-500 mt-2">Status = closed</p>
                </div>

                <span class="inline-flex items-center justify-center w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 11l3 3L22 4"/>
                        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                    </svg>
                </span>
            </div>
        </a>

        {{-- NEW: New Jobs (7d) -> Job postings --}}
        <a wire:navigate href="{{ route('admin.job-posts.index') }}"
           class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 block hover:shadow-md hover:-translate-y-0.5 transition">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs text-gray-500">New Jobs (7 days)</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" data-metric="jobs.new_7d">
                        {{ $dashboard['jobs']['new_7d'] ?? 0 }}
                    </p>
                    <p class="text-xs text-gray-500 mt-2">Recently posted</p>
                </div>

                <span class="inline-flex items-center justify-center w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                        <path d="M12 22V12"/>
                        <path d="M12 12L3.5 7.5"/>
                        <path d="M12 12l8.5-4.5"/>
                    </svg>
                </span>
            </div>
        </a>

        {{-- Active Employers -> Subscriptions --}}
        <a wire:navigate href="{{ route('admin.subscriptions.index') }}"
           class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 block hover:shadow-md hover:-translate-y-0.5 transition">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs text-gray-500">Active Employers</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">
                        <span data-metric="employers.paid_active">{{ $dashboard['employers']['paid_active'] ?? 0 }}</span>
                        <span class="text-sm font-medium text-gray-500">paid</span>
                    </p>
                    <p class="text-xs text-gray-500 mt-2">
                        Free:
                        <span class="font-medium text-gray-700" data-metric="employers.free_active">{{ $dashboard['employers']['free_active'] ?? 0 }}</span>
                    </p>
                </div>

                <span class="inline-flex items-center justify-center w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 21V7a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v14"/>
                        <path d="M13 21V11a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v10"/>
                    </svg>
                </span>
            </div>
        </a>

        {{-- Revenue + Pending + Expired -> Payments --}}
        <a wire:navigate href="{{ route('admin.subscriptions.payments.index') }}"
           class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 block hover:shadow-md hover:-translate-y-0.5 transition">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs text-gray-500">Revenue (Subscriptions)</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" data-metric="payments.revenue_completed">
                        {{ number_format((float)($dashboard['payments']['revenue_completed'] ?? 0), 2) }}
                    </p>
                    <p class="text-xs text-gray-500 mt-2">
                        Pending:
                        <span class="font-medium text-gray-700" data-metric="payments.pending_payments">{{ $dashboard['payments']['pending_payments'] ?? 0 }}</span>
                        • Expired:
                        <span class="font-medium text-gray-700" data-metric="payments.expired_subscriptions">{{ $dashboard['payments']['expired_subscriptions'] ?? 0 }}</span>
                    </p>
                </div>

                <span class="inline-flex items-center justify-center w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 1v22"/>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7H14a3.5 3.5 0 0 1 0 7H6"/>
                    </svg>
                </span>
            </div>
        </a>

    </div>

    {{-- ROW 2: Quick Actions (very useful for admins) --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-3">
            <div>
                <h3 class="text-sm font-semibold text-gray-800">Quick Actions</h3>
                <p class="text-xs text-gray-500">Jump to common admin tasks</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <a wire:navigate href="{{ route('admin.job-posts.index') }}"
               class="rounded-xl border border-gray-200 bg-white hover:bg-gray-50 px-4 py-3 text-sm font-semibold text-gray-800">
                Review Job Posts
                <div class="text-xs text-gray-500 mt-1">Moderate and manage postings</div>
            </a>

            <a wire:navigate href="{{ route('admin.users.index') }}"
               class="rounded-xl border border-gray-200 bg-white hover:bg-gray-50 px-4 py-3 text-sm font-semibold text-gray-800">
                Manage Users
                <div class="text-xs text-gray-500 mt-1">Employers & employees</div>
            </a>

            <a wire:navigate href="{{ route('admin.subscriptions.payments.index') }}"
               class="rounded-xl border border-gray-200 bg-white hover:bg-gray-50 px-4 py-3 text-sm font-semibold text-gray-800">
                Review Payments
                <div class="text-xs text-gray-500 mt-1">Pending & completed</div>
            </a>

            <a wire:navigate href="{{ route('admin.subscriptions.expired') }}"
               class="rounded-xl border border-gray-200 bg-white hover:bg-gray-50 px-4 py-3 text-sm font-semibold text-gray-800">
                Expired Subscriptions
                <div class="text-xs text-gray-500 mt-1">Renewals & reminders</div>
            </a>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const metricsUrl = "{{ route('admin.dashboard.metrics') }}";
  const intervalMs = 25000;

  const fmtNumber = (n) => new Intl.NumberFormat(undefined).format(Number(n ?? 0));
  const fmtMoney  = (n) => new Intl.NumberFormat(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })
                          .format(Number(n ?? 0));

  let refreshing = false;

  async function refreshMetrics() {
    if (refreshing) return;
    refreshing = true;

    try {
      const res = await fetch(metricsUrl, { headers: { 'Accept': 'application/json' } });
      if (!res.ok) return;

      const data = await res.json();

      const setMetric = (key, value, formatter = (v) => v) => {
        const el = document.querySelector(`[data-metric="${key}"]`);
        if (el) el.textContent = formatter(value);
      };

      setMetric('users.total', data.users?.total, fmtNumber);
      setMetric('users.employers', data.users?.employers, fmtNumber);
      setMetric('users.employees', data.users?.employees, fmtNumber);
      setMetric('users.new_7d', data.users?.new_7d, fmtNumber);

      setMetric('jobs.active', data.jobs?.active, fmtNumber);
      setMetric('jobs.pending', data.jobs?.pending, fmtNumber);
      setMetric('jobs.closed', data.jobs?.closed, fmtNumber);
      setMetric('jobs.new_7d', data.jobs?.new_7d, fmtNumber);

      setMetric('employers.paid_active', data.employers?.paid_active, fmtNumber);
      setMetric('employers.free_active', data.employers?.free_active, fmtNumber);

      setMetric('payments.revenue_completed', data.payments?.revenue_completed, fmtMoney);
      setMetric('payments.pending_payments', data.payments?.pending_payments, fmtNumber);
      setMetric('payments.expired_subscriptions', data.payments?.expired_subscriptions, fmtNumber);

    } catch (e) {
      // silent fail
    } finally {
      refreshing = false;
    }
  }

  setInterval(refreshMetrics, intervalMs);
});
</script>
@endsection