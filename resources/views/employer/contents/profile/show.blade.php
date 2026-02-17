@extends('employer.layout')

@section('content')
    @php
        $company = trim($employerProfile->company_name ?? '');
        $letter = $company !== '' ? strtoupper(mb_substr($company, 0, 1)) : 'C';
    @endphp

    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex items-start sm:items-center justify-between gap-4 flex-col sm:flex-row">
            <div>
                <h1 class="text-xl sm:text-2xl font-semibold text-gray-900">Company Profile</h1>
                <p class="text-sm text-gray-600 mt-1">This is how your profile looks.</p>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('employer.company-profile.edit') }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700 transition">
                    <i data-lucide="pencil" class="h-4 w-4"></i>
                    Edit Profile
                </a>

                <form action="{{ route('employer.delete-account') }}" method="POST"
                    onsubmit="return confirm('Are you sure you want to delete your account?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl border border-red-200 bg-red-50 px-5 py-2.5 text-sm font-semibold text-red-700 hover:bg-red-100 transition">
                        <i data-lucide="trash-2" class="h-4 w-4"></i>
                        Delete
                    </button>
                </form>
            </div>
        </div>

        {{-- Toast (floating) --}}
        <div x-data="{
                    show: @js(session()->has('success') || session()->has('danger')),
                    type: @js(session('success') ? 'success' : (session('danger') ? 'danger' : '')),
                    message: @js(session('success') ?? session('danger') ?? ''),
                    init() {
                        if (this.show) setTimeout(() => this.show = false, 3500);
                        this.$nextTick(() => { if (window.lucide) window.lucide.createIcons(); });
                    }
                }" x-init="init()" x-show="show" x-transition.opacity.duration.200ms x-cloak
            class="fixed top-5 right-5 z-[9999] w-[92vw] max-w-sm">
            <div class="rounded-2xl border shadow-lg p-4 text-sm flex items-start gap-3" :class="type === 'success'
                        ? 'bg-emerald-50 border-emerald-200 text-emerald-800'
                        : 'bg-red-50 border-red-200 text-red-800'">
                <div class="mt-0.5">
                    <i data-lucide="info" class="h-4 h-4 w-4"></i>
                </div>

                <div class="flex-1">
                    <p class="font-semibold" x-text="type === 'success' ? 'Success' : 'Notice'"></p>
                    <p class="mt-0.5" x-text="message"></p>
                </div>

                <button type="button" class="text-xs underline opacity-80 hover:opacity-100" @click="show=false">
                    Close
                </button>
            </div>
        </div>

        {{-- Profile Card --}}
        <div class="rounded-2xl bg-white border border-gray-200 shadow-sm overflow-hidden">

            {{-- Cover --}}
            <div class="h-40 sm:h-80 bg-gray-100">
                @if($employerProfile->cover_path)
                    <img src="{{ asset('storage/' . $employerProfile->cover_path) }}" class="w-full h-full object-cover"
                        alt="Cover">
                @else
                    {{-- ✅ PH flag placeholder --}}
                    <img src="{{ asset('images/cover.png') }}" class="w-full h-full object-cover" alt="Philippines Flag">
                @endif
            </div>

            {{-- Main --}}
            <div class="p-6">
                <div class="flex flex-col sm:flex-row gap-5 sm:items-start">

                    {{-- Logo --}}
                    <div class="-mt-14 sm:-mt-16">
                        <div
                            class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl bg-white border border-gray-200 shadow-sm overflow-hidden flex items-center justify-center">
                            @if($employerProfile->logo_path)
                                <img src="{{ asset('storage/' . $employerProfile->logo_path) }}"
                                    class="w-full h-full object-cover" alt="Logo">
                            @else
                                {{-- ✅ First letter placeholder --}}
                                <div
                                    class="w-full h-full flex items-center justify-center bg-emerald-600 text-white font-black text-3xl sm:text-4xl">
                                    {{ $letter }}
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Details --}}
                    <div class="flex-1 min-w-0">
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900">
                            {{ $employerProfile->company_name ?: '—' }}
                        </h2>

                        <div class="mt-2 flex flex-wrap items-center gap-3 text-sm text-gray-600">
                            <span class="inline-flex items-center gap-2">
                                <i data-lucide="map-pin" class="w-4 h-4 text-gray-400"></i>
                                {{ $employerProfile->company_address ?: '—' }}
                            </span>

                            <span class="inline-flex items-center gap-2">
                                <i data-lucide="mail" class="w-4 h-4 text-gray-400"></i>
                                {{ $email }}
                            </span>
                        </div>

                        <p class="text-sm text-justify mt-4 text-gray-700 leading-relaxed">
                            {{ $employerProfile->description ?: 'No intro/description yet.' }}
                        </p>

              
                        {{-- Industries --}}
                        @php
                            $inds = is_array($employerProfile->industries) ? $employerProfile->industries : [];
                        @endphp

                        <div class="mt-4">
                            <p class="text-xs font-semibold text-gray-500">Industries</p>

                            @if(count($inds) === 0)
                                <div class="mt-2 text-xs text-gray-500">No industries set.</div>
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
                            <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4">
                                <p class="text-xs font-semibold text-gray-500">Status</p>
                                <p class="mt-1 font-bold text-gray-900 capitalize">{{ $employerProfile->status }}</p>
                            </div>

                            <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4">
                                <p class="text-xs font-semibold text-gray-500">Profile Views</p>
                                <p class="mt-1 font-bold text-gray-900">
                                    {{ number_format($employerProfile->total_profile_views ?? 0) }}
                                </p>
                            </div>

                            <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4">
                                <p class="text-xs font-semibold text-gray-500">Website</p>
                                <p class="mt-1 font-bold text-gray-900 truncate">
                                    {{ $employerProfile->company_website ?: '—' }}
                                </p>
                            </div>
                        </div>

                        {{-- Extra details --}}
                        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                            <div class="rounded-2xl border border-gray-200 p-4">
                                <p class="text-xs font-semibold text-gray-500">Phone</p>
                                <p class="mt-1 font-semibold text-gray-900">{{ $employerProfile->company_contact ?: '—' }}
                                </p>
                            </div>

                            <div class="rounded-2xl border border-gray-200 p-4">
                                <p class="text-xs font-semibold text-gray-500">Representative</p>
                                <p class="mt-1 font-semibold text-gray-900">
                                    {{ $employerProfile->representative_name ?: '—' }}
                                    <span class="text-gray-500 font-normal">({{ $employerProfile->position ?: '—' }})</span>
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection