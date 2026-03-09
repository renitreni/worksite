@extends('main')

@section('title', $industry->name . ' Jobs')

@section('content')

    <section class="bg-gray-50 min-h-screen py-24">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Industry Header --}}
            <div class="mb-10">

                <div class="flex items-center gap-3 text-sm text-gray-500 mb-2">
                    <a href="{{ route('home') }}" class="hover:text-gray-700">Home</a>
                    <span>/</span>
                    <span class="text-gray-700 font-medium">{{ $industry->name }}</span>
                </div>

                <h1 class="text-3xl font-bold text-gray-900">
                    {{ $industry->name }} Jobs
                </h1>

                <p class="text-gray-600 mt-2">
                    {{ $jobs->total() }} job opportunities available in this industry.
                </p>

            </div>


            {{-- JOBS GRID --}}
            @if ($jobs->count())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($jobs as $job)
                        <x-job-card :job="$job" />
                    @endforeach

                </div>

                <div class="mt-12">
                    {{ $jobs->links() }}
                </div>
            @else
                {{-- EMPTY STATE --}}
                <div class="bg-white border border-gray-200 rounded-xl p-10 text-center">

                    <h3 class="text-lg font-semibold text-gray-900">
                        No jobs available
                    </h3>

                    <p class="text-gray-500 mt-2">
                        There are currently no job listings in this industry.
                    </p>

                </div>
            @endif


            {{-- BROWSE OTHER INDUSTRIES --}}
            @if (isset($otherIndustries) && count($otherIndustries))

                <div class="mt-16">

                    <div class="flex items-center justify-between mb-6">

                        <h2 class="text-xl font-semibold text-gray-900">
                            Browse Other Industries
                        </h2>

                        <div class="flex gap-2">

                            <button onclick="slideIndustries(-1)"
                                class="p-2 rounded-lg border bg-white hover:bg-gray-100 transition">
                                <i data-lucide="chevron-left" class="w-5 h-5"></i>
                            </button>

                            <button onclick="slideIndustries(1)"
                                class="p-2 rounded-lg border bg-white hover:bg-gray-100 transition">
                                <i data-lucide="chevron-right" class="w-5 h-5"></i>
                            </button>

                        </div>

                    </div>


                    <div id="industrySlider" class="flex gap-6 overflow-hidden">

                        @foreach ($otherIndustries as $item)
                            <div class="industry-card flex-shrink-0">
                                <x-industry-card :item="$item" />
                            </div>
                        @endforeach

                    </div>

                </div>

            @endif

        </div>

    </section>


    <style>
        .industry-card {
            width: 260px;
        }

        .industry-card a {
            display: flex;
            flex-direction: column;
            height: 100%;
        }
    </style>


    <script>
        function slideIndustries(direction) {

            const slider = document.getElementById('industrySlider');

            const scrollAmount = 280;

            slider.scrollBy({
                left: direction * scrollAmount,
                behavior: "smooth"
            });

        }
    </script>

@endsection
