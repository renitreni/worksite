<div class="mt-10">
    <div class="flex items-center justify-between">
        <h2 class="text-lg sm:text-xl font-semibold text-slate-800">
            More jobs from this agency
        </h2>
    </div>

    @if($agencyJobs->count())
        <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($agencyJobs as $job)
                <x-job-card :job="$job" />
            @endforeach
        </div>

        {{-- Pagination: < 1 > style --}}
        @if($agencyJobs->hasPages())
            <div class="mt-6 flex justify-center">
                <div class="flex items-center gap-2">
                    {{-- Prev --}}
                    @if($agencyJobs->onFirstPage())
                        <span class="px-3 py-2 rounded-xl border border-slate-200 text-slate-400 text-sm">&lt;</span>
                    @else
                        <a href="{{ $agencyJobs->previousPageUrl() }}"
                           class="px-3 py-2 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 text-sm">&lt;</a>
                    @endif

                    <span class="px-3 py-2 rounded-xl bg-slate-900 text-white text-sm">
                        {{ $agencyJobs->currentPage() }}
                    </span>

                    {{-- Next --}}
                    @if($agencyJobs->hasMorePages())
                        <a href="{{ $agencyJobs->nextPageUrl() }}"
                           class="px-3 py-2 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 text-sm">&gt;</a>
                    @else
                        <span class="px-3 py-2 rounded-xl border border-slate-200 text-slate-400 text-sm">&gt;</span>
                    @endif
                </div>
            </div>
        @endif
    @else
        <p class="mt-4 text-sm text-slate-500">No other jobs available.</p>
    @endif
</div>
