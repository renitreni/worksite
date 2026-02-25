<section class="py-16 bg-white">
    <div class="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="text-center mb-10">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900">
                Featured Jobs
            </h2>
            <p class="text-gray-600 mt-2">
                Top opportunities from leading employers
            </p>
        </div>

        @if ($featuredJobs->isEmpty())
                {{-- EMPTY STATE --}}
                <div class="max-w-2xl mx-auto bg-white border border-gray-200 rounded-2xl p-8 text-center">
                    <p class="text-gray-700 font-medium">No featured jobs yet.</p>
                    <p class="text-gray-500 text-sm mt-1">Once jobs are posted, they will appear here.</p>
                </div>
            </div>

        @else

        {{-- MOBILE / TABLET --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 lg:hidden">
            @foreach ($featuredJobs->take(6) as $job)
                <x-job-card :job="$job" />
            @endforeach
        </div>

        {{-- LARGE SCREEN --}}
        <div class="hidden lg:grid grid-cols-3 gap-6">
            @foreach ($featuredJobs as $job)
                <x-job-card :job="$job" />
            @endforeach
        </div>

    @endif

    </div>
</section>