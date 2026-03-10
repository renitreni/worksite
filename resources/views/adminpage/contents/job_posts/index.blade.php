@extends('adminpage.layout')
@section('title', 'Job Posts')
@section('page_title', 'Manage Jobs')

@section('content')

    <div class="space-y-6">

        @include('adminpage.components.flash')

        {{-- Filters --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

            <form method="GET" action="{{ route('admin.job-posts.index') }}"
                class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">

                <div class="flex flex-col gap-2 sm:flex-row sm:flex-wrap sm:items-center">

                    {{-- Search --}}
                    <div class="w-full sm:w-96">
                        <div class="flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2">

                            <i data-lucide="search" class="w-4 h-4 text-slate-400"></i>

                            <input name="q" value="{{ $q ?? '' }}"
                                class="w-full bg-transparent text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none"
                                placeholder="Search jobs">

                        </div>
                    </div>


                    {{-- Status --}}
                    <select name="status"
                        class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100">

                        <option value="">Status</option>
                        <option value="open" {{ ($status ?? '') === 'open' ? 'selected' : '' }}>Open</option>
                        <option value="closed" {{ ($status ?? '') === 'closed' ? 'selected' : '' }}>Closed</option>

                    </select>


                    {{-- Held --}}
                    <select name="held"
                        class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100">

                        <option value="">Held</option>
                        <option value="1" {{ ($held ?? '') === '1' ? 'selected' : '' }}>Held</option>
                        <option value="0" {{ ($held ?? '') === '0' ? 'selected' : '' }}>Not held</option>

                    </select>


                    {{-- Disabled --}}
                    <select name="disabled"
                        class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100">

                        <option value="">Disabled</option>
                        <option value="1" {{ ($disabled ?? '') === '1' ? 'selected' : '' }}>Disabled</option>
                        <option value="0" {{ ($disabled ?? '') === '0' ? 'selected' : '' }}>Enabled</option>

                    </select>


                    {{-- Apply --}}
                    <button type="submit"
                        class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">

                        Apply

                    </button>


                    {{-- Reset --}}
                    <a href="{{ route('admin.job-posts.index') }}"
                        class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50">

                        Reset

                    </a>

                </div>


                <div class="text-sm text-slate-500">

                    Showing {{ $jobPosts->firstItem() ?? 0 }}
                    – {{ $jobPosts->lastItem() ?? 0 }}
                    of {{ $jobPosts->total() }}

                </div>

            </form>

        </div>



        {{-- Table --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 p-5">

                <div class="text-sm font-semibold">
                    Job Posts
                </div>

                <div class="mt-1 text-xs text-slate-500">
                    Employer job postings queue
                </div>

            </div>


            <div class="overflow-x-auto">

                <table class="w-full text-left text-sm">

                    <thead class="bg-slate-50 text-xs font-semibold text-slate-600">

                        <tr>
                            <th class="px-5 py-3">Job</th>
                            <th class="px-5 py-3">Location</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3">Held</th>
                            <th class="px-5 py-3">Disabled</th>
                            <th class="px-5 py-3">Posted</th>
                            <th class="px-5 py-3">Actions</th>
                        </tr>

                    </thead>


                    <tbody class="divide-y divide-slate-200">

                        @forelse($jobPosts as $jp)
                            <tr class="hover:bg-slate-50">

                                <td class="px-5 py-4">

                                    <div class="font-semibold text-slate-900">
                                        {{ $jp->title }}
                                    </div>

                                    <div class="text-xs text-slate-500">
                                        {{ $jp->industry ?? '—' }}
                                    </div>

                                </td>


                                <td class="px-5 py-4 text-slate-700">

                                    {{ $jp->country ?? '—' }}
                                    {{ $jp->city ? ', ' . $jp->city : '' }}
                                    {{ $jp->area ? ', ' . $jp->area : '' }}

                                </td>


                                {{-- Status --}}
                                <td class="px-5 py-4">

                                    @if ($jp->status === 'open')
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

                                </td>


                                {{-- Held --}}
                                <td class="px-5 py-4">

                                    @if ($jp->is_held)
                                        <span
                                            class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1 bg-amber-50 text-amber-800 ring-amber-200">
                                            Held
                                        </span>
                                    @else
                                        <span class="text-slate-400 text-xs">—</span>
                                    @endif

                                </td>


                                {{-- Disabled --}}
                                <td class="px-5 py-4">

                                    @if ($jp->is_disabled)
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

                                </td>


                                <td class="px-5 py-4 text-slate-700">

                                    {{ optional($jp->posted_at)->format('Y-m-d') ?? (optional($jp->created_at)->format('Y-m-d') ?? '—') }}

                                </td>


                                {{-- Actions --}}
                                <td class="px-5 py-4">

                                    <div class="flex items-center gap-2">

                                        <a href="{{ route('admin.job-posts.show', $jp) }}"
                                            class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold hover:bg-slate-50">

                                            View

                                        </a>


                                        @if (!$jp->is_held)
                                            <form method="POST" action="{{ route('admin.job-posts.hold', $jp) }}">
                                                @csrf
                                                @method('PATCH')

                                                <button
                                                    class="rounded-xl border border-amber-200 bg-amber-50 px-3 py-2 text-xs font-semibold text-amber-800 hover:bg-amber-100">

                                                    Hold

                                                </button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('admin.job-posts.unhold', $jp) }}">
                                                @csrf
                                                @method('PATCH')

                                                <button
                                                    class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold hover:bg-slate-50">

                                                    Unhold

                                                </button>
                                            </form>
                                        @endif


                                        @if (!$jp->is_disabled)
                                            <a href="{{ route('admin.job-posts.show', $jp) }}#disable"
                                                class="rounded-xl bg-rose-600 px-3 py-2 text-xs font-semibold text-white hover:bg-rose-700">

                                                Disable

                                            </a>
                                        @else
                                            <form method="POST" action="{{ route('admin.job-posts.enable', $jp) }}">
                                                @csrf
                                                @method('PATCH')

                                                <button
                                                    class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold hover:bg-slate-50">

                                                    Enable

                                                </button>
                                            </form>
                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7" class="px-5 py-10 text-center text-sm text-slate-500">
                                    No job posts found.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>


            <div class="border-t border-slate-200 p-4">

                {{ $jobPosts->links() }}

            </div>

        </div>

    </div>

@endsection
