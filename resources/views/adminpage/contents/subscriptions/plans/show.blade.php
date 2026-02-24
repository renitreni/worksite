@extends('adminpage.layout')
@section('title', 'Plan Details')
@section('page_title', 'Plan Details')

@section('content')
  <div class="w-full max-w-7xl mx-auto space-y-6">

    <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">

      {{-- Header --}}
      <div class="px-6 sm:px-8 py-6 border-b border-slate-200 bg-gradient-to-r from-emerald-50 to-white">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
          <div>
            <div class="text-xs text-slate-500">Subscription Plan</div>
            <div class="mt-1 flex flex-wrap items-center gap-2">
              <h1 class="text-2xl sm:text-3xl font-semibold text-slate-900">{{ $plan->name }}</h1>
              <span class="rounded-full border border-slate-200 bg-white px-2.5 py-1 text-xs font-mono text-slate-700">
                {{ $plan->code }}
              </span>
            </div>
            <div class="mt-2 text-sm text-slate-600">
              Price: <span class="font-semibold text-slate-900">₱{{ number_format((int) $plan->price) }}</span>
            </div>
          </div>

          {{-- Actions --}}
          <div class="flex flex-col-reverse sm:flex-row sm:items-center gap-2">
            <a href="{{ route('admin.subscriptions.plans.index') }}"
              class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">
              Back
            </a>

            <a href="{{ route('admin.subscriptions.plans.edit', $plan) }}"
              class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700">
              Edit Plan
            </a>
          </div>
        </div>
      </div>

      {{-- Body --}}
      <div class="p-6 sm:p-8 space-y-6">

        <div class="grid gap-4 md:grid-cols-3">
          <div class="rounded-2xl border border-slate-200 bg-white p-4">
            <div class="text-xs text-slate-500">Code</div>
            <div class="mt-1 font-mono text-sm text-slate-900">{{ $plan->code }}</div>
          </div>

          <div class="rounded-2xl border border-slate-200 bg-white p-4">
            <div class="text-xs text-slate-500">Name</div>
            <div class="mt-1 text-sm font-semibold text-slate-900">{{ $plan->name }}</div>
          </div>

          <div class="rounded-2xl border border-slate-200 bg-white p-4">
            <div class="text-xs text-slate-500">Price</div>
            <div class="mt-1 text-sm text-slate-900">₱{{ number_format((int) $plan->price) }}</div>
          </div>
        </div>

        @php
          $features = $plan->features ?? [];
        @endphp

        <div>
          <div class="text-sm font-semibold text-slate-900 mb-4">Features</div>

          <div class="grid gap-4 md:grid-cols-2">

            <div class="rounded-2xl border border-slate-200 p-4 bg-white">
              <div class="text-xs text-slate-500">Max Active Jobs</div>
              <div class="mt-1 text-sm text-slate-900">
                {{ $features['max_active_jobs'] ?? 'Unlimited' }}
              </div>
            </div>

            <div class="rounded-2xl border border-slate-200 p-4 bg-white">
              <div class="text-xs text-slate-500">Candidate Views / Day</div>
              <div class="mt-1 text-sm text-slate-900">
                {{ $features['candidate_views_per_day'] ?? 'Unlimited' }}
              </div>
            </div>

            <div class="rounded-2xl border border-slate-200 p-4 bg-white">
              <div class="text-xs text-slate-500">CV Access</div>
              <div class="mt-1 text-sm text-slate-900 capitalize">
                {{ $features['cv_access'] ?? 'none' }}
              </div>
            </div>

            <div class="rounded-2xl border border-slate-200 p-4 bg-white">
              <div class="text-xs text-slate-500">Analytics Level</div>
              <div class="mt-1 text-sm text-slate-900 capitalize">
                {{ $features['analytics_level'] ?? 'basic' }}
              </div>
            </div>

          </div>

          {{-- Boolean Features --}}
          <div class="mt-6">
            <div class="text-sm font-semibold text-slate-900 mb-3">Enabled Features</div>

            <div class="flex flex-wrap gap-2">
              @foreach($features as $key => $value)
                @if($value === true || $value === 1 || $value === "1")
                  <span class="rounded-full bg-emerald-100 text-emerald-700 text-xs px-3 py-1 capitalize">
                    {{ str_replace('_', ' ', $key) }}
                  </span>
                @endif
              @endforeach
            </div>
          </div>

        </div>

      </div>
    </div>

  </div>
@endsection