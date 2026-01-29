{{-- Career CTA Section --}}
<section class="relative py-20 bg-gradient-to-br from-[#0f5f2f] via-[#16A34A] to-[#22c55e] overflow-hidden">
    <div class="container max-w-7xl mx-auto px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

            {{-- LEFT CONTENT --}}
            <div class="text-white">
                <h2 class="text-4xl md:text-5xl font-bold leading-tight">
                    Ready to Start Your <br class="hidden sm:block">
                    Career Journey?
                </h2>

                <p class="mt-5 max-w-xl text-white/90 text-lg">
                    Join thousands of job seekers who have found their dream jobs through Worksite.
                    Create your free account today and get access to exclusive opportunities.
                </p>

                {{-- Benefits --}}
                <ul class="mt-8 space-y-4">
                    <li class="flex items-start gap-3">
                        <i data-lucide="check-circle" class="w-6 h-6 text-white"></i>
                        <span class="text-white/90">Access to thousands of verified job listings</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i data-lucide="check-circle" class="w-6 h-6 text-white"></i>
                        <span class="text-white/90">Direct connection with top recruitment agencies</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i data-lucide="check-circle" class="w-6 h-6 text-white"></i>
                        <span class="text-white/90">Personalized job recommendations</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i data-lucide="check-circle" class="w-6 h-6 text-white"></i>
                        <span class="text-white/90">Free career guidance and support</span>
                    </li>
                </ul>

                {{-- Buttons --}}
                <div class="mt-10 flex flex-wrap gap-4">
                    <a href="#"
                       class="inline-flex items-center gap-2 rounded-xl bg-yellow-400 px-6 py-3 text-sm font-semibold text-black hover:bg-yellow-300 transition">
                        Get Started
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>

                    <a href="#"
                       class="inline-flex items-center gap-2 rounded-xl border border-white px-6 py-3 text-sm font-semibold text-white hover:bg-white/10 transition">
                        Browse Jobs
                    </a>
                </div>
            </div>

            {{-- RIGHT CONTENT --}}
            <div class="relative">
                {{-- Image --}}
                <img src="{{ asset('images/back.jpg') }}"
                     alt="Worksite Office"
                     class="rounded-2xl shadow-2xl w-full object-cover">

                {{-- Floating Stats --}}
                <div class="absolute -left-6 top-10 bg-white rounded-xl shadow-lg px-5 py-4 flex items-center gap-3">
                    <div class="bg-green-100 p-2 rounded-lg">
                        <i data-lucide="zap" class="w-5 h-5 text-[#16A34A]"></i>
                    </div>
                    <div>
                        <p class="text-lg font-bold text-[#16A34A]">95%</p>
                        <p class="text-sm text-gray-600">Success Rate</p>
                    </div>
                </div>

                <div class="absolute -right-6 top-20 bg-white rounded-xl shadow-lg px-5 py-4 flex items-center gap-3">
                    <div class="bg-yellow-100 p-2 rounded-lg">
                        <i data-lucide="shield-check" class="w-5 h-5 text-yellow-600"></i>
                    </div>
                    <div>
                        <p class="text-lg font-bold text-gray-900">500+</p>
                        <p class="text-sm text-gray-600">Partner Agencies</p>
                    </div>
                </div>

                <div class="absolute left-10 -bottom-6 bg-white rounded-xl shadow-lg px-5 py-4 flex items-center gap-3">
                    <div class="bg-green-100 p-2 rounded-lg">
                        <i data-lucide="briefcase" class="w-5 h-5 text-[#16A34A]"></i>
                    </div>
                    <div>
                        <p class="text-lg font-bold text-gray-900">10,000+</p>
                        <p class="text-sm text-gray-600">Active Jobs</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

   
</section>
