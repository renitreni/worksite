@extends('main')

@section('title', 'Terms of Use')

@section('content')

    {{-- HERO --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-green-50 via-white to-green-100 ">

        <div class="absolute -top-20 -left-20 w-96 h-96 bg-green-200 rounded-full blur-3xl opacity-30"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-green-300 rounded-full blur-3xl opacity-30"></div>

        <div class="max-w-6xl mx-auto px-6 py-24 relative">

            <div class="grid md:grid-cols-2 gap-10 items-center">

                <div>

                    <h1 class="hero-title text-4xl md:text-5xl font-bold text-gray-900 leading-tight">
                        Terms of Use
                    </h1>

                    <p class="text-gray-600 mt-5 text-lg">
                        Please review the terms that govern your use of the
                        <span class="font-semibold text-[#16A34A]">JobAbroad</span>
                        platform including job listings, employer services,
                        and recruitment tools.
                    </p>

                    <p class="text-gray-500 mt-4">
                        By accessing our website you agree to comply with the
                        policies, rules, and conditions outlined in this document.
                    </p>

                    <div class="mt-6 flex items-center gap-4">

                        <span class="px-4 py-2 bg-green-100 text-green-700 text-sm rounded-full font-medium">
                            Updated 2026
                        </span>

                        <span class="text-sm text-gray-500">
                            Last updated: February 2026
                        </span>

                    </div>

                </div>

                <div class="flex justify-center">

                    <img src="images/term-illustration.jpg" class="w-100 drop-shadow-xl"
                        alt="Terms Illustration">

                </div>

            </div>

        </div>

    </section>



    <section x-data="termsNavigation()" x-init="init()" class="py-16 bg-white">

        <div class="max-w-7xl mx-auto px-6 lg:flex gap-12">

            {{-- MOBILE TERMS MENU --}}
            <div class="lg:hidden mb-8">

                <button @click="open = !open"
                    class="w-full flex justify-between items-center bg-gray-100 px-4 py-3 rounded-lg text-sm font-semibold">

                    <span>Terms Navigation</span>

                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />

                    </svg>

                </button>

                <ul x-show="open" x-transition class="mt-4 bg-gray-50 rounded-lg p-4 space-y-2 text-sm">

                    <template x-for="item in sections">

                        <li>

                            <a :href="'#' + item.id" @click.prevent="scrollTo(item.id)"
                                class="block py-1 text-gray-600 hover:text-[#16A34A]" x-text="item.title">
                            </a>

                        </li>

                    </template>

                </ul>

            </div>



            {{-- SIDEBAR --}}
            <aside class="lg:w-72 hidden lg:block">

                <div class="sticky top-28">

                    <h3 class="section-title font-semibold text-gray-900 mb-6">
                        Terms of Use
                    </h3>

                    <ul class="space-y-3 text-sm">

                        <template x-for="item in sections">

                            <li>

                                <a :href="'#' + item.id" @click.prevent="scrollTo(item.id)"
                                    :class="active === item.id ?
                                        'text-[#16A34A] font-semibold' :
                                        'text-gray-600 hover:text-[#16A34A]'">

                                    <span x-text="item.title"></span>

                                </a>

                            </li>

                        </template>

                    </ul>

                </div>

            </aside>



            {{-- CONTENT --}}
            <div class="flex-1 max-w-4xl text-sm text-gray-700 leading-relaxed">

                <div class="border-l-4 border-[#16A34A] pl-6 space-y-12">

                    <p>
                        These Terms of Use constitute a legally binding agreement between you
                        and <strong>JobAbroad</strong>. By accessing our website or using our
                        services you acknowledge that you have read, understood, and agreed
                        to comply with these terms.
                    </p>

                    <p>
                        The JobAbroad platform connects job seekers and employers while
                        providing tools for recruitment, candidate discovery, and job
                        advertisement management.
                    </p>



                    {{-- SECTION 1 --}}
                    <section id="binding" class="scroll-mt-32">

                        <h2 class="section-tile text-lg font-semibold text-[#16A34A] mb-4">
                            1. Binding Agreement
                        </h2>

                        <p>
                            These Terms constitute a binding agreement between the user and
                            JobAbroad regarding the use of the platform and services.
                        </p>

                        <p class="mt-3">
                            By creating an account, browsing the website, or using any features
                            provided on the platform you acknowledge that you agree to be legally
                            bound by these Terms of Use.
                        </p>

                    </section>



                    {{-- SECTION 2 --}}
                    <section id="definitions" class="scroll-mt-32">

                        <h2 class="section-title text-lg font-semibold text-[#16A34A] mb-4">
                            2. Definitions
                        </h2>

                        <ul class="list-disc pl-6 space-y-2">

                            <li><strong>Candidate</strong> – an individual seeking employment.</li>

                            <li><strong>Employer</strong> – a company or organization recruiting workers.</li>

                            <li><strong>Advertiser</strong> – a party posting job advertisements.</li>

                            <li><strong>Services</strong> – all platform tools and features.</li>

                            <li><strong>Platform</strong> – the JobAbroad website and related systems.</li>

                        </ul>

                    </section>



                    {{-- SECTION 3 --}}
                    <section id="registration" class="scroll-mt-32">

                        <h2 class="section-title text-lg font-semibold text-[#16A34A] mb-4">
                            3. User Registration
                        </h2>

                        <p>
                            To access certain features of the platform users must register and
                            create an account.
                        </p>

                        <p class="mt-3">
                            Users agree to provide accurate, complete, and updated information
                            during the registration process.
                        </p>

                        <p class="mt-3">
                            Failure to provide accurate information may result in account
                            suspension or termination.
                        </p>

                    </section>



                    {{-- SECTION 4 --}}
                    <section id="security" class="scroll-mt-32">

                        <h2 class="section-title text-lg font-semibold text-[#16A34A] mb-4">
                            4. Password and Account Security
                        </h2>

                        <p>
                            Users are responsible for maintaining the confidentiality of their
                            account credentials.
                        </p>

                        <p class="mt-3">
                            Any activity occurring under your account is considered your
                            responsibility.
                        </p>

                        <p class="mt-3">
                            Users must immediately report unauthorized access to their accounts.
                        </p>

                    </section>



                    {{-- SECTION 5 --}}
                    <section id="intellectual" class="scroll-mt-32">

                        <h2 class="section-title text-lg font-semibold text-[#16A34A] mb-4">
                            5. Intellectual Property
                        </h2>

                        <p>
                            All website content including graphics, text, logos, and software
                            belongs to JobAbroad and is protected by intellectual property laws.
                        </p>

                        <p class="mt-3">
                            Users may not reproduce or distribute platform materials without
                            written permission.
                        </p>

                    </section>



                    {{-- SECTION 6 --}}
                    <section id="availability" class="scroll-mt-32">

                        <h2 class="section-title text-lg font-semibold text-[#16A34A] mb-4">
                            6. Website Availability
                        </h2>

                        <p>
                            JobAbroad aims to maintain continuous platform availability but
                            cannot guarantee uninterrupted service.
                        </p>

                        <p class="mt-3">
                            Maintenance, upgrades, and technical issues may temporarily
                            interrupt access.
                        </p>

                    </section>



                    {{-- SECTION 7 --}}
                    <section id="usage" class="scroll-mt-32">

                        <h2 class="section-title text-lg font-semibold text-[#16A34A] mb-4">
                            7. Acceptable Use
                        </h2>

                        <ul class="list-disc pl-6 space-y-2">

                            <li>Users must use the platform only for legitimate employment purposes.</li>

                            <li>Posting fraudulent job listings is prohibited.</li>

                            <li>Uploading malicious software is prohibited.</li>

                            <li>Unauthorized system access is strictly forbidden.</li>

                        </ul>

                    </section>



                    {{-- SECTION 8 --}}
                    <section id="services" class="scroll-mt-32">

                        <h2 class="section-title text-lg font-semibold text-[#16A34A] mb-4">
                            8. Platform Services
                        </h2>

                        <p>
                            The platform provides tools for job discovery, candidate management,
                            and recruitment.
                        </p>

                        <p class="mt-3">
                            JobAbroad does not guarantee employment opportunities or hiring
                            outcomes.
                        </p>

                    </section>



                    {{-- SECTION 9 --}}
                    <section id="employers" class="scroll-mt-32">

                        <h2 class="section-title text-lg font-semibold text-[#16A34A] mb-4">
                            9. Employer Responsibilities
                        </h2>

                        <p>
                            Employers must ensure that job listings are legitimate and comply
                            with labor laws.
                        </p>

                        <p class="mt-3">
                            JobAbroad reserves the right to remove job listings that violate
                            platform policies.
                        </p>

                    </section>



                    {{-- SECTION 10 --}}
                    <section id="law" class="scroll-mt-32">

                        <h2 class="section-title text-lg font-semibold text-[#16A34A] mb-4">
                            10. Applicable Law
                        </h2>

                        <p>
                            These Terms are governed by the laws of the Republic of the
                            Philippines.
                        </p>

                    </section>



                </div>
            </div>

        </div>

    </section>



    {{-- SCROLLSPY --}}
    <script>
        function termsNavigation() {

            return {

                open: false,

                active: 'binding',

                sections: [

                    {
                        id: 'binding',
                        title: '1. Binding Agreement'
                    },

                    {
                        id: 'definitions',
                        title: '2. Definitions'
                    },

                    {
                        id: 'registration',
                        title: '3. Registration'
                    },

                    {
                        id: 'security',
                        title: '4. Password & Security'
                    },

                    {
                        id: 'intellectual',
                        title: '5. Intellectual Property'
                    },

                    {
                        id: 'availability',
                        title: '6. Website Availability'
                    },

                    {
                        id: 'usage',
                        title: '7. Acceptable Use'
                    },

                    {
                        id: 'services',
                        title: '8. Services'
                    },

                    {
                        id: 'employers',
                        title: '9. Employers'
                    },

                    {
                        id: 'law',
                        title: '10. Applicable Law'
                    }

                ],

                init() {

                    window.addEventListener('scroll', () => {

                        let scrollY = window.scrollY + 200

                        this.sections.forEach(section => {

                            let el = document.getElementById(section.id)

                            if (el && el.offsetTop <= scrollY) {

                                this.active = section.id

                            }

                        })

                    })

                },

                scrollTo(id) {

                    const el = document.getElementById(id)

                    window.scrollTo({

                        top: el.offsetTop - 120,
                        behavior: 'smooth'

                    })

                }

            }

        }
    </script>

@endsection
