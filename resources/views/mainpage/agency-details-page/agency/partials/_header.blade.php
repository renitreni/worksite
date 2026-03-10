@php
    $companyName = $agency->company_name ?? 'Agency';
    $initial = strtoupper(mb_substr(trim($companyName), 0, 1));

    $coverExists = !empty($agency->cover_path);
    $logoExists = !empty($agency->logo_path);

    $bgClasses = [
        'bg-emerald-100 text-emerald-700 ring-emerald-200',
        'bg-lime-100 text-lime-700 ring-lime-200',
        'bg-green-100 text-green-700 ring-green-200',
        'bg-teal-100 text-teal-700 ring-teal-200',
    ];

    $pick = (ord($initial) ?: 0) % count($bgClasses);
@endphp


<div x-data="{ loginModal: false }" class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    {{-- LOGIN REQUIRED MODAL --}}
    <div x-show="loginModal" x-transition x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4">

        <div @click.outside="loginModal=false" class="bg-white w-full max-w-md rounded-2xl shadow-xl p-6 text-center">

            {{-- Icon --}}
            <div class="mx-auto w-14 h-14 rounded-full bg-green-50 flex items-center justify-center mb-4">

                <i data-lucide="lock" class="w-7 h-7 text-green-600"></i>

            </div>

            <h3 class="section-title text-lg font-bold text-gray-900">
                Login Required
            </h3>

            <p class="mt-2 text-gray-600 text-sm leading-relaxed">
                You need to login first before following this agency and receiving job updates.
            </p>

            <div class="mt-6 flex gap-3 justify-center">

                <button @click="loginModal=false"
                    class="px-4 py-2 rounded-xl border border-gray-200 hover:bg-gray-50 font-medium">
                    Cancel
                </button>

                <a href="{{ route('candidate.login') }}"
                    class="px-4 py-2 rounded-xl bg-green-600 hover:bg-green-700 text-white font-semibold">
                    Login
                </a>

            </div>

        </div>

    </div>


    {{-- COVER --}}
    <div class="relative h-52 sm:h-60 lg:h-64">

        <img src="{{ $coverExists ? asset('storage/' . $agency->cover_path) : asset('images/cover.png') }}"
            alt="Agency Cover" class="w-full h-full object-cover">

        {{-- Gradient overlay --}}
        <div class="absolute inset-0 bg-gradient-to-b from-black/10 via-black/10 to-black/30"></div>

    </div>


    {{-- PROFILE AREA --}}
    <div class="relative px-6 pb-6">

        {{-- LOGO --}}
        <div class="absolute -top-14 left-6">

            <div
                class="w-28 h-28 rounded-2xl bg-white border border-gray-200 shadow-lg overflow-hidden flex items-center justify-center">

                @if ($logoExists)
                    <img src="{{ asset('storage/' . $agency->logo_path) }}" class="w-full h-full object-cover"
                        alt="Logo">
                @else
                    <div class="w-full h-full grid place-items-center ring-1 {{ $bgClasses[$pick] }}">
                        <span class="font-extrabold text-3xl">
                            {{ $initial }}
                        </span>
                    </div>
                @endif

            </div>

        </div>



        <div class="pt-20 flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">

            {{-- LEFT SIDE --}}
            <div class="min-w-0">

                {{-- COMPANY NAME --}}
                <div class="flex items-center gap-2 flex-wrap">

                    <h1 class="section-title text-2xl font-bold text-gray-900 truncate">
                        {{ $companyName }}
                    </h1>

                    {{-- VERIFIED BADGE --}}
                    @if ($agency->verification && $agency->verification->status === 'approved')
                        <span
                            class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200 rounded-full">

                            {{-- Check Icon --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm14.03-2.28a.75.75 0 0 0-1.06-1.06l-4.47 4.47-1.97-1.97a.75.75 0 0 0-1.06 1.06l2.5 2.5a.75.75 0 0 0 1.06 0l5-5Z"
                                    clip-rule="evenodd" />
                            </svg>

                            Verified
                        </span>
                    @endif

                </div>


                {{-- META INFO --}}
                <div class="mt-3 flex flex-wrap items-center gap-3 text-sm text-gray-600">

                    <span
                        class="px-3 py-1 rounded-full bg-green-50 text-green-700 border border-green-200 font-semibold">
                        {{ $openJobsCount ?? 0 }} jobs available
                    </span>


                    {{-- FOLLOWERS --}}
                    <span class="flex items-center gap-1">

                        {{-- Users icon --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5V9H2v11h5m10 0a4 4 0 01-8 0m8 0H9" />

                        </svg>

                        {{ number_format($followersCount) }} followers

                    </span>


                    {{-- ADDRESS --}}
                    @if ($agency->company_address)
                        <span class="truncate max-w-[220px]">
                            • {{ $agency->company_address }}
                        </span>
                    @endif

                </div>


                {{-- DESCRIPTION --}}
                @if ($agency->description)
                    <p class="mt-3 text-gray-600 max-w-3xl line-clamp-2 leading-relaxed">
                        {{ $agency->description }}
                    </p>
                @endif

            </div>



            {{-- RIGHT ACTIONS --}}
            <div class="flex items-center gap-3">

                <a href="#jobs"
                    class="px-4 py-2.5 rounded-xl bg-green-600 hover:bg-green-700 text-white font-semibold transition">
                    View Jobs
                </a>


                @auth

                    <form action="{{ route('agency.follow', $agency->id) }}" method="POST">
                        @csrf

                        <button type="submit"
                            class="px-4 py-2.5 rounded-xl font-semibold border transition flex items-center gap-1
                            {{ $isFollowing
                                ? 'bg-gray-100 border-gray-300 text-gray-700 hover:bg-gray-200'
                                : 'border-gray-200 hover:bg-gray-50' }}">

                            @if ($isFollowing)
                                ✓ Following
                            @else
                                + Follow
                            @endif

                        </button>

                    </form>
                @else
                    <button @click="loginModal = true"
                        class="px-4 py-2.5 rounded-xl border border-gray-200 hover:bg-gray-50 font-semibold">
                        + Follow
                    </button>

                @endauth

            </div>

        </div>

    </div>

</div>
