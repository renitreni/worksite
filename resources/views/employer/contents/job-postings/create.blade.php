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
                        @error('title')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Industry --}}
                    <div>
                        <label for="industry" class="block text-sm font-medium text-slate-700">Industry <span
                                class="text-red-500">*</span></label>
                        <div class="mt-2 relative">

                            <select name="industry_id" id="industry_id"
                                class="block w-full appearance-none rounded-2xl border-slate-300 bg-white px-4 py-3 pr-10 text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                <option value="">Select Industry</option>
                                @foreach ($industries ?? [] as $industry)
                                    <option value="{{ $industry->id }}"
                                        {{ (string) old('industry_id') === (string) $industry->id ? 'selected' : '' }}>
                                        {{ $industry->name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('industry_id')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                            <svg class="pointer-events-none absolute right-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                        @error('industry')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Skills (Checkboxes) --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Skills <span
                                class="text-red-500">*</span></label>
                        <p class="text-xs text-slate-500 mt-1">Select all that apply.</p>

                        @php
                            $oldSkillIds = old('skills', []);
                            if (!is_array($oldSkillIds)) {
                                $oldSkillIds = [];
                            }
                        @endphp

                        <div id="skillsLoading" class="mt-3 hidden text-sm text-slate-500">Loading skills...</div>

                        <div id="skillsEmpty"
                            class="mt-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600">
                            Please select an industry to load skills.
                        </div>

                        <div id="skillsContainer" class="mt-3 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3"></div>

                        <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach ($skills ?? [] as $skill)
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

                        @error('skills')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                        @error('skills.*')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
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
                                    @foreach ($countries ?? [] as $country)
                                        <option value="{{ $country }}"
                                            {{ old('country') == $country ? 'selected' : '' }}>
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
                            @error('country')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- City --}}
                        <div x-data="{ useCustomCity: {{ old('city_custom') ? 'true' : 'false' }} }">
                            <label for="city" class="block text-sm font-medium text-slate-700">City</label>

                            <div class="mt-2 relative">
                                <select name="city" id="city"
                                    @change="useCustomCity = ($event.target.value === '__custom__')"
                                    class="block w-full appearance-none rounded-2xl border-slate-300 bg-white px-4 py-3 pr-10 text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                    <option value="">Select City</option>

                                    @foreach ($cities ?? [] as $city)
                                        <option value="{{ $city }}" {{ old('city') == $city ? 'selected' : '' }}>
                                            {{ $city }}
                                        </option>
                                    @endforeach

                                    <option value="__custom__" :selected="useCustomCity">Other (type manually)</option>
                                </select>

                                <svg class="pointer-events-none absolute right-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>

                            <div x-show="useCustomCity" x-cloak class="mt-3">
                                <label for="city_custom" class="block text-xs font-semibold text-slate-600">
                                    Enter City (will be sent for admin approval)
                                </label>
                                <input type="text" name="city_custom" id="city_custom"
                                    value="{{ old('city_custom') }}" placeholder="Type city..."
                                    class="mt-2 block w-full rounded-2xl border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                @error('city_custom')
                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            @error('city')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Area --}}
                        <div x-data="{ useCustomArea: {{ old('area_custom') ? 'true' : 'false' }} }">
                            <label for="area" class="block text-sm font-medium text-slate-700">Area</label>

                            <div class="mt-2 relative">
                                <select name="area" id="area"
                                    @change="useCustomArea = ($event.target.value === '__custom__')"
                                    class="block w-full appearance-none rounded-2xl border-slate-300 bg-white px-4 py-3 pr-10 text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                    <option value="">Select Area</option>

                                    @foreach ($areas ?? [] as $area)
                                        <option value="{{ $area }}" {{ old('area') == $area ? 'selected' : '' }}>
                                            {{ $area }}
                                        </option>
                                    @endforeach

                                    <option value="__custom__" :selected="useCustomArea">Other (type manually)</option>
                                </select>

                                <svg class="pointer-events-none absolute right-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>

                            <div x-show="useCustomArea" x-cloak class="mt-3">
                                <label for="area_custom" class="block text-xs font-semibold text-slate-600">
                                    Enter Area (will be sent for admin approval)
                                </label>
                                <input type="text" name="area_custom" id="area_custom"
                                    value="{{ old('area_custom') }}" placeholder="Type area..."
                                    class="mt-2 block w-full rounded-2xl border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                @error('area_custom')
                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            @error('area')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
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
                            @error('min_experience_years')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
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

                                    <input type="number" step="0.01" min="0" name="salary_min"
                                        id="salary_min" value="{{ old('salary_min') }}" placeholder="Minimum"
                                        class="pl-10 block w-full rounded-2xl border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                </div>

                                {{-- Salary Max --}}
                                <div class="relative">
                                    <span
                                        class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 text-sm font-semibold">
                                        —
                                    </span>

                                    <input type="number" step="0.01" min="0" name="salary_max"
                                        id="salary_max" value="{{ old('salary_max') }}" placeholder="Maximum"
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
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2M19 9h2m-2 0a2 2 0 00-2 2v2a2 2 0 002 2h2m-4-6h2m0 0V7m0 10v-2" />
                                    </svg>
                                </span>
                                <select name="salary_currency" id="salary_currency"
                                    class="pl-10 block w-full appearance-none rounded-2xl border-slate-300 bg-white px-4 py-3 pr-10 text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                    @foreach ($currencies ?? [] as $code => $name)
                                        <option value="{{ $code }}"
                                            {{ old('salary_currency', 'PHP') == $code ? 'selected' : '' }}>
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
                            @error('salary_currency')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
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

                    {{-- Education Level --}}
                    <div>
                        <label for="education_level" class="block text-sm font-medium text-slate-700">
                            Required Education
                        </label>

                        <div class="mt-2 relative">
                            <select name="education_level" id="education_level"
                                class="block w-full appearance-none rounded-2xl border-slate-300 bg-white px-4 py-3 pr-10 text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                <option value="">Any / Not required</option>
                                <option value="high_school"
                                    {{ old('education_level') === 'high_school' ? 'selected' : '' }}>
                                    High school diploma
                                </option>
                                <option value="college" {{ old('education_level') === 'college' ? 'selected' : '' }}>
                                    College graduate
                                </option>
                                <option value="masteral" {{ old('education_level') === 'masteral' ? 'selected' : '' }}>
                                    Masteral degree
                                </option>
                                <option value="phd" {{ old('education_level') === 'phd' ? 'selected' : '' }}>
                                    PhD / Doctorate
                                </option>
                            </select>

                            <svg class="pointer-events-none absolute right-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>

                        @error('education_level')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Gender + Age --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label for="gender" class="block text-sm font-medium text-slate-700">Gender <span
                                    class="text-red-500">*</span></label>
                            <div class="mt-2 relative">
                                <select name="gender" id="gender"
                                    class="block w-full appearance-none rounded-2xl border-slate-300 bg-white px-4 py-3 pr-10 text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                    <option value="both" {{ old('gender', 'both') == 'both' ? 'selected' : '' }}>Both
                                    </option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female
                                    </option>
                                </select>
                                <svg class="pointer-events-none absolute right-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                            @error('gender')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="age_min" class="block text-sm font-medium text-slate-700">Age Min</label>
                            <input type="number" min="0" name="age_min" id="age_min"
                                value="{{ old('age_min') }}"
                                class="mt-2 block w-full rounded-2xl border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                            @error('age_min')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="age_max" class="block text-sm font-medium text-slate-700">Age Max</label>
                            <input type="number" min="0" name="age_max" id="age_max"
                                value="{{ old('age_max') }}"
                                class="mt-2 block w-full rounded-2xl border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                            @error('age_max')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Dates --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="apply_until" class="block text-sm font-medium text-slate-700">Apply
                                Until</label>
                            <input type="date" name="apply_until" id="apply_until" value="{{ old('apply_until') }}"
                                class="mt-2 block w-full rounded-2xl border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                            @error('apply_until')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
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
                        <label for="job_description" class="block text-sm font-medium text-slate-700">Job Description
                            <span class="text-red-500">*</span></label>
                        <textarea name="job_description" id="job_description" rows="6"
                            class="mt-2 block w-full rounded-2xl border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">{{ old('job_description') }}</textarea>
                        @error('job_description')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Job Qualifications --}}
                    <div>
                        <label for="job_qualifications" class="block text-sm font-medium text-slate-700">Job
                            Qualifications</label>
                        <textarea name="job_qualifications" id="job_qualifications" rows="4"
                            class="mt-2 block w-full rounded-2xl border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">{{ old('job_qualifications') }}</textarea>
                        @error('job_qualifications')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Additional Information --}}
                    <div>
                        <label for="additional_information" class="block text-sm font-medium text-slate-700">Additional
                            Information</label>
                        <textarea name="additional_information" id="additional_information" rows="4"
                            class="mt-2 block w-full rounded-2xl border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">{{ old('additional_information') }}</textarea>
                        @error('additional_information')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Principal / Employer --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="principal_employer" class="block text-sm font-medium text-slate-700">Principal /
                                Employer</label>
                            <input type="text" name="principal_employer" id="principal_employer"
                                value="{{ old('principal_employer') }}"
                                class="mt-2 block w-full rounded-2xl border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                            @error('principal_employer')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="dmw_registration_no" class="block text-sm font-medium text-slate-700">DMW
                                (formerly
                                POEA) Reg/Accreditation No.</label>
                            <input type="text" name="dmw_registration_no" id="dmw_registration_no"
                                value="{{ old('dmw_registration_no') }}"
                                class="mt-2 block w-full rounded-2xl border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                            @error('dmw_registration_no')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="principal_employer_address" class="block text-sm font-medium text-slate-700">Principal
                            /
                            Employer Address</label>
                        <input type="text" name="principal_employer_address" id="principal_employer_address"
                            value="{{ old('principal_employer_address') }}"
                            class="mt-2 block w-full rounded-2xl border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                        @error('principal_employer_address')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
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
                            @error('placement_fee')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="placement_fee_currency" class="block text-sm font-medium text-slate-700">Placement
                                Fee Currency</label>
                            <div class="mt-2 relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500" aria-hidden="true">
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2M19 9h2m-2 0a2 2 0 00-2 2v2a2 2 0 002 2h2m-4-6h2m0 0V7m0 10v-2" />
                                    </svg>
                                </span>
                                <select name="placement_fee_currency" id="placement_fee_currency"
                                    class="pl-10 block w-full appearance-none rounded-2xl border-slate-300 bg-white px-4 py-3 pr-10 text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                    @foreach ($currencies ?? [] as $code => $name)
                                        <option value="{{ $code }}"
                                            {{ old('placement_fee_currency', 'PHP') == $code ? 'selected' : '' }}>
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
                            @error('placement_fee_currency')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Footer actions --}}
                @php
                    $cancelUrl =
                        request('from') === 'navbar'
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
        (function() {
            const symbolMap = {
                PHP: "₱",
                USD: "$",
                EUR: "€",
                GBP: "£",
                JPY: "¥",
                AUD: "$",
                CAD: "$",
                NZD: "$",
                SGD: "$",
                HKD: "$",
                SAR: "﷼",
                AED: "د.إ",
                QAR: "﷼",
                KWD: "د.ك",
                INR: "₹",
                KRW: "₩",
                CNY: "¥",
                THB: "฿",
                IDR: "Rp",
                MYR: "RM",
                VND: "₫",
                RUB: "₽",
                ZAR: "R",
                TRY: "₺"
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
    <script>
        (function() {
            const countryEl = document.getElementById('country');
            const cityEl = document.getElementById('city');
            const areaEl = document.getElementById('area');

            if (!countryEl || !cityEl || !areaEl) return;

            const oldCity = @json(old('city'));
            const oldArea = @json(old('area'));
            const oldCountry = @json(old('country'));

            function resetSelect(selectEl, placeholder) {
                const customOpt = Array.from(selectEl.options).find(o => o.value === '__custom__');
                selectEl.innerHTML = '';
                selectEl.appendChild(new Option(placeholder, ''));
                if (customOpt) selectEl.appendChild(new Option(customOpt.text, '__custom__'));
            }

            function fillSelect(selectEl, items, selectedValue) {
                const customOpt = Array.from(selectEl.options).find(o => o.value === '__custom__');
                if (customOpt) customOpt.remove();

                (items || []).forEach(name => {
                    const opt = new Option(name, name);
                    if (selectedValue && selectedValue === name) opt.selected = true;
                    selectEl.appendChild(opt);
                });

                if (customOpt) selectEl.appendChild(customOpt);
            }

            async function fetchJson(url) {
                const res = await fetch(url, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                if (!res.ok) {
                    const text = await res.text();
                    console.error('Geo API failed:', res.status, url, text);
                    return [];
                }

                const ct = res.headers.get('content-type') || '';
                if (!ct.includes('application/json')) {
                    const text = await res.text();
                    console.error('Geo API not JSON:', ct, url, text);
                    return [];
                }

                return await res.json();
            }

            async function loadCities(country, selectedCity = null) {
                resetSelect(cityEl, 'Select City');
                resetSelect(areaEl, 'Select Area');
                if (!country) return;

                const url = `{{ route('employer.geo.cities') }}?country=${encodeURIComponent(country)}`;
                const cities = await fetchJson(url);
                fillSelect(cityEl, cities, selectedCity);
            }

            async function loadAreas(country, city, selectedArea = null) {
                resetSelect(areaEl, 'Select Area');
                if (!country || !city) return;

                const url =
                    `{{ route('employer.geo.areas') }}?country=${encodeURIComponent(country)}&city=${encodeURIComponent(city)}`;
                const areas = await fetchJson(url);
                fillSelect(areaEl, areas, selectedArea);
            }

            countryEl.addEventListener('change', async () => {
                await loadCities(countryEl.value);
            });

            cityEl.addEventListener('change', async () => {
                const country = countryEl.value;
                const city = cityEl.value;

                if (city === '__custom__' || !city) {
                    resetSelect(areaEl, 'Select Area');
                    return;
                }

                await loadAreas(country, city);
            });

            (async function init() {
                if (oldCountry) {
                    await loadCities(oldCountry, oldCity);
                    if (oldCity && oldCity !== '__custom__') {
                        await loadAreas(oldCountry, oldCity, oldArea);
                    }
                }
            })();
        })();
    </script>
    <script>
        (function() {
            const industryEl = document.getElementById('industry_id');
            const skillsContainer = document.getElementById('skillsContainer');
            const skillsEmpty = document.getElementById('skillsEmpty');
            const skillsLoading = document.getElementById('skillsLoading');

            const oldIndustryId = @json(old('industry_id'));
            const oldSkillIds = @json(old('skills', []));

            function setLoading(v) {
                skillsLoading?.classList.toggle('hidden', !v);
            }

            function showEmpty(msg) {
                skillsEmpty.textContent = msg;
                skillsEmpty.classList.remove('hidden');
            }

            function hideEmpty() {
                skillsEmpty.classList.add('hidden');
            }

            function clear() {
                skillsContainer.innerHTML = '';
            }

            function render(skills) {
                clear();
                if (!skills || skills.length === 0) {
                    showEmpty('No skills available for this industry.');
                    return;
                }
                hideEmpty();

                const oldSet = new Set((Array.isArray(oldSkillIds) ? oldSkillIds : []).map(String));

                skills.forEach(skill => {
                    const checked = oldSet.has(String(skill.id));
                    const label = document.createElement('label');
                    label.className =
                        "group flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-sm " +
                        "hover:border-emerald-200 hover:bg-emerald-50/30 transition cursor-pointer";

                    label.innerHTML = `
        <input type="checkbox" name="skills[]" value="${skill.id}"
          class="h-5 w-5 rounded-md border-slate-300 text-emerald-600 focus:ring-4 focus:ring-emerald-100"
          ${checked ? 'checked' : ''}>
        <span class="text-sm font-medium text-slate-700 group-hover:text-slate-900">${skill.name}</span>
      `;
                    skillsContainer.appendChild(label);
                });
            }

            async function load(industryId) {
                clear();

                if (!industryId) {
                    showEmpty('Please select an industry to load skills.');
                    return;
                }

                setLoading(true);
                hideEmpty();

                try {
                    const url = `{{ route('employer.industries.skills', ['industry' => '__ID__']) }}`.replace(
                        '__ID__', industryId);
                    const res = await fetch(url, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    if (!res.ok) {
                        showEmpty('Failed to load skills.');
                        return;
                    }
                    const data = await res.json();
                    render(data);
                } catch (e) {
                    console.error(e);
                    showEmpty('Failed to load skills.');
                } finally {
                    setLoading(false);
                }
            }

            if (!industryEl) return;

            industryEl.addEventListener('change', () => load(industryEl.value));

            if (oldIndustryId) {
                load(oldIndustryId);
            } else {
                showEmpty('Please select an industry to load skills.');
            }
        })();
    </script>
@endsection
