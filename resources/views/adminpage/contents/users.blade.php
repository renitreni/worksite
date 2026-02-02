@extends('adminpage.layout')
@section('title','Users')
@section('page_title','Manage Users')

@section('content')
@php
  $stats = [
    ['label'=>'Total Users','value'=>'2,314'],
    ['label'=>'Active Employers','value'=>'128'],
    ['label'=>'Pending Employer Approvals','value'=>'6'],
    ['label'=>'Suspended (Billing)','value'=>'14'],
  ];

  $users = [
    ['name'=>'John Doe','email'=>'john@example.com','role'=>'Candidate','status'=>'Active','sub'=>'—','joined'=>'2026-01-20'],
    ['name'=>'ACME Corp','email'=>'hr@acme.com','role'=>'Employer','status'=>'Pending','sub'=>'Free','joined'=>'2026-02-01'],
    ['name'=>'QuickShip PH','email'=>'admin@quickship.ph','role'=>'Employer','status'=>'Active','sub'=>'Pro','joined'=>'2026-01-05'],
    ['name'=>'Maria Santos','email'=>'maria@gmail.com','role'=>'Candidate','status'=>'Active','sub'=>'—','joined'=>'2026-01-11'],
    ['name'=>'TechTalent Hub','email'=>'ops@techtalent.io','role'=>'Employer','status'=>'Expired','sub'=>'Pro','joined'=>'2025-12-29'],
  ];

  $statusPill = fn($s) => match($s){
    'Active' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
    'Pending' => 'bg-amber-50 text-amber-700 ring-amber-200',
    'Suspended' => 'bg-rose-50 text-rose-700 ring-rose-200',
    'Expired' => 'bg-slate-100 text-slate-700 ring-slate-200',
    default => 'bg-slate-100 text-slate-700 ring-slate-200'
  };
@endphp

<div class="space-y-6">

  {{-- Top quick stats --}}
  <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
    @foreach($stats as $s)
      <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="text-sm text-slate-500">{{ $s['label'] }}</div>
        <div class="mt-2 text-3xl font-bold tracking-tight text-slate-900">{{ $s['value'] }}</div>
        <div class="mt-3 h-2 w-full rounded-full bg-slate-100">
          <div class="h-2 w-2/3 rounded-full bg-emerald-600"></div>
        </div>
      </div>
    @endforeach
  </div>

  {{-- Controls --}}
  <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
      <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
        <div class="flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2">
          <span class="text-slate-400">⌕</span>
          <input class="w-64 bg-transparent text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none"
                 placeholder="Search name or email…" />
        </div>

        <select class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100">
          <option>All Roles</option>
          <option>Candidate</option>
          <option>Employer</option>
        </select>

        <select class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100">
          <option>All Status</option>
          <option>Active</option>
          <option>Pending</option>
          <option>Suspended</option>
          <option>Expired</option>
        </select>
      </div>

      <div class="flex gap-2">
        <button class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50">
          Export
        </button>
        <button class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
          + Add User
        </button>
      </div>
    </div>
  </div>

  {{-- Table + details panel layout --}}
  <div class="grid grid-cols-1 gap-4 xl:grid-cols-5">

    {{-- Users table --}}
    <div class="xl:col-span-3 rounded-2xl border border-slate-200 bg-white shadow-sm">
      <div class="border-b border-slate-200 p-5">
        <div class="text-sm font-semibold">User List</div>
        <div class="mt-1 text-xs text-slate-500">Activate, approve employers, monitor subscription status</div>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
          <thead class="bg-slate-50 text-xs font-semibold text-slate-600">
            <tr>
              <th class="px-5 py-3">User</th>
              <th class="px-5 py-3">Role</th>
              <th class="px-5 py-3">Status</th>
              <th class="px-5 py-3">Subscription</th>
              <th class="px-5 py-3">Joined</th>
              <th class="px-5 py-3"></th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-200">
            @foreach($users as $u)
              <tr class="hover:bg-slate-50">
                <td class="px-5 py-4">
                  <div class="font-semibold text-slate-900">{{ $u['name'] }}</div>
                  <div class="text-xs text-slate-500">{{ $u['email'] }}</div>
                </td>
                <td class="px-5 py-4 text-slate-700">{{ $u['role'] }}</td>
                <td class="px-5 py-4">
                  <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1 {{ $statusPill($u['status']) }}">
                    {{ $u['status'] }}
                  </span>
                </td>
                <td class="px-5 py-4 text-slate-700">{{ $u['sub'] }}</td>
                <td class="px-5 py-4 text-slate-700">{{ $u['joined'] }}</td>
                <td class="px-5 py-4 text-right">
                  <button class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold hover:bg-slate-50">
                    View
                  </button>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div class="flex items-center justify-between border-t border-slate-200 p-4 text-sm text-slate-600">
        <span>Showing 1–5 of 2,314</span>
        <div class="flex gap-2">
          <button class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm font-semibold hover:bg-slate-50">Prev</button>
          <button class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm font-semibold hover:bg-slate-50">Next</button>
        </div>
      </div>
    </div>

    {{-- Details panel (UI only) --}}
    <div class="xl:col-span-2 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
      <div class="flex items-start justify-between">
        <div>
          <div class="text-sm font-semibold">Selected User</div>
          <div class="mt-1 text-xs text-slate-500">Preview + actions (wire later)</div>
        </div>
        <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-200">
          Active
        </span>
      </div>

      <div class="mt-5 rounded-2xl border border-slate-200 bg-slate-50 p-4">
        <div class="text-sm font-semibold text-slate-900">QuickShip PH</div>
        <div class="mt-1 text-xs text-slate-600">admin@quickship.ph</div>

        <div class="mt-4 grid grid-cols-2 gap-3 text-sm">
          <div class="rounded-xl bg-white p-3 ring-1 ring-slate-200">
            <div class="text-xs text-slate-500">Role</div>
            <div class="mt-1 font-semibold">Employer</div>
          </div>
          <div class="rounded-xl bg-white p-3 ring-1 ring-slate-200">
            <div class="text-xs text-slate-500">Plan</div>
            <div class="mt-1 font-semibold">Pro</div>
          </div>
          <div class="rounded-xl bg-white p-3 ring-1 ring-slate-200">
            <div class="text-xs text-slate-500">Jobs Posted</div>
            <div class="mt-1 font-semibold">12</div>
          </div>
          <div class="rounded-xl bg-white p-3 ring-1 ring-slate-200">
            <div class="text-xs text-slate-500">Payment Status</div>
            <div class="mt-1 font-semibold">Completed</div>
          </div>
        </div>
      </div>

      <div class="mt-5 space-y-2">
        <button class="w-full rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
          Activate / Approve
        </button>
        <button class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50">
          Suspend (Expired/Payment Issue)
        </button>
        <button class="w-full rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700">
          Deactivate
        </button>
      </div>

      <div class="mt-5 rounded-2xl border border-slate-200 p-4">
        <div class="text-sm font-semibold">Notes</div>
        <textarea class="mt-2 h-24 w-full rounded-xl border border-slate-200 bg-white p-3 text-sm focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100"
                  placeholder="Add internal notes…"></textarea>
        <button class="mt-3 w-full rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50">
          Save Notes
        </button>
      </div>
    </div>

  </div>
</div>
@endsection
