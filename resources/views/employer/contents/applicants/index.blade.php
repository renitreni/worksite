@extends('employer.layout')

@section('content')
@php
    $status = $status ?? 'all';

    // Page titles per status
    $titleMap = [
        'all' => 'All Applicants',
        'new' => 'New Applicants',
        'pending' => 'Pending Applicants',
        'shortlisted' => 'Shortlisted Applicants',
        'interview' => 'Interview Stage',
        'hired' => 'Hired Applicants',
        'rejected' => 'Rejected Applicants'
    ];
    // Status badge classes
    $statusClasses = function($s) {
        switch($s) {
            case 'new':
            case 'pending':
                return 'bg-emerald-100 text-emerald-700';
            case 'shortlisted':
                return 'bg-blue-100 text-blue-700';
            case 'interview':
                return 'bg-yellow-100 text-yellow-700';
            case 'hired':
                return 'bg-purple-100 text-purple-700';
            case 'rejected':
                return 'bg-red-100 text-red-700';
            default:
                return 'bg-gray-100 text-gray-700';
        }
    };
    // Filter labels (optional)
    $filterLabels = [
        'all' => 'All',
        'new' => 'New',
        'pending' => 'Pending',
        'shortlisted' => 'Shortlisted',
        'interview' => 'Interview',
        'hired' => 'Hired',
        'rejected' => 'Rejected'
    ];
@endphp

{{-- Flash message for status updates --}}
@if(session('success'))
    <div 
        x-data="{ show: true }" 
        x-show="show" 
        x-init="setTimeout(() => show = false, 4000)" 
        class="mb-4 px-4 py-2 rounded-lg bg-green-100 text-green-800 border border-green-200"
    >
        {{ session('success') }}
    </div>
@endif

<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-semibold text-gray-900">{{ $titleMap[$status] ?? 'Applicants' }}</h1>
    <a href="{{ route('employer.applicants.export', ['status' => $status]) }}"
        class="inline-flex items-center gap-2 rounded-2xl border border-emerald-500 bg-emerald-500 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-600 transition">
        <i data-lucide="download" class="h-4 w-4"></i>
        Export List
    </a>
</div>

{{-- Filter Bar --}}
<div class="mb-4 flex gap-3">
    @foreach(array_keys($filterLabels) as $filter)
        <a href="{{ route('employer.applicants.index', ['status' => $filter]) }}"
           class="px-3 py-1 rounded-full text-sm font-medium border 
                  {{ $status == $filter 
                      ? 'bg-gray-800 text-white border-gray-800' 
                      : 'bg-gray-100 text-gray-700 border-gray-200' }}">
           {{ $filterLabels[$filter] }}
        </a>
    @endforeach
</div>

<div class="overflow-x-auto bg-white rounded-xl shadow border border-gray-200">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applicant Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applied Position</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
        @forelse($candidates as $candidate)
            @php
                $cStatus = $candidate->status ?? 'new';
            @endphp
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ $candidate->user->name ?? 'No Name' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $candidate->bio ?? 'N/A' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $candidate->user->email ?? 'N/A' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $candidate->contact_e164 ?? 'N/A' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold {{ $statusClasses($cStatus) }}">
                        {{ ucfirst($cStatus) }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-center flex justify-center gap-2">
                    {{-- View placeholder --}}
                    <button class="text-blue-600 hover:underline cursor-pointer"
                            @click="alert('Frontend-only: View Applicant details coming soon')">View</button>

                    @if($cStatus === 'new' || $cStatus === 'pending')
                        {{-- Shortlist --}}
                        <form action="{{ route('employer.applicants.shortlist', $candidate) }}" method="POST" class="inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="text-green-600 hover:underline cursor-pointer">Shortlist</button>
                        </form>
                        {{-- Reject --}}
                        <form action="{{ route('employer.applicants.reject', $candidate) }}" method="POST" class="inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="text-red-600 hover:underline cursor-pointer">Reject</button>
                        </form>

                    @elseif($cStatus === 'shortlisted')
                        {{-- Move to Interview --}}
                        <form action="{{ route('employer.applicants.interview', $candidate) }}" method="POST" class="inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="text-yellow-600 hover:underline cursor-pointer">Interview</button>
                        </form>
                        {{-- Reject --}}
                        <form action="{{ route('employer.applicants.reject', $candidate) }}" method="POST" class="inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="text-red-600 hover:underline cursor-pointer">Reject</button>
                        </form>

                    @elseif($cStatus === 'interview')
                        {{-- Hire --}}
                        <form action="{{ route('employer.applicants.hire', $candidate) }}" method="POST" class="inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="text-purple-600 hover:underline cursor-pointer">Hire</button>
                        </form>
                        {{-- Reject --}}
                        <form action="{{ route('employer.applicants.reject', $candidate) }}" method="POST" class="inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="text-red-600 hover:underline cursor-pointer">Reject</button>
                        </form>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center py-4 text-gray-500">No applicants found.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
