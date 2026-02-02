@extends('adminpage.layout')
@section('title','Reports')
@section('page_title','Generate Reports')

@section('content')
@php
  $reportRows = [
    ['metric'=>'New users','value'=>'124'],
    ['metric'=>'Active employers','value'=>'128'],
    ['metric'=>'Jobs posted','value'=>'437'],
    ['metric'=>'Applications submitted','value'=>'1,902'],
  ];

  $preview = [
    ['date'=>'2026-02-02','type'=>'Jobs','detail'=>'28 new posts pending review'],
    ['date'=>'2026-02-01','type'=>'Billing','detail'=>'4 pending payments'],
    ['date'=>'2026-01-31','type'=>'Users','detail'=>'2 suspensions (expired subs)'],
  ];
@endphp

<div class="space-y-6">

  {{-- Builder --}}
  <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
      <div>
        <div class="text-sm font-semibold">Report Builder</div>
        <div class="mt-1 text-xs text-slate-500">Select report type and date range, then generate</div>
      </div>

      <div class="flex flex-col gap-2 sm:flex-row">
        <select class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100">
          <option>User Activity</option>
          <option>Job Postings</option>
          <option>Subscriptions & Revenue</option>
          <option>Applications & Hires</option>
        </select>

        <input type="date" class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100" />
        <input type="date" class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100" />

        <button class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
          Generate
        </button>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 gap-4 xl:grid-cols-5">

    {{-- Summary --}}
    <div class="xl:col-span-2 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
      <div class="text-sm font-semibold">Summary</div>
      <div class="mt-1 text-xs text-slate-500">Key results (sample)</div>

      <div class="mt-5 space-y-3">
        @foreach($reportRows as $r)
          <div class="flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
            <div class="text-sm font-semibold text-slate-800">{{ $r['metric'] }}</div>
            <div class="text-sm font-bold text-slate-900">{{ $r['value'] }}</div>
          </div>
        @endforeach
      </div>

      <div class="mt-5 grid grid-cols-2 gap-2">
        <button class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50">Export PDF</button>
        <button class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50">Export Excel</button>
      </div>
    </div>

    {{-- Preview --}}
    <div class="xl:col-span-3 rounded-2xl border border-slate-200 bg-white shadow-sm">
      <div class="border-b border-slate-200 p-5">
        <div class="text-sm font-semibold">Preview</div>
        <div class="mt-1 text-xs text-slate-500">Sample rows (wire real data later)</div>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
          <thead class="bg-slate-50 text-xs font-semibold text-slate-600">
            <tr>
              <th class="px-5 py-3">Date</th>
              <th class="px-5 py-3">Type</th>
              <th class="px-5 py-3">Details</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-200">
            @foreach($preview as $p)
              <tr class="hover:bg-slate-50">
                <td class="px-5 py-4 text-slate-700">{{ $p['date'] }}</td>
                <td class="px-5 py-4 font-semibold text-slate-900">{{ $p['type'] }}</td>
                <td class="px-5 py-4 text-slate-700">{{ $p['detail'] }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div class="border-t border-slate-200 p-5">
        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
          <div class="text-sm font-semibold">Notes</div>
          <div class="mt-1 text-xs text-slate-500">Add notes to include in exports</div>
          <textarea class="mt-3 h-24 w-full rounded-xl border border-slate-200 bg-white p-3 text-sm focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100"
                    placeholder="Write notes here…"></textarea>
        </div>
      </div>

    </div>
  </div>
</div>
@endsection
