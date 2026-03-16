@extends('employer.layout')

@section('content')
    @php
        $status = $status ?? 'all';

        // ✅ Updated: include "applied" since your DB uses Applied
        $titleMap = [
            'all' => 'All Applicants',
            'applied' => 'Applied Applicants',
            'new' => 'New Applicants',
            'pending' => 'Pending Applicants',
            'shortlisted' => 'Shortlisted Applicants',
            'interview' => 'Interview Stage',
            'hired' => 'Hired Applicants',
            'rejected' => 'Rejected Applicants',
        ];

        // ✅ Updated: status classes now includes applied
        $statusClasses = function ($s) {
            switch ($s) {
                case 'applied':
                case 'new':
                case 'pending':
                    return 'bg-emerald-100 text-emerald-800';
                case 'shortlisted':
                    return 'bg-sky-100 text-sky-800';
                case 'interview':
                    return 'bg-amber-100 text-amber-800';
                case 'hired':
                    return 'bg-violet-100 text-violet-800';
                case 'rejected':
                    return 'bg-rose-100 text-rose-800';
                default:
                    return 'bg-slate-100 text-slate-700';
            }
        };

        // ✅ Updated: add applied tab (since you have Applied status)
        $filterLabels = [
            'all' => 'All',
            'applied' => 'Applied',
            'shortlisted' => 'Shortlisted',
            'interview' => 'Interview',
            'hired' => 'Hired',
            'rejected' => 'Rejected',
        ];
    @endphp

    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-semibold text-slate-900">
                    {{ $titleMap[$status] ?? 'Applicants' }}
                </h1>
                <p class="text-sm text-slate-600 mt-1">
                    Review applicants, update their status, and export your list.
                </p>
            </div>

            <a href="{{ route('employer.applicants.export', ['status' => $status]) }}"
                class="inline-flex items-center justify-center gap-2 rounded-2xl bg-emerald-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-4 focus:ring-emerald-200 transition">
                <x-lucide-icon name="download" class="h-4 w-4" />
                Export List
            </a>
        </div>

        <x-toast type="success" :message="session('success')" />
        <x-toast type="error" :message="session('error')" />

        @include('employer.contents.applicants.profile-views-limit-modal')

        <div class="flex flex-wrap gap-2">
            @foreach (array_keys($filterLabels) as $filter)
                <a href="{{ route('employer.applicants.index', ['status' => $filter]) }}"
                    class="inline-flex items-center rounded-full px-4 py-2 text-sm font-semibold border transition
                               {{ $status == $filter ? 'bg-slate-900 text-white border-slate-900' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100' }}">
                    {{ $filterLabels[$filter] }}
                </a>
            @endforeach
        </div>
        <livewire:employer.applicants.applicants-table :status="$status" :access="$access" />
    </div>
@endsection
