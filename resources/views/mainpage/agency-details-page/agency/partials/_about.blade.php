<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
    <h2 class="section-title text-base font-bold text-gray-900">
        About
    </h2>
    <p class="mt-3 text-gray-600 leading-relaxed">
        {{ $agency->description ?? 'No description provided.' }}
    </p>

    <div class="mt-5 space-y-3 text-sm">

        {{-- Location --}}
        <div class="flex items-start gap-3">
            <x-lucide-icon name="map-pin" class="w-4 h-4 text-gray-400 mt-0.5" />
            <div class="min-w-0">
                <p class="font-semibold text-gray-800">Location</p>
                <p class="text-gray-600">
                    {{ $agency->company_address ?? '—' }}
                </p>
            </div>
        </div>

        {{-- Email --}}
        <div class="flex items-start gap-3">
            <x-lucide-icon name="mail" class="w-4 h-4 text-gray-400 mt-0.5" />
            <div class="min-w-0">
                <p class="font-semibold text-gray-800">Email</p>
                <p class="text-gray-600 break-all">
                    {{ optional($agency->user)->email ?? '—' }}
                </p>
            </div>
        </div>

        {{-- Phone --}}
        <div class="flex items-start gap-3">
            <x-lucide-icon name="phone" class="w-4 h-4 text-gray-400 mt-0.5" />
            <div class="min-w-0">
                <p class="font-semibold text-gray-800">Phone</p>
                <p class="text-gray-600">
                    {{ $agency->company_contact ?? '—' }}
                </p>
            </div>
        </div>

        {{-- Website --}}
        <div class="flex items-start gap-3">
            <x-lucide-icon name="globe" class="w-4 h-4 text-gray-400 mt-0.5" />

            <div class="min-w-0">
                <p class="font-semibold text-gray-800">Website</p>

                @php
                    $website = $agency->company_website;
                @endphp

                @if (!empty($website))
                    @php
                        $url = Str::startsWith($website, ['http://', 'https://']) ? $website : 'https://' . $website;
                    @endphp

                    <a href="{{ $url }}" target="_blank" rel="noopener noreferrer"
                        class="text-emerald-700 font-medium hover:text-emerald-800 hover:underline break-all inline-flex items-center gap-1">

                        {{ $website }}

                        <x-lucide-icon name="external-link" class="w-3.5 h-3.5" />
                    </a>
                @else
                    <p class="text-gray-500">
                        No website provided
                    </p>
                @endif
            </div>
        </div>

        {{-- DMW License --}}
        <div class="flex items-start gap-3">
            <x-lucide-icon name="badge-check" class="w-4 h-4 text-gray-400 mt-0.5" />
            <div class="min-w-0">
                <p class="font-semibold text-gray-800">DMW Registration No</p>

                @if ($agency->dmw_license_number)
                    <p class="text-emerald-700 font-semibold">
                        {{ $agency->dmw_license_number }}
                    </p>
                @else
                    <p class="text-gray-500">
                        Not provided
                    </p>
                @endif
            </div>
        </div>

    </div>

    {{-- Stats --}}
    <div class="mt-6 grid grid-cols-2 gap-3">
        <div class="rounded-2xl bg-gray-50 border border-gray-100 p-4">
            <p class="text-xs text-gray-500">Active jobs</p>
            <p class="mt-1 text-lg font-extrabold text-gray-900">
                {{ $openJobsCount ?? 0 }}
            </p>
        </div>

        <div class="rounded-2xl bg-gray-50 border border-gray-100 p-4">
            <p class="text-xs text-gray-500">Profile views</p>
            <p class="mt-1 text-lg font-extrabold text-gray-900">
                {{ $agency->total_profile_views ?? 0 }}
            </p>
        </div>
    </div>
</div>
