<div class="p-5 rounded-2xl border border-gray-100 hover:border-green-200 hover:shadow-sm transition bg-white">
    <div class="flex items-start justify-between gap-4">
        <div class="min-w-0">
            <h3 class="text-base font-extrabold text-gray-900 truncate">
                {{ $job['title'] ?? 'Job Title' }}
            </h3>

            <div class="mt-2 flex flex-wrap gap-2 text-xs">
                <span class="px-2.5 py-1 rounded-full bg-gray-100 text-gray-700 font-semibold">
                    {{ $job['type'] ?? '—' }}
                </span>
                <span class="px-2.5 py-1 rounded-full bg-green-50 text-green-700 border border-green-200 font-semibold">
                    {{ $job['location'] ?? '—' }}
                </span>
                <span class="px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200 font-semibold">
                    {{ $job['salary'] ?? '—' }}
                </span>
            </div>

            <p class="mt-3 text-sm text-gray-500">Posted {{ $job['posted'] ?? '—' }}</p>
        </div>

        <div class="flex items-center gap-2">
            <button class="w-10 h-10 rounded-xl border border-gray-200 hover:bg-gray-50 flex items-center justify-center">
                <i data-lucide="bookmark" class="w-5 h-5 text-gray-600"></i>
            </button>
            <button class="px-4 py-2 rounded-xl bg-green-600 hover:bg-green-700 text-white text-sm font-semibold">
                Apply
            </button>
        </div>
    </div>
</div>
