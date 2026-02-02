@extends('adminpage.layout')
@section('title','Job Postings')
@section('page_title','Manage Job Postings')

@section('content')
@php
  $queue = [
    ['title'=>'Senior Full Stack Developer','company'=>'TechTalent Hub','status'=>'Pending','location'=>'Santa Rosa, Laguna','pay'=>'₱60k–₱120k','date'=>'2026-02-02'],
    ['title'=>'Registered Nurse','company'=>'Global Workforce Solutions','status'=>'Active','location'=>'Parañaque, Metro Manila','pay'=>'₱35k–₱55k','date'=>'2026-01-28'],
    ['title'=>'Civil Engineer','company'=>'Qatar Construction Group','status'=>'Pending','location'=>'Doha (Remote Posting)','pay'=>'₱4.2k–₱6k/mo','date'=>'2026-02-01'],
    ['title'=>'Customer Support Agent','company'=>'QuickShip PH','status'=>'Flagged','location'=>'Taguig, Metro Manila','pay'=>'₱22k–₱30k','date'=>'2026-01-30'],
  ];

  $pill = fn($s) => match($s){
    'Active' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
    'Pending' => 'bg-amber-50 text-amber-700 ring-amber-200',
    'Flagged' => 'bg-rose-50 text-rose-700 ring-rose-200',
    'Closed' => 'bg-slate-100 text-slate-700 ring-slate-200',
    default => 'bg-slate-100 text-slate-700 ring-slate-200'
  };
@endphp

<div class="space-y-6">

  {{-- Filters --}}
  <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
      <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
        <div class="flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2">
          <span class="text-slate-400">⌕</span>
          <input class="w-72 bg-transparent text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none"
                 placeholder="Search job title or company…" />
        </div>

        <select class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100">
          <option>All Status</option>
          <option>Pending</option>
          <option>Active</option>
          <option>Flagged</option>
          <option>Closed</option>
        </select>

        <select class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100">
          <option>Newest First</option>
          <option>Oldest First</option>
        </select>
      </div>

      <div class="flex gap-2">
        <button class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50">
          Export
        </button>
        <button class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
          Review Queue
        </button>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 gap-4 xl:grid-cols-5">

    {{-- Queue list --}}
    <div class="xl:col-span-3 rounded-2xl border border-slate-200 bg-white shadow-sm">
      <div class="border-b border-slate-200 p-5">
        <div class="text-sm font-semibold">Job Review Queue</div>
        <div class="mt-1 text-xs text-slate-500">Approve, reject, remove fake/expired or invalid postings</div>
      </div>

      <div class="divide-y divide-slate-200">
        @foreach($queue as $q)
          <div class="p-5 hover:bg-slate-50">
            <div class="flex items-start justify-between gap-3">
              <div>
                <div class="text-base font-semibold text-slate-900">{{ $q['title'] }}</div>
                <div class="mt-1 text-sm text-slate-600">{{ $q['company'] }}</div>

                <div class="mt-3 flex flex-wrap gap-2 text-xs text-slate-600">
                  <span class="rounded-full bg-slate-50 px-3 py-1 ring-1 ring-slate-200">📍 {{ $q['location'] }}</span>
                  <span class="rounded-full bg-slate-50 px-3 py-1 ring-1 ring-slate-200">💰 {{ $q['pay'] }}</span>
                  <span class="rounded-full bg-slate-50 px-3 py-1 ring-1 ring-slate-200">🗓 {{ $q['date'] }}</span>
                </div>
              </div>

              <div class="text-right">
                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1 {{ $pill($q['status']) }}">
                  {{ $q['status'] }}
                </span>
                <div class="mt-3 flex gap-2 justify-end">
                  <button class="rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-emerald-700">
                    Approve
                  </button>
                  <button class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold hover:bg-slate-50">
                    Reject
                  </button>
                  <button class="rounded-lg bg-rose-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-rose-700">
                    Remove
                  </button>
                </div>
              </div>
            </div>

            <div class="mt-4 grid grid-cols-1 gap-2 sm:grid-cols-3">
              <div class="rounded-xl border border-slate-200 bg-white p-3">
                <div class="text-xs text-slate-500">Compliance</div>
                <div class="mt-1 text-sm font-semibold">Needs review</div>
              </div>
              <div class="rounded-xl border border-slate-200 bg-white p-3">
                <div class="text-xs text-slate-500">Reports</div>
                <div class="mt-1 text-sm font-semibold">0</div>
              </div>
              <div class="rounded-xl border border-slate-200 bg-white p-3">
                <div class="text-xs text-slate-500">Applicant Count</div>
                <div class="mt-1 text-sm font-semibold">—</div>
              </div>
            </div>

          </div>
        @endforeach
      </div>
    </div>

    {{-- Job detail preview --}}
    <div class="xl:col-span-2 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
      <div class="text-sm font-semibold">Job Details</div>
      <div class="mt-1 text-xs text-slate-500">Preview panel (wire click later)</div>

      <div class="mt-5 rounded-2xl border border-slate-200 bg-slate-50 p-4">
        <div class="text-base font-semibold">Senior Full Stack Developer</div>
        <div class="mt-1 text-sm text-slate-600">TechTalent Hub</div>

        <div class="mt-4 space-y-3 text-sm text-slate-700">
          <div class="rounded-xl bg-white p-3 ring-1 ring-slate-200">
            <div class="text-xs text-slate-500">Summary</div>
            <div class="mt-1">Build and maintain web systems, collaborate with cross-functional teams, ensure code quality.</div>
          </div>

          <div class="rounded-xl bg-white p-3 ring-1 ring-slate-200">
            <div class="text-xs text-slate-500">Requirements</div>
            <ul class="mt-2 list-disc pl-5 space-y-1">
              <li>Laravel / PHP experience</li>
              <li>MySQL and REST API</li>
              <li>Frontend fundamentals</li>
            </ul>
          </div>

          <div class="rounded-xl bg-white p-3 ring-1 ring-slate-200">
            <div class="text-xs text-slate-500">Risk Checks</div>
            <div class="mt-2 flex flex-wrap gap-2">
              <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-200">Company verified</span>
              <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700 ring-1 ring-amber-200">Salary review</span>
              <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700 ring-1 ring-slate-200">Location check</span>
            </div>
          </div>
        </div>
      </div>

      <div class="mt-5 space-y-2">
        <button class="w-full rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Approve</button>
        <button class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50">Reject</button>
        <button class="w-full rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700">Remove</button>
      </div>

      <div class="mt-5 rounded-2xl border border-slate-200 p-4">
        <div class="text-sm font-semibold">Admin Note</div>
        <textarea class="mt-2 h-24 w-full rounded-xl border border-slate-200 bg-white p-3 text-sm focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100"
                  placeholder="Add internal notes for this posting…"></textarea>
        <button class="mt-3 w-full rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50">Save</button>
      </div>
    </div>

  </div>
</div>
@endsection
