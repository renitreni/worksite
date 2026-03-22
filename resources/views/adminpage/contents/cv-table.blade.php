@extends('adminpage.layout')
@section('title', 'Candidate CVs')
@section('page_title', 'Candidate CVs')

@section('content')

    <div class="space-y-6" x-data="cvUI()" x-cloak>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

            {{-- TOTAL CV --}}
            <div class="rounded-2xl bg-white p-5 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between">

                    <div>
                        <div class="text-xs text-slate-500">Total CV Uploaded</div>
                        <div class="text-2xl font-bold text-slate-900 mt-1">
                            {{ $stats['total_cv'] }}
                        </div>
                    </div>

                    <div class="rounded-xl bg-slate-100 p-3">
                        <x-lucide-icon name="file-text" class="h-5 w-5 text-slate-600" />
                    </div>

                </div>
            </div>

            {{-- APPLIED WITH CV --}}
            <div class="rounded-2xl bg-white p-5 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between">

                    <div>
                        <div class="text-xs text-slate-500">Applied (With CV)</div>
                        <div class="text-2xl font-bold text-emerald-600 mt-1">
                            {{ $stats['applied_with_cv'] }}
                        </div>
                    </div>

                    <div class="rounded-xl bg-emerald-50 p-3">
                        <x-lucide-icon name="check-circle" class="h-5 w-5 text-emerald-600" />
                    </div>

                </div>
            </div>

            {{-- APPLIED BUT REMOVED --}}
            <div class="rounded-2xl bg-white p-5 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between">

                    <div>
                        <div class="text-xs text-slate-500">Applied (CV Removed)</div>
                        <div class="text-2xl font-bold text-rose-500 mt-1">
                            {{ $stats['applied_removed_cv'] }}
                        </div>
                    </div>

                    <div class="rounded-xl bg-rose-50 p-3">
                        <x-lucide-icon name="alert-circle" class="h-5 w-5 text-rose-500" />
                    </div>

                </div>
            </div>

        </div>

        {{-- 🔥 TABLE --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 p-5">
                <div class="text-sm font-semibold">Candidate Resumes</div>
                <div class="text-xs text-slate-500">Unified CV + Applications view</div>
            </div>

            @php
                // ✅ FILTER OUT useless rows
                $filtered = $resumes->filter(function ($resume) {
                    $hasCv = $resume->resume_path && Storage::disk('public')->exists($resume->resume_path);
                    $hasApplication = $resume->user?->jobApplications?->count() > 0;
                    return $hasCv || $hasApplication;
                });
            @endphp

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">

                    <thead class="bg-slate-50 text-xs font-semibold text-slate-600">
                        <tr>
                            <th class="px-5 py-3">Candidate</th>
                            <th class="px-5 py-3">Applications</th>
                            <th class="px-5 py-3">Uploaded</th>
                            <th class="px-5 py-3">CV</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200">

                        @forelse ($filtered as $resume)

                            @php
                                $user = $resume->user;
                                $applications = $user?->jobApplications ?? collect();

                                $hasCv = $resume->resume_path && Storage::disk('public')->exists($resume->resume_path);
                            @endphp

                            <tr class="hover:bg-slate-50 align-top">

                                {{-- ✅ USER --}}
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-2">

                                        <div class="font-semibold text-slate-900">
                                            {{ $user->name ?? '—' }}
                                        </div>

                                        {{-- STATUS BADGE --}}
                                        @if ($hasCv && $applications->count())
                                            <span class="text-xs bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded-full">
                                                Applied
                                            </span>
                                        @elseif ($hasCv)
                                            <span class="text-xs bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full">
                                                CV Only
                                            </span>
                                        @elseif ($applications->count())
                                            <span class="text-xs bg-rose-50 text-rose-600 px-2 py-0.5 rounded-full">
                                                CV Removed
                                            </span>
                                        @endif

                                    </div>

                                    <div class="text-xs text-slate-500">
                                        {{ $user->email ?? '—' }}
                                    </div>
                                </td>

                                {{-- ✅ APPLICATIONS --}}
                                <td class="px-5 py-4">

                                    @if ($applications->count())
                                        <div class="flex flex-col gap-1">

                                            @foreach ($applications as $app)
                                                <div class="text-sm font-semibold text-slate-900">
                                                    {{ $app->jobPost->title ?? 'No job title' }}
                                                </div>

                                                <div class="text-xs text-slate-500">
                                                    {{ $app->jobPost?->employerProfile?->company_name ?? 'No employer' }}
                                                </div>

                                                @if (!$loop->last)
                                                    <div class="h-px bg-slate-100 my-1"></div>
                                                @endif
                                            @endforeach

                                        </div>
                                    @else
                                        <span class="text-xs text-slate-400">No applications</span>
                                    @endif

                                </td>

                                {{-- ✅ DATE --}}
                                <td class="px-5 py-4">
                                    @if ($hasCv)
                                        {{ $resume->created_at->format('Y-m-d') }}
                                    @else
                                        <span class="text-xs text-rose-500 font-semibold">
                                            Removed
                                        </span>
                                    @endif
                                </td>

                                {{-- ✅ CV --}}
                                <td class="px-5 py-4">
                                    @if ($hasCv)
                                        <a href="{{ asset('storage/' . $resume->resume_path) }}" target="_blank"
                                            class="rounded-lg border px-3 py-1.5 text-xs font-semibold hover:bg-slate-50">
                                            View CV
                                        </a>
                                    @else
                                        <button @click="openMissingCv('{{ $user->name ?? 'Candidate' }}')"
                                            class="rounded-lg bg-rose-50 text-rose-600 px-3 py-1.5 text-xs font-semibold hover:bg-rose-100">
                                            Missing CV
                                        </button>
                                    @endif
                                </td>

                            </tr>

                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-10 text-center text-sm text-slate-500">
                                    No resumes found.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>
            </div>

            {{-- PAGINATION --}}
            <div class="border-t border-slate-200 p-4">
                {{ $resumes->links() }}
            </div>

        </div>

        {{-- MODAL --}}
        <div x-show="missingCvOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">

            <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">

                <h3 class="text-lg font-semibold text-slate-900">
                    CV Not Available
                </h3>

                <p class="mt-2 text-sm text-slate-600">
                    The CV for <span class="font-semibold" x-text="selectedName"></span>
                    has been removed or deleted by the candidate.
                </p>

                <div class="mt-6 flex justify-end">
                    <button @click="missingCvOpen = false"
                        class="rounded-xl border px-4 py-2 text-sm font-semibold hover:bg-slate-50">
                        Close
                    </button>
                </div>

            </div>

        </div>

    </div>

    <script>
        function cvUI() {
            return {
                missingCvOpen: false,
                selectedName: '',

                openMissingCv(name) {
                    this.selectedName = name;
                    this.missingCvOpen = true;
                }
            }
        }
    </script>

@endsection
