@extends('candidate.layout')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-xl sm:text-2xl font-semibold text-gray-900">My Meetings</h1>
    </div>

    {{-- Tabs --}}
    <div class="border-b border-gray-200">
        <div class="flex items-center gap-10">
            <button type="button" class="relative pb-4 text-sm font-semibold text-blue-600">
                <span>Upcoming</span>
                <span class="ml-2 inline-flex items-center justify-center rounded-full bg-blue-100 text-blue-700 text-xs font-semibold px-2 py-0.5">1</span>
                <span class="absolute left-0 -bottom-[1px] h-0.5 w-full bg-blue-600 rounded-full"></span>
            </button>

            <button type="button" class="pb-4 text-sm font-semibold text-gray-500 hover:text-gray-700 transition">
                <span>Past Meetings</span>
                <span class="ml-2 inline-flex items-center justify-center rounded-full bg-gray-100 text-gray-700 text-xs font-semibold px-2 py-0.5">1</span>
            </button>
        </div>
    </div>

    {{-- Meeting card --}}
    <div class="rounded-2xl bg-white border border-gray-200 shadow-sm">
        <div class="p-5 sm:p-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
            <div class="flex items-center gap-4 min-w-0">
                {{-- Date --}}
                <div class="h-14 w-14 rounded-2xl bg-blue-50 border border-blue-100 flex flex-col items-center justify-center leading-none">
                    <span class="text-xs font-semibold text-blue-600">Oct</span>
                    <span class="text-lg font-extrabold text-blue-700">24</span>
                </div>

                {{-- Details --}}
                <div class="min-w-0">
                    <p class="text-base font-semibold text-gray-900 truncate">Technical Interview</p>
                    <a href="#" class="text-sm font-semibold text-blue-600 hover:text-blue-700 transition">with TechFlow</a>

                    <div class="mt-2 flex flex-wrap items-center gap-4 text-sm text-gray-500">
                        <div class="inline-flex items-center gap-2">
                            <i data-lucide="clock" class="h-4 w-4"></i>
                            <span>2:00 PM - 3:00 PM</span>
                        </div>
                        <div class="inline-flex items-center gap-2">
                            <i data-lucide="video" class="h-4 w-4"></i>
                            <span>Video Call</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3 lg:justify-end">
                <button type="button"
                    class="inline-flex items-center justify-center rounded-2xl border border-red-200 bg-white px-5 py-2.5 text-sm font-semibold text-red-600 hover:bg-red-50 transition">
                    Cancel
                </button>

                <button type="button"
                    class="inline-flex items-center gap-2 rounded-2xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700 transition">
                    <span>Join Meeting</span>
                    <i data-lucide="external-link" class="h-4 w-4"></i>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection