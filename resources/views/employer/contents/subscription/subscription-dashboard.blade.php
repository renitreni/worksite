@extends('employer.layout')

@section('content')
@php
  $statusPill = function ($s) {
    return match($s) {
      'active' => 'bg-emerald-100 text-emerald-800',
      'inactive' => 'bg-amber-100 text-amber-800',
      'expired' => 'bg-slate-100 text-slate-700',
      'canceled' => 'bg-rose-100 text-rose-800',
      default => 'bg-slate-100 text-slate-700',
    };
  };

  $fmtUnlimited = fn($v) => ($v === null || $v === '' ? 'Unlimited' : $v);

  $planFeatures = function ($plan) {
    $rows = ($plan?->featureValues ?? collect())
      ->filter(fn($pf) => $pf->definition)
      ->sortBy(fn($pf) => $pf->definition->sort_order ?? 999999)
      ->values();

    $limits  = $rows->filter(fn($pf) => $pf->definition->type === 'number');
    $selects = $rows->filter(fn($pf) => $pf->definition->type === 'select');
    $bools   = $rows->filter(fn($pf) => $pf->definition->type === 'boolean' && (bool)$pf->value);

    return compact('rows','limits','selects','bools');
  };
@endphp

<div class="space-y-6">

  {{-- Header --}}
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-2">
    <div>
      <h1 class="text-3xl font-semibold text-slate-900">Subscription</h1>
      <p class="text-sm text-slate-600 mt-1">
        Review your plan, see history, and upgrade anytime.
      </p>
    </div>
  </div>

  <x-toast type="success" :message="session('success')" />
  <x-toast type="error" :message="session('error')" />

  {{-- Current Plan --}}
  @if($currentSubscription ?? false)
    @php
      $status = $currentSubscription->subscription_status;
      $daysLeft = $currentSubscription->daysLeft();
      $end = $currentSubscription->ends_at?->format('M d, Y');
      $start = $currentSubscription->starts_at?->format('M d, Y');

      $pct = 0;
      if ($currentSubscription->starts_at && $currentSubscription->ends_at) {
        $total = $currentSubscription->starts_at->startOfDay()->diffInDays($currentSubscription->ends_at->startOfDay(), false);
        $left  = now()->startOfDay()->diffInDays($currentSubscription->ends_at->startOfDay(), false);
        $pct = $total > 0 ? max(0, min(100, (int) round(($left / $total) * 100))) : 0;
      }

      $pf = $planFeatures($currentSubscription->plan);
      $enabledBadges = $pf['bools']->take(8);
    @endphp

    <div class="rounded-3xl shadow-sm border border-slate-200 overflow-hidden bg-white">
      <div class="px-6 sm:px-8 py-6 border-b border-slate-200 bg-gradient-to-r from-emerald-50 to-white">
        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
          <div>
            <div class="text-xs text-slate-500">Current Plan</div>
            <div class="mt-1 flex flex-wrap items-center gap-2">
              <h2 class="text-2xl font-semibold text-slate-900">
                {{ $currentSubscription->plan?->name ?? 'N/A' }}
              </h2>
              <span class="inline-flex items-center justify-center rounded-full px-3 py-1 text-xs font-semibold {{ $statusPill($status) }}">
                {{ ucfirst($status) }}
              </span>
            </div>

            <div class="mt-2 text-sm text-slate-600">
              <span class="font-semibold text-slate-900">
                ₱{{ number_format((float)($currentSubscription->plan?->price ?? 0), 2) }}
              </span>
              <span class="text-slate-300 mx-2">•</span>
              Period:
              <span class="font-semibold text-slate-900">{{ $start ?? '—' }}</span>
              <span class="text-slate-400">→</span>
              <span class="font-semibold text-slate-900">{{ $end ?? '—' }}</span>
            </div>
          </div>

          <div class="w-full lg:w-[360px] rounded-3xl border border-slate-200 bg-white p-4">
            <div class="flex items-center justify-between">
              <div class="text-sm font-semibold text-slate-900">Days left</div>
              <div class="text-xs text-slate-600">
                {{ is_null($daysLeft) ? '—' : $daysLeft.' day'.($daysLeft===1?'':'s') }}
              </div>
            </div>

            <div class="mt-3 h-2 rounded-full bg-slate-100 overflow-hidden">
              <div class="h-full bg-emerald-600" style="width: {{ $pct }}%"></div>
            </div>

            <div class="mt-2 text-xs text-slate-500">
              If payment is pending, days may update after admin verification.
            </div>
          </div>
        </div>
      </div>

      <div class="p-6 sm:p-8">
        <div class="text-sm font-semibold text-slate-900 mb-3">Included highlights</div>

        @if($enabledBadges->count())
          <div class="flex flex-wrap gap-2">
            @foreach($enabledBadges as $x)
              <span class="rounded-full bg-emerald-100 text-emerald-800 text-xs px-3 py-1 font-semibold">
                {{ $x->definition->label }}
              </span>
            @endforeach
          </div>
        @else
          <p class="text-sm text-slate-600">No toggle features enabled for this plan.</p>
        @endif
      </div>
    </div>
  @endif

  {{-- History --}}
  <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="px-6 sm:px-8 py-5 bg-gradient-to-r from-emerald-50 to-white border-b border-slate-200">
      <div>
        <div class="text-sm font-semibold text-slate-900">Subscription History</div>
        <div class="text-xs text-slate-600">Your previous subscriptions and statuses.</div>
      </div>
    </div>

    <div class="overflow-x-auto">
      <table class="min-w-full table-fixed divide-y divide-slate-200">
        <thead class="bg-slate-50">
          <tr>
            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase">Plan</th>
            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase">Status</th>
            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase">Start</th>
            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase">End</th>
          </tr>
        </thead>

        <tbody class="divide-y divide-slate-200">
          @forelse($subscriptions as $sub)
            <tr class="hover:bg-slate-50 transition">
              <td class="px-6 py-5">
                <p class="text-sm font-semibold text-slate-900 truncate">{{ $sub->plan?->name ?? 'N/A' }}</p>
                <p class="text-xs text-slate-500 truncate">₱{{ number_format((float)($sub->plan?->price ?? 0), 2) }}</p>
              </td>

              <td class="px-6 py-5">
                <span class="inline-flex items-center justify-center rounded-full px-3 py-1 text-xs font-semibold {{ $statusPill($sub->subscription_status) }}">
                  {{ ucfirst($sub->subscription_status) }}
                </span>
              </td>

              <td class="px-6 py-5 text-sm text-slate-700">
                {{ $sub->starts_at?->format('M d, Y') ?? '—' }}
              </td>

              <td class="px-6 py-5 text-sm text-slate-700">
                {{ $sub->ends_at?->format('M d, Y') ?? '—' }}
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="px-6 py-12 text-center">
                <p class="text-sm font-semibold text-slate-900">No subscriptions found</p>
                <p class="mt-1 text-sm text-slate-500">Once you subscribe to a plan, it will appear here.</p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- Plans --}}
  <div class="space-y-3">
    <div>
      <h2 class="text-2xl font-semibold text-slate-900">Choose a Plan</h2>
      <p class="text-sm text-slate-600 mt-1">Compare features and upgrade anytime.</p>
    </div>

    <div class="grid gap-5 md:grid-cols-3">
      @foreach($plans as $plan)
        @php
          $existingSub = $subscriptions->firstWhere('plan_id', $plan->id);
          $subStatus = $existingSub?->subscription_status;
          $canSelect = !$existingSub || in_array($subStatus, ['expired', 'canceled'], true);

          $isCurrent = ($currentSubscription?->plan_id === $plan->id) && ($currentSubscription?->subscription_status === 'active');

          $pf = $planFeatures($plan);
          $limits = $pf['limits'];
          $selects = $pf['selects'];
          $bools = $pf['bools']->take(7);
        @endphp

        <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden flex flex-col">
          <div class="p-5 border-b border-slate-100 bg-gradient-to-r from-emerald-50 to-white">
            <div class="flex items-start justify-between gap-3">
              <div>
                <div class="text-lg font-semibold text-slate-900">{{ $plan->name }}</div>
                <div class="mt-1 text-sm text-slate-600">
                  <span class="text-3xl font-semibold text-slate-900">₱{{ number_format((float)$plan->price, 2) }}</span>
                  <span class="text-xs text-slate-500">/ month</span>
                </div>
              </div>

              @if($isCurrent)
                <span class="rounded-full bg-emerald-600 text-white text-xs px-3 py-1 font-semibold">
                  Current
                </span>
              @endif
            </div>

            <div class="mt-4 space-y-2">
              @foreach($limits as $x)
                @php
                  $label = $x->definition->label;
                  $val = $fmtUnlimited($x->value);
                @endphp
                <div class="flex items-center justify-between text-sm">
                  <span class="text-slate-600">{{ $label }}</span>
                  <span class="font-semibold text-slate-900">{{ $val }}</span>
                </div>
              @endforeach

              @foreach($selects as $x)
                @php
                  $label = $x->definition->label;
                  $val = $x->value ?? $x->definition->default_value ?? 'Default';
                @endphp
                <div class="flex items-center justify-between text-sm">
                  <span class="text-slate-600">{{ $label }}</span>
                  <span class="font-semibold text-slate-900 capitalize">{{ str_replace('_',' ', (string)$val) }}</span>
                </div>
              @endforeach
            </div>
          </div>

          <div class="p-5 flex-1">
            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Included</div>
            <div class="mt-3 flex flex-wrap gap-2">
              @forelse($bools as $x)
                <span class="rounded-full bg-emerald-100 text-emerald-800 text-xs px-3 py-1 font-semibold">
                  {{ $x->definition->label }}
                </span>
              @empty
                <span class="text-sm text-slate-600">No toggle features enabled.</span>
              @endforelse
            </div>
          </div>

          <div class="p-5 pt-0">
            @if(!$canSelect)
              <span class="inline-flex items-center justify-center rounded-2xl px-4 py-2 text-sm font-semibold border
                {{ $subStatus === 'active' ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : 'bg-amber-50 text-amber-800 border-amber-200' }}">
                {{ ucfirst($subStatus) }}
              </span>
            @else
              <a href="{{ route('employer.subscription.select', $plan->id) }}"
                 class="inline-flex w-full items-center justify-center rounded-2xl bg-emerald-600 px-6 py-3 text-sm font-semibold text-white shadow-sm
                        hover:bg-emerald-700 focus:outline-none focus:ring-4 focus:ring-emerald-200 transition">
                Choose Plan
              </a>
            @endif
          </div>
        </div>
      @endforeach
    </div>
  </div>

</div>
@endsection