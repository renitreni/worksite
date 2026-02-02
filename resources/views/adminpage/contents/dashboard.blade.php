@extends('adminpage.layout')
@section('title','Dashboard')
@section('page_title','Dashboard Overview')

@section('content')
@php
  // sample data (replace later with DB)
  $kpis = [
    ['label'=>'Total Users', 'value'=>'2,314', 'delta'=>'+6.2%', 'hint'=>'vs last 7 days'],
    ['label'=>'Jobs (Active)', 'value'=>'312', 'delta'=>'+3.1%', 'hint'=>'active now'],
    ['label'=>'Jobs (Pending)', 'value'=>'28', 'delta'=>'+9', 'hint'=>'needs review'],
    ['label'=>'Active Employers', 'value'=>'128', 'delta'=>'+2', 'hint'=>'free + paid'],
    ['label'=>'Revenue', 'value'=>'₱ 86,420', 'delta'=>'+12.4%', 'hint'=>'this month'],
    ['label'=>'Expired Subs', 'value'=>'14', 'delta'=>'+4', 'hint'=>'action needed'],
  ];

  $jobsBreakdown = [
    ['label'=>'Active', 'value'=>312],
    ['label'=>'Pending', 'value'=>28],
    ['label'=>'Closed', 'value'=>97],
    ['label'=>'Removed', 'value'=>16],
  ];

  $actions = [
    ['title'=>'Approve Employers', 'count'=>6, 'desc'=>'New employer registrations pending review', 'btn'=>'Review'],
    ['title'=>'Review Job Posts', 'count'=>28, 'desc'=>'Jobs waiting for approval/rejection', 'btn'=>'Open Queue'],
    ['title'=>'Verify Payments', 'count'=>4, 'desc'=>'Pending payments to activate subscriptions', 'btn'=>'Verify'],
  ];

  $activity = [
    ['who'=>'ACME Corp', 'what'=>'submitted a new job post', 'when'=>'2m ago', 'tag'=>'Jobs'],
    ['who'=>'QuickShip PH', 'what'=>'payment marked as completed', 'when'=>'14m ago', 'tag'=>'Billing'],
    ['who'=>'Mark Reyes', 'what'=>'account suspended (expired subscription)', 'when'=>'1h ago', 'tag'=>'Users'],
    ['who'=>'TechTalent Hub', 'what'=>'job post approved', 'when'=>'3h ago', 'tag'=>'Jobs'],
  ];

  // fake revenue bars (0-60 range)
  $revenueBars = [18,22,15,28,30,24,40,35,46,39,52,48];

  // helper: map "percentage-ish" value to Tailwind height classes (no inline styles)
  $barHeight = function(int $b): string {
    return match(true) {
      $b >= 55 => 'h-36',
      $b >= 50 => 'h-32',
      $b >= 45 => 'h-28',
      $b >= 40 => 'h-24',
      $b >= 35 => 'h-20',
      $b >= 30 => 'h-16',
      $b >= 25 => 'h-14',
      $b >= 20 => 'h-12',
      $b >= 15 => 'h-10',
      default  => 'h-8',
    };
  };

  // helper: map percentage to Tailwind width classes (no inline styles)
  $barWidth = function(int $pct): string {
    return match(true) {
      $pct >= 95 => 'w-[95%]',
      $pct >= 90 => 'w-[90%]',
      $pct >= 80 => 'w-4/5',
      $pct >= 75 => 'w-3/4',
      $pct >= 66 => 'w-2/3',
      $pct >= 50 => 'w-1/2',
      $pct >= 40 => 'w-2/5',
      $pct >= 33 => 'w-1/3',
      $pct >= 25 => 'w-1/4',
      $pct >= 20 => 'w-1/5',
      $pct >= 10 => 'w-[10%]',
      default  => 'w-[5%]',
    };
  };
@endphp

<div class="space-y-6">

  {{-- Top KPI row --}}
  <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
    @foreach($kpis as $k)
      <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="flex items-start justify-between gap-3">
          <div>
            <p class="text-sm text-slate-500">{{ $k['label'] }}</p>
            <p class="mt-2 text-3xl font-bold tracking-tight">{{ $k['value'] }}</p>
          </div>
          <div class="text-right">
            <div class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-100">
              {{ $k['delta'] }}
            </div>
            <div class="mt-1 text-[11px] text-slate-500">{{ $k['hint'] }}</div>
          </div>
        </div>

        <div class="mt-4 h-2 w-full rounded-full bg-slate-100">
          <div class="h-2 w-2/3 rounded-full bg-emerald-600"></div>
        </div>
      </div>
    @endforeach
  </div>

  {{-- Analytics row --}}
  <div class="grid grid-cols-1 gap-4 xl:grid-cols-5">

    {{-- Revenue trend --}}
    <div class="xl:col-span-3 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
      <div class="flex items-start justify-between gap-3">
        <div>
          <div class="text-sm font-semibold">Revenue Trend</div>
          <div class="mt-1 text-xs text-slate-500">Monthly revenue (placeholder visualization)</div>
        </div>
        <div class="flex gap-2">
          <button class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold hover:bg-slate-50">Last 12 mo</button>
          <button class="rounded-xl bg-emerald-600 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-700">This year</button>
        </div>
      </div>

      <div class="mt-5">
        <div class="flex h-40 items-end gap-2 rounded-2xl border border-slate-200 bg-slate-50 p-4">
          @foreach($revenueBars as $b)
            <div class="flex-1 rounded-lg bg-emerald-600/80 {{ $barHeight((int)$b) }}"></div>
          @endforeach
        </div>
        <div class="mt-3 flex items-center justify-between text-xs text-slate-500">
          <span>Jan</span><span>Mar</span><span>May</span><span>Jul</span><span>Sep</span><span>Dec</span>
        </div>
      </div>
    </div>

    {{-- Jobs breakdown --}}
    <div class="xl:col-span-2 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
      <div class="text-sm font-semibold">Jobs Breakdown</div>
      <div class="mt-1 text-xs text-slate-500">Distribution by status</div>

      @php
        $totalJobs = array_sum(array_map(fn($x)=>$x['value'], $jobsBreakdown));
      @endphp

      <div class="mt-5 space-y-3">
        @foreach($jobsBreakdown as $j)
          @php
            $pct = $totalJobs ? (int) round(($j['value']/$totalJobs)*100) : 0;
            $wClass = $barWidth($pct);
          @endphp
          <div>
            <div class="flex items-center justify-between text-sm">
              <span class="font-semibold text-slate-800">{{ $j['label'] }}</span>
              <span class="text-slate-600">{{ $j['value'] }} ({{ $pct }}%)</span>
            </div>
            <div class="mt-2 h-2 w-full rounded-full bg-slate-100">
              <div class="h-2 rounded-full bg-emerald-600 {{ $wClass }}"></div>
            </div>
          </div>
        @endforeach
      </div>

      <a href="{{ route('admin.jobs') }}"
         class="mt-5 inline-flex w-full items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50">
        Go to Job Queue
      </a>
    </div>
  </div>

  {{-- Action center + Activity feed --}}
  <div class="grid grid-cols-1 gap-4 xl:grid-cols-5">
    <div class="xl:col-span-3 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
      <div class="flex items-start justify-between">
        <div>
          <div class="text-sm font-semibold">Action Center</div>
          <div class="mt-1 text-xs text-slate-500">Items that need admin attention</div>
        </div>
        <a href="{{ route('admin.users') }}" class="text-xs font-semibold text-emerald-700 hover:underline">Manage Users</a>
      </div>

      <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-3">
        @foreach($actions as $a)
          <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
            <div class="text-xs text-slate-500">{{ $a['title'] }}</div>
            <div class="mt-2 text-3xl font-bold">{{ $a['count'] }}</div>
            <div class="mt-1 text-xs text-slate-600">{{ $a['desc'] }}</div>
            <button class="mt-4 w-full rounded-xl bg-emerald-600 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-700">
              {{ $a['btn'] }}
            </button>
          </div>
        @endforeach
      </div>
    </div>

    <div class="xl:col-span-2 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
      <div class="flex items-start justify-between">
        <div>
          <div class="text-sm font-semibold">Recent Activity</div>
          <div class="mt-1 text-xs text-slate-500">Latest system events</div>
        </div>
        <a href="{{ route('admin.reports') }}" class="text-xs font-semibold text-emerald-700 hover:underline">View Reports</a>
      </div>

      <div class="mt-5 space-y-3">
        @foreach($activity as $x)
          <div class="flex items-start gap-3 rounded-xl border border-slate-200 bg-slate-50 p-3">
            <div class="mt-0.5 grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-emerald-600 text-xs font-bold text-white">
              {{ strtoupper(substr($x['tag'],0,1)) }}
            </div>
            <div class="min-w-0">
              <div class="text-sm text-slate-800">
                <span class="font-semibold">{{ $x['who'] }}</span> {{ $x['what'] }}
              </div>
              <div class="mt-1 flex items-center gap-2 text-xs text-slate-500">
                <span>{{ $x['when'] }}</span>
                <span class="rounded-full bg-white px-2 py-0.5 text-[11px] font-semibold text-slate-700 ring-1 ring-slate-200">
                  {{ $x['tag'] }}
                </span>
              </div>
            </div>
          </div>
        @endforeach
      </div>

      <button class="mt-5 w-full rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50">
        Load more
      </button>
    </div>
  </div>

</div>
@endsection
