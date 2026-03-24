@extends('adminpage.layout')

@section('content')
    <div class="space-y-6">

        <div class="bg-white rounded-3xl shadow-sm overflow-hidden">

            {{-- 🔷 Top header --}}
            <div class="px-6 sm:px-8 py-6 border-b border-slate-200 bg-gradient-to-r from-indigo-50 to-white">

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                    <div>
                        <h1 class="text-2xl sm:text-3xl font-semibold text-slate-900">
                            Edit Job
                        </h1>
                        <p class="text-sm text-slate-600 mt-1">
                            Update the job details below.
                        </p>
                    </div>

                    <div class="hidden sm:flex items-center gap-2">
                        <span
                            class="inline-flex items-center gap-2 rounded-full bg-indigo-600 px-3 py-1 text-xs font-semibold text-white">
                            <span class="h-2 w-2 rounded-full bg-white/90"></span>
                            Admin Panel
                        </span>
                    </div>

                </div>

            </div>

            {{-- FORM --}}
            @include('components.job-post.form', [
                'action' => route('admin.admin-job-posts.update', $job->id),
                'method' => 'PUT',
                'job' => $job,
                'employers' => $employers,
            ])

        </div>

    </div>
@endsection