@extends('adminpage.layout')
@section('title','Payment Details')
@section('page_title','Payment Details')

@section('content')
<div class="max-w-3xl space-y-6">

  <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm space-y-4">
    <div class="flex justify-between gap-4">
      <div>
        <div class="text-xs text-slate-500">Employer</div>
        <div class="font-semibold text-slate-900">{{ $payment->employer->name ?? '—' }}</div>
        <div class="text-sm text-slate-600">{{ $payment->employer->email ?? '' }}</div>
      </div>
      <div class="text-right">
        <div class="text-xs text-slate-500">Status</div>
        <div class="font-semibold text-slate-900">{{ $payment->status }}</div>
        <div class="text-xs text-slate-500 mt-2">Created</div>
        <div class="text-sm text-slate-700">{{ optional($payment->created_at)->format('Y-m-d H:i') }}</div>
      </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
      <div class="rounded-xl border border-slate-200 p-4">
        <div class="text-xs text-slate-500">Plan</div>
        <div class="font-semibold text-slate-900">{{ $payment->plan->name ?? '—' }}</div>
        <div class="text-xs font-mono text-slate-500">{{ $payment->plan->code ?? '' }}</div>
      </div>
      <div class="rounded-xl border border-slate-200 p-4">
        <div class="text-xs text-slate-500">Amount</div>
        <div class="text-xl font-semibold text-slate-900">₱{{ number_format((int)$payment->amount) }}</div>
      </div>
    </div>

    @if($payment->status === 'failed' && $payment->fail_reason)
      <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">
        <div class="font-semibold">Fail reason</div>
        <div class="mt-1">{{ $payment->fail_reason }}</div>
      </div>
    @endif

    <div class="flex justify-end gap-2">
      <a href="{{ route('admin.subscriptions.payments.index') }}"
         class="rounded-xl border border-slate-200 px-4 py-2">Back</a>
    </div>
  </div>

</div>
@endsection