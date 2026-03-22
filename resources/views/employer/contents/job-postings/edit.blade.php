{{-- resources/views/employer/contents/job-postings/edit.blade.php --}}
@extends('employer.layout')

@section('content')
    <div class="space-y-6">
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
            {{-- Header --}}
            <div class="px-6 sm:px-8 py-6 border-b border-slate-200 bg-gradient-to-r from-emerald-50 to-white">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-semibold text-slate-900">Edit Job Posting</h1>
                        <p class="text-sm text-slate-600 mt-1">Update the details then save changes.</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span
                            class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold
                            {{ $job->status === 'open' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                            {{ strtoupper($job->status) }}
                        </span>
                    </div>
                </div>
            </div>

            @php
                // Industry ID from job (job stores industry name)
                $currentIndustryId = optional(collect($industries ?? [])->firstWhere('name', $job->industry))->id;
                $oldIndustryId = old('industry_id', $currentIndustryId);

                // Country cascading init
                $oldCountry = old('country', $job->country);
                $oldCity = old('city', $job->city);
                $oldArea = old('area', $job->area);

                // old('skills') on validation fail = IDs (array)
                $oldSkillIds = old('skills', []);
                if (!is_array($oldSkillIds)) {
                    $oldSkillIds = [];
                }
                $oldSkillIds = collect($oldSkillIds)->map(fn($v) => (string) $v)->values()->all();

                // ✅ Detect if saved skills are IDs ("11,12") or names ("Caregiver,Nurse")
                $rawSkills = trim((string) $job->skills);
                $savedSkillIds = [];

                if ($rawSkills !== '') {
                    $parts = collect(explode(',', $rawSkills))->map(fn($s) => trim($s))->filter()->values();

                    $looksNumeric = $parts->every(fn($s) => preg_match('/^\d+$/', $s));

                    if ($looksNumeric) {
                        // Saved as IDs
                        $savedSkillIds = $parts->map(fn($s) => (string) $s)->all();
                    } else {
                        // Saved as NAMES -> map to IDs (needs industry)
                        if (!empty($currentIndustryId)) {
                            $savedSkillIds = \App\Models\Skill::query()
                                ->where('industry_id', $currentIndustryId)
                                ->whereIn('name', $parts->all())
                                ->pluck('id')
                                ->map(fn($id) => (string) $id)
                                ->all();
                        }
                    }
                }

                // Extract custom skills (those NOT in predefined skills)
                $customSkillsText = '';

                if ($rawSkills !== '') {
                    $allParts = collect(explode(',', $rawSkills))->map(fn($s) => trim($s))->filter();

                    if (!empty($currentIndustryId)) {
                        $existingSkillNames = \App\Models\Skill::where('industry_id', $currentIndustryId)
                            ->pluck('name')
                            ->map(fn($n) => strtolower($n));

                        $customSkills = $allParts->filter(function ($skill) use ($existingSkillNames) {
                            return !$existingSkillNames->contains(strtolower($skill));
                        });

                        $customSkillsText = $customSkills->implode(', ');
                    } else {
                        // fallback: assume all are custom
                        $customSkillsText = $allParts->implode(', ');
                    }
                }
            @endphp

            <form action="{{ route('employer.job-postings.update', $job->id) }}" method="POST"
                class="px-6 sm:px-8 py-8 space-y-8">
                @csrf
                @method('PUT')

                {{-- Job Details --}}
                <div class="space-y-8">

                    <h2 class="text-lg font-semibold text-slate-900">Job Details</h2>

                    {{-- Title --}}
                    <div class="space-y-1">
                        <label for="title" class="block text-sm font-medium text-slate-800">
                            Job Title <span class="text-red-500">(Required)</span>
                        </label>

                        <input type="text" name="title" id="title" value="{{ old('title', $job->title) }}"
                            placeholder="e.g. Welder, Caregiver"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-emerald-500 focus:outline-none">

                        <p class="text-xs text-slate-500">
                            Enter the main job position you are hiring for.
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

                                <option value="">Select industry</option>

                                @foreach ($industries ?? [] as $ind)
                                    <option value="{{ $ind->id }}"
                                        {{ (string) $oldIndustryId === (string) $ind->id ? 'selected' : '' }}>
                                        {{ $ind->name }}
                                    </option>
                                @endforeach
                            </select>


                        </div>

                        <p class="text-xs text-slate-500">
                            Selecting an industry will load relevant skills below.
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
                            Select all applicable skills for this job.
                        </p>

                        <div id="skillsHint" class="text-xs text-slate-500">
                            Skills will load automatically after selecting an industry.
                        </div>

                        <div id="skillsGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
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
                            Custom Skills <span class="text-slate-400">(Optional)</span>
                        </label>

                        <input type="text" name="custom_skills" value="{{ old('custom_skills', $customSkillsText) }}"
                            placeholder="e.g. Welding, Machine Operation"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-emerald-500 focus:outline-none">

                        <p class="text-xs text-slate-500">
                            Separate multiple skills using commas.
                        </p>
                    </div>

                </div>

                <div class="h-px bg-slate-700"></div>

                {{-- Location & Compensation --}}
                <div class="space-y-8">

                    <h2 class="text-lg font-semibold text-slate-900">Location & Compensation</h2>

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
                                            {{ old('country', $job->country) == $country ? 'selected' : '' }}>
                                            {{ $country }}
                                        </option>
                                    @endforeach
                                </select>

                            </div>

                            <p class="text-xs text-slate-500">
                                Select where the job is located.
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
                                    <option value="__custom__" :selected="useCustomCity">
                                        Other (type manually)
                                    </option>
                                </select>


                            </div>

                            <p class="text-xs text-slate-500">
                                Choose a city or enter manually.
                            </p>

                            {{-- Custom City --}}
                            <div x-show="useCustomCity" x-cloak class="space-y-1">
                                <input type="text" name="city_custom" id="city_custom" value="{{ old('city_custom') }}"
                                    placeholder="Enter city"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-emerald-500 focus:outline-none">

                                <p class="text-xs text-slate-500">
                                    Custom entries will be reviewed.
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
                                    <option value="__custom__" :selected="useCustomArea">
                                        Other (type manually)
                                    </option>
                                </select>

                            </div>

                            <p class="text-xs text-slate-500">
                                Optional: specify a more detailed location.
                            </p>

                            {{-- Custom Area --}}
                            <div x-show="useCustomArea" x-cloak class="space-y-1">
                                <input type="text" name="area_custom" id="area_custom"
                                    value="{{ old('area_custom') }}" placeholder="Enter area"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-emerald-500 focus:outline-none">

                                <p class="text-xs text-slate-500">
                                    Custom entries will be reviewed.
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

                {{-- Experience + Salary --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    {{-- Experience --}}
                    <div class="space-y-1">
                        <label for="min_experience_years" class="block text-sm font-medium text-slate-800">
                            Minimum Experience <span class="text-slate-400">(Optional)</span>
                        </label>

                        <input type="number" min="0" name="min_experience_years" id="min_experience_years"
                            value="{{ old('min_experience_years', $job->min_experience_years) }}" placeholder="e.g. 2"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-emerald-500 focus:outline-none">

                        <p class="text-xs text-slate-500">
                            Years of experience required for this role.
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
                                value="{{ old('salary_min', $job->salary_min) }}" placeholder="Minimum"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-emerald-500 focus:outline-none">

                            {{-- Max --}}
                            <input type="number" step="0.01" min="0" name="salary_max" id="salary_max"
                                value="{{ old('salary_max', $job->salary_max) }}" placeholder="Maximum"
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
                                        {{ old('salary_currency', $job->salary_currency ?? 'PHP') == $code ? 'selected' : '' }}>
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

                <div class="h-px bg-slate-700"></div>

                {{-- Content + Fees --}}
                <div class="space-y-8">

                    <h2 class="text-lg font-semibold text-slate-900">Descriptions & Fees</h2>

                    {{-- Education --}}
                    <div class="space-y-1">
                        <label for="education_level" class="block text-sm font-medium text-slate-800">
                            Required Education <span class="text-slate-400">(Optional)</span>
                        </label>

                        <div class="relative">
                            <select name="education_level" id="education_level"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm bg-white focus:border-emerald-500 focus:outline-none">

                                <option value="">Any / Not required</option>
                                <option value="high_school"
                                    {{ old('education_level', $job->education_level) === 'high_school' ? 'selected' : '' }}>
                                    High school diploma</option>
                                <option value="college"
                                    {{ old('education_level', $job->education_level) === 'college' ? 'selected' : '' }}>
                                    College graduate</option>
                                <option value="masteral"
                                    {{ old('education_level', $job->education_level) === 'masteral' ? 'selected' : '' }}>
                                    Masteral degree</option>
                                <option value="phd"
                                    {{ old('education_level', $job->education_level) === 'phd' ? 'selected' : '' }}>PhD /
                                    Doctorate</option>
                            </select>


                        </div>

                        <p class="text-xs text-slate-500">
                            Leave as “Any” if no specific education is required.
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

                            <select name="gender" id="gender"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">

                                <option value="both" {{ old('gender', $job->gender) == 'both' ? 'selected' : '' }}>Both
                                </option>
                                <option value="male" {{ old('gender', $job->gender) == 'male' ? 'selected' : '' }}>Male
                                </option>
                                <option value="female" {{ old('gender', $job->gender) == 'female' ? 'selected' : '' }}>
                                    Female</option>
                            </select>

                            @error('gender')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Age Min --}}
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-slate-800">
                                Minimum Age <span class="text-slate-400">(Optional)</span>
                            </label>

                            <input type="number" name="age_min" value="{{ old('age_min', $job->age_min) }}"
                                placeholder="e.g. 21"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">

                            @error('age_min')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Age Max --}}
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-slate-800">
                                Maximum Age <span class="text-slate-400">(Optional)</span>
                            </label>

                            <input type="number" name="age_max" value="{{ old('age_max', $job->age_max) }}"
                                placeholder="e.g. 45"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">

                            @error('age_max')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    {{-- Dates --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                        {{-- Apply Until --}}
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-slate-800">
                                Application Deadline <span class="text-slate-400">(Optional)</span>
                            </label>

                            <input type="date" name="apply_until"
                                value="{{ old('apply_until', optional($job->apply_until)->format('Y-m-d')) }}"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">

                            @error('apply_until')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-slate-800">
                                Status
                            </label>

                            <select name="status" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">

                                <option value="open" {{ old('status', $job->status) == 'open' ? 'selected' : '' }}>Open
                                </option>
                                <option value="closed" {{ old('status', $job->status) == 'closed' ? 'selected' : '' }}>
                                    Closed</option>
                            </select>

                            @error('status')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    {{-- Text Areas --}}
                    <div class="space-y-6">

                        <div class="space-y-1">
                            <label class="text-sm font-medium text-slate-800">
                                Job Description <span class="text-red-500">(Required)</span>
                            </label>

                            <textarea name="job_description" rows="6" placeholder="Describe responsibilities, tasks, and expectations..."
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">{{ old('job_description', $job->job_description) }}</textarea>

                            @error('job_description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-1">
                            <label class="text-sm font-medium text-slate-800">
                                Job Qualifications <span class="text-slate-400">(Optional)</span>
                            </label>

                            <textarea name="job_qualifications" rows="4" placeholder="Required skills, certifications, or experience..."
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">{{ old('job_qualifications', $job->job_qualifications) }}</textarea>

                            @error('job_qualifications')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-1">
                            <label class="text-sm font-medium text-slate-800">
                                Additional Information <span class="text-slate-400">(Optional)</span>
                            </label>

                            <textarea name="additional_information" rows="4" placeholder="Benefits, schedule, or additional notes..."
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">{{ old('additional_information', $job->additional_information) }}</textarea>

                            @error('additional_information')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                </div>

                <div class="space-y-8">

                    {{-- Employer Info --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                        {{-- Employer --}}
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-slate-800">
                                Principal / Employer <span class="text-slate-400">(Optional)</span>
                            </label>

                            <input type="text" name="principal_employer"
                                value="{{ old('principal_employer', $job->principal_employer) }}"
                                placeholder="Company name"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-emerald-500 focus:outline-none">

                            @error('principal_employer')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- DMW --}}
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-slate-800">
                                DMW Accreditation No. <span class="text-slate-400">(Optional)</span>
                            </label>

                            <input type="text" name="dmw_registration_no"
                                value="{{ old('dmw_registration_no', $job->dmw_registration_no) }}"
                                placeholder="e.g. DMW-123456"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-emerald-500 focus:outline-none">

                            <p class="text-xs text-slate-500">
                                Required for overseas job postings.
                            </p>

                            @error('dmw_registration_no')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    {{-- Address --}}
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-slate-800">
                            Employer Address <span class="text-slate-400">(Optional)</span>
                        </label>

                        <input type="text" name="principal_employer_address"
                            value="{{ old('principal_employer_address', $job->principal_employer_address) }}"
                            placeholder="Full address of employer"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-emerald-500 focus:outline-none">

                        @error('principal_employer_address')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Placement Fee --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                        {{-- Fee --}}
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-slate-800">
                                Placement Fee <span class="text-slate-400">(Optional)</span>
                            </label>

                            <input type="number" step="0.01" name="placement_fee"
                                value="{{ old('placement_fee', $job->placement_fee) }}" placeholder="e.g. 15000"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-emerald-500 focus:outline-none">

                            <p class="text-xs text-slate-500">
                                Enter amount only (no symbols).
                            </p>

                            @error('placement_fee')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Currency --}}
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-slate-800">
                                Fee Currency <span class="text-slate-400">(Optional)</span>
                            </label>

                            <div class="relative">
                                <select name="placement_fee_currency"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm bg-white focus:border-emerald-500 focus:outline-none">

                                    @foreach ($currencies ?? [] as $code => $name)
                                        <option value="{{ $code }}"
                                            {{ old('placement_fee_currency', $job->placement_fee_currency ?? 'PHP') == $code ? 'selected' : '' }}>
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


                @php $backUrl = url()->previous(); @endphp
                <div class="pt-4 flex flex-col sm:flex-row sm:items-center sm:justify-end gap-3">
                    <a href="{{ $backUrl }}"
                        class="inline-flex justify-center rounded-2xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        Cancel
                    </a>
                    <button type="submit"
                        class="inline-flex justify-center rounded-2xl bg-emerald-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-4 focus:ring-emerald-200">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Industry -> Skills (AJAX) --}}
    <script>
        (function() {
            const industryEl = document.getElementById('industry_id');
            const grid = document.getElementById('skillsGrid');
            const hint = document.getElementById('skillsHint');

            if (!industryEl || !grid) return;

            const oldSkillIds = (@json($oldSkillIds) || []).map(String);
            const savedSkillIds = (@json($savedSkillIds) || []).map(String);

            function renderSkills(skills) {
                grid.innerHTML = '';

                if (!skills || skills.length === 0) {
                    hint.textContent = 'No skills found for this industry.';
                    return;
                }

                hint.textContent = 'Select all that apply.';

                skills.forEach(s => {
                    const id = String(s.id);
                    const checked = oldSkillIds.includes(id) || savedSkillIds.includes(id);

                    const label = document.createElement('label');
                    label.className =
                        'group flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-sm hover:border-emerald-200 hover:bg-emerald-50/30 transition cursor-pointer';

                    label.innerHTML = `
                        <input type="checkbox" name="skills[]" value="${s.id}"
                            class="h-5 w-5 rounded-md border-slate-300 text-emerald-600 focus:ring-4 focus:ring-emerald-100"
                            ${checked ? 'checked' : ''}>
                        <span class="text-sm font-medium text-slate-700 group-hover:text-slate-900">${s.name}</span>
                    `;

                    grid.appendChild(label);
                });
            }

            async function fetchJson(url) {
                const res = await fetch(url, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                if (!res.ok) return [];
                const ct = res.headers.get('content-type') || '';
                if (!ct.includes('application/json')) return [];
                return await res.json();
            }

            async function loadSkills(industryId) {
                grid.innerHTML = '';
                if (!industryId) {
                    hint.textContent = 'Select an industry to load skills.';
                    return;
                }

                hint.textContent = 'Loading skills...';
                const url = `{{ url('/employer/industries') }}/${encodeURIComponent(industryId)}/skills`;
                const skills = await fetchJson(url);
                renderSkills(skills);
            }

            industryEl.addEventListener('change', async () => {
                await loadSkills(industryEl.value);
            });

            (async function init() {
                if (industryEl.value) await loadSkills(industryEl.value);
            })();
        })();
    </script>
@endsection
