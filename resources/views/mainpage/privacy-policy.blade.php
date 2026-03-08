@extends('main')

@section('title', 'Privacy Policy')

@section('content')

    <div x-data="policyPage()" x-init="init()" class="scroll-smooth">

        {{-- HERO --}}
        <section class="relative overflow-hidden bg-gradient-to-br from-green-50 via-white to-green-100">

            <div class="absolute -top-24 -left-24 w-96 h-96 bg-green-200 rounded-full blur-3xl opacity-40"></div>
            <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-green-300 rounded-full blur-3xl opacity-40"></div>

            <div class="max-w-7xl mx-auto px-6 py-24 relative">

                <div class="grid md:grid-cols-2 gap-10 items-center">

                    <div>

                        <h1 class="hero-title text-4xl md:text-5xl font-bold text-gray-900">
                            Privacy Policy
                        </h1>

                        <p class="text-gray-600 mt-5 text-lg">
                            This Privacy Policy explains how
                            <span class="text-[#16A34A] font-semibold">JobAbroad</span>
                            collects, uses, protects, and manages your personal
                            information when you access or use our recruitment
                            platform, job listings, and employer services.
                        </p>

                        <div class="mt-6 flex items-center gap-4">

                            <span class="px-4 py-2 bg-green-100 text-green-700 text-sm rounded-full font-medium">
                                Updated 2026
                            </span>

                            <span class="text-sm text-gray-500">
                                Last updated: January 2026
                            </span>

                        </div>

                    </div>

                    <div class="flex justify-center">

                        <img src="/images/policy-illustration.jpg" class="w-96 drop-shadow-xl" alt="Privacy Illustration">

                    </div>

                </div>

            </div>

        </section>



        {{-- MOBILE MENU --}}
        <div class="lg:hidden border-b bg-white">

            <button @click="mobileMenu=!mobileMenu"
                class="w-full flex justify-between items-center px-6 py-4 font-semibold">

                Privacy Sections

                <span x-show="!mobileMenu">+</span>
                <span x-show="mobileMenu">−</span>

            </button>

            <div x-show="mobileMenu" x-transition class="px-6 pb-4">

                <ul class="space-y-2 text-sm">

                    <template x-for="section in sections">

                        <li>
                            <a :href="'#' + section.id" class="text-gray-600 block" @click="mobileMenu=false"
                                x-text="section.title">
                            </a>
                        </li>

                    </template>

                </ul>

            </div>

        </div>



        {{-- CONTENT --}}
        <section class="py-16">

            <div class="max-w-7xl mx-auto px-6 flex gap-12">

                {{-- SIDEBAR --}}
                <aside class="hidden lg:block w-72">

                    <div class="sticky top-28">

                        <h3 class="section-title font-semibold text-gray-900 mb-6">
                            Privacy Policy
                        </h3>

                        <ul class="space-y-3 text-sm">

                            <template x-for="section in sections">

                                <li>

                                    <a :href="'#' + section.id" class="block transition font-medium"
                                        :class="active === section.id ?
                                            'text-[#16A34A]' :
                                            'text-gray-600 hover:text-[#16A34A]'"
                                        x-text="section.title"></a>

                                </li>

                            </template>

                        </ul>

                    </div>

                </aside>



                {{-- DIVIDER LINE --}}
                <div class="hidden lg:block w-px bg-gray-200"></div>



                {{-- MAIN CONTENT --}}
                <div class="flex-1 max-w-4xl space-y-12">

                    <p class="text-gray-600 text-sm leading-relaxed">
                        At JobAbroad, we recognize the importance of protecting your privacy
                        and maintaining the security of your personal data. This Privacy Policy
                        describes how information is collected, used, stored, and disclosed when
                        you interact with our platform. By accessing or using JobAbroad, you
                        acknowledge that you have read and understood this policy and agree to
                        the collection and use of your information as described herein.
                    </p>



                    {{-- SECTIONS --}}
                    <template x-for="section in sections">

                        <div :id="section.id" class="scroll-mt-32">

                            <h2 class="section-title text-lg font-semibold mb-3" x-text="section.title"></h2>

                            <p class="text-gray-600 text-sm leading-relaxed whitespace-pre-line" x-text="section.text"></p>

                        </div>

                    </template>


                </div>

            </div>

        </section>

    </div>



    {{-- ALPINE LOGIC --}}
    <script>
        function policyPage() {

            return {

                mobileMenu: false,
                active: null,

                sections: [

                    {
                        id: 'collection',
                        title: '1. Collection of Personal Information',
                        text: `JobAbroad collects personal information that you voluntarily provide when creating an account, applying for jobs, or interacting with the platform. This information may include your full name, email address, phone number, employment history, education background, professional skills, uploaded resumes, and other supporting documents.

We may also collect technical information such as IP address, device type, browser type, and usage data to help us understand how users interact with the platform and to improve the performance, security, and reliability of our services.`
                    },

                    {
                        id: 'purposes',
                        title: '2. Purposes of Collecting Information',
                        text: `The personal information collected through JobAbroad is used for several legitimate purposes. These include facilitating job applications, allowing employers to review candidate profiles, improving our recruitment services, verifying user identity, and maintaining the security of the platform.

We may also use your information to communicate important updates, notify you of job opportunities, improve our platform features, and ensure compliance with applicable legal obligations.`
                    },

                    {
                        id: 'profile',
                        title: '3. User Profiles',
                        text: `Job seekers may create professional profiles that contain personal and professional information such as resumes, skills, certifications, work experience, and educational background. This information may be visible to employers who are seeking qualified candidates.

Users are responsible for ensuring that the information provided in their profiles is accurate, truthful, and up-to-date. Misleading or false information may result in account suspension or removal from the platform.`
                    },

                    {
                        id: 'choice',
                        title: '4. Choice and Access',
                        text: `Users have the ability to access, update, or correct their personal information through their account dashboard. Maintaining accurate and updated information helps ensure better job matching and improves communication between candidates and employers.

If you wish to modify or remove specific information from your profile, you may do so at any time through your account settings.`
                    },

                    {
                        id: 'retention',
                        title: '5. Data Retention',
                        text: `JobAbroad retains personal information only for as long as necessary to fulfill the purposes outlined in this Privacy Policy. Data may also be retained for legal, administrative, or security purposes when required by applicable laws or regulations.

Once the retention period expires, personal information may be securely deleted or anonymized to prevent unauthorized access.`
                    },

                    {
                        id: 'security',
                        title: '6. Security of Information',
                        text: `We implement appropriate technical and organizational security measures to protect your personal data against unauthorized access, misuse, disclosure, or alteration.

These measures may include secure servers, encrypted communications, restricted access to sensitive information, and monitoring systems designed to detect suspicious activity.`
                    },

                    {
                        id: 'disclosure',
                        title: '7. Disclosure of Information',
                        text: `Personal information may be shared with verified employers who are actively recruiting candidates through the JobAbroad platform. Information may also be disclosed to trusted service providers who assist in operating the platform, provided they adhere to strict confidentiality requirements.

We do not sell personal information to third parties.`
                    },

                    {
                        id: 'obligations',
                        title: '8. User Responsibilities',
                        text: `Users are responsible for maintaining the confidentiality of their account credentials and ensuring that any information submitted to the platform is accurate and lawful.

Any misuse of the platform or submission of fraudulent information may result in account suspension or permanent termination.`
                    },

                    {
                        id: 'transfer',
                        title: '9. Data Transfers',
                        text: `Because JobAbroad may operate across different regions, your information may be transferred and processed in locations outside your immediate jurisdiction. In such cases, appropriate safeguards are implemented to ensure the protection of your data.`
                    },

                    {
                        id: 'links',
                        title: '10. External Links',
                        text: `The JobAbroad platform may contain links to external websites or third-party services. These websites operate independently and have their own privacy policies.

We encourage users to review the privacy policies of any external sites they visit.`
                    },

                    {
                        id: 'consent',
                        title: '11. Your Consent',
                        text: `By using the JobAbroad platform, you consent to the collection, processing, and use of your personal information as described in this Privacy Policy.`
                    },

                    {
                        id: 'rights',
                        title: '12. Your Rights',
                        text: `Depending on applicable laws, users may have the right to request access to their personal information, request corrections, request deletion, or object to certain forms of data processing.

Requests may be submitted through our official contact channels.`
                    },

                    {
                        id: 'contact',
                        title: '13. Contact Information',
                        text: `If you have questions, concerns, or requests related to this Privacy Policy or your personal data, you may contact the JobAbroad support team through our official Contact page.`
                    }

                ],

                init() {

                    window.addEventListener('scroll', () => {

                        this.sections.forEach(section => {

                            let el = document.getElementById(section.id)

                            if (el) {

                                let rect = el.getBoundingClientRect()

                                if (rect.top <= 120 && rect.bottom >= 120) {

                                    this.active = section.id

                                }

                            }

                        })

                    })

                }

            }

        }
    </script>

@endsection
