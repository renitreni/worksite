@extends('adminpage.layout')
@section('title', 'Employer Requests')
@section('page_title', 'Employer Management')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="text-lg font-semibold text-slate-900">
            Employer Admin Requests
        </div>
        <div class="text-xs text-slate-500 mt-1">
            Manage employers requesting admin account management
        </div>
    </div>

    {{-- TABLE --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 p-5 text-sm font-semibold">
            Requests List
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">

                <thead class="bg-slate-50 text-xs font-semibold text-slate-600">
                    <tr>
                        <th class="px-5 py-3">Employer</th>
                        <th class="px-5 py-3">Message</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3">Date</th>
                        <th class="px-5 py-3">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-200">

                    @forelse($requests as $req)
                        <tr class="hover:bg-slate-50">

                            {{-- Employer --}}
                            <td class="px-5 py-4">
                                <div class="font-semibold text-slate-900">
                                    {{ $req->employer->company_name }}
                                </div>
                                <div class="text-xs text-slate-500">
                                    {{ $req->employer->user->email }}
                                </div>
                            </td>

                            {{-- Message --}}
                            <td class="px-5 py-4 text-slate-600">
                                {{ $req->message ?? '—' }}
                            </td>

                            {{-- Status --}}
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1
                                    {{ $req->status === 'approved'
                                        ? 'bg-emerald-50 text-emerald-700 ring-emerald-200'
                                        : ($req->status === 'declined'
                                            ? 'bg-rose-50 text-rose-700 ring-rose-200'
                                            : 'bg-blue-50 text-blue-700 ring-blue-200') }}">
                                    {{ ucfirst($req->status) }}
                                </span>
                            </td>

                            {{-- Date --}}
                            <td class="px-5 py-4 text-xs text-slate-500">
                                {{ $req->created_at->format('Y-m-d H:i') }}
                            </td>

                            {{-- Actions --}}
                            <td class="px-5 py-4">

                                @if($req->status === 'pending')
                                <div class="flex gap-2">

                                    <form method="POST" action="{{ route('admin.employer-requests.approve', $req->id) }}">
                                        @csrf
                                        <button class="px-3 py-1.5 text-xs rounded-lg bg-emerald-600 text-white hover:bg-emerald-700">
                                            Approve
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('admin.employer-requests.decline', $req->id) }}">
                                        @csrf
                                        <button class="px-3 py-1.5 text-xs rounded-lg bg-red-600 text-white hover:bg-red-700">
                                            Decline
                                        </button>
                                    </form>

                                </div>
                                @else
                                    <span class="text-xs text-slate-400">
                                        No actions
                                    </span>
                                @endif

                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-10 text-center text-sm text-slate-500">
                                No employer requests found.
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>
        </div>

    </div>

</div>

@endsection