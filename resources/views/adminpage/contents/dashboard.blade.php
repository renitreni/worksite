@extends('adminpage.layout')
@section('title','Dashboard')
@section('page_title','Dashboard Overview')

@section('content')
@php
  // =========================
  // SAMPLE DATA (frontend only)
  // Replace later with DB queries.
  // =========================

  // Splits required by spec
  $users = [
    'employees' => 1984,
    'employers'  => 330,
  ];
  $users['total'] = $users['employees'] + $users['employers'];

  $employers = [
    'free' => 92,
    'paid' => 36,
  ];
  $employers['active'] = $employers['free'] + $employers['paid'];

  $jobs = [
    'active'   => 312,
    'pending'  => 28,
    'closed'   => 97,
    'removed'  => 16,
  ];
  $jobs['total'] = array_sum($jobs);

  $billing = [
    'revenue_month' => 86420,
    'pending_payments' => 4,
    'expired_subs' => 14,
  ];

  // KPI cards (top row)
  $kpis = [
    ['label'=>'Total Users', 'value'=>number_format($users['total']), 'delta'=>'+6.2%', 'hint'=>'vs last 7 days'],
    ['label'=>'Employees', 'value'=>number_format($users['employees']), 'delta'=>'+4.1%', 'hint'=>'active profiles'],
    ['label'=>'Employers', 'value'=>number_format($users['employers']), 'delta'=>'+2.1%', 'hint'=>'registered'],
    ['label'=>'Jobs Active', 'value'=>number_format($jobs['active']), 'delta'=>'+3.1%', 'hint'=>'active now'],
    ['label'=>'Jobs Pending', 'value'=>number_format($jobs['pending']), 'delta'=>'+9', 'hint'=>'needs review'],
    ['label'=>'Revenue', 'value'=>'₱ '.number_format($billing['revenue_month']), 'delta'=>'+12.4%', 'hint'=>'this month'],
  ];

  // For breakdown bars
  $jobsBreakdown = [
    ['label'=>'Active',  'value'=>$jobs['active']],
    ['label'=>'Pending', 'value'=>$jobs['pending']],
    ['label'=>'Closed',  'value'=>$jobs['closed']],
    ['label'=>'Removed', 'value'=>$jobs['removed']],
  ];

  $actions = [
    ['title'=>'Approve Employers', 'count'=>6, 'desc'=>'New employer registrations pending review', 'btn'=>'Review'],
    ['title'=>'Review Job Posts', 'count'=>$jobs['pending'], 'desc'=>'Jobs waiting for approval/rejection', 'btn'=>'Open Queue'],
    ['title'=>'Verify Payments', 'count'=>$billing['pending_payments'], 'desc'=>'Pending payments to activate subscriptions', 'btn'=>'Verify'],
  ];

  $activity = [
    ['who'=>'ACME Corp', 'what'=>'submitted a new job post', 'when'=>'2m ago', 'tag'=>'Jobs'],
    ['who'=>'QuickShip PH', 'what'=>'payment marked as completed', 'when'=>'14m ago', 'tag'=>'Billing'],
    ['who'=>'Mark Reyes', 'what'=>'account suspended (expired subscription)', 'when'=>'1h ago', 'tag'=>'Users'],
    ['who'=>'TechTalent Hub', 'what'=>'job post approved', 'when'=>'3h ago', 'tag'=>'Jobs'],
  ];

  // placeholder bars for revenue (0-60 range)
  $revenueBars = [18,22,15,28,30,24,40,35,46,39,52,48];

  // chart-like job trend (placeholder)
  $jobTrend = [12, 18, 16, 22, 30, 28, 34, 26, 24, 29, 35, 38];

  // Helpers: no inline styles, no w-[%]
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

  $barWidth = function(int $pct): string {
    return match(true) {
      $pct >= 95 => 'w-full',
      $pct >= 90 => 'w-11/12',
      $pct >= 80 => 'w-4/5',
      $pct >= 75 => 'w-3/4',
      $pct >= 66 => 'w-2/3',
      $pct >= 60 => 'w-3/5',
      $pct >= 50 => 'w-1/2',
      $pct >= 40 => 'w-2/5',
      $pct >= 33 => 'w-1/3',
      $pct >= 25 => 'w-1/4',
      $pct >= 20 => 'w-1/5',
      $pct >= 10 => 'w-1/12',
      default  => 'w-1/12',
    };
  };

  $chip = function(string $tone): string {
    return match($tone) {
      'good' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
      'warn' => 'bg-amber-50 text-amber-700 ring-amber-200',
      'bad'  => 'bg-rose-50 text-rose-700 ring-rose-200',
      default=> 'bg-slate-100 text-slate-700 ring-slate-200',
    };
  };

  // pretend “last updated” label for realtime feel (frontend only)
  $lastUpdated = 'Just now';
@endphp

<div class="space-y-6">

  {{-- Header controls (responsive) --}}
  <div class="flex flex-col gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:flex-row sm:items-center sm:justify-between">
    <div class="min-w-0">
      <div class="text-sm font-semibold text-slate-900">Live Overview</div>
      <div class="mt-1 text-xs text-slate-500">
        Last updated: <span class="font-semibold text-slate-700">{{ $lastUpdated }}</span>
        <span class="hidden sm:inline">•</span>
        <span class="block sm:inline">All values are placeholders (frontend)</span>
      </div>
    </div>

    <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
      <select class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm sm:w-auto">
        <option>Last 7 days</option>
        <option>Last 30 days</option>
        <option>This month</option>
        <option>This year</option>
      </select>

      <button class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50 sm:w-auto">
        Refresh
      </button>

      <a href="{{ route('admin.reports') }}"
         class="w-full rounded-xl bg-emerald-600 px-4 py-2 text-center text-sm font-semibold text-white hover:bg-emerald-700 sm:w-auto">
        Generate Report
      </a>
    </div>
  </div>

  {{-- KPI grid (mobile 1, tablet 2, desktop 3) --}}
  <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
    @foreach($kpis as $k)
      <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="flex items-start justify-between gap-3">
          <div class="min-w-0">
            <p class="text-sm text-slate-500">{{ $k['label'] }}</p>
            <p class="mt-2 text-3xl font-bold tracking-tight text-slate-900">{{ $k['value'] }}</p>
          </div>
          <div class="shrink-0 text-right">
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

  {{-- Breakdown strip (Users + Employers split) --}}
  <div class="grid grid-cols-1 gap-4 xl:grid-cols-3">
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
      <div class="text-sm font-semibold text-slate-900">Users Split</div>
      <div class="mt-1 text-xs text-slate-500">Employees vs Employers</div>

      @php
        $uTotal = max(1, $users['total']);
        $empPct = (int) round(($users['employees'] / $uTotal) * 100);
        $erPct  = 100 - $empPct;
      @endphp

      <div class="mt-4 space-y-3">
        <div>
          <div class="flex items-center justify-between text-sm">
            <span class="font-semibold text-slate-800">Employees</span>
            <span class="text-slate-600">{{ number_format($users['employees']) }} ({{ $empPct }}%)</span>
          </div>
          <div class="mt-2 h-2 w-full rounded-full bg-slate-100">
            <div class="h-2 rounded-full bg-emerald-600 {{ $barWidth($empPct) }}"></div>
          </div>
        </div>

        <div>
          <div class="flex items-center justify-between text-sm">
            <span class="font-semibold text-slate-800">Employers</span>
            <span class="text-slate-600">{{ number_format($users['employers']) }} ({{ $erPct }}%)</span>
          </div>
          <div class="mt-2 h-2 w-full rounded-full bg-slate-100">
            <div class="h-2 rounded-full bg-slate-700 {{ $barWidth($erPct) }}"></div>
          </div>
        </div>
      </div>

      <a href="{{ route('admin.users') }}"
         class="mt-5 inline-flex w-full items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50">
        Manage Users
      </a>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
      <div class="text-sm font-semibold text-slate-900">Employer Plans</div>
      <div class="mt-1 text-xs text-slate-500">Free vs Paid subscriptions</div>

      @php
        $eTotal = max(1, $employers['active']);
        $freePct = (int) round(($employers['free'] / $eTotal) * 100);
        $paidPct = 100 - $freePct;
      @endphp

      <div class="mt-4 space-y-3">
        <div>
          <div class="flex items-center justify-between text-sm">
            <span class="font-semibold text-slate-800">Free</span>
            <span class="text-slate-600">{{ number_format($employers['free']) }} ({{ $freePct }}%)</span>
          </div>
          <div class="mt-2 h-2 w-full rounded-full bg-slate-100">
            <div class="h-2 rounded-full bg-slate-700 {{ $barWidth($freePct) }}"></div>
          </div>
        </div>

        <div>
          <div class="flex items-center justify-between text-sm">
            <span class="font-semibold text-slate-800">Paid</span>
            <span class="text-slate-600">{{ number_format($employers['paid']) }} ({{ $paidPct }}%)</span>
          </div>
          <div class="mt-2 h-2 w-full rounded-full bg-slate-100">
            <div class="h-2 rounded-full bg-emerald-600 {{ $barWidth($paidPct) }}"></div>
          </div>
        </div>
      </div>

      <a href="{{ route('admin.billing') }}"
         class="mt-5 inline-flex w-full items-center justify-center rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
        Subscription & Billing
      </a>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
      <div class="text-sm font-semibold text-slate-900">Payments & Risk</div>
      <div class="mt-1 text-xs text-slate-500">Operational alerts</div>

      <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-1">
        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
          <div class="text-xs text-slate-500">Pending payments</div>
          <div class="mt-1 text-2xl font-bold text-slate-900">{{ $billing['pending_payments'] }}</div>
          <div class="mt-2 inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1 {{ $chip($billing['pending_payments'] ? 'warn' : 'good') }}">
            {{ $billing['pending_payments'] ? 'Needs verification' : 'All clear' }}
          </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
          <div class="text-xs text-slate-500">Expired subscriptions</div>
          <div class="mt-1 text-2xl font-bold text-slate-900">{{ $billing['expired_subs'] }}</div>
          <div class="mt-2 inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1 {{ $chip($billing['expired_subs'] >= 10 ? 'bad' : 'warn') }}">
            Action recommended
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Analytics row (responsive: stacks on mobile) --}}
  <div class="grid grid-cols-1 gap-4 xl:grid-cols-5">

    {{-- Revenue trend --}}
    <div class="xl:col-span-3 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
      <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
        <div>
          <div class="text-sm font-semibold text-slate-900">Revenue Trend</div>
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

      {{-- Job trend mini chart --}}
      <div class="mt-5">
        <div class="text-sm font-semibold text-slate-900">Jobs Posted Trend</div>
        <div class="mt-1 text-xs text-slate-500">New jobs created per month (placeholder)</div>

        <div class="mt-4 flex h-24 items-end gap-2 rounded-2xl border border-slate-200 bg-white p-4">
          @foreach($jobTrend as $t)
            <div class="flex-1 rounded-md bg-slate-800/80 {{ $barHeight((int)$t) }}"></div>
          @endforeach
        </div>
      </div>
    </div>

    {{-- Jobs breakdown --}}
    <div class="xl:col-span-2 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
      <div class="text-sm font-semibold text-slate-900">Jobs Breakdown</div>
      <div class="mt-1 text-xs text-slate-500">Distribution by status</div>

      @php
        $totalJobs = max(1, array_sum(array_map(fn($x)=>$x['value'], $jobsBreakdown)));
      @endphp

      <div class="mt-5 space-y-3">
        @foreach($jobsBreakdown as $j)
          @php
            $pct = (int) round(($j['value']/$totalJobs)*100);
            $wClass = $barWidth($pct);
          @endphp
          <div>
            <div class="flex items-center justify-between text-sm">
              <span class="font-semibold text-slate-800">{{ $j['label'] }}</span>
              <span class="text-slate-600">{{ number_format($j['value']) }} ({{ $pct }}%)</span>
            </div>
            <div class="mt-2 h-2 w-full rounded-full bg-slate-100">
              <div class="h-2 rounded-full bg-emerald-600 {{ $wClass }}"></div>
            </div>
          </div>
        @endforeach
      </div>

      <div class="mt-5 grid grid-cols-1 gap-2 sm:grid-cols-2 xl:grid-cols-1">
        <a href="{{ route('admin.jobs') }}"
           class="inline-flex w-full items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50">
          Go to Job Queue
        </a>

        <a href="{{ route('admin.jobs') }}"
           class="inline-flex w-full items-center justify-center rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
          Review Pending ({{ number_format($jobs['pending']) }})
        </a>
      </div>
    </div>
  </div>

  {{-- Action center + Activity feed --}}
  <div class="grid grid-cols-1 gap-4 xl:grid-cols-5">
    <div class="xl:col-span-3 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
      <div class="flex items-start justify-between gap-3">
        <div>
          <div class="text-sm font-semibold text-slate-900">Action Center</div>
          <div class="mt-1 text-xs text-slate-500">Items that need admin attention</div>
        </div>
        <a href="{{ route('admin.users') }}" class="text-xs font-semibold text-emerald-700 hover:underline">Manage Users</a>
      </div>

      <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-3">
        @foreach($actions as $a)
          <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
            <div class="text-xs text-slate-500">{{ $a['title'] }}</div>
            <div class="mt-2 text-3xl font-bold text-slate-900">{{ $a['count'] }}</div>
            <div class="mt-1 text-xs text-slate-600">{{ $a['desc'] }}</div>
            <button class="mt-4 w-full rounded-xl bg-emerald-600 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-700">
              {{ $a['btn'] }}
            </button>
          </div>
        @endforeach
      </div>
    </div>

    <div class="xl:col-span-2 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
      <div class="flex items-start justify-between gap-3">
        <div>
          <div class="text-sm font-semibold text-slate-900">Recent Activity</div>
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
              <div class="mt-1 flex flex-wrap items-center gap-2 text-xs text-slate-500">
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
