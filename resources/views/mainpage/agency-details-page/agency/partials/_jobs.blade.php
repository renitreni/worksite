<div id="jobs" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-lg font-extrabold text-gray-900">Listed Jobs</h2>
            <p class="mt-1 text-sm text-gray-600">All openings posted by this agency.</p>
        </div>
    </div>

    {{-- GRID --}}
    <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-6">
        @forelse($jobs as $job)
            <div>
                @include('components.job-card', ['job' => $job])
            </div>
        @empty
            <div class="col-span-full rounded-2xl border border-gray-100 bg-gray-50 p-6 text-center">
                <p class="font-semibold text-gray-800">No open jobs right now.</p>
                <p class="text-sm text-gray-600 mt-1">Check again later for new opportunities.</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-8">
        {{ $jobs->links() }}
    </div>
</div>