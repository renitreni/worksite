{{-- Footer --}}
<footer class="bg-[#0F4D2A] text-white">
    <div class="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        {{-- Top grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            {{-- Brand --}}
            <div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[#16A34A]/30 flex items-center justify-center border border-white/10">
                        <i data-lucide="briefcase" class="w-5 h-5 text-white"></i>
                    </div>
                    <span class="text-2xl font-bold">Worksite</span>
                </div>

                <p class="mt-4 text-white/70 leading-relaxed max-w-md">
                    Connecting job seekers with top recruitment agencies. Find your dream job easily and start your career
                    journey with confidence.
                </p>

                {{-- Socials --}}
                <div class="mt-6 flex items-center gap-3">
                    <a href="#"
                       class="w-11 h-11 rounded-xl bg-white/10 hover:bg-white/15 transition flex items-center justify-center border border-white/10">
                        <i data-lucide="facebook" class="w-5 h-5 text-white/90"></i>
                    </a>
                    <a href="#"
                       class="w-11 h-11 rounded-xl bg-white/10 hover:bg-white/15 transition flex items-center justify-center border border-white/10">
                        <i data-lucide="twitter" class="w-5 h-5 text-white/90"></i>
                    </a>
                    <a href="#"
                       class="w-11 h-11 rounded-xl bg-white/10 hover:bg-white/15 transition flex items-center justify-center border border-white/10">
                        <i data-lucide="linkedin" class="w-5 h-5 text-white/90"></i>
                    </a>
                    <a href="#"
                       class="w-11 h-11 rounded-xl bg-white/10 hover:bg-white/15 transition flex items-center justify-center border border-white/10">
                        <i data-lucide="instagram" class="w-5 h-5 text-white/90"></i>
                    </a>
                </div>
            </div>

            {{-- Quick Links --}}
            <div class="md:justify-self-center">
                <h4 class="text-lg font-semibold">Quick Links</h4>
                <ul class="mt-4 space-y-3 text-white/80">
                    <li><a href="#" class="hover:text-white transition">About Us</a></li>
                    <li><a href="#" class="hover:text-white transition">Browse Jobs</a></li>
                    <li><a href="#" class="hover:text-white transition">Our Agencies</a></li>
                    <li><a href="#" class="hover:text-white transition">Industries</a></li>
                </ul>
            </div>

            {{-- Support --}}
            <div class="md:justify-self-end">
                <h4 class="text-lg font-semibold">Support</h4>
                <ul class="mt-4 space-y-3 text-white/80">
                    <li><a href="#" class="hover:text-white transition">Contact Us</a></li>
                    <li><a href="#" class="hover:text-white transition">Help Center</a></li>
                    <li><a href="#" class="hover:text-white transition">Terms of Service</a></li>
                    <li><a href="#" class="hover:text-white transition">Privacy Policy</a></li>
                </ul>
            </div>
        </div>

        {{-- Divider --}}
        <div class="my-10 border-t border-white/10"></div>

        {{-- Newsletter --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-center">
            <div>
                <h4 class="text-xl font-semibold">Subscribe to Our Newsletter</h4>
                <p class="mt-2 text-white/70">Get the latest job opportunities delivered to your inbox.</p>
            </div>

            <form class="flex flex-col sm:flex-row gap-3 sm:justify-end">
                <div class="relative w-full sm:max-w-md">
                    <span class="absolute inset-y-0 left-3 flex items-center text-white/70">
                        <i data-lucide="mail" class="w-5 h-5"></i>
                    </span>
                    <input type="email" placeholder="Enter your email"
                           class="w-full pl-11 pr-4 py-3 rounded-xl bg-white/10 border border-white/10 text-white placeholder:text-white/50 focus:outline-none focus:ring-2 focus:ring-[#16A34A]">
                </div>

                <button type="submit"
                        class="px-6 py-3 rounded-xl bg-yellow-400 text-black font-semibold hover:bg-yellow-300 transition">
                    Subscribe
                </button>
            </form>
        </div>

        {{-- Divider --}}
        <div class="my-10 border-t border-white/10"></div>

        {{-- Bottom --}}
        <div class="text-center text-white/60 text-sm">
            © 2026 Worksite. All rights reserved. Built with passion for connecting talent with opportunities.
        </div>
    </div>
</footer>
