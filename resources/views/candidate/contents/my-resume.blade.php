@extends('candidate.layout')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-start sm:items-center justify-between gap-4 flex-col sm:flex-row">
        <h1 class="text-xl sm:text-2xl font-semibold text-gray-900">My Resume</h1>

        <button type="button"
            class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700 transition">
            <i data-lucide="file-text" class="h-4 w-4"></i>
            Preview Resume
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        {{-- Left column --}}
        <div class="lg:col-span-5 space-y-6">
            {{-- CV Upload --}}
            <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-6">
                <h2 class="text-sm font-semibold text-gray-900">CV / Resume</h2>

                <div class="mt-4 rounded-2xl border border-dashed border-gray-300 bg-gray-50 p-6 text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-white border border-gray-200">
                        <i data-lucide="upload-cloud" class="h-6 w-6 text-gray-500"></i>
                    </div>
                    <p class="mt-3 text-sm font-semibold text-gray-800">Click to upload or drag and drop</p>
                    <p class="mt-1 text-xs text-gray-500">PDF, DOC, DOCX (Max 5MB)</p>
                </div>

                {{-- Uploaded file --}}
                <div class="mt-5 rounded-2xl border border-gray-200 bg-white p-4 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="h-10 w-10 rounded-2xl bg-red-50 border border-red-100 flex items-center justify-center">
                            <i data-lucide="file" class="h-5 w-5 text-red-500"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-gray-900 truncate">Keith_CV_2023.pdf</p>
                            <p class="text-xs text-gray-500">2.4 MB • Uploaded 2 days ago</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <button class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 hover:bg-gray-50" title="Download">
                            <i data-lucide="download" class="h-5 w-5 text-gray-600"></i>
                        </button>
                        <button class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 hover:bg-gray-50" title="Delete">
                            <i data-lucide="trash-2" class="h-5 w-5 text-gray-600"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Skills --}}
            <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-gray-900">Skills</h2>
                    <button class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-gray-900">
                        <i data-lucide="plus" class="h-4 w-4"></i> Add
                    </button>
                </div>

                <div class="mt-4 flex flex-wrap gap-2">
                    @foreach (['Figma', 'React', 'TypeScript', 'User Research', 'Prototyping', 'Tailwind CSS', 'Next.js'] as $skill)
                        <span class="inline-flex items-center rounded-full bg-gray-100 border border-gray-200 px-3 py-1 text-xs font-semibold text-gray-700">
                            {{ $skill }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Right column --}}
        <div class="lg:col-span-7 space-y-6">
            {{-- Work Experience --}}
            <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-gray-900">Work Experience</h2>
                    <button class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                        <i data-lucide="plus" class="h-4 w-4"></i> Add Experience
                    </button>
                </div>

                <div class="mt-5 space-y-6">
                    {{-- Experience item --}}
                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-gray-900">Senior Product Designer</p>
                            <p class="text-sm font-semibold text-blue-600 mt-1">TechFlow Inc.</p>
                            <p class="text-xs text-gray-500 mt-1">Jan 2021 - Present • 2 yrs 9 mos</p>
                            <p class="text-sm text-gray-600 mt-3 leading-relaxed">
                                Leading the design system initiative and managing a team of 3 designers.
                                Responsible for the core product experience and user research.
                            </p>
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            <button class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 hover:bg-gray-50" title="Edit">
                                <i data-lucide="pencil" class="h-4 w-4 text-gray-600"></i>
                            </button>
                            <button class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 hover:bg-gray-50" title="Delete">
                                <i data-lucide="trash-2" class="h-4 w-4 text-gray-600"></i>
                            </button>
                        </div>
                    </div>

                    <div class="h-px bg-gray-100"></div>

                    {{-- Experience item 2 --}}
                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-gray-900">Senior Product Designer</p>
                            <p class="text-sm font-semibold text-blue-600 mt-1">TechFlow Inc.</p>
                            <p class="text-xs text-gray-500 mt-1">Jan 2021 - Present • 2 yrs 9 mos</p>
                            <p class="text-sm text-gray-600 mt-3 leading-relaxed">
                                Leading the design system initiative and managing a team of 3 designers.
                                Responsible for the core product experience and user research.
                            </p>
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            <button class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 hover:bg-gray-50" title="Edit">
                                <i data-lucide="pencil" class="h-4 w-4 text-gray-600"></i>
                            </button>
                            <button class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 hover:bg-gray-50" title="Delete">
                                <i data-lucide="trash-2" class="h-4 w-4 text-gray-600"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Education --}}
            <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-gray-900">Education</h2>
                    <button class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                        <i data-lucide="plus" class="h-4 w-4"></i> Add Education
                    </button>
                </div>

                <div class="mt-5 space-y-5">
                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-gray-900">Master of Interaction Design</p>
                            <p class="text-sm font-semibold text-emerald-600 mt-1">California College of the Arts</p>
                            <p class="text-xs text-gray-500 mt-1">2018 - 2020</p>
                            <p class="text-sm text-gray-600 mt-3 leading-relaxed">
                                Focus on human-computer interaction and user research methodologies.
                            </p>
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            <button class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 hover:bg-gray-50" title="Edit">
                                <i data-lucide="pencil" class="h-4 w-4 text-gray-600"></i>
                            </button>
                            <button class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 hover:bg-gray-50" title="Delete">
                                <i data-lucide="trash-2" class="h-4 w-4 text-gray-600"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection