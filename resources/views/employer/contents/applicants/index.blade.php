@extends('employer.layout')

@section('content')
    @php
        $status = $status ?? 'all';

        $titleMap = [
            'all' => 'All Applicants',
            'new' => 'New Applicants',
            'pending' => 'Pending Applicants',
            'shortlisted' => 'Shortlisted Applicants',
            'interview' => 'Interview Stage',
            'hired' => 'Hired Applicants',
            'rejected' => 'Rejected Applicants'
        ];

        $statusClasses = function ($s) {
            switch ($s) {
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

    <div class="space-y-6">

        {{-- Header (match Active Posting style) --}}
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
                <i data-lucide="download" class="h-4 w-4"></i>
                Export List
            </a>
        </div>

        {{-- Flash (match Active Posting flash) --}}
        <x-toast type="success" :message="session('success')" />
        <x-toast type="error" :message="session('error')" />





        {{-- Filter Bar (match slate look) --}}
        <div class="flex flex-wrap gap-2">
            @foreach(array_keys($filterLabels) as $filter)
                <a href="{{ route('employer.applicants.index', ['status' => $filter]) }}" class="inline-flex items-center rounded-full px-4 py-2 text-sm font-semibold border transition
                                                      {{ $status == $filter
                ? 'bg-slate-900 text-white border-slate-900'
                : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100' }}">
                    {{ $filterLabels[$filter] }}
                </a>
            @endforeach
        </div>

        {{-- Table container (match Active Posting table container) --}}
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full table-fixed divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="w-[25%] px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase">Applicant
                            </th>
                            <th class="w-[20%] px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase">Applied
                                Position</th>
                            <th class="w-[20%] px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase">Email
                            </th>

                            <th class="w-[10%] px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase">Status
                            </th>
                            <th class="w-[25%] px-6 py-4 text-center text-xs font-semibold text-slate-600 uppercase">Actions
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200">
                        @forelse($candidates as $candidate)
                            @php $cStatus = $candidate->status ?? 'new'; @endphp

                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-5">
                                    <p class="text-sm font-semibold text-slate-900 truncate">
                                        {{ $candidate->user->name ?? 'No Name' }}
                                    </p>
                                    <p class="text-xs text-slate-500 truncate">
                                        Candidate
                                    </p>
                                </td>

                                <td class="px-6 py-5 text-sm text-slate-700">
                                    {{ $candidate->bio ?? 'N/A' }}
                                </td>

                                <td class="px-6 py-5 text-sm text-slate-700 truncate">
                                    {{ $candidate->user->email ?? 'N/A' }}
                                </td>


                                <td class="px-6 py-5">
                                    <span
                                        class="inline-flex items-center justify-center rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses($cStatus) }}">
                                        {{ ucfirst($cStatus) }}
                                    </span>
                                </td>

                                <td class="px-6 py-5">
                                    @php
                                        // Step flow WITHOUT pending
                                        $flow = ['new', 'shortlisted', 'interview', 'hired'];

                                        $idx = array_search($cStatus, $flow, true);
                                        $next = ($idx !== false && isset($flow[$idx + 1])) ? $flow[$idx + 1] : null;

                                        $locked = in_array($cStatus, ['rejected', 'hired'], true);

                                        $canMoveTo = function ($target) use ($next, $locked) {
                                            if ($locked)
                                                return false;
                                            return $target === $next;
                                        };

                                        $canReject = !$locked;
                                    @endphp

                                    <div class="flex justify-center gap-2" x-data="{ open: false }" @click.outside="open=false">

                                        {{-- View --}}
                                        <button type="button"
                                            class="px-3 py-1.5 rounded-xl text-xs font-semibold border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 transition"
                                            @click="alert('Frontend-only: View Applicant details coming soon')">
                                            View
                                        </button>

                                        {{-- Dropdown --}}
                                        {{-- Update Status (MODAL) --}}
                                        <div x-data="{ open:false }">
                                            <button type="button"
                                                class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-semibold border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 transition"
                                                @click="open = true">
                                                Update Status
                                                <i data-lucide="chevron-down" class="h-4 w-4"></i>
                                            </button>

                                            {{-- Modal --}}
                                            <div x-show="open" x-transition.opacity.duration.150ms x-cloak
                                                class="fixed inset-0 z-[80] flex items-center justify-center p-4"
                                                @keydown.escape.window="open=false">

                                                {{-- Backdrop --}}
                                                <div class="absolute inset-0 bg-black/40" @click="open=false"></div>

                                                {{-- Panel --}}
                                                <div class="relative w-full max-w-md rounded-3xl border border-slate-200 bg-white shadow-xl overflow-hidden"
                                                    @click.outside="open=false">

                                                    {{-- Header --}}
                                                    <div class="px-5 py-4 bg-slate-50 border-b border-slate-200">
                                                        <div class="flex items-start justify-between gap-3">
                                                            <div>
                                                                <div class="text-sm font-semibold text-slate-900">Update
                                                                    Applicant Status</div>
                                                                <div class="mt-1 text-xs text-slate-600">
                                                                    Current:
                                                                    <span
                                                                        class="font-bold text-slate-800">{{ ucfirst($cStatus) }}</span>
                                                                </div>
                                                            </div>

                                                            <button type="button"
                                                                class="rounded-xl p-2 text-slate-500 hover:bg-slate-100 hover:text-slate-700 transition"
                                                                @click="open=false">
                                                                <i data-lucide="x" class="h-4 w-4"></i>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    {{-- Body (scrollable) --}}
                                                    <div class="p-4 max-h-[60vh] overflow-y-auto overscroll-contain space-y-2">

                                                        {{-- NEW (initial state) --}}
                                                        <button type="button" disabled
                                                            class="w-full text-left px-4 py-2.5 rounded-2xl text-sm font-semibold bg-slate-50 text-slate-400 cursor-not-allowed">
                                                            New (start)
                                                        </button>

                                                        {{-- SHORTLISTED --}}
                                                        @if($canMoveTo('shortlisted'))
                                                            <form action="{{ route('employer.applicants.shortlist', $candidate) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <button type="submit"
                                                                    class="w-full text-left px-4 py-2.5 rounded-2xl text-sm font-semibold bg-sky-50 text-sky-800 hover:bg-sky-100 transition">
                                                                    Move to Shortlisted
                                                                </button>
                                                            </form>
                                                        @else
                                                            <button type="button" disabled
                                                                class="w-full text-left px-4 py-2.5 rounded-2xl text-sm font-semibold text-slate-400 cursor-not-allowed opacity-70">
                                                                Shortlisted (locked)
                                                            </button>
                                                        @endif

                                                        {{-- INTERVIEW --}}
                                                        @if($canMoveTo('interview'))
                                                            <form action="{{ route('employer.applicants.interview', $candidate) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <button type="submit"
                                                                    class="w-full text-left px-4 py-2.5 rounded-2xl text-sm font-semibold bg-amber-50 text-amber-800 hover:bg-amber-100 transition">
                                                                    Move to Interview
                                                                </button>
                                                            </form>
                                                        @else
                                                            <button type="button" disabled
                                                                class="w-full text-left px-4 py-2.5 rounded-2xl text-sm font-semibold text-slate-400 cursor-not-allowed opacity-70">
                                                                Interview (locked)
                                                            </button>
                                                        @endif

                                                        {{-- HIRED --}}
                                                        @if($canMoveTo('hired'))
                                                            <form action="{{ route('employer.applicants.hire', $candidate) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <button type="submit"
                                                                    class="w-full text-left px-4 py-2.5 rounded-2xl text-sm font-semibold bg-violet-50 text-violet-800 hover:bg-violet-100 transition">
                                                                    Mark as Hired
                                                                </button>
                                                            </form>
                                                        @else
                                                            <button type="button" disabled
                                                                class="w-full text-left px-4 py-2.5 rounded-2xl text-sm font-semibold text-slate-400 cursor-not-allowed opacity-70">
                                                                Hired (locked)
                                                            </button>
                                                        @endif

                                                        <div class="my-2 h-px bg-slate-200"></div>

                                                        {{-- REJECT --}}
                                                        @if($canReject)
                                                            <form action="{{ route('employer.applicants.reject', $candidate) }}"
                                                                method="POST" onsubmit="return confirm('Reject this applicant?');">
                                                                @csrf
                                                                @method('PUT')
                                                                <button type="submit"
                                                                    class="w-full text-left px-4 py-2.5 rounded-2xl text-sm font-semibold bg-rose-50 text-rose-700 hover:bg-rose-100 transition">
                                                                    Reject Applicant
                                                                </button>
                                                            </form>
                                                        @else
                                                            <button type="button" disabled
                                                                class="w-full text-left px-4 py-2.5 rounded-2xl text-sm font-semibold text-slate-400 cursor-not-allowed opacity-70">
                                                                Reject (locked)
                                                            </button>
                                                        @endif
                                                    </div>

                                                    {{-- Footer --}}
                                                    <div class="px-5 py-4 border-t border-slate-200 bg-white flex justify-end">
                                                        <button type="button"
                                                            class="rounded-2xl px-4 py-2 text-sm font-semibold border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 transition"
                                                            @click="open=false">
                                                            Close
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <p class="text-sm font-semibold text-slate-900">No applicants found</p>
                                    <p class="mt-1 text-sm text-slate-500">Try selecting a different status filter.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection