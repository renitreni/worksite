@extends('employer.layout')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-semibold text-slate-900">Active Job Postings</h1>
            <p class="text-sm text-slate-600 mt-1">Manage your open jobs and track applicants.</p>
        </div>

        <a href="{{ route('employer.job-postings.create', ['from' => 'listing']) }}"
           class="inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-4 focus:ring-emerald-200 transition">
            + Post New Job
        </a>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    {{-- Table --}}
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full table-fixed divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="w-[30%] px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase">Job</th>
                    <th class="w-[15%] px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase">Country</th>
                    <th class="w-[15%] px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase">Posted</th>
                    <th class="w-[10%] px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase">Applicants</th>
                    <th class="w-[10%] px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase">Status</th>
                    <th class="w-[20%] px-6 py-4 text-center text-xs font-semibold text-slate-600 uppercase">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-200">
                @forelse($jobs as $job)
                    <tr class="hover:bg-slate-50 transition">
                        {{-- Job --}}
                        <td class="px-6 py-5">
                            <p class="text-sm font-semibold text-slate-900 truncate">
                                {{ $job->title }}
                            </p>
                            <p class="text-xs text-slate-500 truncate">
                                {{ $job->industry ?? 'Job Posting' }}
                            </p>
                        </td>

                        {{-- Country --}}
                        <td class="px-6 py-5 text-sm text-slate-700">
                            {{ $job->country ?? '—' }}
                        </td>

                        {{-- Posted --}}
                        <td class="px-6 py-5 text-sm text-slate-700">
                            {{ ($job->posted_at ?? $job->created_at)->format('M d, Y') }}
                        </td>

                        {{-- Applicants --}}
                        <td class="px-6 py-5">
                            <span class="inline-flex items-center justify-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                                {{ $job->applications()->count() ?? 0 }}
                            </span>
                        </td>

                        {{-- Status --}}
                        <td class="px-6 py-5">
                            <span class="inline-flex items-center justify-center rounded-full px-3 py-1 text-xs font-semibold
                                {{ $job->status === 'open' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                {{ ucfirst($job->status) }}
                            </span>
                        </td>

                        {{-- Actions --}}
                        <td class="px-6 py-5">
                            <div class="flex justify-center gap-2">

                                <a href="{{ route('employer.job-postings.show', $job->id) }}"
                                   class="px-3 py-1.5 rounded-xl text-xs font-semibold border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 transition">
                                    View
                                </a>

                                <a href="{{ route('employer.job-postings.edit', $job->id) }}"
                                   class="px-3 py-1.5 rounded-xl text-xs font-semibold border border-emerald-200 bg-emerald-50 text-emerald-800 hover:bg-emerald-100 transition">
                                    Edit
                                </a>

                                <form action="{{ route('employer.job-postings.destroy', $job->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Close this job posting?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="px-3 py-1.5 rounded-xl text-xs font-semibold border border-rose-200 bg-rose-50 text-rose-700 hover:bg-rose-100 transition">
                                        Close
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <p class="text-sm font-semibold text-slate-900">No active jobs found</p>
                            <p class="mt-1 text-sm text-slate-500">Create your first job posting to start receiving applications.</p>

                            <a href="{{ route('employer.job-postings.create', ['from' => 'listing']) }}"
                               class="mt-5 inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-6 py-3 text-sm font-semibold text-white hover:bg-emerald-700 focus:outline-none focus:ring-4 focus:ring-emerald-200 transition">
                                + Post New Job
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
