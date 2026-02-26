@extends('adminpage.layout')
@section('title', 'Create Plan')
@section('page_title', 'Create Plan')

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
                            <label class="text-sm font-medium text-slate-700">Code <span
                                    class="text-red-500">*</span></label>
                            <input name="code" value="{{ old('code') }}" placeholder="STANDARD"
                                class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm
                          focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400"
                                required>
                            <p class="mt-1 text-xs text-slate-500">Uppercase recommended (e.g., STANDARD, GOLD).</p>
                        </div>

                        <div class="md:col-span-1">
                            <label class="text-sm font-medium text-slate-700">Price (PHP) <span
                                    class="text-red-500">*</span></label>
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
                                    class="rounded border-slate-300" {{ old('is_active', 1) ? 'checked' : '' }}>
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

                {{-- FEATURES (DYNAMIC) --}}
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5 sm:p-6 space-y-5">
                    <div>
                        <div class="text-sm font-semibold text-slate-900">Features</div>
                        <div class="text-xs text-slate-600">
                            Add/edit features dynamically. Admin feature definitions control what appears here.
                        </div>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        @foreach ($features as $f)
                            @php
                                // Use default values when creating (unless user typed old input)
                                $oldVal = old('feature_values.' . $f->id, $f->default_value);

                                // For number: show empty if null (so it looks like "unlimited")
                                $numberVal = $oldVal === null ? '' : $oldVal;

                                // For boolean
                                $checked = (bool) old('feature_values.' . $f->id, $f->default_value ? 1 : 0);
                            @endphp

                            <div class="bg-white rounded-2xl border border-slate-200 p-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <label class="text-sm font-medium text-slate-700">{{ $f->label }}</label>
                                        <div class="text-xs text-slate-500 mt-1">
                                            Key: <span class="font-mono">{{ $f->key }}</span>
                                        </div>
                                    </div>

                                    {{-- Boolean toggle --}}
                                    @if ($f->type === 'boolean')
                                        <input type="checkbox" name="feature_values[{{ $f->id }}]" value="1"
                                            class="mt-1 rounded border-slate-300" {{ $checked ? 'checked' : '' }}>
                                    @endif
                                </div>

                                {{-- Number --}}
                                @if ($f->type === 'number')
                                    <input type="number" min="0" name="feature_values[{{ $f->id }}]"
                                        value="{{ $numberVal }}"
                                        class="mt-3 w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm
                    focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400"
                                        placeholder="Leave blank = unlimited">
                                    <div class="mt-2 text-xs text-slate-500">Blank means “unlimited” (stored as null).</div>
                                @endif

                                {{-- Select --}}
                                @if ($f->type === 'select')
                                    @php $val = old('feature_values.'.$f->id, $f->default_value); @endphp
                                    <select name="feature_values[{{ $f->id }}]"
                                        class="mt-3 w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm
                     focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400">
                                        <option value="">— Default —</option>
                                        @foreach ($f->options ?? [] as $opt)
                                            <option value="{{ $opt }}"
                                                {{ (string) $val === (string) $opt ? 'selected' : '' }}>
                                                {{ ucfirst(str_replace('_', ' ', $opt)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="mt-2 text-xs text-slate-500">If empty, system uses default value.</div>
                                @endif

                                {{-- Text --}}
                                @if ($f->type === 'text')
                                    <input type="text" name="feature_values[{{ $f->id }}]"
                                        value="{{ $oldVal }}"
                                        class="mt-3 w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm
                    focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400"
                                        placeholder="Enter value...">
                                @endif
                            </div>
                        @endforeach
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
