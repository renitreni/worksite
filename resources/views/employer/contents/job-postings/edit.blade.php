@extends('employer.layout')

@section('content')
    <div class="space-y-6">

        <div class="bg-white rounded-3xl shadow-sm overflow-hidden">

            {{-- Header --}}
            <div class="px-6 sm:px-8 py-6 border-b border-slate-200 bg-gradient-to-r from-emerald-50 to-white">

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                    <div>
                        <h1 class="text-2xl sm:text-3xl font-semibold text-slate-900">
                            Edit Job Posting
                        </h1>
                        <p class="text-sm text-slate-600 mt-1">
                            Update the details then save changes.
                        </p>
                    </div>

                    <div class="flex items-center gap-2">
                        <span
                            class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold
                            {{ $job->status === 'open' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                            {{ strtoupper($job->status) }}
                        </span>
                    </div>

                </div>

            </div>

            {{-- ✅ REUSABLE FORM --}}
            @include('components.job-post.form', [
                'action' => route('employer.job-postings.update', $job->id),
                'method' => 'PUT',
                'job' => $job
            ])

        </div>

    </div>
@endsection