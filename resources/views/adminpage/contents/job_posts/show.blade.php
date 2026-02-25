@extends('adminpage.layout')
@section('title','Job Post')
@section('page_title','Job Post')
@section('content')
<div class="space-y-6">

    <div class="flex items-start justify-between gap-4">
        <div>
            <div class="text-sm text-gray-500">Administrator</div>
            <h1 class="text-2xl font-semibold text-gray-900">Job Post Details</h1>
            <div class="text-sm text-gray-500 mt-1">{{ $jobPost->title }}</div>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('admin.job-posts.index') }}"
               class="rounded-xl border border-gray-200 px-5 py-2.5 font-medium text-gray-700 hover:bg-gray-50">
                Back
            </a>

            <a href="{{ route('admin.job-posts.index') }}"
               class="rounded-xl bg-gray-900 px-5 py-2.5 font-medium text-white hover:bg-black">
                Job Posts
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-800">
            <div class="font-medium mb-1">Please fix the errors:</div>
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Status summary --}}
    <div class="rounded-2xl bg-white border border-gray-200 p-5">
        <div class="flex flex-wrap items-center gap-2">
            @if ($jobPost->status === 'open')
                <span class="inline-flex items-center rounded-full border border-green-200 bg-green-50 px-3 py-1 text-green-700 font-medium">Open</span>
            @else
                <span class="inline-flex items-center rounded-full border border-gray-200 bg-gray-50 px-3 py-1 text-gray-700 font-medium">Closed</span>
            @endif

            @if ($jobPost->is_held)
                <span class="inline-flex items-center rounded-full border border-yellow-200 bg-yellow-50 px-3 py-1 text-yellow-800 font-medium">Held</span>
            @else
                <span class="inline-flex items-center rounded-full border border-gray-200 bg-gray-50 px-3 py-1 text-gray-700 font-medium">Not Held</span>
            @endif

            @if ($jobPost->is_disabled)
                <span class="inline-flex items-center rounded-full border border-red-200 bg-red-50 px-3 py-1 text-red-700 font-medium">Disabled</span>
            @else
                <span class="inline-flex items-center rounded-full border border-gray-200 bg-gray-50 px-3 py-1 text-gray-700 font-medium">Enabled</span>
            @endif

            <div class="ml-auto text-sm text-gray-500">
                Posted: {{ optional($jobPost->posted_at)->format('Y-m-d') ?? optional($jobPost->created_at)->format('Y-m-d') ?? '—' }}
            </div>
        </div>

        @if ($jobPost->is_held && $jobPost->hold_reason)
            <div class="mt-3 text-sm text-yellow-900 bg-yellow-50 border border-yellow-200 rounded-xl px-4 py-3">
                <div class="font-semibold">Hold reason</div>
                <div class="mt-1">{{ $jobPost->hold_reason }}</div>
            </div>
        @endif

        @if ($jobPost->is_disabled && $jobPost->disabled_reason)
            <div class="mt-3 text-sm text-red-900 bg-red-50 border border-red-200 rounded-xl px-4 py-3">
                <div class="font-semibold">Disabled reason</div>
                <div class="mt-1">{{ $jobPost->disabled_reason }}</div>
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Job info --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="rounded-2xl bg-white border border-gray-200 p-5">
                <div class="font-semibold text-gray-900">Job Information</div>

                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <div class="text-gray-500">Industry</div>
                        <div class="font-medium text-gray-900">{{ $jobPost->industry ?? '—' }}</div>
                    </div>

                    <div>
                        <div class="text-gray-500">Location</div>
                        <div class="font-medium text-gray-900">
                            {{ $jobPost->country ?? '—' }}{{ $jobPost->city ? ', '.$jobPost->city : '' }}{{ $jobPost->area ? ', '.$jobPost->area : '' }}
                        </div>
                    </div>

                    <div>
                        <div class="text-gray-500">Min experience</div>
                        <div class="font-medium text-gray-900">{{ $jobPost->min_experience_years ?? '—' }}</div>
                    </div>

                    <div>
                        <div class="text-gray-500">Apply until</div>
                        <div class="font-medium text-gray-900">{{ $jobPost->apply_until ?? '—' }}</div>
                    </div>

                    <div>
                        <div class="text-gray-500">Salary</div>
                        <div class="font-medium text-gray-900">
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
                        <div class="text-gray-500">Gender</div>
                        <div class="font-medium text-gray-900">{{ $jobPost->gender ?? '—' }}</div>
                    </div>

                    <div>
                        <div class="text-gray-500">Age range</div>
                        <div class="font-medium text-gray-900">
                            {{ $jobPost->age_min ?? '—' }} - {{ $jobPost->age_max ?? '—' }}
                        </div>
                    </div>

                    <div>
                        <div class="text-gray-500">Placement fee</div>
                        <div class="font-medium text-gray-900">
                            @php
                                $pcur = $jobPost->placement_fee_currency ?? '';
                                $fee = $jobPost->placement_fee;
                            @endphp
                            {{ $fee ? ($pcur.' '.$fee) : '—' }}
                        </div>
                    </div>
                </div>

                <div class="mt-5">
                    <div class="text-gray-500 text-sm">Skills</div>
                    <div class="mt-1 text-gray-900 text-sm whitespace-pre-line">{{ $jobPost->skills ?? '—' }}</div>
                </div>

                <div class="mt-5">
                    <div class="text-gray-500 text-sm">Job Description</div>
                    <div class="mt-1 text-gray-900 text-sm whitespace-pre-line">{{ $jobPost->job_description ?? '—' }}</div>
                </div>

                <div class="mt-5">
                    <div class="text-gray-500 text-sm">Qualifications</div>
                    <div class="mt-1 text-gray-900 text-sm whitespace-pre-line">{{ $jobPost->job_qualifications ?? '—' }}</div>
                </div>

                <div class="mt-5">
                    <div class="text-gray-500 text-sm">Additional Information</div>
                    <div class="mt-1 text-gray-900 text-sm whitespace-pre-line">{{ $jobPost->additional_information ?? '—' }}</div>
                </div>
            </div>

            <div class="rounded-2xl bg-white border border-gray-200 p-5">
                <div class="font-semibold text-gray-900">Employer Information</div>
                <div class="mt-3 text-sm text-gray-700">
                    <div><span class="text-gray-500">Principal employer:</span> <span class="font-medium text-gray-900">{{ $jobPost->principal_employer ?? '—' }}</span></div>
                    <div class="mt-1"><span class="text-gray-500">DMW registration no:</span> <span class="font-medium text-gray-900">{{ $jobPost->dmw_registration_no ?? '—' }}</span></div>
                    <div class="mt-1"><span class="text-gray-500">Address:</span> <span class="font-medium text-gray-900">{{ $jobPost->principal_employer_address ?? '—' }}</span></div>
                </div>

                {{-- If employerProfile relation exists --}}
                @if (isset($jobPost->employerProfile))
                    <div class="mt-4 text-sm text-gray-500">
                        Employer Profile ID: {{ $jobPost->employer_profile_id }}
                    </div>
                @endif
            </div>
        </div>

        {{-- Moderation --}}
        <div class="space-y-6">
            <div class="rounded-2xl bg-white border border-gray-200 p-5">
                <div class="font-semibold text-gray-900">Moderation</div>
                <div class="text-sm text-gray-500 mt-1">Hold or disable job posts for verification/quality control.</div>

                {{-- Hold / Unhold --}}
                <div class="mt-4">
                    <div class="text-sm font-medium text-gray-900">Hold</div>
                    @if (!$jobPost->is_held)
                        <form method="POST" action="{{ route('admin.job-posts.hold', $jobPost) }}" class="mt-2 space-y-2">
                            @csrf
                            @method('PATCH')
                            <textarea name="hold_reason" rows="3"
                                class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500"
                                placeholder="Optional: reason for holding this post..."></textarea>

                            <button type="submit"
                                class="w-full rounded-xl border border-yellow-200 bg-yellow-50 px-4 py-2.5 font-medium text-yellow-800 hover:bg-yellow-100">
                                Hold Job Post
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('admin.job-posts.unhold', $jobPost) }}" class="mt-2">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="w-full rounded-xl border border-gray-200 px-4 py-2.5 font-medium text-gray-700 hover:bg-gray-50">
                                Unhold Job Post
                            </button>
                        </form>
                    @endif
                </div>

                <hr class="my-5">

                {{-- Disable / Enable --}}
                <div id="disable">
                    <div class="text-sm font-medium text-gray-900">Disable</div>
                    @if (!$jobPost->is_disabled)
                        <form method="POST" action="{{ route('admin.job-posts.disable', $jobPost) }}" class="mt-2 space-y-2">
                            @csrf
                            @method('PATCH')

                            <textarea name="disabled_reason" rows="3" required
                                class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500"
                                placeholder="Required: why is this job post invalid/fake/expired?"></textarea>

                            <button type="submit"
                                class="w-full rounded-xl bg-red-600 px-4 py-2.5 font-medium text-white hover:bg-red-700">
                                Disable Job Post
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('admin.job-posts.enable', $jobPost) }}" class="mt-2">
                            @csrf
                            @method('PATCH')

                            <button type="submit"
                                class="w-full rounded-xl border border-gray-200 px-4 py-2.5 font-medium text-gray-700 hover:bg-gray-50">
                                Enable Job Post
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            {{-- Admin notes --}}
            <div class="rounded-2xl bg-white border border-gray-200 p-5">
                <div class="font-semibold text-gray-900">Admin Notes</div>
                <div class="text-sm text-gray-500 mt-1">Internal notes (not visible to employers).</div>

                <form method="POST" action="{{ route('admin.job-posts.notes', $jobPost) }}" class="mt-3 space-y-3">
                    @csrf
                    @method('PATCH')

                    <textarea name="admin_notes" rows="6"
                        class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500"
                        placeholder="Write notes here...">{{ old('admin_notes', $jobPost->admin_notes) }}</textarea>

                    <button type="submit"
                        class="w-full rounded-xl bg-gray-900 px-4 py-2.5 font-medium text-white hover:bg-black">
                        Save Notes
                    </button>

                    @if ($jobPost->notes_updated_at)
                        <div class="text-xs text-gray-500">
                            Last updated: {{ optional($jobPost->notes_updated_at)->format('Y-m-d H:i') }}
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
@endsection