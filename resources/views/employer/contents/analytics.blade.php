@extends('employer.layout')

@section('content')
    <div class="space-y-8">

        {{-- HEADER --}}
        <div class="flex items-center justify-between">

            <div class="flex items-center gap-3">

                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Recruitment Analytics</h1>
                    <p class="text-sm text-gray-500">Understand hiring performance and applicant trends</p>
                </div>

                {{-- PLAN BADGE --}}
                <span
                    class="px-2 py-1 text-xs font-semibold rounded
@if ($analyticsLevel === 'enterprise') bg-purple-100 text-purple-700
@elseif($analyticsLevel === 'advanced')
bg-blue-100 text-blue-700
@elseif($analyticsLevel === 'basic')
bg-green-100 text-green-700
@else
bg-gray-100 text-gray-700 @endif
">
                    {{ ucfirst($analyticsLevel) }} Plan
                </span>

            </div>

            {{-- UPGRADE BUTTON --}}
            @if ($analyticsLevel !== 'enterprise')
                <a href="{{ route('employer.subscription.dashboard') }}"
                    class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700">
                    Upgrade Plan
                </a>
            @endif

        </div>


        {{-- KPI CARDS --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

            <div class="bg-white rounded-xl p-6 shadow flex items-center justify-between">
                <div>
                    <p class="text-2xl font-bold">{{ $activeJobs }}</p>
                    <p class="text-sm text-gray-500">Active Jobs</p>
                </div>
                <x-lucide-icon name="briefcase" class="h-8 w-8 text-emerald-500" />
            </div>

            <div class="bg-white rounded-xl p-6 shadow flex items-center justify-between">
                <div>
                    <p class="text-2xl font-bold">{{ $totalApplications }}</p>
                    <p class="text-sm text-gray-500">Total Applicants</p>
                </div>
                <x-lucide-icon name="users" class="h-8 w-8 text-blue-500" />
            </div>

            <div class="bg-white rounded-xl p-6 shadow flex items-center justify-between">
                <div>
                    <p class="text-2xl font-bold">{{ $hiresThisMonth }}</p>
                    <p class="text-sm text-gray-500">Hires This Month</p>
                </div>
                <x-lucide-icon name="check-circle" class="h-8 w-8 text-yellow-500" />
            </div>

            <div class="bg-white rounded-xl p-6 shadow flex items-center justify-between">
                <div>
                    <p class="text-2xl font-bold">
                        {{ $activeJobs > 0 ? round($totalApplications / $activeJobs, 1) : 0 }}
                    </p>
                    <p class="text-sm text-gray-500">Applicants per Job</p>
                </div>
                <x-lucide-icon name="bar-chart" class="h-8 w-8 text-purple-500" />
            </div>

        </div>


        {{-- BASIC ANALYTICS --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 relative">

            {{-- Applicants per Job --}}
            <div class="bg-white rounded-xl p-6 shadow relative">

                <h2 class="text-lg font-semibold mb-4">Applicants per Job</h2>

                <div class="h-64">
                    @if ($analyticsLevel !== 'default')
                        <canvas id="applicationsChart"></canvas>
                    @endif
                </div>

                @if ($analyticsLevel === 'default')
                    <div class="absolute inset-0 bg-white/80 flex flex-col items-center justify-center rounded-xl">
                        <p class="text-sm text-gray-600 mb-3">Basic analytics required</p>
                        <a href="{{ route('employer.subscription.dashboard') }}"
                            class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm">
                            Upgrade Plan
                        </a>
                    </div>
                @endif

            </div>


            {{-- Applicant Status --}}
            <div class="bg-white rounded-xl p-6 shadow relative">

                <h2 class="text-lg font-semibold mb-4">Applicant Status Distribution</h2>

                <div class="h-64">
                    @if ($analyticsLevel !== 'default')
                        <canvas id="statusChart"></canvas>
                    @endif
                </div>

                @if ($analyticsLevel === 'default')
                    <div class="absolute inset-0 bg-white/80 flex flex-col items-center justify-center rounded-xl">
                        <p class="text-sm text-gray-600 mb-3">Basic analytics required</p>
                        <a href="{{ route('employer.subscription.dashboard') }}"
                            class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm">
                            Upgrade Plan
                        </a>
                    </div>
                @endif

            </div>

        </div>


        {{-- TOP JOBS --}}
        <div class="bg-white rounded-xl shadow p-6 mt-6 relative">

            <h2 class="text-lg font-semibold mb-5">Top Performing Jobs</h2>

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead>
                        <tr class="text-gray-400 text-xs uppercase tracking-wide">
                            <th class="pb-3 text-left">Rank</th>
                            <th class="pb-3 text-left">Job Title</th>
                            <th class="pb-3 text-left">Industry</th>
                            <th class="pb-3 text-right">Applications</th>
                        </tr>
                    </thead>

                    <tbody class="text-gray-700">

                        @foreach ($applicationsPerJob ?? [] as $index => $job)
                            <tr class="hover:bg-gray-50 transition">

                                <td class="py-3 font-semibold text-gray-500">
                                    #{{ $index + 1 }}
                                </td>

                                <td class="py-3 font-medium">
                                    {{ $job->title }}
                                </td>

                                <td class="py-3 text-gray-500">
                                    {{ $job->industry ?? 'N/A' }}
                                </td>

                                <td class="py-3 text-right">

                                    <span
                                        class="px-3 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">
                                        {{ $job->applications_count }}
                                    </span>

                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>

            @if ($analyticsLevel === 'default')
                <div class="absolute inset-0 bg-white/80 flex flex-col items-center justify-center rounded-xl">
                    <p class="text-sm text-gray-600 mb-3">Upgrade to view job performance analytics</p>
                    <a href="{{ route('employer.subscription.dashboard') }}"
                        class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm">
                        Upgrade Plan
                    </a>
                </div>
            @endif

        </div>


        {{-- ADVANCED ANALYTICS --}}
        <div class="bg-white rounded-xl p-6 shadow mt-6 relative">

            <h2 class="text-lg font-semibold mb-4">Monthly Applicant Trend</h2>

            <div class="h-64">
                @if (in_array($analyticsLevel, ['advanced', 'enterprise']))
                    <canvas id="trendChart"></canvas>
                @endif
            </div>

            @if (!in_array($analyticsLevel, ['advanced', 'enterprise']))
                <div class="absolute inset-0 bg-white/80 flex flex-col items-center justify-center rounded-xl">
                    <p class="text-sm text-gray-600 mb-3">Advanced analytics required</p>
                    <a href="{{ route('employer.subscription.dashboard') }}"
                        class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm">
                        Upgrade Plan
                    </a>
                </div>
            @endif

        </div>


        {{-- ENTERPRISE ANALYTICS --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">

            {{-- Funnel --}}
            <div class="bg-white rounded-xl p-6 shadow relative">

                <h2 class="text-lg font-semibold mb-4">Recruitment Funnel</h2>

                <div class="h-64">
                    @if ($analyticsLevel === 'enterprise')
                        <canvas id="funnelChart"></canvas>
                    @endif
                </div>

                @if ($analyticsLevel !== 'enterprise')
                    <div class="absolute inset-0 bg-white/80 flex flex-col items-center justify-center rounded-xl">
                        <p class="text-sm text-gray-600 mb-3">Enterprise analytics required</p>
                        <a href="{{ route('employer.subscription.dashboard') }}"
                            class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm">
                            Upgrade Plan
                        </a>
                    </div>
                @endif

            </div>


            {{-- Experience --}}
            <div class="bg-white rounded-xl p-6 shadow relative">

                <h2 class="text-lg font-semibold mb-4">Candidate Experience Levels</h2>

                <div class="h-64">
                    @if ($analyticsLevel === 'enterprise')
                        <canvas id="experienceChart"></canvas>
                    @endif
                </div>

                @if ($analyticsLevel !== 'enterprise')
                    <div class="absolute inset-0 bg-white/80 flex flex-col items-center justify-center rounded-xl">
                        <p class="text-sm text-gray-600 mb-3">Enterprise analytics required</p>
                        <a href="{{ route('employer.subscription.dashboard') }}"
                            class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm">
                            Upgrade Plan
                        </a>
                    </div>
                @endif

            </div>

        </div>


        {{-- EXPORT --}}
        <div class="bg-white rounded-xl p-6 shadow mt-6 relative">

            <h2 class="text-lg font-semibold mb-2">Analytics Report</h2>

            <p class="text-sm text-gray-500 mb-4">
                Download recruitment analytics as PDF or CSV reports.
            </p>

            <div class="flex gap-3">

                <a href="{{ route('employer.analytics.export.pdf') }}"
                    class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm">
                    Export PDF
                </a>

                <a href="{{ route('employer.analytics.export.csv') }}"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm">
                    Export CSV
                </a>

            </div>

            @if ($analyticsLevel !== 'enterprise')
                <div class="absolute inset-0 bg-white/80 flex flex-col items-center justify-center rounded-xl">
                    <p class="text-sm text-gray-600 mb-3">Enterprise plan required for exports</p>
                    <a href="{{ route('employer.subscription.dashboard') }}"
                        class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm">
                        Upgrade Plan
                    </a>
                </div>
            @endif

        </div>


    </div>

    {{-- CHART.JS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {

            const commonOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.raw + " applicants";
                            }
                        }
                    }
                }
            };


            // Applications per Job
            const applicationsChart = document.getElementById('applicationsChart');

            if (applicationsChart) {

                new Chart(applicationsChart, {

                    type: 'bar',

                    data: {
                        labels: @json(isset($applicationsPerJob) ? $applicationsPerJob->pluck('title') : []),
                        datasets: [{
                            label: "Applications",
                            data: @json(isset($applicationsPerJob) ? $applicationsPerJob->pluck('applications_count') : []),
                            backgroundColor: '#10B981'
                        }]
                    },

                    options: commonOptions

                });

            }


            // Applicant Status
            const statusChart = document.getElementById('statusChart');

            if (statusChart) {

                new Chart(statusChart, {
                    type: 'pie',
                    data: {
                        labels: @json(isset($statusDistribution) ? array_keys($statusDistribution) : []),
                        datasets: [{
                            data: @json(isset($statusDistribution) ? array_values($statusDistribution) : []),
                            backgroundColor: ['#3B82F6', '#10B981', '#FBBF24', '#EF4444']
                        }]
                    },
                    options: commonOptions
                });

            }
            // Monthly Trend
            const trendChart = document.getElementById('trendChart');

            if (trendChart) {

                new Chart(trendChart, {

                    type: 'line',

                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct',
                            'Nov', 'Dec'
                        ],
                        datasets: [{
                            label: "Hires",
                            data: @json(isset($hiresPerMonthData) ? $hiresPerMonthData : []),
                            borderColor: '#3B82F6',
                            backgroundColor: 'rgba(59,130,246,0.1)',
                            fill: true,
                            tension: 0.4
                        }]
                    },

                    options: commonOptions

                });

            }


            // Funnel
            const funnelChart = document.getElementById('funnelChart');

            if (funnelChart) {

                new Chart(funnelChart, {

                    type: 'bar',

                    data: {
                        labels: ['Applied', 'Shortlisted', 'Interview', 'Hired'],
                        datasets: [{
                            label: "Applicants",
                            data: @json(isset($funnel) ? array_values($funnel) : []),
                            backgroundColor: '#6366F1'
                        }]
                    },

                    options: commonOptions

                });

            }


            // Experience
            const experienceChart = document.getElementById('experienceChart');

            if (experienceChart) {

                new Chart(experienceChart, {

                    type: 'pie',

                    data: {
                        labels: @json(isset($experienceLevels) ? array_keys($experienceLevels) : []),
                        datasets: [{
                            data: @json(isset($experienceLevels) ? array_values($experienceLevels) : []),
                            backgroundColor: ['#3B82F6', '#F59E0B', '#10B981']
                        }]
                    },

                    options: commonOptions

                });

            }

            // Industry
            const industryChart = document.getElementById('industryChart');

            if (industryChart) {

                new Chart(document.getElementById('categoryChart'), {

                    type: 'bar',

                    data: {
                        labels: @json(isset($applicantsByCategory) ? array_keys($applicantsByCategory) : []),
                        datasets: [{
                            label: "Applicants",
                            data: @json(isset($applicantsByCategory) ? array_values($applicantsByCategory) : []),
                            backgroundColor: '#8B5CF6'
                        }]
                    },

                    options: commonOptions

                });

            }


            if (window.lucide) window.lucide.createIcons();

        });
    </script>
@endsection
