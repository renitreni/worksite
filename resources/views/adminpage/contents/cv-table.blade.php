@extends('adminpage.layout')
@section('title', 'Candidate CVs')
@section('page_title', 'Candidate CVs')

@section('content')

    <div class="space-y-6" x-data="cvUI()" x-cloak>

        {{-- HEADER --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex justify-between items-center">
                <div class="text-sm text-slate-500">
                    Total CVs:
                    <span class="font-semibold text-slate-900">
                        {{ $resumes->total() }}
                    </span>
                </div>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 p-5">
                <div class="text-sm font-semibold">Candidate Resumes</div>
                <div class="text-xs text-slate-500">Unified CV + Applications view</div>
            </div>

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

                        @forelse ($resumes as $resume)

                            @php
                                $user = $resume->user;
                                $applications = $user?->jobApplications ?? collect();
                                $hasCv = $resume->resume_path && Storage::disk('public')->exists($resume->resume_path);
                            @endphp

                            <tr class="hover:bg-slate-50 align-top">

                                {{-- USER --}}
                                <td class="px-5 py-4">
                                    <div class="font-semibold text-slate-900">
                                        {{ $user->name ?? '—' }}
                                    </div>
                                    <div class="text-xs text-slate-500">
                                        {{ $user->email ?? '—' }}
                                    </div>
                                </td>

                                {{-- APPLICATIONS --}}
                                <td class="px-5 py-4">

                                    @if ($applications->count())
                                        <div class="flex flex-col gap-1">

                                            @foreach ($applications as $app)
                                                <div class="text-sm text-slate-900 font-semibold">
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

                                {{-- DATE --}}
                                <td class="px-5 py-4">
                                    @if ($hasCv)
                                        {{ $resume->created_at->format('Y-m-d') }}
                                    @else
                                        <span class="text-xs text-rose-500 font-semibold">
                                            Removed
                                        </span>
                                    @endif
                                </td>

                                {{-- CV --}}
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
