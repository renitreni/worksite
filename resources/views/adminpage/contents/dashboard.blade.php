@extends('adminpage.layout')
@section('title','Dashboard')
@section('page_title','Dashboard Overview')

@section('content')
@php
  $users = [
    'candidates' => 1984,
    'employers'  => 330,
  ];
  $users['total'] = $users['candidates'] + $users['employers'];

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

  $kpis = [
    ['label'=>'Total Users', 'value'=>number_format($users['total']), 'delta'=>'+6.2%', 'hint'=>'vs last 7 days'],
    ['label'=>'Candidates', 'value'=>number_format($users['candidates']), 'delta'=>'+4.1%', 'hint'=>'active profiles'],
    ['label'=>'Employers', 'value'=>number_format($users['employers']), 'delta'=>'+2.1%', 'hint'=>'registered'],
    ['label'=>'Jobs Active', 'value'=>number_format($jobs['active']), 'delta'=>'+3.1%', 'hint'=>'active now'],
    ['label'=>'Jobs Pending', 'value'=>number_format($jobs['pending']), 'delta'=>'+9', 'hint'=>'needs review'],
    ['label'=>'Revenue', 'value'=>'₱ '.number_format($billing['revenue_month']), 'delta'=>'+12.4%', 'hint'=>'this month'],
  ];

  $jobsBreakdown = [
    ['label'=>'Active',  'value'=>$jobs['active']],
    ['label'=>'Pending', 'value'=>$jobs['pending']],
    ['label'=>'Closed',  'value'=>$jobs['closed']],
    ['label'=>'Removed', 'value'=>$jobs['removed']],
  ];

  $actions = [
    ['title'=>'Approve Employers', 'count'=>6, 'desc'=>'New employer registrations pending review', 'btn'=>'Review', 'href'=>route('admin.users')],
    ['title'=>'Review Job Posts', 'count'=>$jobs['pending'], 'desc'=>'Jobs waiting for approval/rejection', 'btn'=>'Open Queue', 'href'=>route('admin.jobs')],
    ['title'=>'Verify Payments', 'count'=>$billing['pending_payments'], 'desc'=>'Pending payments to activate subscriptions', 'btn'=>'Verify', 'href'=>route('admin.billing')],
  ];

  $activity = [
    ['who'=>'ACME Corp', 'what'=>'submitted a new job post', 'when'=>'2m ago', 'tag'=>'Jobs'],
    ['who'=>'QuickShip PH', 'what'=>'payment marked as completed', 'when'=>'14m ago', 'tag'=>'Billing'],
    ['who'=>'Mark Reyes', 'what'=>'account suspended (expired subscription)', 'when'=>'1h ago', 'tag'=>'Users'],
    ['who'=>'TechTalent Hub', 'what'=>'job post approved', 'when'=>'3h ago', 'tag'=>'Jobs'],
  ];

  $revenueBars = [18,22,15,28,30,24,40,35,46,39,52,48];
  $jobTrend    = [12,18,16,22,30,28,34,26,24,29,35,38];

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
      default   => 'w-1/12',
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

  $months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

  $revenueValues = array_map(fn($b) => 10000 + ($b * 1200), $revenueBars);
  $jobValues     = array_map(fn($t) => (int)$t, $jobTrend);
@endphp

<div
  class="space-y-6"
  x-data="dashUI({
    months: @js($months),
    revenueBars: @js($revenueBars),
    revenueValues: @js($revenueValues),
    jobBars: @js($jobTrend),
    jobValues: @js($jobValues),
  })"
  x-init="init()"
>
  <div class="flex flex-col gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:flex-row sm:items-center sm:justify-between">
    <div class="min-w-0">
      <div class="text-sm font-semibold text-slate-900">Live Overview</div>
      <div class="mt-1 text-xs text-slate-500">
        Last updated: <span class="font-semibold text-slate-700" x-text="lastUpdated"></span>
        <span class="hidden sm:inline">•</span>
        <span class="block sm:inline">All values are placeholders</span>
      </div>
    </div>

    <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
      <select
        x-model="range"
        @change="applyRange()"
        class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm sm:w-auto"
      >
        <option value="7d">Last 7 days</option>
        <option value="30d">Last 30 days</option>
        <option value="month">This month</option>
        <option value="year">This year</option>
      </select>

      <button
        type="button"
        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50 sm:w-auto"
        @click="refresh()"
      >
        Refresh
      </button>

      <a
        href="{{ route('admin.reports') }}"
        class="w-full rounded-xl bg-emerald-600 px-4 py-2 text-center text-sm font-semibold text-white hover:bg-emerald-700 sm:w-auto"
      >
        Generate Report
      </a>
    </div>
  </div>

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

  <div class="grid grid-cols-1 gap-4 xl:grid-cols-3">
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
      <div class="text-sm font-semibold text-slate-900">Highlights</div>
      <div class="mt-1 text-xs text-slate-500">Quick signals from current charts</div>

      <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-1">
        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
          <div class="text-xs text-slate-500">Top revenue month</div>
          <div class="mt-1 text-lg font-bold text-slate-900" x-text="highlights.topRevenueLabel"></div>
          <div class="mt-1 text-sm font-semibold text-slate-700" x-text="highlights.topRevenueValue"></div>
          <div class="mt-2 text-xs text-slate-500" x-text="highlights.revChange"></div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
          <div class="text-xs text-slate-500">Top jobs month</div>
          <div class="mt-1 text-lg font-bold text-slate-900" x-text="highlights.topJobsLabel"></div>
          <div class="mt-1 text-sm font-semibold text-slate-700" x-text="highlights.topJobsValue"></div>
          <div class="mt-2 text-xs text-slate-500" x-text="highlights.jobsChange"></div>
        </div>
      </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
      <div class="text-sm font-semibold text-slate-900">Users Split</div>
      <div class="mt-1 text-xs text-slate-500">Candidates vs Employers</div>

      @php
        $uTotal = max(1, $users['total']);
        $candPct = (int) round(($users['candidates'] / $uTotal) * 100);
        $empPct  = 100 - $candPct;
      @endphp

      <div class="mt-4 space-y-3">
        <div>
          <div class="flex items-center justify-between text-sm">
            <span class="font-semibold text-slate-800">Candidates</span>
            <span class="text-slate-600">{{ number_format($users['candidates']) }} ({{ $candPct }}%)</span>
          </div>
          <div class="mt-2 h-2 w-full rounded-full bg-slate-100">
            <div class="h-2 rounded-full bg-emerald-600 {{ $barWidth($candPct) }}"></div>
          </div>
        </div>

        <div>
          <div class="flex items-center justify-between text-sm">
            <span class="font-semibold text-slate-800">Employers</span>
            <span class="text-slate-600">{{ number_format($users['employers']) }} ({{ $empPct }}%)</span>
          </div>
          <div class="mt-2 h-2 w-full rounded-full bg-slate-100">
            <div class="h-2 rounded-full bg-slate-700 {{ $barWidth($empPct) }}"></div>
          </div>
        </div>
      </div>

      <a
        href="{{ route('admin.users') }}"
        class="mt-5 inline-flex w-full items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50"
      >
        Manage Users
      </a>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
      <div class="text-sm font-semibold text-slate-900">Payments & Risk</div>
      <div class="mt-1 text-xs text-slate-500">Operational alerts</div>

      <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-1">
        <a href="{{ route('admin.billing') }}" class="block rounded-2xl border border-slate-200 bg-slate-50 p-4 hover:bg-white">
          <div class="flex items-start justify-between gap-3">
            <div>
              <div class="text-xs text-slate-500">Pending payments</div>
              <div class="mt-1 text-2xl font-bold text-slate-900">{{ $billing['pending_payments'] }}</div>
            </div>
            <div class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1 {{ $chip($billing['pending_payments'] ? 'warn' : 'good') }}">
              {{ $billing['pending_payments'] ? 'Needs verification' : 'All clear' }}
            </div>
          </div>
          <div class="mt-2 text-xs font-semibold text-emerald-700">Open Billing →</div>
        </a>

        <a href="{{ route('admin.billing') }}" class="block rounded-2xl border border-slate-200 bg-slate-50 p-4 hover:bg-white">
          <div class="flex items-start justify-between gap-3">
            <div>
              <div class="text-xs text-slate-500">Expired subscriptions</div>
              <div class="mt-1 text-2xl font-bold text-slate-900">{{ $billing['expired_subs'] }}</div>
            </div>
            <div class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1 {{ $chip($billing['expired_subs'] >= 10 ? 'bad' : 'warn') }}">
              Action recommended
            </div>
          </div>
          <div class="mt-2 text-xs font-semibold text-emerald-700">Review Expired →</div>
        </a>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 gap-4 xl:grid-cols-5">
    <div class="xl:col-span-3 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
      <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
        <div>
          <div class="text-sm font-semibold text-slate-900">Revenue Trend</div>
          <div class="mt-1 text-xs text-slate-500">Hover or tap bars to see values</div>
        </div>
        <div class="flex gap-2">
          <button type="button" class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold hover:bg-slate-50" @click="setMode('revenue')">Revenue</button>
          <button type="button" class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold hover:bg-slate-50" @click="setMode('jobs')">Jobs</button>
        </div>
      </div>

      <div x-show="mode==='revenue'" class="mt-5">
        <div x-ref="revenueWrap" class="relative flex h-40 items-end gap-2 rounded-2xl border border-slate-200 bg-slate-50 p-4">
          <template x-for="(b, i) in charts.revenueBars" :key="'r'+i">
            <button
              type="button"
              class="group relative flex-1"
              @mouseenter="showTipFromEl($event.currentTarget, 'Revenue: ' + charts.months[i], '₱ ' + numberWithCommas(charts.revenueValues[i]), 'revenueWrap')"
              @mousemove="moveTipFromEl($event.currentTarget, 'revenueWrap')"
              @mouseleave="hideTip()"
              @click="toggleTipFromEl($event.currentTarget, 'Revenue: ' + charts.months[i], '₱ ' + numberWithCommas(charts.revenueValues[i]), 'revenueWrap')"
            >
              <div class="w-full rounded-lg bg-emerald-600/80 group-hover:bg-emerald-700/90" :class="heightClass(b)"></div>
            </button>
          </template>

          <div
            x-show="tip.show"
            x-transition.opacity
            class="pointer-events-none absolute z-10 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs shadow-lg"
            :style="`left:${tip.x}px; top:${tip.y}px; transform: translate(-50%, -105%);`"
          >
            <div class="font-semibold text-slate-900" x-text="tip.title"></div>
            <div class="text-slate-600" x-text="tip.value"></div>
          </div>
        </div>

        <div class="mt-3 grid grid-cols-12 gap-2 text-[11px] text-slate-500">
          <template x-for="(m, i) in charts.months" :key="'rm'+i">
            <div class="text-center" x-text="m"></div>
          </template>
        </div>
      </div>

      <div x-show="mode==='jobs'" class="mt-5">
        <div x-ref="jobsWrap" class="relative flex h-40 items-end gap-2 rounded-2xl border border-slate-200 bg-white p-4">
          <template x-for="(b, i) in charts.jobBars" :key="'j'+i">
            <button
              type="button"
              class="group relative flex-1"
              @mouseenter="showTipFromEl($event.currentTarget, 'Jobs Posted: ' + charts.months[i], charts.jobValues[i] + ' jobs', 'jobsWrap')"
              @mousemove="moveTipFromEl($event.currentTarget, 'jobsWrap')"
              @mouseleave="hideTip()"
              @click="toggleTipFromEl($event.currentTarget, 'Jobs Posted: ' + charts.months[i], charts.jobValues[i] + ' jobs', 'jobsWrap')"
            >
              <div class="w-full rounded-lg bg-slate-800/80 group-hover:bg-slate-900/90" :class="heightClass(b)"></div>
            </button>
          </template>

          <div
            x-show="tip.show"
            x-transition.opacity
            class="pointer-events-none absolute z-10 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs shadow-lg"
            :style="`left:${tip.x}px; top:${tip.y}px; transform: translate(-50%, -105%);`"
          >
            <div class="font-semibold text-slate-900" x-text="tip.title"></div>
            <div class="text-slate-600" x-text="tip.value"></div>
          </div>
        </div>

        <div class="mt-3 grid grid-cols-12 gap-2 text-[11px] text-slate-500">
          <template x-for="(m, i) in charts.months" :key="'jm'+i">
            <div class="text-center" x-text="m"></div>
          </template>
        </div>
      </div>
    </div>

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
        <a href="{{ route('admin.jobs') }}" class="inline-flex w-full items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50">
          Go to Job Queue
        </a>

        <a href="{{ route('admin.jobs') }}" class="inline-flex w-full items-center justify-center rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
          Review Pending ({{ number_format($jobs['pending']) }})
        </a>
      </div>
    </div>
  </div>

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
          <a href="{{ $a['href'] }}" class="block rounded-2xl border border-slate-200 bg-slate-50 p-4 hover:bg-white">
            <div class="text-xs text-slate-500">{{ $a['title'] }}</div>
            <div class="mt-2 text-3xl font-bold text-slate-900">{{ $a['count'] }}</div>
            <div class="mt-1 text-xs text-slate-600">{{ $a['desc'] }}</div>
            <div class="mt-4 inline-flex w-full items-center justify-center rounded-xl bg-emerald-600 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-700">
              {{ $a['btn'] }}
            </div>
          </a>
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

      <button
        type="button"
        class="mt-5 w-full rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50"
        disabled
      >
        Load more
      </button>
    </div>
  </div>
</div>

<script>
  function dashUI(charts){
    return {
      charts,
      range: '7d',
      mode: 'revenue',
      lastUpdated: 'Just now',
      tip: { show:false, x:0, y:0, title:'', value:'', locked:false },
      highlights: {
        topRevenueLabel: '',
        topRevenueValue: '',
        revChange: '',
        topJobsLabel: '',
        topJobsValue: '',
        jobsChange: '',
      },

      // ✅ UPDATED: use global Notyf helper (window.toast)
      toast(type, message, title = ''){
        if (!window.toast) return;

        const allowed = ['success','info','warning','error'];
        const safeType = allowed.includes(type) ? type : 'info';

        const msg = String(message || '');
        const ttl = String(title || '');
        const text = ttl ? `${ttl}: ${msg}` : msg;

        window.toast(safeType, text);
      },

      init(){
        this.computeHighlights();
      },

      setMode(m){
        this.mode = m;
        this.tip.show = false;
        this.tip.locked = false;
      },

      refresh(){
        this.lastUpdated = new Date().toLocaleTimeString();
        this.tip.show = false;
        this.tip.locked = false;
        this.computeHighlights();
      },

      applyRange(){
        if(this.range === '7d'){
          this.charts.revenueBars = [18,22,15,28,30,24,40,35,46,39,52,48];
          this.charts.revenueValues = this.charts.revenueBars.map(b => 10000 + (b * 1200));
          this.charts.jobBars = [12,18,16,22,30,28,34,26,24,29,35,38];
          this.charts.jobValues = [...this.charts.jobBars];
        } else if(this.range === '30d'){
          this.charts.revenueBars = [20,24,18,30,33,28,42,38,44,41,49,46];
          this.charts.revenueValues = this.charts.revenueBars.map(b => 12000 + (b * 1100));
          this.charts.jobBars = [10,14,15,20,25,27,29,23,26,28,31,33];
          this.charts.jobValues = [...this.charts.jobBars];
        } else if(this.range === 'month'){
          this.charts.revenueBars = [0,0,0,0,0,0,0,0,0,0,0,48];
          this.charts.revenueValues = this.charts.revenueBars.map(b => (b ? 86420 : 0));
          this.charts.jobBars = [0,0,0,0,0,0,0,0,0,0,0,38];
          this.charts.jobValues = [...this.charts.jobBars];
        } else {
          this.charts.revenueBars = [12,14,16,20,26,28,31,34,36,40,44,48];
          this.charts.revenueValues = this.charts.revenueBars.map(b => 9000 + (b * 1400));
          this.charts.jobBars = [8,10,12,15,18,21,20,23,24,26,28,30];
          this.charts.jobValues = [...this.charts.jobBars];
        }

        this.computeHighlights();
        this.lastUpdated = new Date().toLocaleTimeString();
        this.tip.show = false;
        this.tip.locked = false;
      },

      computeHighlights(){
        let maxR = -1, maxRi = 0;
        this.charts.revenueValues.forEach((v,i)=>{ if(v>maxR){ maxR=v; maxRi=i; }});
        this.highlights.topRevenueLabel = this.charts.months[maxRi] + ' (peak)';
        this.highlights.topRevenueValue = '₱ ' + this.numberWithCommas(maxR);

        const prevRi = Math.max(0, maxRi - 1);
        const prevR = this.charts.revenueValues[prevRi] || 0;
        this.highlights.revChange = prevR ? ('vs prev: ' + this.pctChange(prevR, maxR)) : 'vs prev: —';

        let maxJ = -1, maxJi = 0;
        this.charts.jobValues.forEach((v,i)=>{ if(v>maxJ){ maxJ=v; maxJi=i; }});
        this.highlights.topJobsLabel = this.charts.months[maxJi] + ' (peak)';
        this.highlights.topJobsValue = this.numberWithCommas(maxJ) + ' jobs';

        const prevJi = Math.max(0, maxJi - 1);
        const prevJ = this.charts.jobValues[prevJi] || 0;
        this.highlights.jobsChange = prevJ ? ('vs prev: ' + this.pctChange(prevJ, maxJ)) : 'vs prev: —';
      },

      pctChange(a,b){
        const diff = b - a;
        const pct = (diff / a) * 100;
        const sign = pct >= 0 ? '+' : '';
        return sign + pct.toFixed(1) + '%';
      },

      numberWithCommas(x){
        const s = String(x ?? 0);
        return s.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
      },

      heightClass(b){
        b = Number(b || 0);
        if(b >= 55) return 'h-36';
        if(b >= 50) return 'h-32';
        if(b >= 45) return 'h-28';
        if(b >= 40) return 'h-24';
        if(b >= 35) return 'h-20';
        if(b >= 30) return 'h-16';
        if(b >= 25) return 'h-14';
        if(b >= 20) return 'h-12';
        if(b >= 15) return 'h-10';
        return 'h-8';
      },

      placeTip(el, wrapRef){
        const wrap = this.$refs[wrapRef];
        if(!wrap || !el) return;

        const wrapRect = wrap.getBoundingClientRect();
        const elRect = el.getBoundingClientRect();

        let x = (elRect.left + elRect.width / 2) - wrapRect.left;
        let y = (elRect.top - wrapRect.top) - 6;

        x = Math.max(16, Math.min(x, wrapRect.width - 16));
        y = Math.max(10, y);

        this.tip.x = x;
        this.tip.y = y;
      },

      showTipFromEl(el, title, value, wrapRef){
        if(this.tip.locked) return;
        this.tip.title = title;
        this.tip.value = value;
        this.tip.show = true;
        this.placeTip(el, wrapRef);
      },

      moveTipFromEl(el, wrapRef){
        if(!this.tip.show || this.tip.locked) return;
        this.placeTip(el, wrapRef);
      },

      hideTip(){
        if(this.tip.locked) return;
        this.tip.show = false;
      },

      toggleTipFromEl(el, title, value, wrapRef){
        if(this.tip.locked && this.tip.title === title){
          this.tip.locked = false;
          this.tip.show = false;
          return;
        }

        this.tip.locked = true;
        this.tip.title = title;
        this.tip.value = value;
        this.tip.show = true;
        this.placeTip(el, wrapRef);

        setTimeout(() => {
          this.tip.locked = false;
          this.tip.show = false;
        }, 1800);
      },
    }
  }
</script>

@endsection
