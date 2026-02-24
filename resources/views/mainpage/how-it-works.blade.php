<section class="py-16">
    <div class="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900">How WorkSITE Works</h2>
            <p class="text-gray-600 mt-2">Get hired in 4 simple steps</p>
        </div>

        @php
            $steps = [
                ['num' => 1, 'title' => 'Register', 'desc' => 'Create your free account in minutes.', 'icon' => 'user-plus'],
                ['num' => 2, 'title' => 'Complete Profile', 'desc' => 'Upload your resume and highlight your skills.', 'icon' => 'file-text'],
                ['num' => 3, 'title' => 'Apply', 'desc' => 'Browse verified jobs and apply with confidence.', 'icon' => 'briefcase'],
                ['num' => 4, 'title' => 'Get Hired', 'desc' => 'Interview and secure your overseas opportunity.', 'icon' => 'badge-check'],
            ];
        @endphp

        {{-- Steps (modern cards) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($steps as $step)
                <div
                    class="group relative overflow-hidden rounded-2xl border border-gray-200 bg-white p-6 shadow-sm
                           transition-all duration-300 hover:-translate-y-1 hover:shadow-lg"
                >
                    {{-- soft hover glow --}}
                    <div
                        class="pointer-events-none absolute -top-24 -right-24 h-44 w-44 rounded-full bg-[#16A34A]/10 blur-2xl
                               opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                    </div>

                    {{-- top row --}}
                    <div class="flex items-center justify-between">
                        {{-- icon bubble --}}
                        <div class="relative">
                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#16A34A]/10 border border-[#16A34A]/20
                                        transition-colors duration-300 group-hover:bg-[#16A34A]/15">
                                <i data-lucide="{{ $step['icon'] }}" class="h-6 w-6 text-[#16A34A]"></i>
                            </div>
                        </div>

                        {{-- step badge --}}
                        <span
                            class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700
                                   transition-colors duration-300 group-hover:bg-[#16A34A]/10 group-hover:text-[#16A34A]">
                            Step {{ $step['num'] }}
                        </span>
                    </div>

                    {{-- content --}}
                    <h3 class="mt-5 text-lg font-semibold text-gray-900">
                        {{ $step['title'] }}
                    </h3>
                    <p class="mt-2 text-sm leading-relaxed text-gray-600">
                        {{ $step['desc'] }}
                    </p>

                    {{-- micro CTA line --}}
                    <div class="mt-5 flex items-center gap-2 text-sm font-medium text-gray-500">
                        <span class="h-1.5 w-1.5 rounded-full bg-gray-300 group-hover:bg-[#16A34A] transition"></span>
                        <span class="group-hover:text-gray-700 transition">Continue</span>
                        <i data-lucide="arrow-right" class="h-4 w-4 opacity-0 -translate-x-1 transition-all duration-300 group-hover:opacity-100 group-hover:translate-x-0"></i>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    
    {{-- Optional: Reduced motion support --}}
    <style>
        @media (prefers-reduced-motion: reduce) {
            .hover\\:-translate-y-1, .transition-all, .transition, .duration-300 { transition: none !important; }
        }
    </style>

    
</section>
