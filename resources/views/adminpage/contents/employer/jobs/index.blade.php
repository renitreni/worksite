@extends('adminpage.layout')

@section('content')
    <div x-data="jobSearch()" class="space-y-6">
        {{-- HEADER --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>
                    <div class="text-lg font-semibold text-slate-900">
                        Admin Posted Jobs
                    </div>
                    <div class="text-xs text-slate-500 mt-1">
                        Manage and edit jobs posted by admin on behalf of employers
                    </div>
                </div>

                {{-- ✅ POST JOB BUTTON (EMERALD) --}}
                <a href="{{ route('admin.admin-job-posts.create') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/40 shadow-sm transition">

                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>

                    Post Job
                </a>

            </div>

        </div>
        <div class="bg-white rounded-2xl shadow-sm p-4">

            <div class="relative max-w-md">

                <input type="text" x-model="search" @input.debounce.400ms="fetchJobs"
                    placeholder="Search employer/company..."
                    class="w-full pl-10 pr-4 py-2 rounded-xl bg-slate-100 text-sm focus:ring-2 focus:ring-emerald-500 outline-none">

                <div class="absolute left-3 top-2.5 text-slate-400">
                    <x-lucide-icon name="search" class="w-4 h-4" />
                </div>

            </div>

        </div>
        {{-- TABLE --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 p-5 text-sm font-semibold">
                Jobs List
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">

                    <thead class="bg-slate-50 text-xs font-semibold text-slate-600">
                        <tr>
                            <th class="px-5 py-3">Title</th>
                            <th class="px-5 py-3">Employer</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3">Date</th>
                            <th class="px-5 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">

                        <template x-for="job in jobs" :key="job.id">
                            <tr class="hover:bg-slate-50">

                                {{-- Title --}}
                                <td class="px-5 py-4">
                                    <div class="font-semibold text-slate-900" x-text="job.title"></div>

                                    <div class="flex items-center gap-2 mt-1">

                                        <span class="text-xs text-slate-500" x-text="job.industry ?? '—'"></span>

                                        <span class="text-[10px] px-2 py-0.5 rounded-full font-semibold"
                                            :class="job.posted_by_admin_id ?
                                                'bg-indigo-100 text-indigo-700' :
                                                'bg-slate-100 text-slate-600'">
                                            <span x-text="job.posted_by_admin_id ? 'ADMIN' : 'EMPLOYER'"></span>
                                        </span>

                                    </div>
                                </td>

                                {{-- Employer --}}
                                <td class="px-5 py-4">
                                    <div class="font-semibold text-slate-900" x-text="job.employer_profile.company_name">
                                    </div>

                                    <div class="text-xs text-slate-500" x-text="job.employer_profile.user?.email ?? ''">
                                    </div>
                                </td>

                                {{-- Status --}}
                                <td class="px-5 py-4">
                                    <span
                                        class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1"
                                        :class="job.status === 'open' ?
                                            'bg-emerald-50 text-emerald-700 ring-emerald-200' :
                                            'bg-rose-50 text-rose-700 ring-rose-200'">

                                        <span x-text="job.status.charAt(0).toUpperCase() + job.status.slice(1)"></span>
                                    </span>
                                </td>

                                {{-- Date --}}
                                <td class="px-5 py-4 text-xs text-slate-500"
                                    x-text="new Date(job.created_at).toLocaleString()">
                                </td>

                                {{-- Actions --}}
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-2">

                                        {{-- 👁 VIEW --}}
                                        <a :href="`/admin/admin-job-posts/${job.id}`"
                                            class="px-3 py-1.5 text-xs rounded-lg bg-slate-600 text-white hover:bg-slate-700">
                                            View
                                        </a>

                                        {{-- ✏️ EDIT --}}
                                        <a :href="`/admin/admin-job-posts/${job.id}/edit`"
                                            class="px-3 py-1.5 text-xs rounded-lg bg-emerald-600 text-white hover:bg-emerald-700">
                                            Edit
                                        </a>

                                        {{-- 🔄 CLOSE / REOPEN --}}
                                        <template x-if="job.status === 'open'">
                                            <form method="POST" :action="`/admin/admin-job-posts/${job.id}/close`">
                                                @csrf
                                                @method('PUT')

                                                <button
                                                    class="px-3 py-1.5 text-xs rounded-lg bg-rose-600 text-white hover:bg-rose-700">
                                                    Close
                                                </button>
                                            </form>
                                        </template>

                                        <template x-if="job.status !== 'open'">
                                            <form method="POST" :action="`/admin/admin-job-posts/${job.id}/reopen`">
                                                @csrf
                                                @method('PUT')

                                                <button
                                                    class="px-3 py-1.5 text-xs rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                                                    Reopen
                                                </button>
                                            </form>
                                        </template>

                                    </div>
                                </td>
                            </tr>
                        </template>

                        <template x-if="jobs.length === 0">
                            <tr>
                                <td colspan="5" class="px-5 py-10 text-center text-sm text-slate-500">
                                    No jobs found.
                                </td>
                            </tr>
                        </template>

                    </tbody>
                </table>

            </div>
            <div class="p-4">
                {{ $jobs->links() }}
            </div>
        </div>

    </div>

    <script>
        function jobSearch() {
            return {
                search: '',
                jobs: @json($jobs->items()), // 🔥 IMPORTANT (fix pagination issue)

                async fetchJobs() {
                    try {
                        const res = await fetch(`{{ route('admin.admin-job-posts.search') }}?search=${this.search}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        const data = await res.json();

                        this.jobs = data;
                    } catch (e) {
                        console.error('Search error:', e);
                    }
                }
            }
        }
    </script>
@endsection
