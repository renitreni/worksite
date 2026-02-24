@extends('adminpage.layout')
@section('title','Edit Plan')
@section('page_title','Edit Plan')

@section('content')
@php
  $f = $plan->features ?? [];
@endphp

<div class="w-full max-w-7xl mx-auto space-y-6">

  @if($errors->any())
    <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
      <div class="font-semibold mb-2">Please fix the following:</div>
      <ul class="list-disc pl-5 space-y-1">
        @foreach($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('admin.subscriptions.plans.update', $plan) }}"
        class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
    @csrf
    @method('PUT')

    {{-- Header --}}
    <div class="px-6 sm:px-8 py-6 border-b border-slate-200 bg-gradient-to-r from-emerald-50 to-white">
      <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
          <div class="text-xs text-slate-500">Editing Plan</div>
          <div class="mt-1 flex flex-wrap items-center gap-2">
            <h1 class="text-2xl sm:text-3xl font-semibold text-slate-900">{{ $plan->name }}</h1>
            <span class="rounded-full border border-slate-200 bg-white px-2.5 py-1 text-xs font-mono text-slate-700">
              {{ $plan->code }}
            </span>
          </div>
          <p class="mt-2 text-sm text-slate-600">Update pricing, status, and feature limits.</p>
        </div>

        <div class="flex flex-col-reverse sm:flex-row sm:items-center gap-2">
          <a href="{{ route('admin.subscriptions.plans.index') }}"
             class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">
            Back
          </a>
          <button type="submit"
                  class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700">
            Update Plan
          </button>
        </div>
      </div>
    </div>

    {{-- Body --}}
    <div class="p-6 sm:p-8 space-y-8">

      {{-- Basics --}}
      <div class="space-y-4">
        <div>
          <div class="text-sm font-semibold text-slate-900">Plan basics</div>
          <div class="text-xs text-slate-500">Code, name, pricing, and status.</div>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
          <div>
            <label class="text-sm font-medium text-slate-700">Code</label>
            <input name="code" value="{{ old('code', $plan->code) }}"
                   class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm
                          focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400"
                   required>
          </div>

          <div>
            <label class="text-sm font-medium text-slate-700">Price (PHP)</label>
            <input type="number" name="price" value="{{ old('price', $plan->price) }}"
                   class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm
                          focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400"
                   min="0" required>
          </div>

          <div>
            <label class="text-sm font-medium text-slate-700">Status</label>

            {{-- ensure unchecked = 0 --}}
            <input type="hidden" name="is_active" value="0">

            <div class="mt-1 flex items-center gap-2 rounded-xl border border-slate-200 px-3 py-2.5">
              <input id="is_active" type="checkbox" name="is_active" value="1"
                     class="rounded border-slate-300"
                     {{ old('is_active', $plan->is_active) ? 'checked' : '' }}>
              <label for="is_active" class="text-sm text-slate-700">Active</label>
            </div>
            <p class="mt-1 text-xs text-slate-500">Inactive plans won’t be available for purchase.</p>
          </div>
        </div>

        <div>
          <label class="text-sm font-medium text-slate-700">Name</label>
          <input name="name" value="{{ old('name', $plan->name) }}"
                 class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm
                        focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400"
                 required>
        </div>
      </div>

      {{-- Features --}}
      <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5 sm:p-6 space-y-5">
        <div>
          <div class="text-sm font-semibold text-slate-900">Features</div>
          <div class="text-xs text-slate-600">Blank in numeric fields means unlimited.</div>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
          <div class="bg-white rounded-2xl border border-slate-200 p-4">
            <label class="text-sm font-medium text-slate-700">Max Active Jobs <span class="text-slate-400">(blank = unlimited)</span></label>
            <input type="number" min="0"
                   name="features[max_active_jobs]"
                   value="{{ old('features.max_active_jobs', data_get($f,'max_active_jobs')) }}"
                   class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm
                          focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400"
                   placeholder="e.g., 2 or 10">
          </div>

          <div class="bg-white rounded-2xl border border-slate-200 p-4">
            <label class="text-sm font-medium text-slate-700">Candidate Profile Views / Day <span class="text-slate-400">(blank = unlimited)</span></label>
            <input type="number" min="0"
                   name="features[candidate_views_per_day]"
                   value="{{ old('features.candidate_views_per_day', data_get($f,'candidate_views_per_day')) }}"
                   class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm
                          focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400"
                   placeholder="e.g., 5">
          </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
          <div class="bg-white rounded-2xl border border-slate-200 p-4">
            <label class="text-sm font-medium text-slate-700">CV Access</label>
            @php $cv = old('features.cv_access', data_get($f,'cv_access','none')); @endphp
            <select name="features[cv_access]"
                    class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm
                           focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400">
              <option value="none"     {{ $cv==='none' ? 'selected' : '' }}>None</option>
              <option value="preview"  {{ $cv==='preview' ? 'selected' : '' }}>Preview only</option>
              <option value="download" {{ $cv==='download' ? 'selected' : '' }}>Download</option>
            </select>
          </div>

          <div class="bg-white rounded-2xl border border-slate-200 p-4">
            <label class="text-sm font-medium text-slate-700">Analytics Level</label>
            @php $al = old('features.analytics_level', data_get($f,'analytics_level','basic')); @endphp
            <select name="features[analytics_level]"
                    class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm
                           focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400">
              <option value="basic"     {{ $al==='basic' ? 'selected' : '' }}>Basic</option>
              <option value="advanced"  {{ $al==='advanced' ? 'selected' : '' }}>Advanced</option>
              <option value="dashboard" {{ $al==='dashboard' ? 'selected' : '' }}>Dashboard</option>
            </select>
          </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-4">
          <div class="text-sm font-medium text-slate-700 mb-3">Toggle features</div>

          @php
            $checks = [
              'featured_badge' => 'Featured badge',
              'priority_placement' => 'Priority placement',
              'advanced_filters' => 'Advanced filters',
              'priority_support' => 'Priority support',
              'homepage_priority' => 'Homepage priority / highlights',
              'can_message_candidates' => 'Direct messaging',
              'branding_upgrades' => 'Branding upgrades',
              'verification_badge' => 'Verification badge',
              'hiring_pipeline' => 'Hiring pipeline',
              'conversion_tracking' => 'Conversion tracking',
            ];
          @endphp

          <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($checks as $key => $label)
              <label class="flex items-center gap-2 rounded-xl border border-slate-200 px-3 py-2 hover:bg-slate-50">
                <input type="checkbox" name="features[{{ $key }}]" value="1"
                       class="rounded border-slate-300"
                       {{ old("features.$key", (bool) data_get($f,$key,false)) ? 'checked' : '' }}>
                <span class="text-sm text-slate-700">{{ $label }}</span>
              </label>
            @endforeach
          </div>

          <details class="mt-4 rounded-xl border border-slate-200 bg-slate-50 p-3">
            <summary class="cursor-pointer text-sm font-semibold text-slate-800">View raw features JSON</summary>
            <pre class="mt-3 overflow-auto rounded-xl bg-white p-3 text-xs border border-slate-200">{{ json_encode($f, JSON_PRETTY_PRINT) }}</pre>
          </details>
        </div>
      </div>

    </div>
  </form>

</div>
@endsection