@extends('adminpage.layout')
@section('title','Create Plan')
@section('page_title','Create Plan')

@section('content')
<div class="max-w-3xl space-y-6">

  @if($errors->any())
    <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
      <ul class="list-disc pl-5">
        @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('admin.subscriptions.plans.store') }}"
        class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm space-y-4">
    @csrf

    <div class="grid gap-4 sm:grid-cols-2">
      <div>
        <label class="text-sm font-medium text-slate-700">Code</label>
        <input name="code" value="{{ old('code') }}" placeholder="STANDARD"
               class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2" required>
      </div>

      <div>
        <label class="text-sm font-medium text-slate-700">Price (PHP)</label>
        <input type="number" name="price" value="{{ old('price') }}" placeholder="350"
               class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2" min="0" required>
      </div>
    </div>

    <div>
      <label class="text-sm font-medium text-slate-700">Name</label>
      <input name="name" value="{{ old('name') }}" placeholder="Standard Plan"
             class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2" required>
    </div>

    <div class="flex items-center gap-2">
      <input id="is_active" type="checkbox" name="is_active" value="1" class="rounded border-slate-300"
             {{ old('is_active', 1) ? 'checked' : '' }}>
      <label for="is_active" class="text-sm text-slate-700">Active</label>
    </div>

    {{-- Features --}}
<div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm space-y-4">
  <div>
    <div class="text-sm font-semibold text-slate-900">Features</div>
    <div class="text-xs text-slate-500">
      Set plan limits/abilities based on your plan rules (jobs, views/day, CV access, messaging, analytics). 
    </div>
  </div>

  <div class="grid gap-4 sm:grid-cols-2">
    <div>
      <label class="text-sm font-medium text-slate-700">Max Active Jobs (blank = unlimited)</label>
      <input type="number" min="0"
             name="features[max_active_jobs]"
             value="{{ old('features.max_active_jobs') }}"
             class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2"
             placeholder="e.g., 2 or 10">
      <div class="mt-1 text-xs text-slate-500">
        Standard = 2, Gold = 10, Platinum = unlimited. :contentReference[oaicite:4]{index=4} :contentReference[oaicite:5]{index=5} :contentReference[oaicite:6]{index=6}
      </div>
    </div>

    <div>
      <label class="text-sm font-medium text-slate-700">Candidate Profile Views / Day (blank = unlimited)</label>
      <input type="number" min="0"
             name="features[candidate_views_per_day]"
             value="{{ old('features.candidate_views_per_day') }}"
             class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2"
             placeholder="e.g., 5">
      <div class="mt-1 text-xs text-slate-500">
        Standard = 5/day, Gold/Platinum = unlimited. :contentReference[oaicite:7]{index=7} :contentReference[oaicite:8]{index=8}
      </div>
    </div>
  </div>

  <div class="grid gap-4 sm:grid-cols-2">
    <div>
      <label class="text-sm font-medium text-slate-700">CV Access</label>
      <select name="features[cv_access]" class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2">
        @php $cv = old('features.cv_access','none'); @endphp
        <option value="none"    {{ $cv==='none' ? 'selected' : '' }}>none</option>
        <option value="preview" {{ $cv==='preview' ? 'selected' : '' }}>preview only</option>
        <option value="download"{{ $cv==='download' ? 'selected' : '' }}>download</option>
      </select>
      <div class="mt-1 text-xs text-slate-500">
        Gold = preview only; Platinum = download. :contentReference[oaicite:9]{index=9} :contentReference[oaicite:10]{index=10}
      </div>
    </div>

    <div>
      <label class="text-sm font-medium text-slate-700">Analytics Level</label>
      <select name="features[analytics_level]" class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2">
        @php $al = old('features.analytics_level','basic'); @endphp
        <option value="basic"    {{ $al==='basic' ? 'selected' : '' }}>basic</option>
        <option value="advanced" {{ $al==='advanced' ? 'selected' : '' }}>advanced</option>
        <option value="dashboard"{{ $al==='dashboard' ? 'selected' : '' }}>dashboard</option>
      </select>
      <div class="mt-1 text-xs text-slate-500">
        Standard = basic; Gold = advanced; Platinum = dashboard + pipeline + conversion tracking. :contentReference[oaicite:11]{index=11} :contentReference[oaicite:12]{index=12} :contentReference[oaicite:13]{index=13}
      </div>
    </div>
  </div>

  <div class="grid gap-3 sm:grid-cols-2">
    <label class="flex items-center gap-2">
      <input type="checkbox" name="features[featured_badge]" value="1" class="rounded border-slate-300"
             {{ old('features.featured_badge') ? 'checked' : '' }}>
      <span class="text-sm text-slate-700">Featured badge</span>
    </label>

    <label class="flex items-center gap-2">
      <input type="checkbox" name="features[priority_placement]" value="1" class="rounded border-slate-300"
             {{ old('features.priority_placement') ? 'checked' : '' }}>
      <span class="text-sm text-slate-700">Priority placement</span>
    </label>

    <label class="flex items-center gap-2">
      <input type="checkbox" name="features[advanced_filters]" value="1" class="rounded border-slate-300"
             {{ old('features.advanced_filters') ? 'checked' : '' }}>
      <span class="text-sm text-slate-700">Advanced filters</span>
    </label>

    <label class="flex items-center gap-2">
      <input type="checkbox" name="features[priority_support]" value="1" class="rounded border-slate-300"
             {{ old('features.priority_support') ? 'checked' : '' }}>
      <span class="text-sm text-slate-700">Priority support</span>
    </label>

    <label class="flex items-center gap-2">
      <input type="checkbox" name="features[homepage_priority]" value="1" class="rounded border-slate-300"
             {{ old('features.homepage_priority') ? 'checked' : '' }}>
      <span class="text-sm text-slate-700">Homepage priority / highlights</span>
    </label>

    <label class="flex items-center gap-2">
      <input type="checkbox" name="features[can_message_candidates]" value="1" class="rounded border-slate-300"
             {{ old('features.can_message_candidates') ? 'checked' : '' }}>
      <span class="text-sm text-slate-700">Direct messaging</span>
    </label>

    <label class="flex items-center gap-2">
      <input type="checkbox" name="features[branding_upgrades]" value="1" class="rounded border-slate-300"
             {{ old('features.branding_upgrades') ? 'checked' : '' }}>
      <span class="text-sm text-slate-700">Branding upgrades</span>
    </label>

    <label class="flex items-center gap-2">
      <input type="checkbox" name="features[verification_badge]" value="1" class="rounded border-slate-300"
             {{ old('features.verification_badge') ? 'checked' : '' }}>
      <span class="text-sm text-slate-700">Verification badge</span>
    </label>

    <label class="flex items-center gap-2">
      <input type="checkbox" name="features[hiring_pipeline]" value="1" class="rounded border-slate-300"
             {{ old('features.hiring_pipeline') ? 'checked' : '' }}>
      <span class="text-sm text-slate-700">Hiring pipeline</span>
    </label>

    <label class="flex items-center gap-2">
      <input type="checkbox" name="features[conversion_tracking]" value="1" class="rounded border-slate-300"
             {{ old('features.conversion_tracking') ? 'checked' : '' }}>
      <span class="text-sm text-slate-700">Conversion tracking</span>
    </label>
  </div>

  <div class="rounded-xl border border-slate-200 bg-slate-50 p-3 text-xs text-slate-600">
    Notes from your plan definitions:
    <ul class="list-disc pl-5 mt-2 space-y-1">
      <li>Gold includes featured badge + higher placement + advanced filters + CV preview only. :contentReference[oaicite:14]{index=14} :contentReference[oaicite:15]{index=15}</li>
      <li>Platinum includes direct messaging + CV/document download + dashboard analytics + pipeline + conversion tracking. :contentReference[oaicite:16]{index=16} :contentReference[oaicite:17]{index=17}</li>
    </ul>
  </div>
</div>

    <div class="flex justify-end gap-2">
      <a href="{{ route('admin.subscriptions.plans.index') }}"
         class="rounded-xl border border-slate-200 px-4 py-2">Cancel</a>
      <button class="rounded-xl bg-emerald-600 px-4 py-2 text-white">Save</button>
    </div>
  </form>

</div>
@endsection