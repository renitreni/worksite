@extends('employer.layout')

@section('content')
    @php
        $company = trim($employerProfile->company_name ?? '');
        $letter = $company !== '' ? strtoupper(mb_substr($company, 0, 1)) : 'C';
    @endphp

    <div class="space-y-6">

        {{-- Header (match Active Posting style) --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-semibold text-slate-900">Company Profile</h1>
                <p class="text-sm text-slate-600 mt-1">This is how your profile looks.</p>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('employer.company-profile.edit') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-2xl bg-emerald-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-4 focus:ring-emerald-200 transition">
                    <i data-lucide="pencil" class="h-4 w-4"></i>
                    Edit Profile
                </a>

                <form action="{{ route('employer.delete-account') }}" method="POST"
                    onsubmit="return confirm('Are you sure you want to delete your account?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-2xl border border-rose-200 bg-rose-50 px-6 py-3 text-sm font-semibold text-rose-700 shadow-sm hover:bg-rose-100 focus:outline-none focus:ring-4 focus:ring-rose-200 transition">
                        <i data-lucide="trash-2" class="h-4 w-4"></i>
                        Delete
                    </button>
                </form>
            </div>
        </div>

        {{-- Flash (same vibe as Active Posting flash) --}}
        @if(session('success'))
            <x-toast type="success" :message="session('success')" />
        @endif

        @if(session('danger'))
            <x-toast type="danger" :message="session('danger')" />
        @endif

        {{-- Profile Card (match table container: rounded-3xl, border-slate, shadow-sm) --}}
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">

            {{-- Cover --}}
            <div class="h-40 sm:h-80 bg-slate-100">
                @if($employerProfile->cover_path)
                    <img src="{{ asset('storage/' . $employerProfile->cover_path) }}" class="w-full h-full object-cover"
                        alt="Cover">
                @else
                    <img src="{{ asset('images/cover.png') }}" class="w-full h-full object-cover" alt="Philippines Flag">
                @endif
            </div>

            {{-- Main --}}
            <div class="p-6 sm:p-8">
                <div class="flex flex-col sm:flex-row gap-6 sm:items-start">

                    {{-- Logo --}}
                    <div class="-mt-14 sm:-mt-16">
                        <div
                            class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl bg-white border border-slate-200 shadow-sm overflow-hidden flex items-center justify-center">
                            @if($employerProfile->logo_path)
                                <img src="{{ asset('storage/' . $employerProfile->logo_path) }}"
                                    class="w-full h-full object-cover" alt="Logo">
                            @else
                                <div
                                    class="w-full h-full flex items-center justify-center bg-emerald-600 text-white font-black text-3xl sm:text-4xl">
                                    {{ $letter }}
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Details --}}
                    <div class="flex-1 min-w-0">
                        <h2 class="text-xl sm:text-2xl font-bold text-slate-900">
                            {{ $employerProfile->company_name ?: '—' }}
                        </h2>

                        <div class="mt-2 flex flex-wrap items-center gap-3 text-sm text-slate-600">
                            <span class="inline-flex items-center gap-2">
                                <i data-lucide="map-pin" class="w-4 h-4 text-slate-400"></i>
                                {{ $employerProfile->company_address ?: '—' }}
                            </span>

                            <span class="inline-flex items-center gap-2">
                                <i data-lucide="mail" class="w-4 h-4 text-slate-400"></i>
                                {{ $email }}
                            </span>
                        </div>

                        <p class="text-sm text-justify mt-4 text-slate-700 leading-relaxed">
                            {{ $employerProfile->description ?: 'No intro/description yet.' }}
                        </p>

                        {{-- Industries --}}
                        {{-- Industries (Pivot) --}}
                        @php
                            // industries is now a relation (Collection)
                            $inds = $employerProfile->industries?->pluck('name')->filter()->values() ?? collect();
                        @endphp

                        <div class="mt-5">
                            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Industries</p>

                            @if($inds->count() === 0)
                                <div class="mt-2 text-xs text-slate-500">No industries set.</div>
                            @else
                                <div class="mt-2 flex flex-wrap gap-2">
                                    @foreach($inds as $chip)
                                                        <span class="inline-flex items-center gap-1.5 rounded-full
                                        border border-emerald-200 bg-emerald-50
                                        px-3 py-1 text-xs font-semibold text-emerald-700">
                                                            <i data-lucide="tag" class="h-3.5 w-3.5"></i>
                                                            {{ $chip }}
                                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        {{-- Stats --}}
                        <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Status</p>

                                @php
                                    $vStatus = $employerProfile->verification?->status ?? 'pending';
                                  @endphp

                                <p class="mt-1 font-bold capitalize
            {{ $vStatus === 'approved' ? 'text-emerald-700' : '' }}
            {{ $vStatus === 'rejected' ? 'text-rose-700' : '' }}
            {{ $vStatus === 'suspended' ? 'text-slate-900' : '' }}
            {{ $vStatus === 'pending' ? 'text-amber-700' : '' }}
          ">
                                    {{ $vStatus }}
                                </p>
                            </div>

                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Profile Views</p>
                                <p class="mt-1 font-bold text-slate-900">
                                    {{ number_format($employerProfile->total_profile_views ?? 0) }}
                                </p>
                            </div>

                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Website</p>
                                <p class="mt-1 font-bold text-slate-900 truncate">
                                    {{ $employerProfile->company_website ?: '—' }}
                                </p>
                            </div>
                        </div>

                        {{-- Extra details --}}
                        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                            <div class="rounded-2xl border border-slate-200 p-4">
                                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Phone</p>
                                <p class="mt-1 font-semibold text-slate-900">
                                    {{ $employerProfile->company_contact ?: '—' }}
                                </p>
                            </div>

                            <div class="rounded-2xl border border-slate-200 p-4">
                                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Representative</p>
                                <p class="mt-1 font-semibold text-slate-900">
                                    {{ $employerProfile->representative_name ?: '—' }}
                                    <span
                                        class="text-slate-500 font-normal">({{ $employerProfile->position ?: '—' }})</span>
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection