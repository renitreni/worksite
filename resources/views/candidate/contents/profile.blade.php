@extends('candidate.layout')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-start sm:items-center justify-between gap-4 flex-col sm:flex-row">
        <div>
            <h1 class="text-xl sm:text-2xl font-semibold text-gray-900">My Profile</h1>
        </div>

        <button type="button"
            class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700 transition">
            Edit Profile
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        {{-- Left column --}}
        <div class="lg:col-span-4 space-y-6">
            {{-- Profile card --}}
            <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-6">
                <div class="flex flex-col items-center text-center">
                    <img
                        src="https://images.unsplash.com/photo-1527980965255-d3b416303d12?auto=format&fit=crop&w=240&h=240&q=80"
                        alt="Keith Pelonio"
                        class="h-28 w-28 rounded-full object-cover ring-4 ring-gray-100"
                    />
                    <p class="mt-4 text-base font-semibold text-gray-900">Keith Pelonio</p>
                    <p class="text-sm text-gray-500">Senior UX Designer</p>
                </div>
            </div>

            {{-- Social Links --}}
            <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-6">
                <h2 class="text-sm font-semibold text-gray-900">Social Links</h2>

                <div class="mt-4 space-y-4">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 mb-1">LinkedIn</p>
                        <div class="rounded-xl bg-gray-50 border border-gray-200 px-4 py-2.5 text-sm text-gray-700">
                            linkedin.com/in/keithpelonio
                        </div>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-500 mb-1">GitHub</p>
                        <div class="rounded-xl bg-gray-50 border border-gray-200 px-4 py-2.5 text-sm text-gray-700">
                            github.com/keithpelonio
                        </div>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-500 mb-1">Portfolio</p>
                        <div class="rounded-xl bg-gray-50 border border-gray-200 px-4 py-2.5 text-sm text-gray-700">
                            keith.design
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right column --}}
        <div class="lg:col-span-8 space-y-6">
            {{-- Personal Information --}}
            <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-6">
                <h2 class="text-sm font-semibold text-gray-900">Personal Information</h2>

                <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 mb-1">Full Name</p>
                        <div class="rounded-xl bg-gray-50 border border-gray-200 px-4 py-2.5 text-sm text-gray-700">
                            Keith Pelonio
                        </div>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-500 mb-1">Job Title</p>
                        <div class="rounded-xl bg-gray-50 border border-gray-200 px-4 py-2.5 text-sm text-gray-700">
                            Senior UX Designer
                        </div>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-500 mb-1">Email</p>
                        <div class="rounded-xl bg-gray-50 border border-gray-200 px-4 py-2.5 text-sm text-gray-700">
                            keithpelonio@example.com
                        </div>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-500 mb-1">Phone</p>
                        <div class="rounded-xl bg-gray-50 border border-gray-200 px-4 py-2.5 text-sm text-gray-700">
                            +1 (555) 123-4567
                        </div>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-500 mb-1">Experience</p>
                        <div class="rounded-xl bg-gray-50 border border-gray-200 px-4 py-2.5 text-sm text-gray-700">
                            5 years
                        </div>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-500 mb-1">Location</p>
                        <div class="rounded-xl bg-gray-50 border border-gray-200 px-4 py-2.5 text-sm text-gray-700">
                            San Francisco, CA
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <p class="text-xs font-semibold text-gray-500 mb-1">Bio</p>
                    <div class="rounded-xl bg-gray-50 border border-gray-200 px-4 py-3 text-sm text-gray-700 leading-relaxed">
                        Passionate UX Designer with a focus on accessible and inclusive design. Experienced in building enterprise applications and consumer-facing products.
                    </div>
                </div>
            </div>

            {{-- Professional Details --}}
            <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-6">
                <h2 class="text-sm font-semibold text-gray-900">Professional Details</h2>

                <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 mb-1">Highest Qualification</p>
                        <select class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300">
                            <option selected>Bachelor's Degree</option>
                            <option>Master's Degree</option>
                            <option>Doctorate</option>
                        </select>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-500 mb-1">Current Salary</p>
                        <select class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300">
                            <option selected>$50k - $80k</option>
                            <option>$80k - $100k</option>
                            <option>$100k - $130k</option>
                        </select>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-500 mb-1">Expected Salary</p>
                        <select class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300">
                            <option selected>$80k - $100k</option>
                            <option>$100k - $130k</option>
                            <option>$130k - $160k</option>
                        </select>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-500 mb-1">Job Type</p>
                        <select class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300">
                            <option selected>Full Time</option>
                            <option>Part Time</option>
                            <option>Contract</option>
                            <option>Freelance</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection