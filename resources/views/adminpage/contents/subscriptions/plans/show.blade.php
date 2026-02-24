@extends('adminpage.layout')
@section('title','Plan Details')
@section('page_title','Plan Details')

@section('content')
<div class="max-w-3xl space-y-6">

  <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <div class="flex items-start justify-between gap-4">
      <div>
        <div class="text-xs text-slate-500">Code</div>
        <div class="font-mono text-slate-800">{{ $plan->code }}</div>

        <div class="mt-3 text-xs text-slate-500">Name</div>
        <div class="text-lg font-semibold text-slate-900">{{ $plan->name }}</div>

        <div class="mt-3 text-xs text-slate-500">Price</div>
        <div class="text-slate-800">₱{{ number_format((int)$plan->price) }}</div>
      </div>

      <div class="flex gap-2">
        <a href="{{ route('admin.subscriptions.plans.edit', $plan) }}"
           class="rounded-xl border border-slate-200 px-4 py-2 hover:bg-slate-50">Edit</a>
        <a href="{{ route('admin.subscriptions.plans.index') }}"
           class="rounded-xl border border-slate-200 px-4 py-2 hover:bg-slate-50">Back</a>
      </div>
    </div>

    <div class="mt-5">
      <div class="text-sm font-semibold text-slate-900">Features</div>
      <pre class="mt-2 overflow-auto rounded-xl bg-slate-50 p-4 text-xs border border-slate-200">{{ json_encode($plan->features, JSON_PRETTY_PRINT) }}</pre>
    </div>
  </div>

</div>
@endsection