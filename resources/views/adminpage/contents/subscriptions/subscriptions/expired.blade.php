@extends('adminpage.layout')
@section('title', 'Expired Subscriptions')
@section('page_title', 'Expired Subscriptions')

@section('content')
    @php
        $q = $q ?? request('q', '');
    @endphp

    <div class="space-y-6">

        @include('adminpage.components.flash')

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <form method="GET" action="{{ route('admin.subscriptions.expired') }}"
                class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:flex-wrap">
                    <div
                        class="flex w-full items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 sm:w-96">
                        <span class="text-slate-400">⌕</span>
                        <input name="q" value="{{ $q }}"
                            class="w-full bg-transparent text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none"
                            placeholder="Search employer name/email..." />
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <button
                        class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
                        Search
                    </button>

                    @if ($q)
                        <a href="{{ route('admin.subscriptions.expired') }}"
                            class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                            Clear
                        </a>
                    @endif
                </div>

            </form>
        </div>


        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-5 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-semibold text-slate-900">Expired Subscriptions</h2>
                        <p class="mt-0.5 text-xs text-slate-500">
                            These subscriptions have passed their end date.
                        </p>
                    </div>
                    <p class="text-xs text-slate-500">Total: {{ $subs->total() }}</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs font-semibold text-slate-600">
                        <tr>
                            <th class="px-5 py-3">Employer</th>
                            <th class="px-5 py-3">Plan</th>
                            <th class="px-5 py-3">Ended</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3 text-right">Reminder</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200">
                        @forelse($subs as $s)
                            <tr class="hover:bg-slate-50">

                                <td class="px-5 py-3">
                                    <div class="font-semibold text-slate-900">
                                        {{ $s->employerProfile?->company_name ?? '—' }}
                                    </div>
                                    <div class="text-xs text-slate-500">
                                        {{ $s->employerProfile?->user?->email ?? '' }}
                                    </div>
                                </td>

                                <td class="px-5 py-3">
                                    <div class="font-semibold text-slate-900">{{ $s->plan->name ?? '—' }}</div>
                                    <div class="text-xs font-mono text-slate-500">{{ $s->plan->code ?? '' }}</div>
                                </td>

                                <td class="px-5 py-3 text-slate-600">
                                    {{ optional($s->ends_at)->format('M d, Y') ?? '—' }}
                                </td>

                                <td class="px-5 py-3">
                                    @php $st = $s->subscription_status ?? '—'; @endphp

                                    @if ($st === 'expired')
                                        <span
                                            class="inline-flex items-center rounded-full bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-700 border border-rose-200">
                                            Expired
                                        </span>
                                    @elseif($st === 'active')
                                        <span
                                            class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 border border-emerald-200">
                                            Active
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600 border border-slate-200">
                                            {{ $st === '—' ? '—' : ucfirst($st) }}
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-3">
                                    <div class="flex justify-end">
                                        <form method="POST" action="{{ route('admin.subscriptions.remind', $s) }}">
                                            @csrf
                                            <button type="submit"
                                                class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                                                Send Reminder
                                            </button>
                                        </form>
                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-10 text-center text-slate-500">
                                    No expired subscriptions.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-200 px-5 py-4">
                {{ $subs->links() }}
            </div>
        </div>

    </div>
@endsection
