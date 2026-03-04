@extends('adminpage.layout') {{-- change if your layout path differs --}}

@section('content')
<div class="px-6 py-5">

    {{-- Page Title --}}
    <div class="mb-4">
        <p class="text-xs text-gray-500">Administrator</p>
        <h1 class="text-xl font-semibold text-gray-900">Reports</h1>
    </div>

    {{-- Filters Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <form method="POST" action="{{ route('admin.reports.generate') }}" class="flex flex-col gap-4">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div class="md:col-span-2">
                    <label class="text-xs text-gray-600">Report Type</label>
                    <select name="type"
                        class="mt-1 w-full rounded-xl border-gray-200 focus:border-emerald-500 focus:ring-emerald-500">
                        @php($type = old('type', $filters['type'] ?? 'user_activity'))
                        <option value="user_activity" @selected($type==='user_activity')>User activity (registered users, active employers)</option>
                        <option value="job_postings" @selected($type==='job_postings')>Job postings (active, pending, removed)</option>
                        <option value="revenue" @selected($type==='revenue')>Subscription & revenue reports</option>
                        <option value="applications_hires" @selected($type==='applications_hires')>Applications & hires per job</option>
                        <option value="applications_detailed" @selected($type==='applications_detailed')>Applications detailed (candidate + job + agency + date)</option>
                    </select>
                    @error('type') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-xs text-gray-600">Date From</label>
                    <input type="date" name="date_from"
                        value="{{ old('date_from', $filters['date_from'] ?? '') }}"
                        class="mt-1 w-full rounded-xl border-gray-200 focus:border-emerald-500 focus:ring-emerald-500">
                    @error('date_from') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-xs text-gray-600">Date To</label>
                    <input type="date" name="date_to"
                        value="{{ old('date_to', $filters['date_to'] ?? '') }}"
                        class="mt-1 w-full rounded-xl border-gray-200 focus:border-emerald-500 focus:ring-emerald-500">
                    @error('date_to') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="inline-flex items-center justify-center px-6 py-2.5 rounded-xl bg-emerald-600 text-white font-semibold hover:bg-emerald-700 transition">
                    Generate
                </button>
            </div>
        </form>
    </div>

    {{-- Results --}}
    @isset($reportData)
        <div class="mt-6 flex items-start justify-between gap-4">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">{{ $reportData['title'] ?? 'Report' }}</h2>
                @if(isset($reportData['filters']['from'], $reportData['filters']['to']))
                    <p class="text-sm text-gray-500">
                        {{ \Carbon\Carbon::parse($reportData['filters']['from'])->format('M d, Y') }}
                        —
                        {{ \Carbon\Carbon::parse($reportData['filters']['to'])->format('M d, Y') }}
                    </p>
                @endif
            </div>

            <div class="flex gap-2">
                {{-- keep filters when exporting --}}
                @php($exportParams = $filters ?? request()->only(['type','date_from','date_to']))

                <a href="{{ route('admin.reports.export.excel', $exportParams) }}"
                   class="px-4 py-2 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 text-sm font-medium">
                    Export Excel
                </a>
                <a href="{{ route('admin.reports.export.pdf', $exportParams) }}"
                   class="px-4 py-2 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 text-sm font-medium">
                    Export PDF
                </a>
            </div>
        </div>

        {{-- Summary Cards --}}
        @if(!empty($reportData['summary']))
            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($reportData['summary'] as $card)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                        <p class="text-xs text-gray-500">{{ $card['label'] }}</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $card['value'] }}</p>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Table Card --}}
        <div class="mt-4 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 flex items-center justify-between border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">Results</h3>
                <p class="text-xs text-gray-500">
                    Total: {{ isset($reportData['rows']) ? count($reportData['rows']) : 0 }}
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            @foreach(($reportData['columns'] ?? []) as $col)
                                <th class="text-left px-5 py-3 font-semibold text-gray-700 whitespace-nowrap">
                                    {{ $col }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse(($reportData['rows'] ?? []) as $row)
                            <tr class="hover:bg-gray-50">
                                @foreach($row as $cell)
                                    <td class="px-5 py-3 text-gray-700 whitespace-nowrap">
                                        {{ $cell }}
                                    </td>
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td class="px-5 py-6 text-gray-500" colspan="{{ count($reportData['columns'] ?? []) }}">
                                    No data found for this range.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endisset

</div>
@endsection