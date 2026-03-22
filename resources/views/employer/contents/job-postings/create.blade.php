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
                <div class="space-y-8">

                    {{-- Header --}}
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-slate-900">Job Details</h2>
                        <span class="text-xs text-slate-500">Step 1 of 3</span>
                    </div>

                    {{-- Job Title --}}
                    <div class="space-y-1">
                        <label for="title" class="block text-sm font-medium text-slate-800">
                            Job Title <span class="text-red-500">(Required)</span>
                        </label>

                        <input type="text" name="title" id="title" value="{{ old('title') }}"
                            placeholder="Enter job title (e.g., Welder, Caregiver)"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-emerald-500 focus:outline-none">

                        <p class="text-xs text-slate-500">
                            This is the main role you are hiring for.
                        </p>

                        @error('title')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Industry --}}
                    <div class="space-y-1">
                        <label for="industry_id" class="block text-sm font-medium text-slate-800">
                            Industry <span class="text-red-500">(Required)</span>
                        </label>

                        <div class="relative">
                            <select name="industry_id" id="industry_id"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm bg-white focus:border-emerald-500 focus:outline-none">

                                <option value="">Select an industry</option>

                                @foreach ($industries ?? [] as $industry)
                                    <option value="{{ $industry->id }}"
                                        {{ (string) old('industry_id') === (string) $industry->id ? 'selected' : '' }}>
                                        {{ $industry->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <p class="text-xs text-slate-500">
                            Choose the industry to load relevant skills.
                        </p>

                        @error('industry_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Skills --}}
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-slate-800">
                            Skills <span class="text-red-500">(Required)</span>
                        </label>

                        <p class="text-xs text-slate-500">
                            Select at least one skill related to the job.
                        </p>

                        @php
                            $oldSkillIds = old('skills', []);
                            if (!is_array($oldSkillIds)) {
                                $oldSkillIds = [];
                            }
                        @endphp

                        <div id="skillsLoading" class="hidden text-xs text-slate-500">
                            Loading skills...
                        </div>

                        <div id="skillsEmpty"
                            class="rounded-lg border border-gray-300 bg-gray-50 px-4 py-2 text-sm text-slate-600">
                            Please select an industry first.
                        </div>

                        <div id="skillsContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                        </div>

                        {{-- Static fallback --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                            @foreach ($skills ?? [] as $skill)
                                <label
                                    class="flex items-center gap-2 border border-gray-300 rounded-lg px-3 py-2 cursor-pointer">

                                    <input type="checkbox" name="skills[]" value="{{ $skill }}"
                                        class="h-4 w-4 border-gray-300 text-emerald-600 focus:ring-0"
                                        {{ in_array($skill, $oldSkills ?? [], true) ? 'checked' : '' }}>

                                    <span class="text-sm text-slate-700">
                                        {{ $skill }}
                                    </span>
                                </label>
                            @endforeach
                        </div>

                        @error('skills')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        @error('skills.*')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Custom Skills --}}
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-slate-800">
                            Add Custom Skills <span class="text-slate-400">(Optional)</span>
                        </label>

                        <input type="text" name="custom_skills" value="{{ old('custom_skills') }}"
                            placeholder="e.g. Welding, Machine Operation"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-emerald-500 focus:outline-none">

                        <p class="text-xs text-slate-500">
                            Separate multiple skills using commas.
                        </p>
                    </div>

                </div>
                {{-- Divider --}}
                <div class="h-px bg-slate-700"></div>

                {{-- Section: Location + Salary --}}
                <div class="space-y-8">

                    {{-- Header --}}
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-slate-900">Location & Compensation</h2>
                        <span class="text-xs text-slate-500">Step 2 of 3</span>
                    </div>

                    {{-- Location --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">

                        {{-- Country --}}
                        <div class="space-y-1">
                            <label for="country" class="block text-sm font-medium text-slate-800">
                                Country <span class="text-red-500">(Required)</span>
                            </label>

                            <div class="relative">
                                <select name="country" id="country"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm bg-white focus:border-emerald-500 focus:outline-none">

                                    <option value="">Select country</option>

                                    @foreach ($countries ?? [] as $country)
                                        <option value="{{ $country }}"
                                            {{ old('country') == $country ? 'selected' : '' }}>
                                            {{ $country }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <p class="text-xs text-slate-500">
                                Select the country where the job is located.
                            </p>

                            @error('country')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- City --}}
                        <div x-data="{ useCustomCity: {{ old('city_custom') ? 'true' : 'false' }} }" class="space-y-1">
                            <label for="city" class="block text-sm font-medium text-slate-800">
                                City <span class="text-slate-400">(Optional)</span>
                            </label>

                            <div class="relative">
                                <select name="city" id="city"
                                    @change="useCustomCity = ($event.target.value === '__custom__')"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm bg-white focus:border-emerald-500 focus:outline-none">

                                    <option value="">Select city</option>

                                    @foreach ($cities ?? [] as $city)
                                        <option value="{{ $city }}" {{ old('city') == $city ? 'selected' : '' }}>
                                            {{ $city }}
                                        </option>
                                    @endforeach

                                    <option value="__custom__" :selected="useCustomCity">
                                        Other (type manually)
                                    </option>
                                </select>
                            </div>

                            <p class="text-xs text-slate-500">
                                Choose a city or enter your own.
                            </p>

                            {{-- Custom City --}}
                            <div x-show="useCustomCity" x-cloak class="space-y-1">
                                <input type="text" name="city_custom" id="city_custom"
                                    value="{{ old('city_custom') }}" placeholder="Enter city name"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-emerald-500 focus:outline-none">

                                <p class="text-xs text-slate-500">
                                    Custom entries will be reviewed by admin.
                                </p>

                                @error('city_custom')
                                    <p class="text-red-500 text-xs">{{ $message }}</p>
                                @enderror
                            </div>

                            @error('city')
                                <p class="text-red-500 text-xs">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Area --}}
                        <div x-data="{ useCustomArea: {{ old('area_custom') ? 'true' : 'false' }} }" class="space-y-1">
                            <label for="area" class="block text-sm font-medium text-slate-800">
                                Area <span class="text-slate-400">(Optional)</span>
                            </label>

                            <div class="relative">
                                <select name="area" id="area"
                                    @change="useCustomArea = ($event.target.value === '__custom__')"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm bg-white focus:border-emerald-500 focus:outline-none">

                                    <option value="">Select area</option>

                                    @foreach ($areas ?? [] as $area)
                                        <option value="{{ $area }}" {{ old('area') == $area ? 'selected' : '' }}>
                                            {{ $area }}
                                        </option>
                                    @endforeach

                                    <option value="__custom__" :selected="useCustomArea">
                                        Other (type manually)
                                    </option>
                                </select>
                            </div>

                            <p class="text-xs text-slate-500">
                                Optional: specify a more exact location.
                            </p>

                            {{-- Custom Area --}}
                            <div x-show="useCustomArea" x-cloak class="space-y-1">
                                <input type="text" name="area_custom" id="area_custom"
                                    value="{{ old('area_custom') }}" placeholder="Enter area"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-emerald-500 focus:outline-none">

                                <p class="text-xs text-slate-500">
                                    Custom entries will be reviewed by admin.
                                </p>

                                @error('area_custom')
                                    <p class="text-red-500 text-xs">{{ $message }}</p>
                                @enderror
                            </div>

                            @error('area')
                                <p class="text-red-500 text-xs">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                </div>

                {{-- Experience + Salary Range + Currency --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    {{-- Experience --}}
                    <div class="space-y-1">
                        <label for="min_experience_years" class="block text-sm font-medium text-slate-800">
                            Minimum Experience <span class="text-slate-400">(Optional)</span>
                        </label>

                        <input type="number" min="0" name="min_experience_years" id="min_experience_years"
                            value="{{ old('min_experience_years') }}" placeholder="e.g. 2"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-emerald-500 focus:outline-none">

                        <p class="text-xs text-slate-500">
                            Enter required years of experience for this job.
                        </p>

                        @error('min_experience_years')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Salary Range --}}
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-slate-800">
                            Salary Range <span class="text-slate-400">(Optional)</span>
                        </label>

                        <div class="grid grid-cols-2 gap-3">

                            {{-- Min --}}
                            <input type="number" step="0.01" min="0" name="salary_min" id="salary_min"
                                value="{{ old('salary_min') }}" placeholder="Minimum"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-emerald-500 focus:outline-none">

                            {{-- Max --}}
                            <input type="number" step="0.01" min="0" name="salary_max" id="salary_max"
                                value="{{ old('salary_max') }}" placeholder="Maximum"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-emerald-500 focus:outline-none">
                        </div>

                        <p class="text-xs text-slate-500">
                            Example: 20000 (min) and 40000 (max)
                        </p>

                        @error('salary_min')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror

                        @error('salary_max')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Currency --}}
                    <div class="space-y-1">
                        <label for="salary_currency" class="block text-sm font-medium text-slate-800">
                            Currency <span class="text-slate-400">(Optional)</span>
                        </label>

                        <div class="relative">
                            <select name="salary_currency" id="salary_currency"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm bg-white focus:border-emerald-500 focus:outline-none">

                                @foreach ($currencies ?? [] as $code => $name)
                                    <option value="{{ $code }}"
                                        {{ old('salary_currency', 'PHP') == $code ? 'selected' : '' }}>
                                        {{ $code }} — {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <p class="text-xs text-slate-500">
                            Select the currency for the salary range.
                        </p>

                        @error('salary_currency')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>


                {{-- Divider --}}
                <div class="h-px bg-slate-700"></div>

                {{-- Section: Requirements + DMW --}}
                <div class="space-y-8">

                    {{-- Header --}}
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-slate-900">Requirements & DMW Info</h2>
                        <span class="text-xs text-slate-500">Step 3 of 3</span>
                    </div>

                    {{-- Education Level --}}
                    <div class="space-y-1">
                        <label for="education_level" class="block text-sm font-medium text-slate-800">
                            Required Education <span class="text-slate-400">(Optional)</span>
                        </label>

                        <div class="relative">
                            <select name="education_level" id="education_level"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm bg-white focus:border-emerald-500 focus:outline-none">

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
                        </div>

                        <p class="text-xs text-slate-500">
                            Leave as "Any" if no specific education is required.
                        </p>

                        @error('education_level')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Gender + Age --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">

                        {{-- Gender --}}
                        <div class="space-y-1">
                            <label for="gender" class="block text-sm font-medium text-slate-800">
                                Gender <span class="text-red-500">(Required)</span>
                            </label>

                            <div class="relative">
                                <select name="gender" id="gender"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm bg-white focus:border-emerald-500 focus:outline-none">

                                    <option value="both" {{ old('gender', 'both') == 'both' ? 'selected' : '' }}>Both
                                    </option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female
                                    </option>
                                </select>
                            </div>

                            <p class="text-xs text-slate-500">
                                Select preferred gender or choose "Both".
                            </p>

                            @error('gender')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Age Min --}}
                        <div class="space-y-1">
                            <label for="age_min" class="block text-sm font-medium text-slate-800">
                                Minimum Age <span class="text-slate-400">(Optional)</span>
                            </label>

                            <input type="number" min="0" name="age_min" id="age_min"
                                value="{{ old('age_min') }}" placeholder="e.g. 21"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-emerald-500 focus:outline-none">

                            @error('age_min')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Age Max --}}
                        <div class="space-y-1">
                            <label for="age_max" class="block text-sm font-medium text-slate-800">
                                Maximum Age <span class="text-slate-400">(Optional)</span>
                            </label>

                            <input type="number" min="0" name="age_max" id="age_max"
                                value="{{ old('age_max') }}" placeholder="e.g. 45"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-emerald-500 focus:outline-none">

                            @error('age_max')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    {{-- Dates --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                        {{-- Apply Until --}}
                        <div class="space-y-1">
                            <label for="apply_until" class="block text-sm font-medium text-slate-800">
                                Application Deadline <span class="text-slate-400">(Optional)</span>
                            </label>

                            <input type="date" name="apply_until" id="apply_until" value="{{ old('apply_until') }}"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-emerald-500 focus:outline-none">

                            <p class="text-xs text-slate-500">
                                Last date applicants can submit applications.
                            </p>

                            @error('apply_until')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Date Posted --}}
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-slate-800">
                                Date Posted
                            </label>

                            <input type="text" value="{{ now()->format('Y-m-d H:i') }}" disabled
                                class="w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-slate-600">

                            <p class="text-xs text-slate-500">
                                Automatically generated when job is posted.
                            </p>
                        </div>

                    </div>

                </div>
                {{-- Job Description --}}
                <div class="space-y-8">

                    {{-- Job Description --}}
                    <div class="space-y-1">
                        <label for="job_description" class="block text-sm font-medium text-slate-800">
                            Job Description <span class="text-red-500">(Required)</span>
                        </label>

                        <textarea name="job_description" id="job_description" rows="6"
                            placeholder="Describe the job responsibilities, tasks, and work details..."
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-emerald-500 focus:outline-none">{{ old('job_description') }}</textarea>

                        <p class="text-xs text-slate-500">
                            Include duties, daily tasks, and expectations for this role.
                        </p>

                        @error('job_description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Job Qualifications --}}
                    <div class="space-y-1">
                        <label for="job_qualifications" class="block text-sm font-medium text-slate-800">
                            Job Qualifications <span class="text-slate-400">(Optional)</span>
                        </label>

                        <textarea name="job_qualifications" id="job_qualifications" rows="4"
                            placeholder="List required skills, certifications, or experience..."
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-emerald-500 focus:outline-none">{{ old('job_qualifications') }}</textarea>

                        <p class="text-xs text-slate-500">
                            Example: Must have NCII, 2+ years experience, etc.
                        </p>

                        @error('job_qualifications')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Additional Info --}}
                    <div class="space-y-1">
                        <label for="additional_information" class="block text-sm font-medium text-slate-800">
                            Additional Information <span class="text-slate-400">(Optional)</span>
                        </label>

                        <textarea name="additional_information" id="additional_information" rows="4"
                            placeholder="Other details (benefits, schedule, notes, etc.)"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-emerald-500 focus:outline-none">{{ old('additional_information') }}</textarea>

                        <p class="text-xs text-slate-500">
                            Optional details such as benefits, working hours, or special notes.
                        </p>

                        @error('additional_information')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Employer Info --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                        {{-- Principal Employer --}}
                        <div class="space-y-1">
                            <label for="principal_employer" class="block text-sm font-medium text-slate-800">
                                Principal / Employer <span class="text-slate-400">(Optional)</span>
                            </label>

                            <input type="text" name="principal_employer" id="principal_employer"
                                value="{{ old('principal_employer') }}" placeholder="Enter employer name"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-emerald-500 focus:outline-none">

                            @error('principal_employer')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- DMW --}}
                        <div class="space-y-1">
                            <label for="dmw_registration_no" class="block text-sm font-medium text-slate-800">
                                DMW Accreditation No. <span class="text-slate-400">(Optional)</span>
                            </label>

                            <input type="text" name="dmw_registration_no" id="dmw_registration_no"
                                value="{{ old('dmw_registration_no') }}" placeholder="Enter registration number"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-emerald-500 focus:outline-none">

                            <p class="text-xs text-slate-500">
                                Formerly POEA accreditation number.
                            </p>

                            @error('dmw_registration_no')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    {{-- Employer Address --}}
                    <div class="space-y-1">
                        <label for="principal_employer_address" class="block text-sm font-medium text-slate-800">
                            Employer Address <span class="text-slate-400">(Optional)</span>
                        </label>

                        <input type="text" name="principal_employer_address" id="principal_employer_address"
                            value="{{ old('principal_employer_address') }}" placeholder="Enter full address"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-emerald-500 focus:outline-none">

                        @error('principal_employer_address')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Placement Fee --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                        {{-- Fee --}}
                        <div class="space-y-1">
                            <label for="placement_fee" class="block text-sm font-medium text-slate-800">
                                Placement Fee <span class="text-slate-400">(Optional)</span>
                            </label>

                            <input type="number" step="0.01" name="placement_fee" id="placement_fee"
                                value="{{ old('placement_fee') }}" placeholder="Enter amount"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-emerald-500 focus:outline-none">

                            <p class="text-xs text-slate-500">
                                Leave empty if no placement fee is required.
                            </p>

                            @error('placement_fee')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Fee Currency --}}
                        <div class="space-y-1">
                            <label for="placement_fee_currency" class="block text-sm font-medium text-slate-800">
                                Fee Currency <span class="text-slate-400">(Optional)</span>
                            </label>

                            <div class="relative">
                                <select name="placement_fee_currency" id="placement_fee_currency"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm bg-white focus:border-emerald-500 focus:outline-none">

                                    @foreach ($currencies ?? [] as $code => $name)
                                        <option value="{{ $code }}"
                                            {{ old('placement_fee_currency', 'PHP') == $code ? 'selected' : '' }}>
                                            {{ $code }} — {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            @error('placement_fee_currency')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                </div>


        {{-- Footer actions --}}
        @php
            $cancelUrl =
                request('from') === 'navbar' ? route('employer.dashboard') : route('employer.job-postings.index');
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
        </form>
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
