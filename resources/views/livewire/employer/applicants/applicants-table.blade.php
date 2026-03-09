<div>


    <div class="mb-8 space-y-6">



        <div x-data="{
            openFilters: localStorage.getItem('openFilters') === 'true'
        }" x-init="$watch('openFilters', value => localStorage.setItem('openFilters', value))">

            <div class="mb-8 space-y-6">

                {{-- ADVANCED FILTER --}}
                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm">

                    {{-- Header --}}
                    <div class="flex items-center justify-between p-6 border-b border-slate-200">

                        <div>
                            <h3 class="text-base font-semibold text-slate-800">
                                Advanced Candidate Filters
                            </h3>

                            <p class="text-xs text-slate-500 mt-1">
                                Filter candidates by job, experience, education, location and age
                            </p>
                        </div>

                        <div class="flex items-center gap-3">

                            {{-- Toggle Button --}}
                            <button @click="openFilters = !openFilters"
                                class="text-xs font-semibold border border-slate-300 px-3 py-2 rounded-lg hover:bg-slate-50">

                                <span x-show="openFilters">Hide Filters</span>
                                <span x-show="!openFilters">Show Filters</span>

                            </button>

                            {{-- Upgrade --}}
                            @if (!$access['can_use_advanced_candidate_filters'])
                                <a href="{{ route('employer.subscription.dashboard') }}"
                                    class="text-xs font-semibold bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow-sm">
                                    Upgrade
                                </a>
                            @endif

                        </div>

                    </div>


                    {{-- FILTER CONTENT --}}
                    <div x-show="openFilters" x-transition class="p-6">

                        {{-- IF USER HAS ACCESS --}}
                        @if ($access['can_use_advanced_candidate_filters'])

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">

                                {{-- Job --}}
                                <div>
                                    <label class="text-xs font-medium text-slate-600 mb-1 block">
                                        Job Post
                                    </label>

                                    <select wire:model.live="jobPost"
                                        class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-green-500">

                                        <option value="">All Jobs</option>

                                        @foreach ($jobs as $job)
                                            <option value="{{ $job->id }}">
                                                {{ $job->title }}
                                            </option>
                                        @endforeach

                                    </select>
                                </div>

                                {{-- Experience --}}
                                <div>
                                    <label class="text-xs font-medium text-slate-600 mb-1 block">
                                        Experience
                                    </label>

                                    <select wire:model.live="experience"
                                        class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">

                                        <option value="">Any</option>
                                        <option value="1">1+ years</option>
                                        <option value="3">3+ years</option>
                                        <option value="5">5+ years</option>

                                    </select>
                                </div>

                                {{-- Education --}}
                                <div>
                                    <label class="text-xs font-medium text-slate-600 mb-1 block">
                                        Education
                                    </label>

                                    <select wire:model.live="education"
                                        class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">

                                        <option value="">Any</option>
                                        <option value="High School">High School</option>
                                        <option value="Senior High School">Senior High School</option>
                                        <option value="Vocational / TESDA">Vocational / TESDA</option>
                                        <option value="Associate Degree">Associate Degree</option>
                                        <option value="Bachelor's Degree">Bachelor's Degree</option>
                                        <option value="Master's Degree">Master's Degree</option>
                                        <option value="Doctorate">Doctorate</option>

                                    </select>
                                </div>

                                {{-- Location --}}
                                <div>
                                    <label class="text-xs font-medium text-slate-600 mb-1 block">
                                        Location
                                    </label>

                                    <input type="text" wire:model.live.debounce.400ms="location"
                                        placeholder="City or Province"
                                        class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm" />
                                </div>

                                {{-- Age --}}
                                <div>
                                    <label class="text-xs font-medium text-slate-600 mb-1 block">
                                        Age
                                    </label>

                                    <select wire:model.live="age"
                                        class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">

                                        <option value="">Any</option>
                                        <option value="20">20+</option>
                                        <option value="25">25+</option>
                                        <option value="30">30+</option>

                                    </select>
                                </div>

                            </div>

                            {{-- Clear Filters --}}
                            <div class="mt-5 flex justify-end">
                                <button wire:click="resetFilters"
                                    class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold bg-slate-50 hover:bg-slate-100">
                                    Clear Filters
                                </button>
                            </div>
                        @else
                            {{-- LOCKED UI --}}
                            <div class="border border-dashed border-slate-300 rounded-xl bg-slate-50 p-8 text-center">

                                <p class="text-sm font-semibold text-slate-700">
                                    Advanced filters are a premium feature
                                </p>

                                <p class="text-xs text-slate-500 mt-2">
                                    Upgrade your subscription to filter candidates by job post,
                                    experience, education, location and age.
                                </p>

                                <a href="{{ route('employer.subscription.dashboard') }}"
                                    class="inline-block mt-5 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold px-5 py-2 rounded-lg shadow-sm">
                                    Upgrade Plan
                                </a>

                            </div>

                        @endif

                    </div>

                </div>

                {{-- Applicants Table --}}
                <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">

                    <div class="overflow-x-auto">

                        <table class="min-w-full table-fixed divide-y divide-slate-200">

                            <thead class="bg-slate-50">
                                <tr>

                                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase">
                                        Applicant
                                    </th>

                                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase">
                                        Applied Position
                                    </th>

                                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase">
                                        Email
                                    </th>

                                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase">
                                        Status
                                    </th>

                                    <th class="px-6 py-4 text-center text-xs font-semibold text-slate-600 uppercase">
                                        Actions
                                    </th>

                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-200">

                                @forelse($applications as $app)
                                    @php
                                        $name = $app->candidateProfile?->user?->name ?? 'No Name';
                                        $email = $app->candidateProfile?->user?->email ?? 'N/A';

                                        $status = strtolower(trim($app->status ?? 'applied'));

                                        $nextMap = [
                                            'applied' => 'shortlisted',
                                            'shortlisted' => 'interview',
                                            'interview' => 'hired',
                                        ];

                                        $next = $nextMap[$status] ?? null;

                                        $locked = in_array($status, ['rejected', 'hired']);
                                    @endphp

                                    <tr wire:key="application-{{ $app->id }}" class="hover:bg-slate-50">

                                        <td class="px-6 py-5">

                                            <p class="text-sm font-semibold text-slate-900">
                                                {{ $name }}
                                            </p>

                                            <p class="text-xs text-slate-500 mb-2">
                                                Candidate
                                            </p>

                                            @php
                                                $steps = ['applied', 'shortlisted', 'interview', 'hired'];
                                                $currentIndex = array_search($status, $steps);
                                            @endphp

                                            <div class="flex items-center gap-2 text-[10px] font-semibold">

                                                @foreach ($steps as $index => $step)
                                                    <div class="flex items-center gap-2">

                                                        <div
                                                            class="h-2 w-2 rounded-full
                {{ $index <= $currentIndex ? 'bg-emerald-500' : 'bg-slate-300' }}">
                                                        </div>

                                                        <span
                                                            class="
                {{ $index <= $currentIndex ? 'text-emerald-600' : 'text-slate-400' }}">
                                                            {{ ucfirst($step) }}
                                                        </span>

                                                        @if (!$loop->last)
                                                            <div class="w-4 h-[1px] bg-slate-300"></div>
                                                        @endif

                                                    </div>
                                                @endforeach

                                            </div>

                                        </td>

                                        <td class="px-6 py-5 text-sm text-slate-700">
                                            {{ $app->jobPost?->title ?? 'N/A' }}
                                        </td>

                                        <td class="px-6 py-5 text-sm text-slate-700">
                                            {{ $email }}
                                        </td>

                                        <td class="px-6 py-5">

                                            <span
                                                class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $this->statusClasses($status) }}">
                                                {{ ucfirst($status) }}
                                            </span>

                                        </td>

                                        <td class="px-6 py-5 text-center">

                                            <div class="flex items-center justify-center gap-2">

                                                {{-- VIEW --}}
                                                <a href="{{ route('employer.applicants.show', $app) }}"
                                                    class="px-3 py-1.5 rounded-xl text-xs font-semibold border bg-white text-slate-700 border-slate-300 hover:bg-slate-50">
                                                    View
                                                </a>

                                                {{-- STATUS MODAL --}}
                                                <div x-data="{ open: false }">

                                                    <button @click="open=true"
                                                        class="px-3 py-1.5 rounded-xl text-xs font-semibold border bg-white text-slate-700 border-slate-300 hover:bg-slate-50">
                                                        Update Status
                                                    </button>

                                                    {{-- MODAL --}}
                                                    <div x-show="open" x-cloak
                                                        class="fixed inset-0 z-50 flex items-center justify-center p-4"
                                                        @keydown.escape.window="open=false">

                                                        {{-- BACKDROP --}}
                                                        <div class="absolute inset-0 bg-black/10" @click="open=false">
                                                        </div>

                                                        {{-- MODAL CARD --}}
                                                        <div @click.stop
                                                            class="relative w-full max-w-md rounded-2xl bg-white shadow-xl border border-slate-200">

                                                            {{-- HEADER --}}
                                                            <div
                                                                class="px-6 py-4 border-b flex justify-between items-center">

                                                                <div>
                                                                    <p class="text-sm font-semibold text-slate-900">
                                                                        Update Applicant Status
                                                                    </p>

                                                                    <p class="text-xs text-slate-500">
                                                                        Current: <span
                                                                            class="font-semibold">{{ ucfirst($status) }}</span>
                                                                    </p>
                                                                </div>

                                                                <button @click="open=false"
                                                                    class="p-2 rounded-lg hover:bg-slate-100">

                                                                    <i data-lucide="x" class="w-4 h-4"></i>

                                                                </button>

                                                            </div>

                                                            {{-- BODY --}}
                                                            <div class="p-4 space-y-3">

                                                                {{-- IF ALREADY HIRED --}}
                                                                @if ($status === 'hired')
                                                                    <div
                                                                        class="p-4 rounded-xl bg-emerald-50 border border-emerald-200">

                                                                        <div class="flex items-start gap-3">

                                                                            <i data-lucide="check-circle"
                                                                                class="w-5 h-5 text-emerald-600"></i>

                                                                            <div>

                                                                                <p
                                                                                    class="text-sm font-semibold text-emerald-700">
                                                                                    Candidate already hired
                                                                                </p>

                                                                                <p
                                                                                    class="text-xs text-emerald-600 mt-1">
                                                                                    This applicant has already been
                                                                                    marked as hired.
                                                                                    No further status updates are
                                                                                    available.
                                                                                </p>

                                                                            </div>

                                                                        </div>

                                                                    </div>

                                                                    {{-- IF REJECTED --}}
                                                                @elseif($status === 'rejected')
                                                                    <div
                                                                        class="p-4 rounded-xl bg-rose-50 border border-rose-200">

                                                                        <div class="flex items-start gap-3">

                                                                            <i data-lucide="x-circle"
                                                                                class="w-5 h-5 text-rose-600"></i>

                                                                            <div>

                                                                                <p
                                                                                    class="text-sm font-semibold text-rose-700">
                                                                                    Applicant rejected
                                                                                </p>

                                                                                <p class="text-xs text-rose-600 mt-1">
                                                                                    This applicant has already been
                                                                                    rejected.
                                                                                    Status updates are no longer
                                                                                    available.
                                                                                </p>

                                                                            </div>

                                                                        </div>

                                                                    </div>

                                                                    {{-- NORMAL STATUS ACTIONS --}}
                                                                @else
                                                                    {{-- SHORTLIST --}}
                                                                    @if ($next === 'shortlisted')
                                                                        <form
                                                                            action="{{ route('employer.applicants.shortlist', $app) }}"
                                                                            method="POST">
                                                                            @csrf
                                                                            @method('PUT')

                                                                            <button
                                                                                class="w-full text-left px-4 py-2 rounded-xl text-sm font-semibold bg-sky-50 text-sky-700 hover:bg-sky-100">
                                                                                Move to Shortlisted
                                                                            </button>
                                                                        </form>
                                                                    @endif


                                                                    {{-- INTERVIEW --}}
                                                                    @if ($next === 'interview')
                                                                        <form
                                                                            action="{{ route('employer.applicants.interview', $app) }}"
                                                                            method="POST">
                                                                            @csrf
                                                                            @method('PUT')

                                                                            <button
                                                                                class="w-full text-left px-4 py-2 rounded-xl text-sm font-semibold bg-amber-50 text-amber-700 hover:bg-amber-100">
                                                                                Move to Interview
                                                                            </button>
                                                                        </form>
                                                                    @endif


                                                                    {{-- HIRED --}}
                                                                    @if ($next === 'hired')
                                                                        <form
                                                                            action="{{ route('employer.applicants.hire', $app) }}"
                                                                            method="POST">
                                                                            @csrf
                                                                            @method('PUT')

                                                                            <button
                                                                                class="w-full text-left px-4 py-2 rounded-xl text-sm font-semibold bg-violet-50 text-violet-700 hover:bg-violet-100">
                                                                                Mark as Hired
                                                                            </button>
                                                                        </form>
                                                                    @endif


                                                                    {{-- REJECT --}}
                                                                    @if (!$locked)
                                                                        <form
                                                                            action="{{ route('employer.applicants.reject', $app) }}"
                                                                            method="POST">
                                                                            @csrf
                                                                            @method('PUT')

                                                                            <button
                                                                                class="w-full text-left px-4 py-2 rounded-xl text-sm font-semibold bg-rose-50 text-rose-700 hover:bg-rose-100">
                                                                                Reject Applicant
                                                                            </button>
                                                                        </form>
                                                                    @endif
                                                                @endif

                                                            </div>

                                                            {{-- FOOTER --}}
                                                            <div class="px-6 py-3 border-t flex justify-end">

                                                                <button @click="open=false"
                                                                    class="px-4 py-2 text-sm font-semibold border rounded-xl bg-white hover:bg-slate-50">
                                                                    Close
                                                                </button>

                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>

                                            </div>

                                        </td>
                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center">

                                            <p class="text-sm font-semibold text-slate-900">
                                                No applicants found
                                            </p>

                                            <p class="text-sm text-slate-500">
                                                Try adjusting filters
                                            </p>

                                        </td>
                                    </tr>
                                @endforelse

                            </tbody>

                        </table>

                    </div>



                </div>

            </div>
