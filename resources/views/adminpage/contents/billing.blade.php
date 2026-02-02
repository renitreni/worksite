@extends('adminpage.layout')
@section('title','Billing')
@section('page_title','Subscription & Payment Management')

@section('content')
@php
  $kpis = [
    ['label'=>'Monthly Revenue','value'=>'₱ 86,420','note'=>'+12.4% vs last month'],
    ['label'=>'Pending Payments','value'=>'4','note'=>'Needs verification'],
    ['label'=>'Active Paid Subs','value'=>'72','note'=>'Pro + Business'],
    ['label'=>'Expired Subs','value'=>'14','note'=>'Restrict access'],
  ];

  $plans = [
    ['name'=>'Free','price'=>'₱0','limit'=>'1 job post / week','badge'=>'Default'],
    ['name'=>'Pro','price'=>'₱799/mo','limit'=>'20 job posts / mo','badge'=>'Popular'],
    ['name'=>'Business','price'=>'₱1,499/mo','limit'=>'Unlimited','badge'=>'Best Value'],
  ];

  $payments = [
    ['employer'=>'TechTalent Hub','plan'=>'Pro','amount'=>'₱799','status'=>'Pending','date'=>'2026-02-02'],
    ['employer'=>'QuickShip PH','plan'=>'Business','amount'=>'₱1,499','status'=>'Completed','date'=>'2026-02-01'],
    ['employer'=>'ACME Corp','plan'=>'Pro','amount'=>'₱799','status'=>'Failed','date'=>'2026-01-31'],
  ];

  $pill = fn($s) => match($s){
    'Completed' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
    'Pending' => 'bg-amber-50 text-amber-700 ring-amber-200',
    'Failed' => 'bg-rose-50 text-rose-700 ring-rose-200',
    default => 'bg-slate-100 text-slate-700 ring-slate-200'
  };
@endphp

<div class="space-y-6">

  {{-- KPIs --}}
  <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
    @foreach($kpis as $k)
      <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="text-sm text-slate-500">{{ $k['label'] }}</div>
        <div class="mt-2 text-3xl font-bold tracking-tight">{{ $k['value'] }}</div>
        <div class="mt-2 text-xs text-slate-500">{{ $k['note'] }}</div>
        <div class="mt-4 h-2 w-full rounded-full bg-slate-100">
          <div class="h-2 w-2/3 rounded-full bg-emerald-600"></div>
        </div>
      </div>
    @endforeach
  </div>

  <div class="grid grid-cols-1 gap-4 xl:grid-cols-5">

    {{-- Plans --}}
    <div class="xl:col-span-2 rounded-2xl border border-slate-200 bg-white shadow-sm">
      <div class="border-b border-slate-200 p-5">
        <div class="text-sm font-semibold">Subscription Plans</div>
        <div class="mt-1 text-xs text-slate-500">Create/edit plans and limits (UI only)</div>
      </div>

      <div class="p-5 space-y-3">
        @foreach($plans as $p)
          <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
            <div class="flex items-start justify-between">
              <div>
                <div class="text-sm font-semibold">{{ $p['name'] }}</div>
                <div class="mt-1 text-xs text-slate-500">{{ $p['limit'] }}</div>
              </div>
              <div class="text-right">
                <div class="text-lg font-bold">{{ $p['price'] }}</div>
                <span class="mt-1 inline-flex rounded-full bg-white px-2 py-0.5 text-[11px] font-semibold text-slate-700 ring-1 ring-slate-200">{{ $p['badge'] }}</span>
              </div>
            </div>

            <div class="mt-4 grid grid-cols-2 gap-2">
              <button class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold hover:bg-slate-50">Edit</button>
              <button class="rounded-xl bg-rose-600 px-3 py-2 text-sm font-semibold text-white hover:bg-rose-700">Delete</button>
            </div>
          </div>
        @endforeach

        <button class="w-full rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
          + Add Plan
        </button>
      </div>
    </div>

    {{-- Payments --}}
    <div class="xl:col-span-3 rounded-2xl border border-slate-200 bg-white shadow-sm">
      <div class="border-b border-slate-200 p-5">
        <div class="text-sm font-semibold">Payments</div>
        <div class="mt-1 text-xs text-slate-500">Track pending, completed, failed payments</div>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
          <thead class="bg-slate-50 text-xs font-semibold text-slate-600">
            <tr>
              <th class="px-5 py-3">Employer</th>
              <th class="px-5 py-3">Plan</th>
              <th class="px-5 py-3">Amount</th>
              <th class="px-5 py-3">Status</th>
              <th class="px-5 py-3">Date</th>
              <th class="px-5 py-3"></th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-200">
            @foreach($payments as $pay)
              <tr class="hover:bg-slate-50">
                <td class="px-5 py-4 font-semibold text-slate-900">{{ $pay['employer'] }}</td>
                <td class="px-5 py-4 text-slate-700">{{ $pay['plan'] }}</td>
                <td class="px-5 py-4 text-slate-700">{{ $pay['amount'] }}</td>
                <td class="px-5 py-4">
                  <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1 {{ $pill($pay['status']) }}">
                    {{ $pay['status'] }}
                  </span>
                </td>
                <td class="px-5 py-4 text-slate-700">{{ $pay['date'] }}</td>
                <td class="px-5 py-4 text-right">
                  <button class="rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-emerald-700">
                    Verify
                  </button>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div class="flex flex-col gap-3 border-t border-slate-200 p-5 sm:flex-row sm:items-center sm:justify-between">
        <div class="text-sm text-slate-600">Reminders</div>
        <div class="flex gap-2">
          <button class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50">
            Send Expiry Reminders
          </button>
          <button class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
            Approve Subscription
          </button>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection
