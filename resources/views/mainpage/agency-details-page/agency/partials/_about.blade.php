<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
    <h2 class="text-base font-bold text-gray-900">Intro</h2>

    <p class="mt-3 text-gray-600 leading-relaxed">
        {{ $agency->description ?? 'No description provided.' }}
    </p>

    <div class="mt-5 space-y-3 text-sm">
        <div class="flex items-start gap-3">
            <i data-lucide="map-pin" class="w-4 h-4 text-gray-400 mt-0.5"></i>
            <div class="min-w-0">
                <p class="font-semibold text-gray-800">Location</p>
                <p class="text-gray-600">
                    {{ $agency->company_address ?? '—' }}
                </p>
            </div>
        </div>

        <div class="flex items-start gap-3">
            <i data-lucide="mail" class="w-4 h-4 text-gray-400 mt-0.5"></i>
            <div class="min-w-0">
                <p class="font-semibold text-gray-800">Email</p>
                <p class="text-gray-600 break-all">
                    {{ optional($agency->user)->email ?? '—' }}
                </p>
            </div>
        </div>

        <div class="flex items-start gap-3">
            <i data-lucide="phone" class="w-4 h-4 text-gray-400 mt-0.5"></i>
            <div class="min-w-0">
                <p class="font-semibold text-gray-800">Phone</p>
                <p class="text-gray-600">
                    {{ $agency->company_contact ?? '—' }}
                </p>
            </div>
        </div>

        <div class="flex items-start gap-3">
            <i data-lucide="globe" class="w-4 h-4 text-gray-400 mt-0.5"></i>
            <div class="min-w-0">
                <p class="font-semibold text-gray-800">Website</p>
                <p class="text-gray-600 break-all">
                    {{ $agency->company_website ?? '—' }}
                </p>
            </div>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-2 gap-3">
        <div class="rounded-2xl bg-gray-50 border border-gray-100 p-4">
            <p class="text-xs text-gray-500">Active jobs</p>
            <p class="mt-1 text-lg font-extrabold text-gray-900">{{ $openJobsCount ?? 0 }}</p>
        </div>
        <div class="rounded-2xl bg-gray-50 border border-gray-100 p-4">
            <p class="text-xs text-gray-500">Profile views</p>
            <p class="mt-1 text-lg font-extrabold text-gray-900">{{ $agency->total_profile_views ?? 0 }}</p>
        </div>
    </div>
</div>