@extends('employer.layout')

@section('content')
    <div class="space-y-6">
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
            {{-- Top header --}}
            <div class="px-6 sm:px-8 py-6 border-b border-slate-200 bg-gradient-to-r from-emerald-50 to-white">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-semibold text-slate-900">Post New Job</h1>
                        <p class="text-sm text-slate-600 mt-1">Fill in the details below. Fields with <span
                                class="text-red-500">*</span> are required.</p>
                    </div>
                    <div class="hidden sm:flex items-center gap-2">
                        <span
                            class="inline-flex items-center gap-2 rounded-full bg-emerald-600 px-3 py-1 text-xs font-semibold text-white">
                            <span class="h-2 w-2 rounded-full bg-white/90"></span>
                            Employer Portal
                        </span>
                    </div>
                </div>
            </div>

            @include('components.job-post.form', [
                'action' => route('employer.job-postings.store'),
            ])

        </div>
    </div>
@endsection
