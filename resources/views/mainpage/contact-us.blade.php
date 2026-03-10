@extends('main')

@section('title', 'Contact Us')

@section('content')

    {{-- HERO --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-green-50 via-white to-green-100">

        <div class="absolute -top-20 -left-20 w-96 h-96 bg-green-200 rounded-full blur-3xl opacity-30"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-green-300 rounded-full blur-3xl opacity-30"></div>

        <div class="max-w-6xl mx-auto px-6 py-20 relative">

            <div class="max-w-2xl">

                <h1 class="text-4xl md:text-5xl font-bold text-gray-900">
                    Contact Us
                </h1>

                <p class="mt-4 text-lg text-gray-600">
                    Have questions about overseas jobs, recruitment agencies,
                    or partnerships with <span class="font-semibold text-[#16A34A]">JobAbroad</span>?
                    Send us a message and our team will get back to you shortly.
                </p>

            </div>

        </div>

    </section>


    <section class="bg-white py-20">

        <div class="max-w-7xl mx-auto px-6">

            <div class="grid lg:grid-cols-3 gap-12 items-start">

                {{-- CONTACT FORM --}}
                <div class="lg:col-span-2 bg-white border border-gray-100 p-10 rounded-2xl shadow-sm">

                    <h2 class="text-xl font-semibold text-gray-900 mb-6">
                        Send Us a Message
                    </h2>

                    {{-- SUCCESS MESSAGE --}}
                    @if (session('success'))
                        <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-6">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- VALIDATION ERRORS --}}
                    @if ($errors->any())
                        <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6">
                            <ul class="list-disc ml-5 text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif


                    <form method="POST" action="{{ route('contact.send') }}" class="space-y-6">

                        @csrf


                        {{-- ROLE / TYPE --}}
                        <div>

                            <label class="text-sm font-medium text-gray-700 block mb-2">
                                I am a
                            </label>

                            <select name="role"
                                class="w-full rounded-lg border border-gray-300 px-4 py-3
focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">

                                <option value="">Select Role</option>

                                <option value="Applicant">Applicant</option>

                                <option value="Recruitment Agency">Recruitment Agency</option>

                                <option value="Employer">Employer</option>

                                <option value="HR">HR</option>

                                <option value="Marketing">Marketing</option>

                                <option value="Partnership">Partnership</option>

                                <option value="Support">Support</option>

                            </select>

                        </div>


                        {{-- NAME --}}
                        <div>

                            <label class="text-sm font-medium text-gray-700 block mb-2">
                                Full Name
                            </label>

                            <input type="text" name="name" value="{{ old('name') }}"
                                placeholder="Enter your full name"
                                class="w-full rounded-lg border border-gray-300 px-4 py-3
focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">

                        </div>


                        {{-- EMAIL --}}
                        <div>

                            <label class="text-sm font-medium text-gray-700 block mb-2">
                                Email Address
                            </label>

                            <input type="email" name="email" value="{{ old('email') }}" placeholder="you@email.com"
                                class="w-full rounded-lg border border-gray-300 px-4 py-3
focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">

                        </div>


                        {{-- PHONE --}}
                        <div>

                            <label class="text-sm font-medium text-gray-700 block mb-2">
                                Contact Number
                            </label>

                            <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+63 9XX XXX XXXX"
                                class="w-full rounded-lg border border-gray-300 px-4 py-3
focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">

                        </div>


                        {{-- MESSAGE --}}
                        <div>

                            <label class="text-sm font-medium text-gray-700 block mb-2">
                                Message
                            </label>

                            <textarea name="message" rows="5" placeholder="Type your message here..."
                                class="w-full rounded-lg border border-gray-300 px-4 py-3
focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">{{ old('message') }}</textarea>

                        </div>
                        <input type="text" name="website" class="hidden">


                        <button type="submit"
                            class="bg-emerald-600 text-white px-6 py-3 rounded-lg font-semibold
hover:bg-emerald-700 transition shadow-sm">

                            Send Message

                        </button>

                    </form>

                </div>



                {{-- CONTACT INFO --}}
                <div class="space-y-6">


                    {{-- PHONE --}}
                    <div class="bg-gray-50 border border-gray-100 rounded-xl p-6 flex gap-4 items-start">

                        <div class="bg-emerald-100 p-3 rounded-lg">
                            <i data-lucide="phone" class="w-5 h-5 text-emerald-600"></i>
                        </div>

                        <div>

                            <p class="font-semibold text-gray-900">
                                Contact Number
                            </p>

                            <p class="text-gray-600 text-sm">
                                (+63) 9617-190-588
                            </p>
                             <p class="text-gray-600 text-sm">
                                (+966) 508-624-264
                            </p>

                        </div>

                    </div>



                    {{-- EMAIL --}}
                    <div class="bg-gray-50 border border-gray-100 rounded-xl p-6 flex gap-4 items-start">

                        <div class="bg-emerald-100 p-3 rounded-lg">
                            <i data-lucide="mail" class="w-5 h-5 text-emerald-600"></i>
                        </div>

                        <div>

                            <p class="font-semibold text-gray-900">
                                Email Address
                            </p>

                            <p class="text-gray-600 text-sm">
                                inquiry@jobabroad.ph
                            </p>

                        </div>

                    </div>



                    {{-- OFFICE HOURS --}}
                    <div class="bg-gray-50 border border-gray-100 rounded-xl p-6 flex gap-4 items-start">

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
            <div class="mt-20 grid md:grid-cols-3 gap-10 border-t pt-12">

                <div>

                    <h4 class="font-semibold text-gray-900 mb-2">
                        For Job Seekers
                    </h4>

                    <p class="text-gray-600 text-sm">
                        Browse overseas job listings and apply directly through JobAbroad.
                        Find verified opportunities from trusted employers.
                    </p>

                </div>


                <div>

                    <h4 class="font-semibold text-gray-900 mb-2">
                        For Agencies
                    </h4>

                    <p class="text-gray-600 text-sm">
                        Partner with JobAbroad to connect with skilled Filipino professionals
                        seeking international career opportunities.
                    </p>

                </div>


                <div>

                    <h4 class="font-semibold text-gray-900 mb-2">
                        Support
                    </h4>

                    <p class="text-gray-600 text-sm">
                        Need help with your account or job application?
                        Our support team is ready to assist you.
                    </p>

                </div>

            </div>

        </div>

    </section>

@endsection
