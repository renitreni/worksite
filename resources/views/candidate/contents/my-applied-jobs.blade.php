@extends('candidate.layout')

@section('content')
    <div x-data="appliedJobsPage()" x-init="init()" class="space-y-6">

        {{-- HEADER --}}
        <div class="flex items-center justify-between">

            <div>
                <h1 class="text-xl font-semibold text-gray-900">
                    My Applied Jobs
                </h1>

                <p class="text-sm text-gray-500">
                    Track the progress of your job applications
                </p>
            </div>

            <select x-model="filterBy" class="border border-gray-200 rounded-lg px-4 py-2 text-sm bg-white">

                <option value="newest">Newest</option>
                <option value="oldest">Oldest</option>
                <option value="submitted">Submitted</option>
                <option value="shortlisted">Shortlisted</option>
                <option value="interview">Interview</option>
                <option value="hired">Hired</option>

            </select>

        </div>


        {{-- EMPTY STATE --}}
        <template x-if="filteredJobs().length === 0">

            <div class="bg-white border border-gray-200 rounded-xl p-10 text-center">

                <div class="mx-auto size-12 rounded-md bg-gray-100 flex items-center justify-center">
                    <x-lucide-icon name="briefcase" class="size-6 text-gray-500" />
                </div>

                <p class="mt-4 font-semibold text-gray-900">
                    No job applications yet
                </p>

                <p class="text-sm text-gray-500">
                    Start applying to jobs and track them here.
                </p>

            </div>

        </template>



        {{-- JOB LIST --}}
        <div class="space-y-4">

            <template x-for="job in filteredJobs()" :key="job.id">

                <div class="bg-white border border-gray-200 rounded-xl p-5 hover:shadow-sm transition">

                    <div class="flex justify-between items-start gap-6">

                        {{-- LEFT SIDE --}}
                        <div class="flex gap-4">

                            {{-- SQUARE COMPANY BADGE --}}
                            <div class="size-12 bg-emerald-600 text-white font-semibold rounded-md flex items-center justify-center text-sm shrink-0"
                                x-text="job.badge">
                            </div>

                            <div>

                                {{-- TITLE --}}
                                <p class="font-semibold text-gray-900 text-sm" x-text="job.title"></p>

                                {{-- COMPANY --}}
                                <p class="text-sm text-blue-600 font-medium mt-1" x-text="job.company"></p>


                                {{-- JOB META --}}
                                <div class="flex flex-wrap gap-4 text-sm text-gray-500 mt-2">

                                    <span class="flex items-center gap-1">
                                        <x-lucide-icon name="map-pin" class="size-4" />
                                        <span x-text="job.location"></span>
                                    </span>

                                    <span class="flex items-center gap-1">
                                        <x-lucide-icon name="building-2" class="size-4" />
                                        <span x-text="job.industry"></span>
                                    </span>

                                    <span class="flex items-center gap-1">
                                        <x-lucide-icon name="dollar-sign" class="size-4" />
                                        <span x-text="job.salaryText"></span>
                                    </span>

                                </div>


                                {{-- STATUS --}}
                                <div class="flex items-center gap-3 mt-3 text-xs">

                                    <span class="px-2 py-1 border rounded-full font-semibold" :class="job.statusPill"
                                        x-text="job.status">
                                    </span>

                                    <span class="text-gray-500">
                                        Applied: <span x-text="job.appliedDate"></span>
                                    </span>

                                </div>

                            </div>

                        </div>


                        {{-- RIGHT SIDE BUTTON --}}
                        <a :href="'{{ route('jobs.show', ':id') }}'.replace(':id', job.job_post_id)"
                            class="flex items-center gap-2 border border-gray-200 rounded-md px-3 py-2 text-sm hover:bg-gray-50">

                            <x-lucide-icon name="eye" class="size-4" />
                            View

                        </a>

                    </div>

                </div>

            </template>

        </div>

    </div>



    <script>
        function appliedJobsPage() {

            return {

                jobs: [],
                filterBy: 'newest',

                async init() {

                    await this.loadJobs()

                },

                async loadJobs() {

                    try {

                        const res = await fetch('/candidate/applied-jobs/data')

                        const data = await res.json()

                        this.jobs = data.jobs ?? []

                        this.$nextTick(() => {
                            lucide.createIcons()
                        })

                    } catch (e) {

                        console.error("Failed to load jobs", e)

                        this.jobs = []

                    }

                },

                filteredJobs() {

                    let list = [...this.jobs]

                    if (this.filterBy === 'submitted')
                        list = list.filter(j => j.status === 'Submitted')

                    if (this.filterBy === 'shortlisted')
                        list = list.filter(j => j.status === 'Shortlisted')

                    if (this.filterBy === 'interview')
                        list = list.filter(j => j.status === 'Interview')

                    if (this.filterBy === 'hired')
                        list = list.filter(j => j.status === 'Hired')

                    if (this.filterBy === 'newest')
                        list.sort((a, b) => b.createdAt - a.createdAt)

                    if (this.filterBy === 'oldest')
                        list.sort((a, b) => a.createdAt - b.createdAt)

                    this.$nextTick(() => {
                        lucide.createIcons()
                    })

                    return list

                }

            }
        }
    </script>
@endsection
