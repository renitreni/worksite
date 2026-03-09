@extends('adminpage.layout')
@section('title', 'Dashboard')
@section('page_title', 'Dashboard Overview')

@section('content')
    <div class="space-y-6">

        {{-- KPI CARDS --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

            {{-- Total Users --}}
            <a wire:navigate href="{{ route('admin.users.index') }}"
                class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-200">

                <div class="flex items-start justify-between">

                    <div class="space-y-2">
                        <p class="text-sm text-gray-500 font-medium">Total Users</p>

                        <p class="text-3xl font-bold text-gray-900" data-metric="users.total">
                            {{ $dashboard['users']['total'] ?? 0 }}
                        </p>

                        <p class="text-xs text-gray-500">
                            Employers:
                            <span class="font-semibold text-gray-700" data-metric="users.employers">
                                {{ $dashboard['users']['employers'] ?? 0 }}
                            </span>
                            •
                            Employees:
                            <span class="font-semibold text-gray-700" data-metric="users.employees">
                                {{ $dashboard['users']['employees'] ?? 0 }}
                            </span>
                        </p>
                    </div>

                    <span class="flex items-center justify-center w-12 h-12 rounded-xl bg-emerald-50 text-emerald-700">

                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">

                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                            <path d="M16 3.13a4 4 0 0 1 0 7.75" />

                        </svg>

                    </span>
                </div>
            </a>


            {{-- New Users --}}
            <a wire:navigate href="{{ route('admin.users.index') }}"
                class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-200">

                <div class="flex items-start justify-between">

                    <div class="space-y-2">
                        <p class="text-sm text-gray-500 font-medium">New Users (7 days)</p>

                        <p class="text-3xl font-bold text-gray-900" data-metric="users.new_7d">
                            {{ $dashboard['users']['new_7d'] ?? 0 }}
                        </p>

                        <p class="text-xs text-gray-500">
                            Recent registrations
                        </p>
                    </div>

                    <span class="flex items-center justify-center w-12 h-12 rounded-xl bg-emerald-50 text-emerald-700">

                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">

                            <path d="M12 5v14" />
                            <path d="M5 12h14" />

                        </svg>

                    </span>
                </div>
            </a>


            {{-- Jobs Active --}}
            <a wire:navigate href="{{ route('admin.job-posts.index') }}"
                class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-200">

                <div class="flex items-start justify-between">

                    <div class="space-y-2">
                        <p class="text-sm text-gray-500 font-medium">Jobs (Active)</p>

                        <p class="text-3xl font-bold text-gray-900" data-metric="jobs.active">
                            {{ $dashboard['jobs']['active'] ?? 0 }}
                        </p>

                        <p class="text-xs text-gray-500">
                            Closed:
                            <span class="font-semibold text-gray-700" data-metric="jobs.closed">
                                {{ $dashboard['jobs']['closed'] ?? 0 }}
                            </span>
                        </p>
                    </div>

                    <span class="flex items-center justify-center w-12 h-12 rounded-xl bg-emerald-50 text-emerald-700">

                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">

                            <rect x="2" y="7" width="20" height="14" rx="2" />
                            <path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2" />
                            <path d="M2 13h20" />

                        </svg>

                    </span>
                </div>
            </a>


            {{-- Jobs Pending --}}
            <a wire:navigate href="{{ route('admin.job-posts.index') }}"
                class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-200">

                <div class="flex items-start justify-between">

                    <div class="space-y-2">
                        <p class="text-sm text-gray-500 font-medium">Jobs (Pending)</p>

                        <p class="text-3xl font-bold text-gray-900" data-metric="jobs.pending">
                            {{ $dashboard['jobs']['pending'] ?? 0 }}
                        </p>

                        <p class="text-xs text-gray-500">
                            Held for review
                        </p>
                    </div>

                    <span class="flex items-center justify-center w-12 h-12 rounded-xl bg-emerald-50 text-emerald-700">

                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">

                            <circle cx="12" cy="12" r="10" />
                            <path d="M12 6v6l4 2" />

                        </svg>

                    </span>
                </div>
            </a>


            {{-- Jobs Closed --}}
            <a wire:navigate href="{{ route('admin.job-posts.index') }}"
                class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-200">

                <div class="flex items-start justify-between">

                    <div class="space-y-2">
                        <p class="text-sm text-gray-500 font-medium">Jobs (Closed)</p>

                        <p class="text-3xl font-bold text-gray-900" data-metric="jobs.closed">
                            {{ $dashboard['jobs']['closed'] ?? 0 }}
                        </p>

                        <p class="text-xs text-gray-500">
                            Status closed
                        </p>
                    </div>

                    <span class="flex items-center justify-center w-12 h-12 rounded-xl bg-emerald-50 text-emerald-700">

                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">

                            <path d="M9 11l3 3L22 4" />
                            <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11" />

                        </svg>

                    </span>
                </div>
            </a>


            {{-- New Jobs --}}
            <a wire:navigate href="{{ route('admin.job-posts.index') }}"
                class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-200">

                <div class="flex items-start justify-between">

                    <div class="space-y-2">
                        <p class="text-sm text-gray-500 font-medium">New Jobs (7 days)</p>

                        <p class="text-3xl font-bold text-gray-900" data-metric="jobs.new_7d">
                            {{ $dashboard['jobs']['new_7d'] ?? 0 }}
                        </p>

                        <p class="text-xs text-gray-500">
                            Recently posted
                        </p>
                    </div>

                    <span class="flex items-center justify-center w-12 h-12 rounded-xl bg-emerald-50 text-emerald-700">

                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">

                            <path
                                d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />

                        </svg>

                    </span>
                </div>
            </a>


            {{-- Active Employers --}}
            <a wire:navigate href="{{ route('admin.subscriptions.index') }}"
                class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-200">

                <div class="flex items-start justify-between">

                    <div class="space-y-2">
                        <p class="text-sm text-gray-500 font-medium">Active Employers</p>

                        <p class="text-3xl font-bold text-gray-900">

                            <span data-metric="employers.paid_active">
                                {{ $dashboard['employers']['paid_active'] ?? 0 }}
                            </span>

                            <span class="text-sm text-gray-500">paid</span>

                        </p>

                        <p class="text-xs text-gray-500">
                            Free:
                            <span class="font-semibold text-gray-700" data-metric="employers.free_active">
                                {{ $dashboard['employers']['free_active'] ?? 0 }}
                            </span>
                        </p>
                    </div>

                    <span class="flex items-center justify-center w-12 h-12 rounded-xl bg-emerald-50 text-emerald-700">

                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">

                            <path d="M3 21V7a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v14" />
                            <path d="M13 21V11a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v10" />

                        </svg>

                    </span>
                </div>
            </a>


            {{-- Revenue --}}
            <a wire:navigate href="{{ route('admin.subscriptions.payments.index') }}"
                class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-200">

                <div class="flex items-start justify-between">

                    <div class="space-y-2">
                        <p class="text-sm text-gray-500 font-medium">Revenue</p>

                        <p class="text-3xl font-bold text-gray-900" data-metric="payments.revenue_completed">
                            {{ number_format((float) ($dashboard['payments']['revenue_completed'] ?? 0), 2) }}
                        </p>

                        <p class="text-xs text-gray-500">
                            Pending:
                            <span class="font-semibold text-gray-700" data-metric="payments.pending_payments">
                                {{ $dashboard['payments']['pending_payments'] ?? 0 }}
                            </span>

                            •
                            Expired:
                            <span class="font-semibold text-gray-700" data-metric="payments.expired_subscriptions">
                                {{ $dashboard['payments']['expired_subscriptions'] ?? 0 }}
                            </span>
                        </p>
                    </div>

                    <span class="flex items-center justify-center w-12 h-12 rounded-xl bg-emerald-50 text-emerald-700">

                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">

                            <path d="M12 1v22" />
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7H14a3.5 3.5 0 0 1 0 7H6" />

                        </svg>

                    </span>
                </div>
            </a>

        </div>


        {{-- =========================
        ROW 2: QUICK ACTIONS
    ========================== --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="mb-3">
                <h3 class="text-sm font-semibold text-gray-800">Quick Actions</h3>
                <p class="text-xs text-gray-500">Jump to common admin tasks</p>
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


        {{-- =========================
        ROW 3: ANALYTICS
    ========================== --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h3 class="text-sm font-semibold text-gray-800">Analytics</h3>
                    <p class="text-xs text-gray-500">Trends and breakdowns</p>
                </div>

                {{-- Range selector --}}
                <div class="inline-flex rounded-xl border border-gray-200 bg-white overflow-hidden">
                    <button type="button" data-range="7d"
                        class="px-4 py-2 text-sm font-semibold hover:bg-gray-50 range-btn bg-emerald-50 text-emerald-700">
                        7D
                    </button>
                    <button type="button" data-range="30d"
                        class="px-4 py-2 text-sm font-semibold hover:bg-gray-50 range-btn">
                        30D
                    </button>
                    <button type="button" data-range="monthly"
                        class="px-4 py-2 text-sm font-semibold hover:bg-gray-50 range-btn">
                        Monthly
                    </button>
                </div>
            </div>

            {{-- Extra KPIs --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div class="rounded-2xl border border-gray-100 bg-gray-50 p-4">
                    <p class="text-xs text-gray-500">Apps per Job</p>
                    <p class="text-xl font-bold text-gray-900 mt-1" data-analytic="kpis.apps_per_job">—</p>
                </div>

                <div class="rounded-2xl border border-gray-100 bg-gray-50 p-4">
                    <p class="text-xs text-gray-500">Hire Rate</p>
                    <p class="text-xl font-bold text-gray-900 mt-1">
                        <span data-analytic="kpis.hire_rate">—</span><span
                            class="text-sm font-semibold text-gray-500">%</span>
                    </p>
                </div>

                <a wire:navigate href="{{ route('admin.subscriptions.expired') }}"
                    class="rounded-2xl border border-gray-100 bg-gray-50 p-4 hover:bg-gray-100 transition block">
                    <p class="text-xs text-gray-500">Expiring in 7 days</p>
                    <p class="text-xl font-bold text-gray-900 mt-1" data-analytic="kpis.expiring_soon_7d">—</p>
                </a>
            </div>

            {{-- Needs Attention (actionable ops) --}}
            <div class="rounded-2xl border border-gray-100 bg-gray-50 p-4">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-sm font-semibold text-gray-800">Needs Attention</p>
                        <p class="text-xs text-gray-500">Quick queue items that typically require action</p>
                    </div>
                </div>

                <div class="mt-3 grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <a wire:navigate href="{{ route('admin.job-posts.index') }}"
                        class="rounded-xl border border-gray-200 bg-white hover:bg-gray-50 px-4 py-3 block">
                        <p class="text-xs text-gray-500">Pending job posts</p>
                        <p class="text-lg font-bold text-gray-900 mt-1" data-metric="jobs.pending">
                            {{ $dashboard['jobs']['pending'] ?? 0 }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">Review & approve</p>
                    </a>

                    <a wire:navigate href="{{ route('admin.subscriptions.payments.index') }}"
                        class="rounded-xl border border-gray-200 bg-white hover:bg-gray-50 px-4 py-3 block">
                        <p class="text-xs text-gray-500">Pending payments</p>
                        <p class="text-lg font-bold text-gray-900 mt-1" data-metric="payments.pending_payments">
                            {{ $dashboard['payments']['pending_payments'] ?? 0 }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">Verify / follow up</p>
                    </a>

                    <a wire:navigate href="{{ route('admin.subscriptions.expired') }}"
                        class="rounded-xl border border-gray-200 bg-white hover:bg-gray-50 px-4 py-3 block">
                        <p class="text-xs text-gray-500">Expiring soon (7d)</p>
                        <p class="text-lg font-bold text-gray-900 mt-1" data-analytic="kpis.expiring_soon_7d">—</p>
                        <p class="text-xs text-gray-500 mt-1">Renewals & reminders</p>
                    </a>
                </div>
            </div>

            {{-- Charts grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div class="rounded-2xl border border-gray-100 p-4">
                    <p class="text-sm font-semibold text-gray-800">New Users</p>
                    <p class="text-xs text-gray-500 mb-2">Selected range</p>
                    <div class="h-64"><canvas id="chartUsers"></canvas></div>
                </div>

                <div class="rounded-2xl border border-gray-100 p-4">
                    <p class="text-sm font-semibold text-gray-800">New Jobs</p>
                    <p class="text-xs text-gray-500 mb-2">Selected range</p>
                    <div class="h-64"><canvas id="chartJobs"></canvas></div>
                </div>

                <div class="rounded-2xl border border-gray-100 p-4">
                    <p class="text-sm font-semibold text-gray-800">Applications</p>
                    <p class="text-xs text-gray-500 mb-2">Selected range</p>
                    <div class="h-64"><canvas id="chartApps"></canvas></div>
                </div>

                <div class="rounded-2xl border border-gray-100 p-4">
                    <p class="text-sm font-semibold text-gray-800">Revenue</p>
                    <p class="text-xs text-gray-500 mb-2">Completed payments</p>
                    <div class="h-64"><canvas id="chartRevenue"></canvas></div>
                </div>

                <div class="rounded-2xl border border-gray-100 p-4">
                    <p class="text-sm font-semibold text-gray-800">Payments Status</p>
                    <p class="text-xs text-gray-500 mb-2">Selected range</p>
                    <div class="h-64"><canvas id="donutPayments"></canvas></div>
                </div>

                <div class="rounded-2xl border border-gray-100 p-4">
                    <p class="text-sm font-semibold text-gray-800">Jobs Status</p>
                    <p class="text-xs text-gray-500 mb-2">Current snapshot</p>
                    <div class="h-64"><canvas id="donutJobs"></canvas></div>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        function initDashboardCharts() {

            // =========================
            // Helpers
            // =========================
            const fmtNumber = (n) => new Intl.NumberFormat(undefined).format(Number(n ?? 0));
            const fmtMoney = (n) => new Intl.NumberFormat(undefined, {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                })
                .format(Number(n ?? 0));

            function setTextBySelector(selector, value, formatter = (v) => v) {
                const el = document.querySelector(selector);
                if (el) el.textContent = formatter(value);
            }

            function setMetric(key, value, formatter = (v) => v) {
                setTextBySelector(`[data-metric="${key}"]`, value, formatter);
            }

            function setAnalytic(key, value, formatter = (v) => v) {
                setTextBySelector(`[data-analytic="${key}"]`, value, formatter);
            }

            // Stop polling when tab hidden
            const isTabVisible = () => document.visibilityState === 'visible';

            // =========================
            // 1) LIVE KPI METRICS REFRESH
            // =========================
            const metricsUrl = "{{ route('admin.dashboard.metrics') }}";
            const metricsIntervalMs = 25000;

            let refreshing = false;

            async function refreshMetrics() {
                if (!isTabVisible()) return;
                if (refreshing) return;
                refreshing = true;

                try {
                    const res = await fetch(metricsUrl, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    if (!res.ok) return;

                    const data = await res.json();

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
                    setMetric('payments.expired_subscriptions', data.payments?.expired_subscriptions,
                        fmtNumber);

                } catch (e) {
                    // if you want: console.debug(e);
                } finally {
                    refreshing = false;
                }
            }

            refreshMetrics();
            setInterval(refreshMetrics, metricsIntervalMs);


            // =========================
            // 2) ANALYTICS (charts + donuts)
            // =========================
            const analyticsUrl = "{{ route('admin.dashboard.analytics') }}";
            const analyticsIntervalMs = 60000;

            let currentRange = '7d';
            let loading = false;

            // Palette: give each chart its own identity (less emerald spam)
            const PALETTE = {
                users: '#10b981', // emerald
                jobs: '#14b8a6', // teal
                apps: '#f59e0b', // amber
                revenue: '#f43f5e', // rose
                emerald: '#10b981',
                amber: '#f59e0b',
                rose: '#f43f5e',
                slate: '#64748b',
                gray: '#94a3b8',
                grid: 'rgba(15, 23, 42, 0.08)',
                ticks: '#64748b',
                legend: '#475569',
                border: '#ffffff',
            };

            function setActiveRangeBtn(range) {
                document.querySelectorAll('.range-btn').forEach(btn => {
                    const active = btn.dataset.range === range;
                    btn.classList.toggle('bg-emerald-50', active);
                    btn.classList.toggle('text-emerald-700', active);
                });
            }

            const subtleGrid = {
                color: PALETTE.grid,
                drawBorder: false
            };

            const makeLine = (canvasId, label, color) => {
                const el = document.getElementById(canvasId);
                if (!el || !window.Chart) return null;

                return new Chart(el, {
                    type: 'line',
                    data: {
                        labels: [],
                        datasets: [{
                            label,
                            data: [],
                            borderColor: color,
                            backgroundColor: color + '22',
                            borderWidth: 2,
                            tension: 0.35,
                            pointRadius: 2,
                            pointBackgroundColor: color,
                            fill: true,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                labels: {
                                    color: PALETTE.legend,
                                    boxWidth: 10,
                                    boxHeight: 10,
                                    usePointStyle: true,
                                    pointStyle: 'rectRounded',
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: (ctx) => ` ${ctx.dataset.label}: ${ctx.parsed.y}`
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    color: PALETTE.ticks,
                                    maxRotation: 0,
                                    autoSkip: true,
                                    maxTicksLimit: 6
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: subtleGrid,
                                ticks: {
                                    color: PALETTE.ticks
                                }
                            }
                        }
                    }
                });
            };

            const makeDonut = (canvasId, colors) => {
                const el = document.getElementById(canvasId);
                if (!el || !window.Chart) return null;

                return new Chart(el, {
                    type: 'doughnut',
                    data: {
                        labels: [],
                        datasets: [{
                            data: [],
                            backgroundColor: colors,
                            borderColor: PALETTE.border,
                            borderWidth: 3,
                            hoverOffset: 8,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '65%',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    color: PALETTE.legend,
                                    usePointStyle: true,
                                    pointStyle: 'rectRounded',
                                    boxWidth: 10
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: (ctx) => ` ${ctx.label}: ${ctx.parsed}`
                                }
                            }
                        }
                    }
                });
            };

            // Charts (distinct colors)
            const chUsers = makeLine('chartUsers', 'Users', PALETTE.users);
            const chJobs = makeLine('chartJobs', 'Jobs', PALETTE.jobs);
            const chApps = makeLine('chartApps', 'Applications', PALETTE.apps);
            const chRevenue = makeLine('chartRevenue', 'Revenue', PALETTE.revenue);

            const dnPay = makeDonut('donutPayments', [PALETTE.emerald, PALETTE.amber, PALETTE.rose]);
            const dnJobs = makeDonut('donutJobs', [PALETTE.emerald, PALETTE.amber, PALETTE.slate, PALETTE.gray]);

            function updateLine(chart, series, formatter) {
                if (!chart) return;
                chart.data.labels = series.labels || [];
                chart.data.datasets[0].data = series.values || [];
                chart.options.plugins.tooltip.callbacks.label = (ctx) =>
                    ` ${ctx.dataset.label}: ${formatter(ctx.parsed.y)}`;
                chart.update('none');
            }

            function updateDonut(chart, donut) {
                if (!chart) return;
                chart.data.labels = donut.labels || [];
                chart.data.datasets[0].data = donut.values || [];
                chart.update('none');
            }

            async function fetchAnalytics(range) {
                if (!isTabVisible()) return;
                if (loading) return;
                loading = true;

                try {
                    const res = await fetch(`${analyticsUrl}?range=${encodeURIComponent(range)}`, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    if (!res.ok) return;

                    const data = await res.json();

                    // No more fake "0" while loading
                    setAnalytic('kpis.apps_per_job', data.kpis?.apps_per_job ?? 0, (v) => v);
                    setAnalytic('kpis.hire_rate', data.kpis?.hire_rate ?? 0, (v) => v);
                    setAnalytic('kpis.expiring_soon_7d', data.kpis?.expiring_soon_7d ?? 0, fmtNumber);

                    updateLine(chUsers, data.series?.users ?? {
                        labels: [],
                        values: []
                    }, fmtNumber);
                    updateLine(chJobs, data.series?.jobs ?? {
                        labels: [],
                        values: []
                    }, fmtNumber);
                    updateLine(chApps, data.series?.applications ?? {
                        labels: [],
                        values: []
                    }, fmtNumber);
                    updateLine(chRevenue, data.series?.revenue ?? {
                        labels: [],
                        values: []
                    }, fmtMoney);

                    updateDonut(dnPay, data.donuts?.payments_status ?? {
                        labels: [],
                        values: []
                    });
                    updateDonut(dnJobs, data.donuts?.jobs_status ?? {
                        labels: [],
                        values: []
                    });

                } catch (e) {
                    // if you want: console.debug(e);
                } finally {
                    loading = false;
                }
            }

            // Buttons
            document.querySelectorAll('.range-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    currentRange = btn.dataset.range;
                    setActiveRangeBtn(currentRange);
                    fetchAnalytics(currentRange);
                });
            });

            setActiveRangeBtn(currentRange);
            fetchAnalytics(currentRange);
            setInterval(() => fetchAnalytics(currentRange), analyticsIntervalMs);


        }

        document.addEventListener('DOMContentLoaded', initDashboardCharts);
        document.addEventListener('livewire:navigated', initDashboardCharts);
    </script>

@endsection
