@extends('main')

@section('title', 'About Us')

@section('content')

    {{-- HERO --}}

    <section class="relative bg-gradient-to-br from-green-50 to-white py-16">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="grid lg:grid-cols-2 gap-12 items-center">

                {{-- TEXT --}}

                <div>

                    <p class="text-green-600 font-semibold tracking-widest mb-3">
                        ABOUT JOBABROAD
                    </p>

                    <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-800 leading-tight">
                        Connecting Filipino Talent to
                        <span class="text-green-600">Global Careers</span>
                    </h1>

                    <p class="text-gray-600 mt-6 text-lg max-w-xl">
                        JobAbroad helps Filipino professionals discover legitimate overseas
                        employment opportunities by connecting them with verified
                        recruitment agencies and global employers.
                    </p>

                    <div class="mt-8 flex flex-col sm:flex-row gap-4">

                        <a href="/jobs"
                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold shadow flex items-center justify-center gap-2">

                            Browse Jobs

                        </a>

                        <a href="/register"
                            class="border border-green-600 text-green-600 px-6 py-3 rounded-lg font-semibold hover:bg-green-50 flex items-center justify-center gap-2">

                            Create Account

                        </a>

                    </div>

                </div>

                {{-- IMAGE --}}

                {{-- IMAGE --}}
                {{-- IMAGE SLIDER --}}
                <div
                    class="relative w-full max-w-lg h-[260px] sm:h-[300px] md:h-[340px] overflow-hidden rounded-2xl shadow-xl">

                    <img src="{{ asset('images/connecting.jpg') }}"
                        class="hero-slide absolute inset-0 w-full h-full object-cover opacity-100" loading="lazy">

                    <img src="{{ asset('images/connecting-2.webp') }}"
                        class="hero-slide absolute inset-0 w-full h-full object-cover opacity-0" loading="lazy">

                    <img src="{{ asset('images/connecting-3.jpg') }}"
                        class="hero-slide absolute inset-0 w-full h-full object-cover opacity-0" loading="lazy">

                    <img src="{{ asset('images/connecting-4.avif') }}"
                        class="hero-slide absolute inset-0 w-full h-full object-cover opacity-0" loading="lazy">

                    <img src="{{ asset('images/connecting-5.jpg') }}"
                        class="hero-slide absolute inset-0 w-full h-full object-cover opacity-0" loading="lazy">

                </div>
            </div>

            {{-- SCROLL BUTTON --}}

            <div class="flex justify-center mt-16 sticky bottom-6">

                <button onclick="document.getElementById('about-section').scrollIntoView({behavior:'smooth'})"
                    class="bg-green-600 text-white w-14 h-14 rounded-full flex items-center justify-center shadow-lg hover:bg-green-700 transition">

                    <i data-lucide="chevron-down" class="animate-bounce"></i>

                </button>

            </div>

    </section>

    {{-- ABOUT --}}
    <section id="about-section" class="py-20 bg-white">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-6">
                Helping Filipinos Work Abroad Safely
            </h2>

            <p class="text-gray-600 text-lg leading-relaxed">
                JobAbroad is a digital platform designed to simplify the overseas job search
                process for Filipino professionals. Our system connects job seekers with
                verified recruitment agencies and international employers who are actively
                seeking skilled Filipino talent across various industries worldwide.
            </p>

            <p class="text-gray-600 mt-5 leading-relaxed">
                Through JobAbroad, users can explore global employment opportunities,
                create professional profiles, upload resumes, and apply directly to overseas
                job openings. By centralizing recruitment opportunities into one platform,
                we make the process of finding international employment more accessible,
                organized, and efficient for Filipino workers.
            </p>

            <p class="text-gray-600 mt-5 leading-relaxed">
                Our goal is to bridge the gap between Filipino talent and global career
                opportunities. By providing a trusted and transparent environment for both
                job seekers and employers, JobAbroad helps empower Filipino professionals
                to pursue meaningful careers abroad while enabling companies to discover
                highly qualified candidates.
            </p>

        </div>

    </section>


    {{-- STATS --}}

    <section class="py-20 bg-gray-50 overflow-hidden">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- MOBILE AUTO SCROLL --}}

            <div class="md:hidden overflow-hidden">

                <div class="flex gap-6 animate-scroll w-max">

                    @for ($i = 0; $i < 2; $i++)
                        <div class="flex gap-6">

                            <div class="bg-white px-8 py-6 rounded-xl shadow min-w-[200px]">
                                <i data-lucide="users" class="text-green-600 w-8 h-8"></i>
                                <h3 class="text-3xl font-bold text-green-600 mt-2">10K+</h3>
                                <p class="text-gray-600 text-sm">Job Seekers</p>
                            </div>

                            <div class="bg-white px-8 py-6 rounded-xl shadow min-w-[200px]">
                                <i data-lucide="building-2" class="text-green-600 w-8 h-8"></i>
                                <h3 class="text-3xl font-bold text-green-600 mt-2">500+</h3>
                                <p class="text-gray-600 text-sm">Partner Agencies</p>
                            </div>

                            <div class="bg-white px-8 py-6 rounded-xl shadow min-w-[200px]">
                                <i data-lucide="globe" class="text-green-600 w-8 h-8"></i>
                                <h3 class="text-3xl font-bold text-green-600 mt-2">40+</h3>
                                <p class="text-gray-600 text-sm">Countries Hiring</p>
                            </div>

                            <div class="bg-white px-8 py-6 rounded-xl shadow min-w-[200px]">
                                <i data-lucide="briefcase" class="text-green-600 w-8 h-8"></i>
                                <h3 class="text-3xl font-bold text-green-600 mt-2">5K+</h3>
                                <p class="text-gray-600 text-sm">Jobs Posted</p>
                            </div>

                        </div>
                    @endfor

                </div>

            </div>

            {{-- DESKTOP GRID --}}

            <div class="hidden md:grid md:grid-cols-2 lg:grid-cols-4 gap-8">

                <div class="bg-white px-8 py-6 rounded-xl shadow">
                    <i data-lucide="users" class="text-green-600 w-8 h-8"></i>
                    <h3 class="text-3xl font-bold text-green-600 mt-2">10K+</h3>
                    <p class="text-gray-600 text-sm">Job Seekers</p>
                </div>

                <div class="bg-white px-8 py-6 rounded-xl shadow">
                    <i data-lucide="building-2" class="text-green-600 w-8 h-8"></i>
                    <h3 class="text-3xl font-bold text-green-600 mt-2">500+</h3>
                    <p class="text-gray-600 text-sm">Partner Agencies</p>
                </div>

                <div class="bg-white px-8 py-6 rounded-xl shadow">
                    <i data-lucide="globe" class="text-green-600 w-8 h-8"></i>
                    <h3 class="text-3xl font-bold text-green-600 mt-2">40+</h3>
                    <p class="text-gray-600 text-sm">Countries Hiring</p>
                </div>

                <div class="bg-white px-8 py-6 rounded-xl shadow">
                    <i data-lucide="briefcase" class="text-green-600 w-8 h-8"></i>
                    <h3 class="text-3xl font-bold text-green-600 mt-2">5K+</h3>
                    <p class="text-gray-600 text-sm">Jobs Posted</p>
                </div>

            </div>

        </div>

    </section>

    {{-- JOB SEEKERS --}}

    <section class="py-20">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="grid lg:grid-cols-2 gap-12 items-center">

                <div class="order-2 lg:order-1">

                    <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f"
                        class="rounded-2xl shadow-xl w-full h-auto object-cover">

                </div>

                <div class="order-1 lg:order-2">

                    <h3 class="text-3xl font-bold text-gray-800 mb-5">
                        Opportunities for Filipino Job Seekers
                    </h3>

                    <p class="text-gray-600 leading-relaxed">
                        Filipino workers can explore overseas opportunities across
                        industries like healthcare, hospitality, construction,
                        engineering, and IT.
                    </p>

                    <p class="text-gray-600 mt-4">
                        Create your professional profile, upload your resume,
                        and apply directly to international employers.
                    </p>

                    <a href="/register"
                        class="inline-flex items-center gap-2 mt-6 bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold shadow">

                        <i data-lucide="user-plus"></i>
                        Create Your Profile

                    </a>

                </div>

            </div>

        </div>

    </section>

    {{-- EMPLOYERS --}}
    <section class="py-20 bg-gray-50">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="grid lg:grid-cols-2 gap-12 items-center">

                <div>

                    <h3 class="text-3xl font-bold text-gray-800 mb-5">
                        Helping Employers Find Skilled Filipino Talent
                    </h3>

                    <p class="text-gray-600 leading-relaxed">
                        JobAbroad provides recruitment agencies and employers with tools to
                        post job openings, manage applicants, and connect with highly skilled
                        Filipino workers ready for international opportunities.
                    </p>

                    <p class="text-gray-600 mt-4">
                        Through our growing database of job seekers, employers can efficiently
                        discover qualified candidates to support their workforce needs
                        worldwide.
                    </p>

                    <a href="/contact"
                        class="inline-flex items-center gap-2 mt-6 bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold shadow">

                        <i data-lucide="handshake"></i>
                        Partner With Us

                    </a>

                </div>

                <div>

                    <img src="https://images.unsplash.com/photo-1552664730-d307ca884978"
                        class="rounded-2xl shadow-xl w-full h-auto object-cover">

                </div>

            </div>

        </div>

    </section>

    {{-- CTA --}}
    <section class="py-20">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-green-600 rounded-3xl p-10 md:p-14 grid lg:grid-cols-2 gap-12 items-center text-white">

                <div>

                    <h2 class="text-3xl font-bold mb-4">
                        Start Your Overseas Career Today
                    </h2>

                    <p class="text-green-100 mb-6 max-w-xl">
                        Create your JobAbroad account and explore thousands of overseas job
                        opportunities waiting for Filipino professionals worldwide.
                    </p>

                    <a href="/jobs"
                        class="bg-white text-green-600 px-7 py-3 rounded-lg font-semibold shadow hover:bg-gray-100 inline-flex items-center gap-2">

                        <i data-lucide="search"></i>
                        Explore Jobs

                    </a>

                </div>

                <div>

                    <img src="https://images.unsplash.com/photo-1504384308090-c894fdcc538d"
                        class="rounded-xl shadow-lg w-full h-auto object-cover">

                </div>

            </div>

        </div>

    </section>

@endsection

<script>
    document.addEventListener("DOMContentLoaded", () => {

        const slides = document.querySelectorAll(".hero-slide");
        let index = 0;

        setInterval(() => {

            slides[index].style.opacity = "0";

            index = (index + 1) % slides.length;

            slides[index].style.opacity = "1";

        }, 5000);

    });
</script>
<style>
    @keyframes scroll {
        0% {
            transform: translateX(0)
        }

        100% {
            transform: translateX(-50%)
        }
    }

    .animate-scroll {
        animation: scroll 22s linear infinite
    }

    .hero-slide {
        transition: opacity 1.4s ease-in-out;
    }
</style>
