@extends('adminpage.layout')
@section('title','Create Plan')
@section('page_title','Create Plan')

@section('content')
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

  <form method="POST" action="{{ route('admin.subscriptions.plans.store') }}"
        class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
    @csrf

    {{-- Header --}}
    <div class="px-6 sm:px-8 py-6 border-b border-slate-200 bg-gradient-to-r from-emerald-50 to-white">
      <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
        <div>
          <h1 class="text-2xl sm:text-3xl font-semibold text-slate-900">Create Subscription Plan</h1>
          <p class="mt-1 text-sm text-slate-600">Define pricing and feature limits for employers.</p>
        </div>
        <div class="text-xs text-slate-500">
          Fields marked <span class="text-red-500 font-semibold">*</span> are required.
        </div>
      </div>
    </div>

    <div class="p-6 sm:p-8 space-y-8">

      {{-- Basics --}}
      <div class="space-y-4">
        <div class="flex items-center justify-between">
          <div>
            <div class="text-sm font-semibold text-slate-900">Plan basics</div>
            <div class="text-xs text-slate-500">Code, name, and pricing.</div>
          </div>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
          <div class="md:col-span-1">
            <label class="text-sm font-medium text-slate-700">Code <span class="text-red-500">*</span></label>
            <input name="code" value="{{ old('code') }}" placeholder="STANDARD"
                   class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm
                          focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400"
                   required>
            <p class="mt-1 text-xs text-slate-500">Uppercase recommended (e.g., STANDARD, GOLD).</p>
          </div>

          <div class="md:col-span-1">
            <label class="text-sm font-medium text-slate-700">Price (PHP) <span class="text-red-500">*</span></label>
            <input type="number" name="price" value="{{ old('price') }}" placeholder="350"
                   class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm
                          focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400"
                   min="0" required>
            <p class="mt-1 text-xs text-slate-500">Whole number amount.</p>
          </div>

          <div class="md:col-span-1">
            <label class="text-sm font-medium text-slate-700">Status</label>
            <div class="mt-1 flex items-center gap-2 rounded-xl border border-slate-200 px-3 py-2.5">
              <input id="is_active" type="checkbox" name="is_active" value="1"
                     class="rounded border-slate-300"
                     {{ old('is_active', 1) ? 'checked' : '' }}>
              <label for="is_active" class="text-sm text-slate-700">Active</label>
            </div>
            <p class="mt-1 text-xs text-slate-500">Inactive plans won’t be available for purchase.</p>
          </div>
        </div>

        <div>
          <label class="text-sm font-medium text-slate-700">Name <span class="text-red-500">*</span></label>
          <input name="name" value="{{ old('name') }}" placeholder="Standard Plan"
                 class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm
                        focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400"
                 required>
        </div>
      </div>

      {{-- Features --}}
      <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5 sm:p-6 space-y-5">
        <div>
          <div class="text-sm font-semibold text-slate-900">Features</div>
          <div class="text-xs text-slate-600">
            Set plan limits/abilities (jobs, views/day, CV access, messaging, analytics).
          </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
          <div class="bg-white rounded-2xl border border-slate-200 p-4">
            <label class="text-sm font-medium text-slate-700">Max Active Jobs <span class="text-slate-400">(blank = unlimited)</span></label>
            <input type="number" min="0"
                   name="features[max_active_jobs]"
                   value="{{ old('features.max_active_jobs') }}"
                   class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm
                          focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400"
                   placeholder="e.g., 2 or 10">
            <div class="mt-2 text-xs text-slate-500">
              Example: Standard = 2, Gold = 10, Platinum = unlimited.
            </div>
          </div>

          <div class="bg-white rounded-2xl border border-slate-200 p-4">
            <label class="text-sm font-medium text-slate-700">Candidate Profile Views / Day <span class="text-slate-400">(blank = unlimited)</span></label>
            <input type="number" min="0"
                   name="features[candidate_views_per_day]"
                   value="{{ old('features.candidate_views_per_day') }}"
                   class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm
                          focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400"
                   placeholder="e.g., 5">
            <div class="mt-2 text-xs text-slate-500">
              Example: Standard = 5/day, Gold/Platinum = unlimited.
            </div>
          </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
          <div class="bg-white rounded-2xl border border-slate-200 p-4">
            <label class="text-sm font-medium text-slate-700">CV Access</label>
            <select name="features[cv_access]"
                    class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm
                           focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400">
              @php $cv = old('features.cv_access','none'); @endphp
              <option value="none"     {{ $cv==='none' ? 'selected' : '' }}>None</option>
              <option value="preview"  {{ $cv==='preview' ? 'selected' : '' }}>Preview only</option>
              <option value="download" {{ $cv==='download' ? 'selected' : '' }}>Download</option>
            </select>
            <div class="mt-2 text-xs text-slate-500">
              Example: Gold = preview only; Platinum = download.
            </div>
          </div>

          <div class="bg-white rounded-2xl border border-slate-200 p-4">
            <label class="text-sm font-medium text-slate-700">Analytics Level</label>
            <select name="features[analytics_level]"
                    class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm
                           focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400">
              @php $al = old('features.analytics_level','basic'); @endphp
              <option value="basic"     {{ $al==='basic' ? 'selected' : '' }}>Basic</option>
              <option value="advanced"  {{ $al==='advanced' ? 'selected' : '' }}>Advanced</option>
              <option value="dashboard" {{ $al==='dashboard' ? 'selected' : '' }}>Dashboard</option>
            </select>
            <div class="mt-2 text-xs text-slate-500">
              Example: Standard = basic; Gold = advanced; Platinum = dashboard + pipeline + conversion tracking.
            </div>
          </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-4">
          <div class="text-sm font-medium text-slate-700 mb-3">Toggle features</div>

          <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
            @php
              $checks = [
                ['key' => 'featured_badge', 'label' => 'Featured badge'],
                ['key' => 'priority_placement', 'label' => 'Priority placement'],
                ['key' => 'advanced_filters', 'label' => 'Advanced filters'],
                ['key' => 'priority_support', 'label' => 'Priority support'],
                ['key' => 'homepage_priority', 'label' => 'Homepage priority / highlights'],
                ['key' => 'can_message_candidates', 'label' => 'Direct messaging'],
                ['key' => 'branding_upgrades', 'label' => 'Branding upgrades'],
                ['key' => 'verification_badge', 'label' => 'Verification badge'],
                ['key' => 'hiring_pipeline', 'label' => 'Hiring pipeline'],
                ['key' => 'conversion_tracking', 'label' => 'Conversion tracking'],
              ];
            @endphp

            @foreach($checks as $c)
              <label class="flex items-center gap-2 rounded-xl border border-slate-200 px-3 py-2 hover:bg-slate-50">
                <input type="checkbox"
                       name="features[{{ $c['key'] }}]"
                       value="1"
                       class="rounded border-slate-300"
                       {{ old('features.'.$c['key']) ? 'checked' : '' }}>
                <span class="text-sm text-slate-700">{{ $c['label'] }}</span>
              </label>
            @endforeach
          </div>

          <div class="mt-4 rounded-xl border border-slate-200 bg-slate-50 p-3 text-xs text-slate-600">
            <div class="font-semibold text-slate-700 mb-1">Notes</div>
            <ul class="list-disc pl-5 space-y-1">
              <li>Gold includes featured badge + higher placement + advanced filters + CV preview only.</li>
              <li>Platinum includes direct messaging + CV/document download + dashboard analytics + pipeline + conversion tracking.</li>
            </ul>
          </div>
        </div>
      </div>

      {{-- Actions --}}
      <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-2">
        <a href="{{ route('admin.subscriptions.plans.index') }}"
           class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">
          Cancel
        </a>
        <button type="submit"
                class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700">
          Save Plan
        </button>
      </div>

    </div>
  </form>

</div>
@endsection