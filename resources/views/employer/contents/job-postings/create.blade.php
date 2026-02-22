{{-- resources/views/employer/contents/job-postings/create.blade.php --}}
@extends('employer.layout')

@section('content')
    <div class="space-y-6">
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
            {{-- Top header --}}
            <div class="px-6 sm:px-8 py-6 border-b border-slate-200 bg-gradient-to-r from-emerald-50 to-white">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-semibold text-slate-900">Post New Job</h1>
                        <p class="text-sm text-slate-600 mt-1">Fill in the details below. Fields with <span
                                class="text-red-500">*</span> are required.</p>
                    </div>
                    <div class="hidden sm:flex items-center gap-2">
                        <span
                            class="inline-flex items-center gap-2 rounded-full bg-emerald-600 px-3 py-1 text-xs font-semibold text-white">
                            <span class="h-2 w-2 rounded-full bg-white/90"></span>
                            Employer Portal
                        </span>
                    </div>
                </div>
            </div>

            <form action="{{ route('employer.job-postings.store') }}" method="POST" class="px-6 sm:px-8 py-8 space-y-8">
                @csrf

                {{-- Section: Job basics --}}
                <div class="space-y-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-base font-semibold text-slate-900">Job Details</h2>
                        <span class="text-xs text-slate-500">Step 1 of 3</span>
                    </div>

                    {{-- Job Title --}}
                    <div>
                        <label for="title" class="block text-sm font-medium text-slate-700">Job Title <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}"
                            placeholder="e.g., Welder / Caregiver / Factory Worker"
                            class="mt-2 block w-full rounded-2xl border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                        @error('title') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                    </div>

                    {{-- Industry --}}
                    <div>
                        <label for="industry" class="block text-sm font-medium text-slate-700">Industry <span
                                class="text-red-500">*</span></label>
                        <div class="mt-2 relative">
                            <select name="industry" id="industry"
                                class="block w-full appearance-none rounded-2xl border-slate-300 bg-white px-4 py-3 pr-10 text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                <option value="">Select Industry</option>
                                @foreach(($industries ?? []) as $industry)
                                    <option value="{{ $industry }}" {{ old('industry') == $industry ? 'selected' : '' }}>
                                        {{ $industry }}
                                    </option>
                                @endforeach
                            </select>
                            <svg class="pointer-events-none absolute right-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                        @error('industry') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                    </div>

                    {{-- Skills (Checkboxes) --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Skills <span
                                class="text-red-500">*</span></label>
                        <p class="text-xs text-slate-500 mt-1">Select all that apply.</p>

                        @php
                            $oldSkills = old('skills', []);
                            if (!is_array($oldSkills))
                                $oldSkills = [];
                        @endphp

                        <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach(($skills ?? []) as $skill)
                                <label
                                    class="group flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-sm hover:border-emerald-200 hover:bg-emerald-50/30 transition cursor-pointer">
                                    <input type="checkbox" name="skills[]" value="{{ $skill }}"
                                        class="h-5 w-5 rounded-md border-slate-300 text-emerald-600 focus:ring-4 focus:ring-emerald-100"
                                        {{ in_array($skill, $oldSkills, true) ? 'checked' : '' }}>
                                    <span
                                        class="text-sm font-medium text-slate-700 group-hover:text-slate-900">{{ $skill }}</span>
                                </label>
                            @endforeach
                        </div>

                        @error('skills') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                        @error('skills.*') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Divider --}}
                <div class="h-px bg-slate-200"></div>

                {{-- Section: Location + Salary --}}
                <div class="space-y-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-base font-semibold text-slate-900">Location & Compensation</h2>
                        <span class="text-xs text-slate-500">Step 2 of 3</span>
                    </div>

                    {{-- Location --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label for="country" class="block text-sm font-medium text-slate-700">Country <span
                                    class="text-red-500">*</span></label>
                            <div class="mt-2 relative">
                                <select name="country" id="country"
                                    class="block w-full appearance-none rounded-2xl border-slate-300 bg-white px-4 py-3 pr-10 text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                    <option value="">Select Country</option>
                                    @foreach(($countries ?? []) as $country)
                                        <option value="{{ $country }}" {{ old('country') == $country ? 'selected' : '' }}>
                                            {{ $country }}
                                        </option>
                                    @endforeach
                                </select>
                                <svg class="pointer-events-none absolute right-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                            @error('country') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="city" class="block text-sm font-medium text-slate-700">City</label>
                            <div class="mt-2 relative">
                                <select name="city" id="city"
                                    class="block w-full appearance-none rounded-2xl border-slate-300 bg-white px-4 py-3 pr-10 text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                    <option value="">Select City</option>
                                    @foreach(($cities ?? []) as $city)
                                        <option value="{{ $city }}" {{ old('city') == $city ? 'selected' : '' }}>
                                            {{ $city }}
                                        </option>
                                    @endforeach
                                </select>
                                <svg class="pointer-events-none absolute right-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                            @error('city') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="area" class="block text-sm font-medium text-slate-700">Area</label>
                            <div class="mt-2 relative">
                                <select name="area" id="area"
                                    class="block w-full appearance-none rounded-2xl border-slate-300 bg-white px-4 py-3 pr-10 text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                    <option value="">Select Area</option>
                                    @foreach(($areas ?? []) as $area)
                                        <option value="{{ $area }}" {{ old('area') == $area ? 'selected' : '' }}>
                                            {{ $area }}
                                        </option>
                                    @endforeach
                                </select>
                                <svg class="pointer-events-none absolute right-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                            @error('area') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Experience + Salary Range + Currency --}}
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                        <div>
                            <label for="min_experience_years" class="block text-sm font-medium text-slate-700">Min.
                                Experience (Years)</label>
                            <input type="number" min="0" name="min_experience_years" id="min_experience_years"
                                value="{{ old('min_experience_years') }}"
                                class="mt-2 block w-full rounded-2xl border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                            @error('min_experience_years') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700">
                                Salary Range (optional)
                            </label>

                            <div class="mt-2 grid grid-cols-2 gap-3">

                                {{-- Salary Min --}}
                                <div class="relative">
                                    <span
                                        class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 text-sm font-semibold"
                                        id="salarySymbol">₱</span>

                                    <input type="number" step="0.01" min="0" name="salary_min" id="salary_min"
                                        value="{{ old('salary_min') }}" placeholder="Minimum"
                                        class="pl-10 block w-full rounded-2xl border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                </div>

                                {{-- Salary Max --}}
                                <div class="relative">
                                    <span
                                        class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 text-sm font-semibold">
                                        —
                                    </span>

                                    <input type="number" step="0.01" min="0" name="salary_max" id="salary_max"
                                        value="{{ old('salary_max') }}" placeholder="Maximum"
                                        class="pl-10 block w-full rounded-2xl border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                </div>
                            </div>

                            <p class="text-xs text-slate-500 mt-1">
                                Enter numeric values only. Example: 20000 and 40000
                            </p>

                            @error('salary_min')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror

                            @error('salary_max')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>


                        <div>
                            <label for="salary_currency" class="block text-sm font-medium text-slate-700">Currency</label>
                            <div class="mt-2 relative">
                                {{-- Modern icon (money) --}}
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500" aria-hidden="true">
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2M19 9h2m-2 0a2 2 0 00-2 2v2a2 2 0 002 2h2m-4-6h2m0 0V7m0 10v-2" />
                                    </svg>
                                </span>
                                <select name="salary_currency" id="salary_currency"
                                    class="pl-10 block w-full appearance-none rounded-2xl border-slate-300 bg-white px-4 py-3 pr-10 text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                    @foreach(($currencies ?? []) as $code => $name)
                                        <option value="{{ $code }}" {{ old('salary_currency', 'PHP') == $code ? 'selected' : '' }}>
                                            {{ $code }} — {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                                <svg class="pointer-events-none absolute right-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                            @error('salary_currency') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Divider --}}
                <div class="h-px bg-slate-200"></div>

                {{-- Section: Requirements + DMW --}}
                <div class="space-y-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-base font-semibold text-slate-900">Requirements & DMW Info</h2>
                        <span class="text-xs text-slate-500">Step 3 of 3</span>
                    </div>

                    {{-- Gender + Age --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label for="gender" class="block text-sm font-medium text-slate-700">Gender <span
                                    class="text-red-500">*</span></label>
                            <div class="mt-2 relative">
                                <select name="gender" id="gender"
                                    class="block w-full appearance-none rounded-2xl border-slate-300 bg-white px-4 py-3 pr-10 text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                    <option value="both" {{ old('gender', 'both') == 'both' ? 'selected' : '' }}>Both</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                </select>
                                <svg class="pointer-events-none absolute right-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                            @error('gender') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="age_min" class="block text-sm font-medium text-slate-700">Age Min</label>
                            <input type="number" min="0" name="age_min" id="age_min" value="{{ old('age_min') }}"
                                class="mt-2 block w-full rounded-2xl border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                            @error('age_min') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="age_max" class="block text-sm font-medium text-slate-700">Age Max</label>
                            <input type="number" min="0" name="age_max" id="age_max" value="{{ old('age_max') }}"
                                class="mt-2 block w-full rounded-2xl border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                            @error('age_max') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Dates --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="apply_until" class="block text-sm font-medium text-slate-700">Apply Until</label>
                            <input type="date" name="apply_until" id="apply_until" value="{{ old('apply_until') }}"
                                class="mt-2 block w-full rounded-2xl border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                            @error('apply_until') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700">Date Posted</label>
                            <input type="text" value="{{ now()->format('Y-m-d H:i') }}" disabled
                                class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-slate-600 shadow-sm">
                            <p class="text-xs text-slate-500 mt-1">Automatically set when you post.</p>
                        </div>
                    </div>

                    {{-- Job Description --}}
                    <div>
                        <label for="job_description" class="block text-sm font-medium text-slate-700">Job Description <span
                                class="text-red-500">*</span></label>
                        <textarea name="job_description" id="job_description" rows="6"
                            class="mt-2 block w-full rounded-2xl border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">{{ old('job_description') }}</textarea>
                        @error('job_description') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                    </div>

                    {{-- Job Qualifications --}}
                    <div>
                        <label for="job_qualifications" class="block text-sm font-medium text-slate-700">Job
                            Qualifications</label>
                        <textarea name="job_qualifications" id="job_qualifications" rows="4"
                            class="mt-2 block w-full rounded-2xl border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">{{ old('job_qualifications') }}</textarea>
                        @error('job_qualifications') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                    </div>

                    {{-- Additional Information --}}
                    <div>
                        <label for="additional_information" class="block text-sm font-medium text-slate-700">Additional
                            Information</label>
                        <textarea name="additional_information" id="additional_information" rows="4"
                            class="mt-2 block w-full rounded-2xl border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">{{ old('additional_information') }}</textarea>
                        @error('additional_information') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                    </div>

                    {{-- Principal / Employer --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="principal_employer" class="block text-sm font-medium text-slate-700">Principal /
                                Employer</label>
                            <input type="text" name="principal_employer" id="principal_employer"
                                value="{{ old('principal_employer') }}"
                                class="mt-2 block w-full rounded-2xl border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                            @error('principal_employer') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="dmw_registration_no" class="block text-sm font-medium text-slate-700">DMW (formerly
                                POEA) Reg/Accreditation No.</label>
                            <input type="text" name="dmw_registration_no" id="dmw_registration_no"
                                value="{{ old('dmw_registration_no') }}"
                                class="mt-2 block w-full rounded-2xl border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                            @error('dmw_registration_no') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="principal_employer_address" class="block text-sm font-medium text-slate-700">Principal /
                            Employer Address</label>
                        <input type="text" name="principal_employer_address" id="principal_employer_address"
                            value="{{ old('principal_employer_address') }}"
                            class="mt-2 block w-full rounded-2xl border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                        @error('principal_employer_address') <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Placement Fee + Currency --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <div>
                            <label for="placement_fee" class="block text-sm font-medium text-slate-700">Placement Fee
                                (optional)</label>
                            <div class="mt-2 relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 text-sm font-semibold"
                                    id="feeSymbol">₱</span>
                                <input type="number" step="0.01" name="placement_fee" id="placement_fee"
                                    value="{{ old('placement_fee') }}"
                                    class="pl-10 block w-full rounded-2xl border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                            </div>
                            @error('placement_fee') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="placement_fee_currency" class="block text-sm font-medium text-slate-700">Placement
                                Fee Currency</label>
                            <div class="mt-2 relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500" aria-hidden="true">
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2M19 9h2m-2 0a2 2 0 00-2 2v2a2 2 0 002 2h2m-4-6h2m0 0V7m0 10v-2" />
                                    </svg>
                                </span>
                                <select name="placement_fee_currency" id="placement_fee_currency"
                                    class="pl-10 block w-full appearance-none rounded-2xl border-slate-300 bg-white px-4 py-3 pr-10 text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                    @foreach(($currencies ?? []) as $code => $name)
                                        <option value="{{ $code }}" {{ old('placement_fee_currency', 'PHP') == $code ? 'selected' : '' }}>
                                            {{ $code }} — {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                                <svg class="pointer-events-none absolute right-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                            @error('placement_fee_currency') <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Footer actions --}}
                @php
                    $cancelUrl = request('from') === 'navbar'
                        ? route('employer.dashboard')
                        : route('employer.job-postings.index');
                @endphp

                <div class="pt-4 flex flex-col sm:flex-row sm:items-center sm:justify-end gap-3">
                    <a href="{{ $cancelUrl }}"
                        class="inline-flex justify-center rounded-2xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        Cancel
                    </a>
                    <button type="submit"
                        class="inline-flex justify-center rounded-2xl bg-emerald-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-4 focus:ring-emerald-200">
                        Post Job
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Currency symbol switcher (fallback to code if unknown) --}}
    <script>
        (function () {
            const symbolMap = {
                PHP: "₱", USD: "$", EUR: "€", GBP: "£", JPY: "¥",
                AUD: "$", CAD: "$", NZD: "$", SGD: "$", HKD: "$",
                SAR: "﷼", AED: "د.إ", QAR: "﷼", KWD: "د.ك",
                INR: "₹", KRW: "₩", CNY: "¥", THB: "฿",
                IDR: "Rp", MYR: "RM", VND: "₫", RUB: "₽",
                ZAR: "R", TRY: "₺"
            };

            const salaryCurrency = document.getElementById("salary_currency");
            const salarySymbol = document.getElementById("salarySymbol");

            const feeCurrency = document.getElementById("placement_fee_currency");
            const feeSymbol = document.getElementById("feeSymbol");

            function setSymbol(selectEl, symbolEl) {
                if (!selectEl || !symbolEl) return;
                const code = selectEl.value || "PHP";
                symbolEl.textContent = symbolMap[code] ?? code;
            }

            if (salaryCurrency) {
                setSymbol(salaryCurrency, salarySymbol);
                salaryCurrency.addEventListener("change", () => setSymbol(salaryCurrency, salarySymbol));
            }

            if (feeCurrency) {
                setSymbol(feeCurrency, feeSymbol);
                feeCurrency.addEventListener("change", () => setSymbol(feeCurrency, feeSymbol));
            }
        })();
    </script>
@endsection