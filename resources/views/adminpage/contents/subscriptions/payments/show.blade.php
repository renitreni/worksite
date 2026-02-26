@extends('adminpage.layout')
@section('title', 'Payment Details')
@section('page_title', 'Payment Details')

@section('content')
    @php
        $st = $payment->status ?? 'pending';

        $statusBadge = match ($st) {
            'pending' => 'bg-amber-50 text-amber-800 border-amber-200',
            'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'failed' => 'bg-rose-50 text-rose-700 border-rose-200',
            default => 'bg-slate-100 text-slate-700 border-slate-200',
        };

        $method = $payment->method ?? null;
        $methodBadge = match ($method) {
            'gcash' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'cash' => 'bg-slate-100 text-slate-700 border-slate-200',
            default => 'bg-slate-50 text-slate-600 border-slate-200',
        };

        $proofUrl = $payment->proof_path ? asset('storage/' . $payment->proof_path) : null;
        $proofExt = $payment->proof_path ? strtolower(pathinfo($payment->proof_path, PATHINFO_EXTENSION)) : null;
        $isPdf = $proofExt === 'pdf';
    @endphp

    <div class="w-full max-w-7xl mx-auto space-y-6">

        {{-- Header --}}
        <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
            <div class="px-6 sm:px-8 py-6 border-b border-slate-200 bg-gradient-to-r from-emerald-50 to-white">
                <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                    <div>
                        <div class="text-xs text-slate-500">Payment Details</div>
                        <h1 class="mt-1 text-2xl sm:text-3xl font-semibold text-slate-900">
                            ₱{{ number_format((float) $payment->amount, 2) }}
                        </h1>
                        <p class="mt-1 text-sm text-slate-600">
                            Review proof, reference, and verify this payment.
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                        <a href="{{ route('admin.subscriptions.payments.index') }}"
                            class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                            Back
                        </a>

                        @if ($st === 'pending')
                            <form method="POST" action="{{ route('admin.subscriptions.payments.complete', $payment) }}">
                                @csrf
                                <button type="submit"
                                    class="inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700">
                                    Approve Payment
                                </button>
                            </form>

                            <button type="button"
                                class="inline-flex items-center justify-center rounded-2xl border border-rose-200 bg-white px-4 py-2.5 text-sm font-semibold text-rose-700 hover:bg-rose-50"
                                x-data="{ open: false, reason: '' }" @click="open=true" @keydown.escape.window="open=false">

                                Reject

                                <div x-show="open" x-transition.opacity x-cloak
                                    class="fixed inset-0 z-50 flex items-center justify-center p-4"
                                    @click.self="open=false">

                                    <div class="absolute inset-0 bg-black/40"></div>

                                    <div
                                        class="relative w-full max-w-md rounded-3xl border border-slate-200 bg-white shadow-xl overflow-hidden">
                                        <div class="px-6 py-5 border-b border-slate-200 bg-slate-50">
                                            <div class="text-sm font-semibold text-slate-900">Reject Payment</div>
                                            <div class="mt-1 text-xs text-slate-600">Reason is required.</div>
                                        </div>

                                        <form method="POST"
                                            action="{{ route('admin.subscriptions.payments.fail', $payment) }}"
                                            class="p-6 space-y-3">
                                            @csrf
                                            <textarea name="fail_reason" x-model="reason" required rows="4"
                                                class="w-full rounded-2xl border border-slate-200 bg-white p-3 text-sm focus:outline-none focus:ring-4 focus:ring-rose-200"
                                                placeholder="e.g., invalid proof / payment not received"></textarea>

                                            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-2 pt-2">
                                                <button type="button"
                                                    class="rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50"
                                                    @click="open=false">
                                                    Cancel
                                                </button>
                                                <button type="submit"
                                                    class="rounded-2xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-rose-700">
                                                    Confirm Reject
                                                </button>
                                            </div>
                                        </form>
                                    </div>

                                </div>
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Summary cards --}}
            <div class="p-6 sm:p-8 space-y-6">


                <div class="grid gap-4 lg:grid-cols-3">
                    {{-- Status --}}
                    <div class="rounded-2xl border border-slate-200 bg-white p-5">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <div class="text-xs text-slate-500">Status</div>
                                <div class="mt-2">
                                    <span
                                        class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold {{ $statusBadge }}">
                                        {{ ucfirst($st) }}
                                    </span>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-xs text-slate-500">Created</div>
                                <div class="mt-1 text-sm font-semibold text-slate-900">
                                    {{ optional($payment->created_at)->format('M d, Y') }}
                                </div>
                                <div class="text-xs text-slate-500">
                                    {{ optional($payment->created_at)->format('h:i A') }}
                                </div>
                            </div>
                        </div>

                        @if ($payment->verified_at)
                            <div class="mt-4 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <div class="text-xs text-slate-500">Verified at</div>
                                <div class="mt-1 text-sm font-semibold text-slate-900">
                                    {{ optional($payment->verified_at)->format('M d, Y • h:i A') }}
                                </div>
                                <div class="mt-1 text-xs text-slate-600">
                                    By: {{ $payment->verifiedBy?->name ?? '—' }}
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Employer --}}
                    <div class="rounded-2xl border border-slate-200 bg-white p-5">
                        <div class="text-xs text-slate-500">Employer</div>
                        <div class="mt-2 text-sm font-semibold text-slate-900">
                            {{ $payment->employer?->employerProfile?->company_name ?? ($payment->employer?->name ?? '—') }}
                        </div>
                        <div class="mt-1 text-sm text-slate-600">
                            {{ $payment->employer?->email ?? '—' }}
                        </div>

                        <div class="mt-4 grid grid-cols-2 gap-3">
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-3">
                                <div class="text-xs text-slate-500">Method</div>
                                <div class="mt-1">
                                    <span
                                        class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold {{ $methodBadge }}">
                                        {{ $method ? strtoupper($method) : '—' }}
                                    </span>
                                </div>
                            </div>

                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-3">
                                <div class="text-xs text-slate-500">Reference</div>
                                <div class="mt-1 text-sm font-mono text-slate-900 truncate">
                                    {{ $payment->reference ?? '—' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Plan --}}
                    <div class="rounded-2xl border border-slate-200 bg-white p-5">
                        <div class="text-xs text-slate-500">Plan</div>
                        <div class="mt-2 text-sm font-semibold text-slate-900">
                            {{ $payment->plan?->name ?? '—' }}
                        </div>
                        <div class="mt-1 text-xs font-mono text-slate-500">
                            {{ $payment->plan?->code ?? '' }}
                        </div>

                        <div class="mt-4 rounded-2xl border border-emerald-200 bg-emerald-50 p-4">
                            <div class="text-xs text-emerald-800">Amount</div>
                            <div class="mt-1 text-2xl font-semibold text-emerald-900">
                                ₱{{ number_format((float) $payment->amount, 2) }}
                            </div>
                        </div>

                        @if ($payment->subscription_id)
                            <div class="mt-3 text-xs text-slate-600">
                                Subscription ID: <span
                                    class="font-mono text-slate-900">#{{ $payment->subscription_id }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Fail reason --}}
                @if ($st === 'failed' && $payment->fail_reason)
                    <div class="rounded-3xl border border-rose-200 bg-rose-50 p-5">
                        <div class="text-sm font-semibold text-rose-800">Rejected reason</div>
                        <div class="mt-1 text-sm text-rose-800">{{ $payment->fail_reason }}</div>
                    </div>
                @endif

                {{-- Proof preview --}}
                <div class="rounded-3xl border border-slate-200 bg-white overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div>
                                <div class="text-sm font-semibold text-slate-900">Payment Proof</div>
                                <div class="text-xs text-slate-600 mt-1">Preview receipt/screenshot (image/PDF).</div>
                            </div>

                            @if ($proofUrl)
                                <a href="{{ $proofUrl }}" target="_blank"
                                    class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                    Open Fullscreen
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="p-6 sm:p-8">
                        @if (!$proofUrl)
                            <div class="rounded-3xl border border-dashed border-slate-200 bg-slate-50 p-10 text-center">
                                <div class="text-sm font-semibold text-slate-900">No proof uploaded</div>
                                <div class="mt-1 text-sm text-slate-600">Employer did not submit a receipt file.</div>
                            </div>
                        @else
                            <div class="rounded-3xl border border-slate-200 bg-slate-50 overflow-hidden">
                                @if ($isPdf)
                                    <iframe src="{{ $proofUrl }}" class="w-full h-[70vh] bg-white"></iframe>
                                @else
                                    <div class="bg-white">
                                        <img src="{{ $proofUrl }}" alt="Payment proof"
                                            class="w-full max-h-[70vh] object-contain bg-white">
                                    </div>
                                @endif
                            </div>

                            <div class="mt-4 text-xs text-slate-500">
                                File: <span class="font-mono text-slate-700">{{ basename($payment->proof_path) }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- ✅ RECEIPT SLIP --}}
                @php
                    $company =
                        $payment->employer?->employerProfile?->company_name ?? ($payment->employer?->name ?? '—');
                    $email = $payment->employer?->email ?? '—';
                    $planName = $payment->plan?->name ?? '—';
                    $planCode = $payment->plan?->code ?? '—';
                    $amount = (float) ($payment->amount ?? 0);
                    $method = strtoupper($payment->method ?? '—');
                    $ref = $payment->reference ?? '—';

                    $issuedAt = optional($payment->created_at)->format('M d, Y • h:i A');
                    $verifiedAt = $payment->verified_at
                        ? optional($payment->verified_at)->format('M d, Y • h:i A')
                        : null;

                    $statusText = strtoupper($payment->status ?? 'PENDING');
                @endphp

                <div class="rounded-2xl border border-slate-200 bg-white p-5">
        
                        <div class="mx-auto max-w-md">

                            {{-- receipt paper --}}
                            <div class="relative rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">

                                {{-- top torn edge --}}
                                <div class="h-5 bg-slate-50 border-b border-slate-200"></div>

                                <div class="p-5">
                                    <div class="text-center">
                                        <div class="text-xs font-semibold tracking-widest text-slate-500">JOBABROAD</div>
                                        <div class="mt-1 text-lg font-bold text-slate-900">PAYMENT RECEIPT</div>
                                        <div class="mt-2 text-xs text-slate-500">{{ $issuedAt }}</div>
                                    </div>

                                    <div class="my-4 border-t border-dashed border-slate-200"></div>

                                    <div class="space-y-2 text-sm">
                                        <div class="flex justify-between gap-4">
                                            <span class="text-slate-500">Company</span>
                                            <span
                                                class="font-semibold text-slate-900 text-right truncate">{{ $company }}</span>
                                        </div>

                                        <div class="flex justify-between gap-4">
                                            <span class="text-slate-500">Email</span>
                                            <span class="text-slate-700 text-right truncate">{{ $email }}</span>
                                        </div>

                                        <div class="my-2 border-t border-dashed border-slate-200"></div>

                                        <div class="flex justify-between gap-4">
                                            <span class="text-slate-500">Plan</span>
                                            <span
                                                class="font-semibold text-slate-900 text-right">{{ $planName }}</span>
                                        </div>

                                        <div class="flex justify-between gap-4">
                                            <span class="text-slate-500">Plan Code</span>
                                            <span class="font-mono text-slate-800 text-right">{{ $planCode }}</span>
                                        </div>

                                        <div class="flex justify-between gap-4">
                                            <span class="text-slate-500">Method</span>
                                            <span
                                                class="font-semibold text-slate-900 text-right">{{ $method }}</span>
                                        </div>

                                        <div class="flex justify-between gap-4">
                                            <span class="text-slate-500">Reference</span>
                                            <span
                                                class="font-mono text-slate-800 text-right truncate max-w-[220px]">{{ $ref }}</span>
                                        </div>

                                        <div class="my-2 border-t border-dashed border-slate-200"></div>

                                        <div class="flex justify-between items-end gap-4">
                                            <span class="text-slate-500">Amount</span>
                                            <span class="text-xl font-extrabold text-emerald-700">
                                                ₱{{ number_format($amount, 2) }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="my-4 border-t border-dashed border-slate-200"></div>

                                    {{-- footer --}}
                                    <div class="text-center text-xs text-slate-500 space-y-1">
                                        <div class="font-semibold text-slate-700">Thank you!</div>
                                        <div>Verification is done by admin before activation.</div>

                                        @if ($verifiedAt)
                                            <div
                                                class="mt-2 rounded-2xl border border-slate-200 bg-slate-50 px-3 py-2 inline-block">
                                                <div class="text-[11px] text-slate-500">Verified at</div>
                                                <div class="text-xs font-semibold text-slate-800">{{ $verifiedAt }}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- bottom torn edge --}}
                                <div class="h-5 bg-slate-50 border-t border-slate-200"></div>
                            </div>

                </div>

            </div>
        </div>

    </div>
@endsection
