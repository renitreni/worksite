@extends('main')

@section('title', 'Contact Us')

@section('content')

    <section class="bg-gray-50 py-16">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- HEADER --}}

            <div class="max-w-xl mb-14">

                <h2 class="text-3xl md:text-4xl font-bold text-gray-900">
                    Contact Us
                </h2>

                <p class="mt-3 text-gray-600">
                    Have a question about overseas jobs or partnerships?
                    Send us a message and our team will get back to you soon.
                </p>

            </div>

            <div class="grid lg:grid-cols-3 gap-12 items-start">

                {{-- CONTACT FORM --}}

                <div class="lg:col-span-2 bg-white p-8 rounded-xl shadow-sm">

                    <form method="POST" class="space-y-6">
                        @csrf

                        {{-- TYPE --}}

                        <div>
                            <label class="text-sm font-medium text-gray-700 mb-2 block">
                                I am a
                            </label>

                            <select name="type"
                                class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-emerald-500 focus:outline-none">

                                <option value="applicant">Applicant</option>
                                <option value="agency">Recruitment Agency</option>

                            </select>
                        </div>

                        {{-- NAME --}}

                        <div>
                            <label class="text-sm font-medium text-gray-700 mb-2 block">
                                Full Name
                            </label>

                            <input type="text" name="name" placeholder="Enter your full name"
                                class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-emerald-500 focus:outline-none">

                        </div>

                        {{-- EMAIL --}}

                        <div>
                            <label class="text-sm font-medium text-gray-700 mb-2 block">
                                Email Address
                            </label>

                            <input type="email" name="email" placeholder="you@email.com"
                                class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-emerald-500 focus:outline-none">

                        </div>

                        {{-- PHONE --}}

                        <div>
                            <label class="text-sm font-medium text-gray-700 mb-2 block">
                                Contact Number
                            </label>

                            <input type="text" name="phone" placeholder="+63 9XX XXX XXXX"
                                class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-emerald-500 focus:outline-none">

                        </div>

                        {{-- MESSAGE --}}

                        <div>
                            <label class="text-sm font-medium text-gray-700 mb-2 block">
                                Message
                            </label>

                            <textarea name="message" rows="5" placeholder="Type your message here..."
                                class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-emerald-500 focus:outline-none"></textarea>

                        </div>

                        <button type="submit"
                            class="bg-emerald-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-emerald-700 transition">

                            Send Message

                        </button>

                    </form>

                </div>

                {{-- CONTACT INFO --}}

                <div class="space-y-6">

                    <div class="flex items-start gap-4">

                        <div class="bg-emerald-100 p-3 rounded-lg">
                            <i data-lucide="phone" class="w-5 h-5 text-emerald-600"></i>
                        </div>

                        <div>
                            <p class="font-semibold text-gray-900">
                                Contact Number
                            </p>
                            <p class="text-gray-600 text-sm">
                                (+632) 8-860-5800
                            </p>
                        </div>

                    </div>

                    <div class="flex items-start gap-4">

                        <div class="bg-emerald-100 p-3 rounded-lg">
                            <i data-lucide="mail" class="w-5 h-5 text-emerald-600"></i>
                        </div>

                        <div>
                            <p class="font-semibold text-gray-900">
                                Email
                            </p>
                            <p class="text-gray-600 text-sm">
                                customercare@workabroad.ph
                            </p>
                        </div>

                    </div>

                    <div class="flex items-start gap-4">

                        <div class="bg-emerald-100 p-3 rounded-lg">
                            <i data-lucide="clock" class="w-5 h-5 text-emerald-600"></i>
                        </div>

                        <div>
                            <p class="font-semibold text-gray-900">
                                Office Hours
                            </p>
                            <p class="text-gray-600 text-sm">
                                Monday – Friday<br>
                                9:00 AM – 6:00 PM
                            </p>
                        </div>

                    </div>

                </div>

            </div>

            {{-- EXTRA INFO --}}

            <div class="mt-20 grid md:grid-cols-3 gap-10">

                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">
                        For Job Seekers
                    </h4>
                    <p class="text-gray-600 text-sm">
                        Browse overseas job listings and apply directly through JobAbroad.
                    </p>
                </div>

                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">
                        For Agencies
                    </h4>
                    <p class="text-gray-600 text-sm">
                        Partner with us to connect with qualified Filipino professionals.
                    </p>
                </div>

                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">
                        Support
                    </h4>
                    <p class="text-gray-600 text-sm">
                        Need help with your account or application? Our support team is here to assist.
                    </p>
                </div>

            </div>

        </div>

    </section>

@endsection
