@extends('employer.layout')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex items-start sm:items-center justify-between gap-4 flex-col sm:flex-row">
            <div>
                <h1 class="text-xl sm:text-2xl font-semibold text-gray-900">Edit Company Profile</h1>
                <p class="text-sm text-gray-600 mt-1">Update your logo, cover, and company details.</p>
            </div>

            <a href="{{ route('employer.company-profile') }}"
                class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                <i data-lucide="arrow-left" class="h-4 w-4"></i>
                Back
            </a>
        </div>

        {{-- Validation errors --}}
        @if($errors->any())
            <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('employer.company-profile.update') }}" method="POST" enctype="multipart/form-data"
            class="rounded-2xl bg-white border border-gray-200 shadow-sm p-6 space-y-5">
            @csrf

            {{-- Images --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-900">Cover Photo</label>
                    <input type="file" name="cover" accept="image/*"
                        class="block w-full text-sm file:mr-4 file:rounded-xl file:border-0 file:bg-gray-900 file:px-4 file:py-2 file:font-semibold file:text-white hover:file:bg-black">
                    <p class="text-xs text-gray-500">Recommended: 1600×500, max 4MB</p>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-900">Company Logo</label>
                    <input type="file" name="logo" accept="image/*"
                        class="block w-full text-sm file:mr-4 file:rounded-xl file:border-0 file:bg-emerald-600 file:px-4 file:py-2 file:font-semibold file:text-white hover:file:bg-emerald-700">
                    <p class="text-xs text-gray-500">Recommended: 400×400, max 2MB</p>
                </div>
            </div>

            {{-- Fields --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="text-xs font-semibold text-gray-700">Company Name</label>
                    <input name="company_name" required value="{{ old('company_name', $employerProfile->company_name) }}"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300">
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-semibold text-gray-700">Email</label>
                    <input name="email" type="email" required value="{{ old('email', $email) }}"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300">
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-semibold text-gray-700">Phone</label>
                    <input name="company_contact" value="{{ old('company_contact', $employerProfile->company_contact) }}"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300">
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-semibold text-gray-700">Location</label>
                    <input name="company_address" value="{{ old('company_address', $employerProfile->company_address) }}"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300">
                </div>

                <div class="sm:col-span-2 space-y-1">
                    <label class="text-xs font-semibold text-gray-700">Website</label>
                    <input name="company_website" value="{{ old('company_website', $employerProfile->company_website) }}"
                        placeholder="https://yourcompany.com"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300">
                </div>

                <div class="sm:col-span-2 space-y-2">
                    <label class="text-xs font-semibold text-gray-700">Industries (select one or more)</label>

                    @php
                        // selected values stored as array of strings in employer_profiles.industries
                        $selectedIndustries = old('industries', $employerProfile->industries ?? []);
                        if (!is_array($selectedIndustries))
                            $selectedIndustries = [];
                    @endphp

                    <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4">
                        @if($industries->count() === 0)
                            <div class="text-sm text-gray-600">
                                No industries available. Ask admin to add industries first.
                            </div>
                        @else
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach($industries as $ind)
                                    @php
                                        $name = $ind->name;
                                        $checked = in_array($name, $selectedIndustries, true);
                                    @endphp

                                    <label
                                        class="flex items-center gap-3 rounded-xl border border-gray-200 bg-white px-4 py-3 hover:bg-gray-50 cursor-pointer">
                                        <input type="checkbox" name="industries[]" value="{{ $name }}" @checked($checked)
                                            class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-200">
                                        <span class="text-sm font-semibold text-gray-800">{{ $name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>



                <div class="sm:col-span-2 space-y-1">
                    <label class="text-xs font-semibold text-gray-700">Intro / Description</label>
                    <textarea name="description" rows="4"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300"
                        placeholder="Short description...">{{ old('description', $employerProfile->description) }}</textarea>
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-semibold text-gray-700">Representative Name</label>
                    <input name="representative_name"
                        value="{{ old('representative_name', $employerProfile->representative_name) }}"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300">
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-semibold text-gray-700">Position</label>
                    <input name="position" value="{{ old('position', $employerProfile->position) }}"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300">
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex flex-col sm:flex-row gap-3 sm:justify-end pt-2">
                <a href="{{ route('employer.company-profile') }}"
                    class="rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition text-center">
                    Cancel
                </a>

                <button
                    class="rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700 transition">
                    Save Changes
                </button>
            </div>
        </form>

    </div>
@endsection