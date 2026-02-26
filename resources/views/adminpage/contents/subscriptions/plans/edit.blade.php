@extends('adminpage.layout')
@section('title', 'Edit Plan')
@section('page_title', 'Edit Plan')

@section('content')
    <div class="w-full max-w-7xl mx-auto space-y-6">

        @if ($errors->any())
            <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                <div class="font-semibold mb-2">Please fix the following:</div>
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $e)
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
                            <span
                                class="rounded-full border border-slate-200 bg-white px-2.5 py-1 text-xs font-mono text-slate-700">
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

                {{-- Features (DYNAMIC) --}}
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5 sm:p-6 space-y-5">
                    <div>
                        <div class="text-sm font-semibold text-slate-900">Features</div>
                        <div class="text-xs text-slate-600">
                            These fields come from Feature Definitions. Blank numeric fields mean unlimited (stored as
                            null).
                        </div>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        @foreach ($features as $fd)
                            @php
                                // existing value from DB (PlanFeature)
                                $existingVal = $existing[$fd->id] ?? null;

                                // value priority: old input -> existing -> default
                                $inputVal = old('feature_values.' . $fd->id, $existingVal ?? $fd->default_value);

                                // Checkbox state
                                $checked = old('feature_values.' . $fd->id, $existingVal ?? $fd->default_value)
                                    ? true
                                    : false;
                            @endphp

                            <div class="bg-white rounded-2xl border border-slate-200 p-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <label class="text-sm font-medium text-slate-700">{{ $fd->label }}</label>
                                        <div class="text-xs text-slate-500 mt-1">
                                            Key: <span class="font-mono">{{ $fd->key }}</span>
                                        </div>
                                    </div>

                                    {{-- Boolean toggle --}}
                                    @if ($fd->type === 'boolean')
                                        <input type="checkbox" name="feature_values[{{ $fd->id }}]" value="1"
                                            class="mt-1 rounded border-slate-300" {{ $checked ? 'checked' : '' }}>
                                    @endif
                                </div>

                                {{-- Number --}}
                                @if ($fd->type === 'number')
                                    <input type="number" min="0" name="feature_values[{{ $fd->id }}]"
                                        value="{{ $inputVal === null ? '' : $inputVal }}"
                                        class="mt-3 w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm
                              focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400"
                                        placeholder="Leave blank = unlimited">
                                    <div class="mt-2 text-xs text-slate-500">Blank means “unlimited” (null).</div>
                                @endif

                                {{-- Select --}}
                                @if ($fd->type === 'select')
                                    <select name="feature_values[{{ $fd->id }}]"
                                        class="mt-3 w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm
                               focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400">
                                        <option value="">— Default —</option>
                                        @foreach ($fd->options ?? [] as $opt)
                                            <option value="{{ $opt }}"
                                                {{ (string) $inputVal === (string) $opt ? 'selected' : '' }}>
                                                {{ ucfirst(str_replace('_', ' ', $opt)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="mt-2 text-xs text-slate-500">If empty, system uses default value.</div>
                                @endif

                                {{-- Text --}}
                                @if ($fd->type === 'text')
                                    <input type="text" name="feature_values[{{ $fd->id }}]"
                                        value="{{ $inputVal }}"
                                        class="mt-3 w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm
                              focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400"
                                        placeholder="Enter value...">
                                @endif
                            </div>
                        @endforeach
                    </div>


                </div>

            </div>
        </form>

    </div>
@endsection
