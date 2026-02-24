<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-lg font-extrabold text-gray-900">Listed Jobs</h2>
            <p class="mt-1 text-sm text-gray-600">All openings posted by this agency.</p>
        </div>

        {{-- UI only --}}
        <div class="flex gap-2">
            <button class="px-4 py-2 rounded-xl border border-gray-200 hover:bg-gray-50 text-sm font-semibold">
                Latest
            </button>
            <button class="px-4 py-2 rounded-xl border border-gray-200 hover:bg-gray-50 text-sm font-semibold">
                Full-time
            </button>
        </div>
    </div>

    <div class="mt-5 space-y-4">
        @foreach($jobs as $job)
            @include('mainpage.agency-details-page.agency.partials._job-card', ['job' => $job])
        @endforeach
    </div>

    {{-- ONE ROW Pagination ( < 1 2 3 > ) --}}
    <div class="mt-8 flex items-center justify-center gap-2">
        <button class="w-10 h-10 rounded-xl border border-gray-200 hover:bg-gray-50 flex items-center justify-center">
            <i data-lucide="chevron-left" class="w-5 h-5"></i>
        </button>

        <button class="w-10 h-10 rounded-xl border border-gray-200 hover:bg-gray-50 font-semibold">1</button>
        <button class="w-10 h-10 rounded-xl border border-gray-200 hover:bg-gray-50 font-semibold">2</button>
        <button class="w-10 h-10 rounded-xl border border-gray-200 hover:bg-gray-50 font-semibold">3</button>

        <button class="w-10 h-10 rounded-xl border border-gray-200 hover:bg-gray-50 flex items-center justify-center">
            <i data-lucide="chevron-right" class="w-5 h-5"></i>
        </button>
    </div>
</div>
