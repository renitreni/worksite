<section class="relative py-20 overflow-x-hidden">

    <!-- Base Gradient -->
    <div class="absolute inset-0 bg-gradient-to-br from-[#0a3f21] via-[#16A34A] to-[#22c55e]"></div>

    <!-- Radial Light Effect -->
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_30%,rgba(255,255,255,0.15),transparent_40%)]"></div>

    <!-- Soft Glow -->
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_80%_70%,rgba(255,255,255,0.08),transparent_50%)]"></div>

    <!-- Content -->
    <div class="relative container max-w-7xl mx-auto px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

            <!-- LEFT CONTENT -->
            <div class="text-white">
                <h2 class="hero-title text-4xl md:text-5xl font-bold leading-tight">
                    Ready to Start Your <br class="hidden sm:block">
                    Career Journey?
                </h2>

                <p class="section-title mt-5 max-w-xl text-white/90 text-lg">
                    Join thousands of job seekers who have found their dream jobs through Worksite.
                    Create your free account today and get access to exclusive opportunities.
                </p>

                <!-- Benefits -->
                <ul class="mt-8 space-y-4">
                    <li class="flex items-start gap-3">
                        <x-lucide-icon name="check-circle" class="w-6 h-6 text-white" />
                        <span class="text-white/90">Access to thousands of verified job listings</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <x-lucide-icon name="check-circle" class="w-6 h-6 text-white" />
                        <span class="text-white/90">Direct connection with top recruitment agencies</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <x-lucide-icon name="check-circle" class="w-6 h-6 text-white" />
                        <span class="text-white/90">Personalized job recommendations</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <x-lucide-icon name="check-circle" class="w-6 h-6 text-white" />
                        <span class="text-white/90">Free career guidance and support</span>
                    </li>
                </ul>

                <!-- Buttons -->
                <div class="mt-10 flex flex-wrap gap-4">
                    <a href="{{ auth()->check() ? route('candidate.dashboard') : route('candidate.register') }}"
                        class="inline-flex items-center gap-2 rounded-xl bg-yellow-400 px-6 py-3 text-sm font-semibold text-black hover:bg-yellow-300 transition">
                        Get Started
                        <x-lucide-icon name="arrow-right" class="w-4 h-4" />
                    </a>

                    <a href="{{ route('search-jobs') }}"
                        class="inline-flex items-center gap-2 rounded-xl border border-white px-6 py-3 text-sm font-semibold text-white hover:bg-white/10 transition">
                        Browse Jobs
                    </a>
                </div>
            </div>

            <!-- RIGHT CONTENT -->
            <div class="relative min-w-0">
                <!-- Image (fixed height on mobile so overlays have space) -->
                <div
                    class="relative rounded-2xl shadow-2xl overflow-hidden
            w-full h-[300px] sm:h-[380px] lg:h-auto">

                    <video autoplay muted loop playsinline preload="metadata"
                        class="w-full h-full object-cover pointer-events-none">

                        <source src="{{ asset('videos/ads/start-your-journey.mp4') }}" type="video/mp4">

                    </video>
                    <!-- Black Overlay -->
                    <div class="absolute inset-0 bg-black/40"></div>
                </div>
                <!-- Floating Card: Success Rate -->
                <div
                    class="absolute z-20
left-3 sm:left-4 lg:-left-6
top-4 sm:top-6 lg:top-10
bg-white/90 backdrop-blur-xl
rounded-2xl shadow-xl border border-white/40
px-4 py-3 sm:px-5 sm:py-4
flex items-center gap-3
transition duration-300 hover:-translate-y-1">

                    <div class="bg-green-100/80 p-2 rounded-lg">
                        <x-lucide-icon name="zap" class="w-5 h-5 text-[#16A34A]" />
                    </div>

                    <div class="leading-tight">
                        <p class="text-lg font-bold text-[#16A34A]">95%</p>
                        <p class="text-xs text-gray-600">Success Rate</p>
                    </div>

                </div>

                <!-- Floating Card: Partner Agencies -->
                <div
                    class="absolute z-20
right-3 sm:right-4 lg:-right-6
top-16 sm:top-20 lg:top-24
bg-white/90 backdrop-blur-xl
rounded-2xl shadow-xl border border-white/40
px-4 py-3 sm:px-5 sm:py-4
flex items-center gap-3
transition duration-300 hover:-translate-y-1">

                    <div class="bg-yellow-100/80 p-2 rounded-lg">
                        <x-lucide-icon name="shield-check" class="w-5 h-5 text-yellow-600" />
                    </div>

                    <div class="leading-tight">
                        <p class="text-lg font-bold text-gray-900">
                            {{ number_format($agenciesCount) }}+
                        </p>
                        <p class="text-xs text-gray-600">Partner Agencies</p>
                    </div>

                </div>

                <!-- Floating Card: Active Jobs -->
                <div
                    class="absolute z-20
left-3 sm:left-4 lg:left-12
bottom-4 sm:bottom-6 lg:-bottom-8
bg-white/90 backdrop-blur-xl
rounded-2xl shadow-xl border border-white/40
px-4 py-3 sm:px-5 sm:py-4
flex items-center gap-3
transition duration-300 hover:-translate-y-1">

                    <div class="bg-green-100/80 p-2 rounded-lg">
                        <x-lucide-icon name="briefcase" class="w-5 h-5 text-[#16A34A]" />
                    </div>

                    <div class="leading-tight">
                        <p class="text-lg font-bold text-gray-900">
                            {{ number_format($activeJobsCount) }}+
                        </p>
                        <p class="text-xs text-gray-600">Active Jobs</p>
                    </div>

                </div>

            </div>

        </div>
    </div>
</section>
