@extends('main')

@section('title', 'Help Center')

@section('content')

    {{-- HERO --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-green-50 via-white to-green-100 py-20">

        {{-- Illustration --}}
        <img src="images/help/illustration.jpg" alt="Help Center Illustration"
            class="absolute -top-6 right-6 w-100 opacity-30 pointer-events-none hidden md:block">

        <div class="max-w-4xl mx-auto text-center px-6 relative z-10">

            <span class="inline-block bg-green-100 text-green-700 text-xs px-3 py-1 rounded-full font-medium">
                JobAbroad Support
            </span>

            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mt-4">
                Help Center
            </h1>

            <p class="text-gray-600 mt-3 text-base max-w-xl mx-auto">
                Browse helpful guides and answers about
                <span class="font-semibold text-[#16A34A]">JobAbroad</span>.
                Whether you're a candidate or an employer, we’re here to help.
            </p>

        </div>

    </section>

    {{-- HELP CATEGORIES --}}
    <section class="py-16">

        <div class="container max-w-7xl mx-auto px-6 lg:px-8">

            {{-- CANDIDATE HELP --}}
            <div>

                <div class="flex items-center gap-3 mb-3">

                    <i data-lucide="user" class="w-7 h-7 text-[#16A34A]"></i>

                    <h2 class="text-3xl font-bold text-gray-900">
                        Candidate Help
                    </h2>

                </div>

                <p class="text-gray-600 mb-10 max-w-xl">
                    Resources and guides to help job seekers create accounts, apply for jobs abroad,
                    and manage their JobAbroad profiles.
                </p>


                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">

                    <x-help-card image="images/help/get-started.jpg" title="Getting Started"
                        description="Create your account, verify your email, and set up your candidate profile."
                        route="{{ route('help.category', 'getting-started') }}" />

                    <x-help-card image="images/help/application.jpg" title="Application Steps"
                        description="Learn how to apply for jobs and track your job applications."
                        route="{{ route('help.category', 'application-steps') }}" />

                    <x-help-card image="images/help/account-settings.jpg" title="Account Settings"
                        description="Update profile information, change password and manage preferences."
                        route="{{ route('help.category', 'account-settings') }}" />

                    <x-help-card image="images/help/visa.jpg" title="Visa & Travel"
                        description="Guidelines for visa applications and preparing for overseas employment."
                        route="{{ route('help.category', 'visa-travel') }}" />

                    <x-help-card image="images/help/tips.jpg" title="Job Abroad Tips"
                        description="Helpful advice for living and working in another country."
                        route="{{ route('help.category', 'job-tips') }}" />

                    <x-help-card image="images/help/safety.jpg" title="Safety & Compliance"
                        description="Learn about safe employment practices abroad."
                        route="{{ route('help.category', 'safety') }}" />

                </div>

            </div>



            {{-- EMPLOYER HELP --}}
            <div class="mt-28">

                <div class="flex items-center gap-3 mb-3">

                    <i data-lucide="briefcase" class="w-7 h-7 text-[#16A34A]"></i>

                    <h2 class="text-3xl font-bold text-gray-900">
                        Employer Help
                    </h2>

                </div>

                <p class="text-gray-600 mb-10 max-w-xl">
                    Learn how employers can post jobs, manage applicants, and use JobAbroad
                    to recruit international talent efficiently.
                </p>


                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">

                    <x-help-card image="images/help/job-posting.jpg" title="Job Posting"
                        description="Create and manage job advertisements for international recruitment."
                        route="{{ route('help.category', 'job-posting') }}" />

                    <x-help-card image="images/help/candidates.jpg" title="Manage Candidates"
                        description="Review applications, shortlist candidates and track hiring progress."
                        route="{{ route('help.category', 'candidate-management') }}" />

                    <x-help-card image="images/help/subscription.jpg" title="Subscription & Billing"
                        description="Manage plans, payments and billing settings."
                        route="{{ route('help.category', 'subscription') }}" />

                    <x-help-card image="images/help/manpower.jpg" title="Manpower Request"
                        description="Submit manpower requests and coordinate recruitment."
                        route="{{ route('help.category', 'manpower-request') }}" />

                    <x-help-card image="images/help/employer-tips.jpg" title="Employer Tips"
                        description="Best practices for recruiting overseas workers."
                        route="{{ route('help.category', 'employer-tips') }}" />

                </div>

            </div>

        </div>

    </section>


    {{-- SUPPORT CTA --}}
    <section class=" py-16">

        <div class="max-w-5xl mx-auto px-6 grid md:grid-cols-2 items-center gap-10">

            <video autoplay loop muted playsinline class="w-full rounded-xl">
                <source src="{{ asset('images/help/customer-service.mp4') }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
            <div>

                <h2 class="text-3xl font-bold text-gray-900">
                    Still need help?
                </h2>

                <p class="text-gray-600 mt-4">
                    If you cannot find the answer you are looking for in our Help Center,
                    our support team is ready to assist you.
                </p>

                <a href="/contact"
                    class="inline-flex items-center gap-2 mt-6 bg-[#16A34A] text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-700 transition">

                    Contact Support

                    <i data-lucide="arrow-right" class="w-4 h-4"></i>

                </a>

            </div>

        </div>

    </section>

@endsection
