@extends('adminpage.layout')
@section('title','Edit Plan')
@section('page_title','Edit Plan')

@section('content')
@php
  $f = $plan->features ?? [];
@endphp

<div class="max-w-3xl space-y-6">

  @if($errors->any())
    <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
      <ul class="list-disc pl-5">
        @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('admin.subscriptions.plans.update', $plan) }}"
        class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm space-y-4">
    @csrf
    @method('PUT')

    <div class="grid gap-4 sm:grid-cols-2">
      <div>
        <label class="text-sm font-medium text-slate-700">Code</label>
        <input name="code" value="{{ old('code', $plan->code) }}"
               class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2" required>
      </div>

      <div>
        <label class="text-sm font-medium text-slate-700">Price (PHP)</label>
        <input type="number" name="price" value="{{ old('price', $plan->price) }}"
               class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2" min="0" required>
      </div>
    </div>

    <div>
      <label class="text-sm font-medium text-slate-700">Name</label>
      <input name="name" value="{{ old('name', $plan->name) }}"
             class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2" required>
    </div>

    <div class="flex items-center gap-2">
      <input id="is_active" type="checkbox" name="is_active" value="1" class="rounded border-slate-300"
             {{ old('is_active', $plan->is_active) ? 'checked' : '' }}>
      <label for="is_active" class="text-sm text-slate-700">Active</label>
    </div>

    {{-- Features --}}
<div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm space-y-4">
  <div>
    <div class="text-sm font-semibold text-slate-900">Features</div>
    <div class="text-xs text-slate-500">Blank in numeric fields means unlimited.</div>
  </div>

  <div class="grid gap-4 sm:grid-cols-2">
    <div>
      <label class="text-sm font-medium text-slate-700">Max Active Jobs (blank = unlimited)</label>
      <input type="number" min="0"
             name="features[max_active_jobs]"
             value="{{ old('features.max_active_jobs', data_get($f,'max_active_jobs')) }}"
             class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2"
             placeholder="e.g., 2 or 10">
    </div>

    <div>
      <label class="text-sm font-medium text-slate-700">Candidate Profile Views / Day (blank = unlimited)</label>
      <input type="number" min="0"
             name="features[candidate_views_per_day]"
             value="{{ old('features.candidate_views_per_day', data_get($f,'candidate_views_per_day')) }}"
             class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2"
             placeholder="e.g., 5">
    </div>
  </div>

  <div class="grid gap-4 sm:grid-cols-2">
    <div>
      <label class="text-sm font-medium text-slate-700">CV Access</label>
      @php $cv = old('features.cv_access', data_get($f,'cv_access','none')); @endphp
      <select name="features[cv_access]" class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2">
        <option value="none"     {{ $cv==='none' ? 'selected' : '' }}>none</option>
        <option value="preview"  {{ $cv==='preview' ? 'selected' : '' }}>preview only</option>
        <option value="download" {{ $cv==='download' ? 'selected' : '' }}>download</option>
      </select>
    </div>

    <div>
      <label class="text-sm font-medium text-slate-700">Analytics Level</label>
      @php $al = old('features.analytics_level', data_get($f,'analytics_level','basic')); @endphp
      <select name="features[analytics_level]" class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2">
        <option value="basic"     {{ $al==='basic' ? 'selected' : '' }}>basic</option>
        <option value="advanced"  {{ $al==='advanced' ? 'selected' : '' }}>advanced</option>
        <option value="dashboard" {{ $al==='dashboard' ? 'selected' : '' }}>dashboard</option>
      </select>
    </div>
  </div>

  <div class="grid gap-3 sm:grid-cols-2">
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

    @foreach($checks as $key => $label)
      <label class="flex items-center gap-2">
        <input type="checkbox" name="features[{{ $key }}]" value="1" class="rounded border-slate-300"
               {{ old("features.$key", (bool) data_get($f,$key,false)) ? 'checked' : '' }}>
        <span class="text-sm text-slate-700">{{ $label }}</span>
      </label>
    @endforeach
  </div>

  <details class="rounded-xl border border-slate-200 bg-slate-50 p-3">
    <summary class="cursor-pointer text-sm font-semibold text-slate-800">View raw features JSON</summary>
    <pre class="mt-3 overflow-auto rounded-xl bg-white p-3 text-xs border border-slate-200">{{ json_encode($f, JSON_PRETTY_PRINT) }}</pre>
  </details>
</div>  

    <div class="flex justify-end gap-2">
      <a href="{{ route('admin.subscriptions.plans.index') }}"
         class="rounded-xl border border-slate-200 px-4 py-2">Back</a>
      <button class="rounded-xl bg-emerald-600 px-4 py-2 text-white">Update</button>
    </div>
  </form>

</div>
@endsection