@extends('employer.layout')

@section('content')
    @php
        $employer = auth()->user()->employerProfile;
    @endphp

    <script>
        window.Laravel = {
            userId: {{ auth()->id() }}
        };
    </script>

    <div class="space-y-8" x-data="employerDashboard()" x-init="init()">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Dashboard</h1>
                <p class="text-sm text-gray-500">
                    Welcome back,
                    <span class="font-semibold text-gray-700">{{ auth()->user()->name }}</span>
                </p>
            </div>

            <a href="{{ route('employer.job-postings.create') }}"
                class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                <i data-lucide="plus" class="h-4 w-4"></i>
                Post Job
            </a>
        </div>


        {{-- Stats --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 flex justify-between">
                <div>
                    <p class="text-2xl font-bold text-gray-900" x-text="stats.postedJobs"></p>
                    <p class="text-sm text-gray-500 mt-1">Posted Jobs</p>
                </div>
                <div class="h-12 w-12 rounded-xl bg-blue-50 flex items-center justify-center">
                    <i data-lucide="briefcase" class="h-6 w-6 text-blue-600"></i>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 flex justify-between">
                <div>
                    <p class="text-2xl font-bold text-gray-900" x-text="stats.applicants"></p>
                    <p class="text-sm text-gray-500 mt-1">Applicants</p>
                </div>
                <div class="h-12 w-12 rounded-xl bg-amber-50 flex items-center justify-center">
                    <i data-lucide="users" class="h-6 w-6 text-amber-600"></i>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 flex justify-between">
                <div>
                    <p class="text-2xl font-bold text-gray-900" x-text="stats.interviews"></p>
                    <p class="text-sm text-gray-500 mt-1">Interviews</p>
                </div>
                <div class="h-12 w-12 rounded-xl bg-purple-50 flex items-center justify-center">
                    <i data-lucide="calendar" class="h-6 w-6 text-purple-600"></i>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 flex justify-between">
                <div>
                    <p class="text-2xl font-bold text-gray-900" x-text="stats.shortlisted"></p>
                    <p class="text-sm text-gray-500 mt-1">Shortlisted</p>
                </div>
                <div class="h-12 w-12 rounded-xl bg-emerald-50 flex items-center justify-center">
                    <i data-lucide="star" class="h-6 w-6 text-emerald-600"></i>
                </div>
            </div>

        </div>


        {{-- Applications Chart --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">

            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-semibold text-gray-900">
                    Job Applications Overview
                </h2>
            </div>

            <div class="h-64">
                <canvas id="applicationsChart"></canvas>
            </div>

        </div>


        {{-- Main Grid --}}
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">

            {{-- Recently Posted Jobs --}}
            <section class="xl:col-span-8 space-y-4">

                <div class="flex items-center justify-between">

                    <h2 class="text-base font-semibold text-gray-900">
                        Recently Posted Jobs
                    </h2>

                    <a href="{{ route('employer.job-postings.index') }}"
                        class="inline-flex items-center gap-1 text-sm font-semibold text-gray-600 hover:text-gray-900">

                        View All
                        <i data-lucide="arrow-right" class="h-4 w-4"></i>

                    </a>

                </div>


                <div class="space-y-3">

                    <template x-for="job in jobs" :key="job.id">

                        <div
                            class="group rounded-2xl bg-white border border-gray-200 shadow-sm p-5 hover:shadow-md transition">

                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                                <div class="flex items-start gap-4">

                                    <div class="h-12 w-12 rounded-xl bg-blue-50 flex items-center justify-center">
                                        <i data-lucide="briefcase" class="h-5 w-5 text-blue-600"></i>
                                    </div>

                                    <div class="min-w-0">

                                        <p class="font-semibold text-gray-900 group-hover:text-blue-600 transition"
                                            x-text="job.title"></p>

                                        <div class="flex flex-wrap items-center gap-4 mt-1 text-sm text-gray-500">

                                            <span class="flex items-center gap-1">

                                                <i data-lucide="users" class="h-4 w-4"></i>

                                                <span x-text="job.applicants"></span>

                                                Applicants

                                            </span>

                                        </div>

                                        <span
                                            class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold border mt-2"
                                            :class="job.statusPill" x-text="job.status">
                                        </span>

                                    </div>

                                </div>


                                <div class="flex items-center gap-2 sm:justify-end">

                                    <button @click="openJob(job)"
                                        class="inline-flex items-center gap-1 rounded-xl border border-gray-200 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">

                                        <i data-lucide="users" class="h-4 w-4"></i>

                                        Applicants

                                    </button>

                                </div>

                            </div>

                        </div>

                    </template>

                </div>

            </section>


            {{-- Notifications --}}
            <aside class="xl:col-span-4 space-y-4">

                <div class="flex items-center justify-between">

                    <h2 class="text-base font-semibold text-gray-900">
                        Notifications
                    </h2>

                    <a href=""
                        class="inline-flex items-center gap-1 text-sm font-semibold text-blue-600 hover:text-blue-700">

                        View All
                        <i data-lucide="arrow-right" class="h-4 w-4"></i>

                    </a>

                </div>


                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">

                    <template x-if="notifications.length === 0">

                        <div class="p-8 text-center">

                            <div class="mx-auto h-12 w-12 rounded-xl bg-gray-100 flex items-center justify-center">
                                <i data-lucide="bell-off" class="h-6 w-6 text-gray-500"></i>
                            </div>

                            <p class="mt-3 text-sm font-semibold text-gray-900">
                                No notifications
                            </p>

                            <p class="text-xs text-gray-500">
                                You're all caught up
                            </p>

                        </div>

                    </template>


                    <div class="divide-y">

                        <template x-for="n in notifications.slice(0,5)" :key="n.id">

                            <div class="flex items-start gap-3 p-4 hover:bg-gray-50 transition">

                                <div class="h-10 w-10 rounded-xl border flex items-center justify-center"
                                    :class="n.iconWrap">

                                    <i :data-lucide="n.icon" class="h-4 w-4" :class="n.iconColor"></i>

                                </div>


                                <div class="flex-1 min-w-0">

                                    <div class="flex items-center justify-between">

                                        <p class="text-sm font-semibold text-gray-900 truncate" x-text="n.title"></p>

                                        <span class="text-xs text-gray-400" x-text="n.time"></span>

                                    </div>

                                    <p class="text-xs text-gray-500 mt-1 line-clamp-2" x-text="n.body"></p>

                                </div>

                            </div>

                        </template>

                    </div>

                </div>

            </aside>

        </div>


        {{-- Applicants Modal --}}
        <div x-show="applicantsModalOpen" x-transition.opacity
            class="fixed inset-0 z-[999] flex items-center justify-center p-4 sm:p-6" x-cloak>

            {{-- Background --}}
            <div class="absolute inset-0 bg-black/20 backdrop-blur-sm" @click="closeApplicants()">
            </div>


            <div @click.stop
                class="relative w-full max-w-3xl max-h-[90vh] overflow-hidden rounded-2xl bg-white border border-gray-200 shadow-xl flex flex-col">

                {{-- Header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">

                    <h2 class="text-lg font-semibold text-gray-900" x-text="selectedJob?.title + ' Applicants'"></h2>

                    <button @click="closeApplicants()" class="rounded-lg p-1 hover:bg-gray-100">

                        <i data-lucide="x" class="h-5 w-5 text-gray-600"></i>

                    </button>

                </div>


                {{-- Applicants List --}}
                <div class="overflow-y-auto p-6 space-y-3">

                    <template x-if="(selectedJob?.applicantsList ?? []).length === 0">

                        <div class="text-center py-10">

                            <div class="mx-auto h-12 w-12 rounded-xl bg-gray-100 flex items-center justify-center">
                                <i data-lucide="users" class="h-6 w-6 text-gray-500"></i>
                            </div>

                            <p class="mt-3 text-sm font-semibold text-gray-900">
                                No Applicants Yet
                            </p>

                            <p class="text-xs text-gray-500">
                                Applicants will appear here once they apply
                            </p>

                        </div>

                    </template>


                    <template x-for="a in selectedJob?.applicantsList ?? []" :key="a.id">

                        <div
                            class="flex items-center justify-between rounded-xl border border-gray-200 p-4 hover:bg-gray-50 transition">

                            <div class="flex items-center gap-3">

                                <div class="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center">

                                    <i data-lucide="user" class="h-4 w-4 text-gray-600"></i>

                                </div>

                                <div>

                                    <p class="text-sm font-semibold text-gray-900" x-text="a.name"></p>

                                    <p class="text-xs text-gray-500" x-text="a.email"></p>

                                </div>

                            </div>


                            <div class="text-right">

                                <p class="text-xs text-gray-400">
                                    Applied
                                </p>

                                <p class="text-sm text-gray-600" x-text="a.appliedDate"></p>

                            </div>

                        </div>

                    </template>

                </div>

            </div>

        </div>

    </div>



    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        function employerDashboard() {

            return {

                jobs: @json($jobsForJson),

                stats: @json($stats),

                notifications: @json($notificationsArray),

                chartData: @json($chartData),

                applicantsModalOpen: false,

                selectedJob: null,



                openJob(job) {

                    this.selectedJob = job

                    this.applicantsModalOpen = true

                    this.$nextTick(() => {

                        if (window.lucide) lucide.createIcons()

                    })

                },


                closeApplicants() {

                    this.applicantsModalOpen = false

                },


                init() {

                    if (window.lucide) lucide.createIcons()

                    const ctx = document.getElementById('applicationsChart')

                    if (ctx) {

                        new Chart(ctx, {

                            type: 'line',

                            data: {

                                labels: this.chartData.labels,

                                datasets: [{

                                    label: 'Applications',

                                    data: this.chartData.data,

                                    borderColor: '#10B981',

                                    backgroundColor: 'rgba(16,185,129,0.1)',

                                    fill: true,

                                    tension: 0.4,

                                    pointBackgroundColor: '#10B981',

                                    pointRadius: 4

                                }]

                            },

                            options: {

                                responsive: true,

                                maintainAspectRatio: false,

                                plugins: {

                                    legend: {
                                        display: false
                                    }

                                },

                                scales: {

                                    y: {

                                        beginAtZero: true,

                                        ticks: {
                                            stepSize: 1
                                        }

                                    }

                                }

                            }

                        })

                    }

                }

            }

        }
    </script>
@endsection
