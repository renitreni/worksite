@extends('adminpage.layout')
@section('title', 'Job Post')
@section('page_title', 'Job Post')

@section('content')

    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex items-start justify-between gap-4">

            <div>
                <div class="text-sm text-slate-500">Administrator</div>
                <h1 class="text-2xl font-semibold text-slate-900">Job Post Details</h1>
                <div class="text-sm text-slate-500 mt-1">{{ $jobPost->title }}</div>
            </div>

            <div class="flex gap-2">

                <a href="{{ route('admin.job-posts.index') }}"
                    class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                    Back
                </a>

                <a href="{{ route('admin.job-posts.index') }}"
                    class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                    Job Posts
                </a>

            </div>

        </div>


        @include('adminpage.components.flash')


        @if ($errors->any())
            <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-rose-800">
                <div class="font-medium mb-1">Please fix the errors:</div>
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif



        {{-- Status Summary --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

            <div class="flex flex-wrap items-center gap-2">

                @if ($jobPost->status === 'open')
                    <span
                        class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1 bg-emerald-50 text-emerald-700 ring-emerald-200">
                        Open
                    </span>
                @else
                    <span
                        class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1 bg-slate-50 text-slate-700 ring-slate-200">
                        Closed
                    </span>
                @endif


                @if ($jobPost->is_held)
                    <span
                        class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1 bg-amber-50 text-amber-800 ring-amber-200">
                        Held
                    </span>
                @else
                    <span
                        class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1 bg-slate-50 text-slate-700 ring-slate-200">
                        Not Held
                    </span>
                @endif


                @if ($jobPost->is_disabled)
                    <span
                        class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1 bg-rose-50 text-rose-700 ring-rose-200">
                        Disabled
                    </span>
                @else
                    <span
                        class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1 bg-slate-50 text-slate-700 ring-slate-200">
                        Enabled
                    </span>
                @endif


                <div class="ml-auto text-sm text-slate-500">
                    Posted:
                    {{ optional($jobPost->posted_at)->format('Y-m-d') ?? (optional($jobPost->created_at)->format('Y-m-d') ?? '—') }}
                </div>

            </div>


            @if ($jobPost->is_held && $jobPost->hold_reason)
                <div class="mt-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">
                    <div class="font-semibold">Hold reason</div>
                    <div class="mt-1">{{ $jobPost->hold_reason }}</div>
                </div>
            @endif


            @if ($jobPost->is_disabled && $jobPost->disabled_reason)
                <div class="mt-4 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-900">
                    <div class="font-semibold">Disabled reason</div>
                    <div class="mt-1">{{ $jobPost->disabled_reason }}</div>
                </div>
            @endif

        </div>



        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">


            {{-- Job Information --}}
            <div class="lg:col-span-2 space-y-6">

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                    <div class="font-semibold text-slate-900">Job Information</div>


                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">

                        <div>
                            <div class="text-slate-500">Industry</div>
                            <div class="font-semibold text-slate-900">{{ $jobPost->industry ?? '—' }}</div>
                        </div>


                        <div>
                            <div class="text-slate-500">Location</div>
                            <div class="font-semibold text-slate-900">
                                {{ $jobPost->country ?? '—' }}{{ $jobPost->city ? ', ' . $jobPost->city : '' }}{{ $jobPost->area ? ', ' . $jobPost->area : '' }}
                            </div>
                        </div>


                        <div>
                            <div class="text-slate-500">Min experience</div>
                            <div class="font-semibold text-slate-900">{{ $jobPost->min_experience_years ?? '—' }}</div>
                        </div>


                        <div>
                            <div class="text-slate-500">Apply until</div>
                            <div class="font-semibold text-slate-900">{{ $jobPost->apply_until ?? '—' }}</div>
                        </div>


                        <div>
                            <div class="text-slate-500">Salary</div>
                            <div class="font-semibold text-slate-900">
                                @php
                                    $cur = $jobPost->salary_currency ?? '';
                                    $min = $jobPost->salary_min;
                                    $max = $jobPost->salary_max;
                                @endphp

                                @if (!is_null($min) || !is_null($max))
                                    {{ $cur }} {{ $min ?? '—' }} - {{ $max ?? '—' }}
                                @else
                                    —
                                @endif

                            </div>
                        </div>


                        <div>
                            <div class="text-slate-500">Gender</div>
                            <div class="font-semibold text-slate-900">{{ $jobPost->gender ?? '—' }}</div>
                        </div>


                        <div>
                            <div class="text-slate-500">Age range</div>
                            <div class="font-semibold text-slate-900">
                                {{ $jobPost->age_min ?? '—' }} - {{ $jobPost->age_max ?? '—' }}
                            </div>
                        </div>


                        <div>
                            <div class="text-slate-500">Placement fee</div>
                            <div class="font-semibold text-slate-900">

                                @php
                                    $pcur = $jobPost->placement_fee_currency ?? '';
                                    $fee = $jobPost->placement_fee;
                                @endphp

                                {{ $fee ? $pcur . ' ' . $fee : '—' }}

                            </div>
                        </div>

                    </div>


                    <div class="mt-5">
                        <div class="text-sm text-slate-500">Skills</div>
                        <div class="mt-1 text-sm text-slate-900 whitespace-pre-line">
                            {{ $jobPost->skills ?? '—' }}
                        </div>
                    </div>


                    <div class="mt-5">
                        <div class="text-sm text-slate-500">Job Description</div>
                        <div class="mt-1 text-sm text-slate-900 whitespace-pre-line">
                            {{ $jobPost->job_description ?? '—' }}
                        </div>
                    </div>


                    <div class="mt-5">
                        <div class="text-sm text-slate-500">Qualifications</div>
                        <div class="mt-1 text-sm text-slate-900 whitespace-pre-line">
                            {{ $jobPost->job_qualifications ?? '—' }}
                        </div>
                    </div>


                    <div class="mt-5">
                        <div class="text-sm text-slate-500">Additional Information</div>
                        <div class="mt-1 text-sm text-slate-900 whitespace-pre-line">
                            {{ $jobPost->additional_information ?? '—' }}
                        </div>
                    </div>

                </div>



                {{-- Employer Information --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                    <div class="font-semibold text-slate-900">Employer Information</div>

                    <div class="mt-3 text-sm text-slate-700 space-y-1">

                        <div>
                            <span class="text-slate-500">Principal employer:</span>
                            <span class="font-semibold text-slate-900">{{ $jobPost->principal_employer ?? '—' }}</span>
                        </div>

                        <div>
                            <span class="text-slate-500">DMW registration no:</span>
                            <span class="font-semibold text-slate-900">{{ $jobPost->dmw_registration_no ?? '—' }}</span>
                        </div>

                        <div>
                            <span class="text-slate-500">Address:</span>
                            <span
                                class="font-semibold text-slate-900">{{ $jobPost->principal_employer_address ?? '—' }}</span>
                        </div>

                    </div>

                    @if (isset($jobPost->employerProfile))
                        <div class="mt-4 text-sm text-slate-500">
                            Employer Profile ID: {{ $jobPost->employer_profile_id }}
                        </div>
                    @endif

                </div>

            </div>



            {{-- Moderation --}}
            <div class="space-y-6">
                


                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                    <div class="font-semibold text-slate-900">Moderation</div>
                    <div class="text-sm text-slate-500 mt-1">Hold or disable job posts for verification.</div>


                    {{-- Hold --}}
                    <div class="mt-4">

                        <div class="text-sm font-semibold text-slate-900">Hold</div>

                        @if (!$jobPost->is_held)
                            <form method="POST" action="{{ route('admin.job-posts.hold', $jobPost) }}"
                                class="mt-2 space-y-2">

                                @csrf
                                @method('PATCH')

                                <textarea name="hold_reason" rows="3"
                                    class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100"
                                    placeholder="Optional reason"></textarea>

                                <button
                                    class="w-full rounded-xl border border-amber-200 bg-amber-50 px-4 py-2 text-sm font-semibold text-amber-800 hover:bg-amber-100">
                                    Hold Job Post
                                </button>

                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.job-posts.unhold', $jobPost) }}" class="mt-2">

                                @csrf
                                @method('PATCH')

                                <button
                                    class="w-full rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                    Unhold Job Post
                                </button>

                            </form>
                        @endif

                    </div>


                    <hr class="my-5 border-slate-200">


                    {{-- Disable --}}
                    <div id="disable">

                        <div class="text-sm font-semibold text-slate-900">Disable</div>

                        @if (!$jobPost->is_disabled)
                            <form method="POST" action="{{ route('admin.job-posts.disable', $jobPost) }}"
                                class="mt-2 space-y-2">

                                @csrf
                                @method('PATCH')

                                <textarea name="disabled_reason" rows="3" required
                                    class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100"
                                    placeholder="Reason for disabling"></textarea>

                                <button
                                    class="w-full rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700">
                                    Disable Job Post
                                </button>

                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.job-posts.enable', $jobPost) }}"
                                class="mt-2">

                                @csrf
                                @method('PATCH')

                                <button
                                    class="w-full rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                    Enable Job Post
                                </button>

                            </form>
                        @endif

                    </div>

                </div>



                {{-- Admin Notes --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                    <div class="font-semibold text-slate-900">Admin Notes</div>
                    <div class="text-sm text-slate-500 mt-1">Internal notes (not visible to employers).</div>

                    <form method="POST" action="{{ route('admin.job-posts.notes', $jobPost) }}" class="mt-3 space-y-3">

                        @csrf
                        @method('PATCH')

                        <textarea name="admin_notes" rows="6"
                            class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100"
                            placeholder="Write notes here...">{{ old('admin_notes', $jobPost->admin_notes) }}</textarea>

                        <button
                            class="w-full rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                            Save Notes
                        </button>

                        @if ($jobPost->notes_updated_at)
                            <div class="text-xs text-slate-500">
                                Last updated: {{ optional($jobPost->notes_updated_at)->format('Y-m-d H:i') }}
                            </div>
                        @endif

                    </form>

                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                <div class="font-semibold text-slate-900 mb-4">
                    Moderation Timeline
                </div>

                <div class="space-y-4">

                    @forelse($jobPost->logs as $log)
                        <div class="flex gap-3">

                            <div class="mt-1 h-3 w-3 rounded-full bg-emerald-500"></div>

                            <div>

                                <div class="text-sm font-semibold text-slate-900">
                                    {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                                </div>

                                @if ($log->description)
                                    <div class="text-sm text-slate-600">
                                        {{ $log->description }}
                                    </div>
                                @endif

                                <div class="text-xs text-slate-500 mt-1">

                                    {{ $log->created_at->format('M d, Y H:i') }}

                                    @if ($log->admin)
                                        • {{ $log->admin->name }}
                                    @endif

                                </div>

                            </div>

                        </div>

                    @empty

                        <div class="text-sm text-slate-500">
                            No moderation history yet.
                        </div>
                    @endforelse

                </div>

            </div>


            </div>


        </div>

    </div>

@endsection
