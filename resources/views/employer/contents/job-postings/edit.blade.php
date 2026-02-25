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
            @endphp

            <form action="{{ route('employer.job-postings.update', $job->id) }}" method="POST"
                class="px-6 sm:px-8 py-8 space-y-8">
                @csrf
                @method('PUT')

                {{-- Job Details --}}
                <div class="space-y-6">
                    <h2 class="text-base font-semibold text-slate-900">Job Details</h2>

                    {{-- Title --}}
                    <div>
                        <label for="title" class="block text-sm font-medium text-slate-700">
                            Job Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" id="title" value="{{ old('title', $job->title) }}"
                            class="mt-2 block w-full rounded-2xl border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                        @error('title')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Industry (ID) --}}
                    <div>
                        <label for="industry_id" class="block text-sm font-medium text-slate-700">
                            Industry <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-2 relative">
                            <select name="industry_id" id="industry_id"
                                class="block w-full appearance-none rounded-2xl border-slate-300 bg-white px-4 py-3 pr-10 text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                <option value="">Select Industry</option>
                                @foreach ($industries ?? [] as $ind)
                                    <option value="{{ $ind->id }}"
                                        {{ (string) $oldIndustryId === (string) $ind->id ? 'selected' : '' }}>
                                        {{ $ind->name }}
                                    </option>
                                @endforeach
                            </select>
                            <svg class="pointer-events-none absolute right-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                        @error('industry_id')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Skills (AJAX by Industry) --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700">
                            Skills <span class="text-red-500">*</span>
                        </label>
                        <div id="skillsHint" class="mt-2 text-xs text-slate-500">
                            Select an industry to load skills.
                        </div>

                        <div id="skillsGrid" class="mt-3 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3"></div>

                        @error('skills')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                        @error('skills.*')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="h-px bg-slate-200"></div>

                {{-- Location & Compensation --}}
                <div class="space-y-6">
                    <h2 class="text-base font-semibold text-slate-900">Location & Compensation</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        {{-- Country --}}
                        <div>
                            <label for="country" class="block text-sm font-medium text-slate-700">
                                Country <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-2 relative">
                                <select name="country" id="country"
                                    class="block w-full appearance-none rounded-2xl border-slate-300 bg-white px-4 py-3 pr-10 text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                    <option value="">Select Country</option>
                                    @foreach ($countries ?? [] as $country)
                                        <option value="{{ $country }}"
                                            {{ old('country', $job->country) == $country ? 'selected' : '' }}>
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
                                <input type="text" name="city_custom" id="city_custom" value="{{ old('city_custom') }}"
                                    placeholder="Type city..."
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

                    {{-- Experience + Salary --}}
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                        <div>
                            <label for="min_experience_years" class="block text-sm font-medium text-slate-700">
                                Min. Experience (Years)
                            </label>
                            <input type="number" min="0" name="min_experience_years" id="min_experience_years"
                                value="{{ old('min_experience_years', $job->min_experience_years) }}"
                                class="mt-2 block w-full rounded-2xl border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                            @error('min_experience_years')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700">Salary Range (optional)</label>
                            <div class="mt-2 grid grid-cols-2 gap-3">
                                <div class="relative">
                                    <span
                                        class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 text-sm font-semibold"
                                        id="salarySymbol">₱</span>
                                    <input type="number" step="0.01" min="0" name="salary_min"
                                        id="salary_min" value="{{ old('salary_min', $job->salary_min) }}"
                                        placeholder="Minimum"
                                        class="pl-10 block w-full rounded-2xl border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                </div>

                                <div class="relative">
                                    <span
                                        class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 text-sm font-semibold">—</span>
                                    <input type="number" step="0.01" min="0" name="salary_max"
                                        id="salary_max" value="{{ old('salary_max', $job->salary_max) }}"
                                        placeholder="Maximum"
                                        class="pl-10 block w-full rounded-2xl border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                </div>
                            </div>
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
                                            {{ old('salary_currency', $job->salary_currency ?? 'PHP') == $code ? 'selected' : '' }}>
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

                <div class="h-px bg-slate-200"></div>

                {{-- Content + Fees --}}
                <div class="space-y-6">
                    <h2 class="text-base font-semibold text-slate-900">Descriptions & Fees</h2>

                    {{-- Required Education --}}
                    <div>
                        <label for="education_level" class="block text-sm font-medium text-slate-700">
                            Required Education
                        </label>

                        <div class="mt-2 relative">
                            <select name="education_level" id="education_level"
                                class="block w-full appearance-none rounded-2xl border-slate-300 bg-white px-4 py-3 pr-10 text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                <option value=""
                                    {{ old('education_level', $job->education_level) === null || old('education_level', $job->education_level) === '' ? 'selected' : '' }}>
                                    Any / Not required
                                </option>

                                <option value="high_school"
                                    {{ old('education_level', $job->education_level) === 'high_school' ? 'selected' : '' }}>
                                    High school diploma
                                </option>

                                <option value="college"
                                    {{ old('education_level', $job->education_level) === 'college' ? 'selected' : '' }}>
                                    College graduate
                                </option>

                                <option value="masteral"
                                    {{ old('education_level', $job->education_level) === 'masteral' ? 'selected' : '' }}>
                                    Masteral degree
                                </option>

                                <option value="phd"
                                    {{ old('education_level', $job->education_level) === 'phd' ? 'selected' : '' }}>
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
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label for="gender" class="block text-sm font-medium text-slate-700">Gender <span
                                    class="text-red-500">*</span></label>
                            <div class="mt-2 relative">
                                <select name="gender" id="gender"
                                    class="block w-full appearance-none rounded-2xl border-slate-300 bg-white px-4 py-3 pr-10 text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                    <option value="both" {{ old('gender', $job->gender) == 'both' ? 'selected' : '' }}>
                                        Both</option>
                                    <option value="male" {{ old('gender', $job->gender) == 'male' ? 'selected' : '' }}>
                                        Male</option>
                                    <option value="female"
                                        {{ old('gender', $job->gender) == 'female' ? 'selected' : '' }}>Female</option>
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
                                value="{{ old('age_min', $job->age_min) }}"
                                class="mt-2 block w-full rounded-2xl border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                            @error('age_min')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="age_max" class="block text-sm font-medium text-slate-700">Age Max</label>
                            <input type="number" min="0" name="age_max" id="age_max"
                                value="{{ old('age_max', $job->age_max) }}"
                                class="mt-2 block w-full rounded-2xl border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                            @error('age_max')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="apply_until" class="block text-sm font-medium text-slate-700">Apply Until</label>
                            <input type="date" name="apply_until" id="apply_until"
                                value="{{ old('apply_until', optional($job->apply_until)->format('Y-m-d')) }}"
                                class="mt-2 block w-full rounded-2xl border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                            @error('apply_until')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-slate-700">Status</label>
                            <div class="mt-2 relative">
                                <select name="status" id="status"
                                    class="block w-full appearance-none rounded-2xl border-slate-300 bg-white px-4 py-3 pr-10 text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                    <option value="open" {{ old('status', $job->status) == 'open' ? 'selected' : '' }}>
                                        Open</option>
                                    <option value="closed"
                                        {{ old('status', $job->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                                </select>
                                <svg class="pointer-events-none absolute right-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                            @error('status')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="job_description" class="block text-sm font-medium text-slate-700">Job Description
                            <span class="text-red-500">*</span></label>
                        <textarea name="job_description" id="job_description" rows="6"
                            class="mt-2 block w-full rounded-2xl border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">{{ old('job_description', $job->job_description) }}</textarea>
                        @error('job_description')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="job_qualifications" class="block text-sm font-medium text-slate-700">Job
                            Qualifications</label>
                        <textarea name="job_qualifications" id="job_qualifications" rows="4"
                            class="mt-2 block w-full rounded-2xl border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">{{ old('job_qualifications', $job->job_qualifications) }}</textarea>
                        @error('job_qualifications')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="additional_information" class="block text-sm font-medium text-slate-700">Additional
                            Information</label>
                        <textarea name="additional_information" id="additional_information" rows="4"
                            class="mt-2 block w-full rounded-2xl border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">{{ old('additional_information', $job->additional_information) }}</textarea>
                        @error('additional_information')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="principal_employer" class="block text-sm font-medium text-slate-700">Principal /
                                Employer</label>
                            <input type="text" name="principal_employer" id="principal_employer"
                                value="{{ old('principal_employer', $job->principal_employer) }}"
                                class="mt-2 block w-full rounded-2xl border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                            @error('principal_employer')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="dmw_registration_no" class="block text-sm font-medium text-slate-700">DMW
                                Reg/Accreditation No.</label>
                            <input type="text" name="dmw_registration_no" id="dmw_registration_no"
                                value="{{ old('dmw_registration_no', $job->dmw_registration_no) }}"
                                class="mt-2 block w-full rounded-2xl border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                            @error('dmw_registration_no')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="principal_employer_address" class="block text-sm font-medium text-slate-700">Principal
                            / Employer Address</label>
                        <input type="text" name="principal_employer_address" id="principal_employer_address"
                            value="{{ old('principal_employer_address', $job->principal_employer_address) }}"
                            class="mt-2 block w-full rounded-2xl border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                        @error('principal_employer_address')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <div>
                            <label for="placement_fee" class="block text-sm font-medium text-slate-700">Placement Fee
                                (optional)</label>
                            <div class="mt-2 relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 text-sm font-semibold"
                                    id="feeSymbol">₱</span>
                                <input type="number" step="0.01" name="placement_fee" id="placement_fee"
                                    value="{{ old('placement_fee', $job->placement_fee) }}"
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
                                            {{ old('placement_fee_currency', $job->placement_fee_currency ?? 'PHP') == $code ? 'selected' : '' }}>
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
