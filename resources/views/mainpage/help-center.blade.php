@extends('main')

@section('title', 'Help Center')

@section('content')

    <section class="bg-gray-50 py-20">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- HEADER --}}
            <div class="text-center max-w-2xl mx-auto mb-16">

                <h2 class="text-3xl md:text-4xl font-bold text-gray-900">
                    Help Center
                </h2>

                <p class="mt-3 text-gray-600">
                    Find answers to common questions about using WorkAbroad.ph.
                    Whether you are an applicant or an agency employer, this guide
                    will help you understand how to use the platform effectively.
                </p>

            </div>


            {{-- HELP CATEGORIES --}}
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-20">

                {{-- ACCOUNT --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm hover:shadow-md transition">

                    <div class="w-10 h-10 flex items-center justify-center bg-emerald-100 text-emerald-600 rounded-lg mb-4">

                        {{-- Lucide User --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor"
                            stroke-width="2" viewBox="0 0 24 24">
                            <path d="M20 21a8 8 0 10-16 0" />
                            <circle cx="12" cy="7" r="4" />
                        </svg>

                    </div>

                    <h3 class="font-semibold text-gray-900 mb-1">
                        Account Management
                    </h3>

                    <p class="text-sm text-gray-600">
                        Learn how to create an account, update your profile, manage
                        login information, and keep your account secure.
                    </p>

                </div>


                {{-- JOB APPLICATIONS --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm hover:shadow-md transition">

                    <div class="w-10 h-10 flex items-center justify-center bg-blue-100 text-blue-600 rounded-lg mb-4">

                        {{-- Lucide Briefcase --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor"
                            stroke-width="2" viewBox="0 0 24 24">
                            <rect x="2" y="7" width="20" height="14" rx="2" />
                            <path d="M16 3H8v4h8V3z" />
                        </svg>

                    </div>

                    <h3 class="font-semibold text-gray-900 mb-1">
                        Job Applications
                    </h3>

                    <p class="text-sm text-gray-600">
                        Understand how to search for jobs, apply to overseas opportunities,
                        and track the status of your job applications.
                    </p>

                </div>


                {{-- EMPLOYERS --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm hover:shadow-md transition">

                    <div class="w-10 h-10 flex items-center justify-center bg-purple-100 text-purple-600 rounded-lg mb-4">

                        {{-- Lucide Building --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor"
                            stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="2" width="18" height="20" rx="2" />
                            <path d="M9 22V12h6v10" />
                        </svg>

                    </div>

                    <h3 class="font-semibold text-gray-900 mb-1">
                        Agencies & Employers
                    </h3>

                    <p class="text-sm text-gray-600">
                        Information for licensed recruitment agencies and employers
                        who want to post overseas job opportunities.
                    </p>

                </div>


                {{-- SAFETY --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm hover:shadow-md transition">

                    <div class="w-10 h-10 flex items-center justify-center bg-orange-100 text-orange-600 rounded-lg mb-4">

                        {{-- Lucide Shield --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor"
                            stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12 3l8 4v5c0 5-3.5 9-8 9s-8-4-8-9V7l8-4z" />
                        </svg>

                    </div>

                    <h3 class="font-semibold text-gray-900 mb-1">
                        Safety Tips
                    </h3>

                    <p class="text-sm text-gray-600">
                        Learn how to avoid job scams and ensure that you only apply
                        to legitimate overseas job opportunities.
                    </p>

                </div>

            </div>


            {{-- FAQ SECTION --}}
            <div class="max-w-4xl mx-auto">

                <h3 class="text-2xl font-semibold text-center text-gray-900 mb-10">
                    Frequently Asked Questions
                </h3>

                <div class="space-y-5">


                    {{-- FAQ --}}
                    <details class="bg-white rounded-xl shadow-sm p-6">
                        <summary class="cursor-pointer font-semibold text-gray-800">
                            How do I apply for a job on WorkAbroad?
                        </summary>

                        <p class="mt-3 text-gray-600 text-sm leading-relaxed">
                            Applicants can search for available overseas job opportunities
                            using the job search page. Once you find a job that matches your
                            skills, click the <strong>Apply Now</strong> button. You may be required
                            to log in or create an account before submitting your application.
                        </p>
                    </details>


                    {{-- FAQ --}}
                    <details class="bg-white rounded-xl shadow-sm p-6">
                        <summary class="cursor-pointer font-semibold text-gray-800">
                            Is it free for applicants to use the platform?
                        </summary>

                        <p class="mt-3 text-gray-600 text-sm leading-relaxed">
                            Yes. Applicants can create an account, search for jobs,
                            and apply to overseas opportunities without any cost.
                            However, recruitment agencies may have their own
                            processing requirements depending on the employer.
                        </p>
                    </details>


                    {{-- FAQ --}}
                    <details class="bg-white rounded-xl shadow-sm p-6">
                        <summary class="cursor-pointer font-semibold text-gray-800">
                            How can recruitment agencies post jobs?
                        </summary>

                        <p class="mt-3 text-gray-600 text-sm leading-relaxed">
                            Licensed recruitment agencies can register an agency account
                            and access the agency dashboard. From the dashboard, employers
                            can create job postings, manage applicants, and monitor
                            applications submitted through the platform.
                        </p>
                    </details>


                    {{-- FAQ --}}
                    <details class="bg-white rounded-xl shadow-sm p-6">
                        <summary class="cursor-pointer font-semibold text-gray-800">
                            Do employers need a subscription to post jobs?
                        </summary>

                        <p class="mt-3 text-gray-600 text-sm leading-relaxed">
                            Yes. Recruitment agencies and employers may need an active
                            subscription plan to post job listings. Subscription plans
                            allow employers to publish job opportunities, manage applicants,
                            and gain access to recruitment tools within the system.
                        </p>
                    </details>


                    {{-- FAQ --}}
                    <details class="bg-white rounded-xl shadow-sm p-6">
                        <summary class="cursor-pointer font-semibold text-gray-800">
                            How can I track my job applications?
                        </summary>

                        <p class="mt-3 text-gray-600 text-sm leading-relaxed">
                            Applicants can log in to their account and navigate to the
                            <strong>Applied Jobs</strong> section of their dashboard.
                            This page shows the status of applications submitted
                            to recruitment agencies.
                        </p>
                    </details>


                    {{-- FAQ --}}
                    <details class="bg-white rounded-xl shadow-sm p-6">
                        <summary class="cursor-pointer font-semibold text-gray-800">
                            How do I avoid overseas job scams?
                        </summary>

                        <p class="mt-3 text-gray-600 text-sm leading-relaxed">
                            Always verify that the recruitment agency is licensed and
                            legitimate. Avoid sending money to individuals claiming to
                            offer overseas jobs without proper documentation.
                            WorkAbroad encourages applicants to apply only through
                            verified job listings.
                        </p>
                    </details>


                </div>

            </div>


            {{-- CONTACT SUPPORT --}}
            <div class="mt-20 text-center">

                <div class="bg-white rounded-2xl shadow-sm p-10 max-w-2xl mx-auto">

                    <h3 class="text-xl font-semibold text-gray-900 mb-2">
                        Still need help?
                    </h3>

                    <p class="text-gray-600 mb-6">
                        If you cannot find the answer you are looking for,
                        our support team is ready to assist you.
                    </p>

                    <a href="{{ route('contact') }}"
                        class="inline-flex items-center justify-center bg-emerald-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-emerald-700 transition">

                        Contact Support

                    </a>

                </div>

            </div>


        </div>

    </section>

@endsection
