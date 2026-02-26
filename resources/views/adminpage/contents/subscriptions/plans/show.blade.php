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
                            <span
                                class="rounded-full border border-slate-200 bg-white px-2.5 py-1 text-xs font-mono text-slate-700">
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
                    // Turn plan_features into a nice list
                    $rows = $plan->featureValues->sortBy(fn($pf) => $pf->definition->sort_order ?? 999999)->values();
                @endphp

                <div>
                    <div class="text-sm font-semibold text-slate-900 mb-4">Features</div>

                    @if ($rows->count() === 0)
                        <div class="rounded-2xl border border-slate-200 bg-white p-4 text-sm text-slate-600">
                            No feature values set for this plan yet.
                        </div>
                    @else
                        <div class="grid gap-4 md:grid-cols-2">
                            @foreach ($rows as $pf)
                                @php
                                    $def = $pf->definition;
                                    $val = $pf->value;

                                    // display formatting
                                    $display = $val;

                                    if ($def->type === 'boolean') {
                                        $display = $val ? 'Enabled' : 'Disabled';
                                    }

                                    if ($def->type === 'number') {
                                        $display = $val === null || $val === '' ? 'Unlimited' : $val;
                                    }

                                    if ($def->type === 'select') {
                                        $display = $val ?? ($def->default_value ?? 'Default');
                                    }

                                    if ($def->type === 'text') {
                                        $display = $val ?? '';
                                    }
                                @endphp

                                <div class="rounded-2xl border border-slate-200 p-4 bg-white">
                                    <div class="text-xs text-slate-500">{{ $def->label }}</div>

                                    <div class="mt-1 text-sm text-slate-900">
                                        @if ($def->type === 'select')
                                            <span class="capitalize">{{ str_replace('_', ' ', (string) $display) }}</span>
                                        @else
                                            {{ $display }}
                                        @endif
                                    </div>

                                    <div class="mt-1 text-[11px] text-slate-400 font-mono">
                                        {{ $def->key }}
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Enabled booleans quick badges --}}
                        @php
                            $enabledBooleans = $rows->filter(
                                fn($pf) => $pf->definition?->type === 'boolean' && (bool) $pf->value,
                            );
                        @endphp

                        @if ($enabledBooleans->count())
                            <div class="mt-6">
                                <div class="text-sm font-semibold text-slate-900 mb-3">Enabled Features</div>
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($enabledBooleans as $pf)
                                        <span class="rounded-full bg-emerald-100 text-emerald-700 text-xs px-3 py-1">
                                            {{ $pf->definition->label }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                    @endif
                </div>

            </div>
        </div>

    </div>
@endsection
