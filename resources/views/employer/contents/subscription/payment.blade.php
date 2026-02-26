@extends('employer.layout')

@section('content')
    @php
        $amount = (float) $subscription->plan->price;

        // ✅ Put your actual admin GCash details here
        $gcashName = config('app.gcash_name', 'JOBABROAD PAYMENTS');
        $gcashNumber = config('app.gcash_number', '09XX-XXX-XXXX');

        // ✅ Put your QR image in: public/images/gcash-qr.png
        $gcashQr = asset('images/gcash-qr.png');

        // optional invoice code shown to employer
        $invoiceRef = 'SUB-' . $subscription->id . '-' . now()->format('Ymd');
    @endphp

    <div class="max-w-4xl mx-auto space-y-6">

        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
            <div>
                <h1 class="text-3xl font-semibold text-slate-900">Payment</h1>
                <p class="mt-1 text-sm text-slate-600">
                    Pay for <span class="font-semibold text-slate-900">{{ $subscription->plan->name }}</span>.
                    Activation happens after admin verification.
                </p>
            </div>

            <div class="inline-flex items-center rounded-2xl bg-emerald-600 text-white px-5 py-3 font-semibold shadow-sm">
                ₱{{ number_format($amount, 2) }}
                <span class="ml-2 text-xs font-semibold text-emerald-100">/ month</span>
            </div>
        </div>

        <x-toast type="success" :message="session('success')" />
        <x-toast type="error" :message="session('error')" />

        @if ($errors->any())
            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
                <div class="font-semibold mb-2">Please fix the following:</div>
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('employer.subscription.pay', $subscription->id) }}" method="POST"
            enctype="multipart/form-data" class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden"
            x-data="{ method: '{{ old('method', 'gcash') }}' }">
            @csrf

            <div class="px-6 sm:px-8 py-6 border-b border-slate-200 bg-gradient-to-r from-emerald-50 to-white">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                    <div>
                        <div class="text-sm font-semibold text-slate-900">Choose payment method</div>
                        <div class="text-xs text-slate-600 mt-1">GCash (QR) or Cash (manual verification).</div>
                    </div>

                    <div class="rounded-2xl border border-emerald-200 bg-white px-4 py-2">
                        <div class="text-xs text-slate-500">Invoice Ref</div>
                        <div class="text-sm font-semibold text-slate-900 font-mono">{{ $invoiceRef }}</div>
                    </div>
                </div>
            </div>

            <div class="p-6 sm:p-8 space-y-6">

                {{-- Method cards --}}
                <div class="grid gap-4 md:grid-cols-2">
                    <label
                        class="cursor-pointer rounded-3xl border border-slate-200 bg-white p-4 hover:bg-slate-50 transition"
                        :class="method === 'gcash' ? 'ring-2 ring-emerald-200 border-emerald-300' : ''">
                        <div class="flex items-start gap-3">
                            <input type="radio" name="method" value="gcash" class="mt-1" @change="method='gcash'"
                                {{ old('method', 'gcash') === 'gcash' ? 'checked' : '' }}>
                            <div>
                                <div class="text-sm font-semibold text-slate-900">GCash QR</div>
                                <div class="text-xs text-slate-600 mt-1">Scan QR and submit reference + proof.</div>
                                <div
                                    class="mt-3 inline-flex items-center rounded-full bg-emerald-100 text-emerald-800 text-xs px-3 py-1 font-semibold">
                                    Fastest
                                </div>
                            </div>
                        </div>
                    </label>

                    <label
                        class="cursor-pointer rounded-3xl border border-slate-200 bg-white p-4 hover:bg-slate-50 transition"
                        :class="method === 'cash' ? 'ring-2 ring-emerald-200 border-emerald-300' : ''">
                        <div class="flex items-start gap-3">
                            <input type="radio" name="method" value="cash" class="mt-1" @change="method='cash'"
                                {{ old('method') === 'cash' ? 'checked' : '' }}>
                            <div>
                                <div class="text-sm font-semibold text-slate-900">Cash</div>
                                <div class="text-xs text-slate-600 mt-1">Pay in person / manual confirmation.</div>
                            </div>
                        </div>
                    </label>
                </div>

                {{-- GCash instructions --}}
                <div x-show="method==='gcash'" x-cloak class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                    <div class="flex flex-col lg:flex-row gap-5">
                        <div class="w-full lg:w-[320px]">
                            <div class="text-sm font-semibold text-slate-900">Scan to pay</div>
                            <div
                                class="mt-3 rounded-3xl border border-slate-200 bg-white p-4 flex items-center justify-center">
                                <img src="{{ $gcashQr }}" alt="GCash QR" class="w-56 h-56 object-contain">
                            </div>
                            <div class="mt-3 text-xs text-slate-600">
                                GCash Name: <span class="font-semibold text-slate-900">{{ $gcashName }}</span><br>
                                GCash No.: <span class="font-semibold text-slate-900">{{ $gcashNumber }}</span>
                            </div>
                        </div>

                        <div class="flex-1 space-y-4">
                            <div class="text-sm font-semibold text-slate-900">Submit payment details</div>
                            <p class="text-sm text-slate-600">
                                After paying via GCash, enter the <span class="font-semibold">Reference Number</span> and
                                upload your receipt/screenshot.
                            </p>

                            <div>
                                <label class="block text-sm font-medium text-slate-700">GCash Reference Number <span
                                        class="text-rose-500">*</span></label>
                                <input type="text" name="gcash_reference" value="{{ old('gcash_reference') }}"
                                    class="mt-1 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm
              focus:outline-none focus:ring-4 focus:ring-emerald-200"
                                    placeholder="e.g., 1234567890">
                                <p class="mt-1 text-xs text-slate-500">
                                    Tip: include invoice ref <span class="font-mono">{{ $invoiceRef }}</span> in notes if
                                    possible.
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700">Upload Proof <span
                                        class="text-rose-500">*</span></label>
                                <input type="file" name="proof" accept="image/*,application/pdf"
                                    class="mt-1 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm">
                                <p class="mt-1 text-xs text-slate-500">Accepted: JPG, PNG, PDF (max 4MB).</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Cash instructions --}}
                <div x-show="method==='cash'" x-cloak class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                    <div class="text-sm font-semibold text-slate-900">Cash payment instructions</div>
                    <p class="mt-2 text-sm text-slate-600">
                        Proceed with cash payment. After payment is received, admin will verify and activate your
                        subscription.
                    </p>
                    <div class="mt-3 rounded-2xl border border-emerald-200 bg-white p-4">
                        <div class="text-xs text-slate-500">Reminder</div>
                        <div class="text-sm text-slate-700">
                            Use this Invoice Ref: <span
                                class="font-mono font-semibold text-slate-900">{{ $invoiceRef }}</span>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-slate-700">Notes (optional)</label>
                        <input type="text" name="cash_note" value="{{ old('cash_note') }}"
                            class="mt-1 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm
              focus:outline-none focus:ring-4 focus:ring-emerald-200"
                            placeholder="Optional notes for admin (e.g., where/when you paid)">
                    </div>
                </div>

                <button type="submit"
                    class="inline-flex w-full items-center justify-center rounded-2xl bg-emerald-600 px-6 py-3 text-sm font-semibold text-white shadow-sm
                     hover:bg-emerald-700 focus:outline-none focus:ring-4 focus:ring-emerald-200 transition">
                    Submit Payment
                </button>

                <p class="text-xs text-slate-500">
                    Note: Your payment will be reviewed by admin. Subscription activates after verification.
                </p>

            </div>
        </form>
    </div>
@endsection
