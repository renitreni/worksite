@extends('candidate.layout')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex items-start sm:items-center justify-between gap-4 flex-col sm:flex-row">
            <h1 class="text-xl sm:text-2xl font-semibold text-gray-900">My Resume</h1>
        </div>

        {{-- Toast (floating) --}}
<div
    x-data="{
        show: @js(session()->has('success') || session()->has('danger')),
        type: @js(session('success') ? 'success' : (session('danger') ? 'danger' : '')),
        message: @js(session('success') ?? session('danger') ?? ''),
        init() {
            if (this.show) setTimeout(() => this.show = false, 3500);
            this.$nextTick(() => { if (window.lucide) window.lucide.createIcons(); });
        }
    }"
    x-init="init()"
    x-show="show"
    x-transition.opacity.duration.200ms
    x-cloak
    class="fixed top-5 right-5 z-[9999] w-[92vw] max-w-sm"
>
    <div
        class="rounded-2xl border shadow-lg p-4 text-sm flex items-start gap-3"
        :class="type === 'success'
            ? 'bg-emerald-50 border-emerald-200 text-emerald-800'
            : 'bg-red-50 border-red-200 text-red-800'"
    >
        <div class="mt-0.5">
            <i data-lucide="info" class="h-4 w-4"></i>
        </div>

        <div class="flex-1">
            <p class="font-semibold" x-text="type === 'success' ? 'Success' : 'Removed'"></p>
            <p class="mt-0.5" x-text="message"></p>
        </div>

        <button
            type="button"
            class="text-xs underline opacity-80 hover:opacity-100"
            @click="show=false"
        >
            Close
        </button>
    </div>
</div>


        

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            {{-- Left column --}}
            <div class="lg:col-span-5 space-y-6">

                {{-- CV Upload --}}
                <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-6">
                    <h2 class="text-sm font-semibold text-gray-900">CV / Resume (PDF / Word)</h2>

                    <form class="mt-4 space-y-3" action="{{ route('candidate.resume.upload') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="resume" accept=".pdf,.doc,.docx"
                            class="block w-full text-sm file:mr-4 file:rounded-xl file:border-0 file:bg-emerald-600 file:px-4 file:py-2 file:font-semibold file:text-white hover:file:bg-emerald-700"
                            required>
                        <button type="submit"
                            class="w-full rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700 transition">
                            Upload Resume
                        </button>
                        <p class="text-xs text-gray-500">Max 5MB</p>
                    </form>

                    <div class="mt-5">
                        @if($resume->resume_path)
                            <div
                                class="rounded-2xl border border-gray-200 bg-white p-4 flex items-center justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $resume->resume_original_name }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ number_format(($resume->resume_size ?? 0) / 1024, 1) }} KB •
                                        {{ $resume->resume_mime }}
                                    </p>
                                    <a class="text-xs font-semibold text-emerald-700 underline"
                                        href="{{ asset('storage/' . $resume->resume_path) }}" target="_blank">
                                        View
                                    </a>
                                    @php
    $resumeUrl = asset('storage/' . $resume->resume_path);
    $resumeMime = strtolower($resume->resume_mime ?? '');
@endphp

@if($resumeMime === 'application/pdf')
    <div class="mt-4 rounded-xl border border-gray-200 bg-gray-50 overflow-hidden">
        <iframe src="{{ $resumeUrl }}#toolbar=0"
            class="w-full h-80"
            title="Resume Preview"></iframe>
    </div>
@else
    <div class="mt-4 rounded-xl border border-gray-200 bg-gray-50 p-4 text-sm text-gray-600">
        Preview available only for PDF.
    </div>
@endif

                                </div>

                                <form action="{{ route('candidate.resume.delete') }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex h-10 items-center justify-center rounded-xl border border-gray-200 px-4 text-sm font-semibold hover:bg-gray-50">
                                        Remove
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4 text-sm text-gray-600">
                                No uploaded resume yet.
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Attachments (replaces Skills) --}}
                <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-sm font-semibold text-gray-900">Document Attachments</h2>
                    </div>

                    <form class="mt-4 space-y-3" action="{{ route('candidate.resume.attachments.upload') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <label class="text-sm font-semibold text-gray-900">Category</label>
                        <select name="category" required
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300">
                            <option value="">Select category</option>
                            <optgroup label="School Documents">
                                <option>College diploma</option>
                                <option>High school diploma</option>
                                <option>Senior high diploma</option>
                                <option>Transcript of Records (TOR)</option>
                                <option>Vocational / TESDA certificate</option>
                            </optgroup>
                            <optgroup label="IDs">
                                <option>Passport</option>
                                <option>UMID</option>
                                <option>Driver’s License</option>
                                <option>National ID</option>
                            </optgroup>
                            <optgroup label="Employment & Qualification Proof">
                                <option>Certificate of Employment (COE)</option>
                                <option>PRC License</option>
                                <option>TESDA NC II / III</option>
                                <option>Other qualification certificates</option>
                            </optgroup>
                            <optgroup label="Medical & Legal">
                                <option>Fit to Work / Medical Certificate</option>
                                <option>NBI Clearance</option>
                                <option>Police Clearance</option>
                            </optgroup>
                        </select>

                        <label class="text-sm font-semibold text-gray-900">Upload file(s)</label>
                        <input type="file" name="files[]" multiple required accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                            class="block w-full text-sm file:mr-4 file:rounded-xl file:border-0 file:bg-gray-900 file:px-4 file:py-2 file:font-semibold file:text-white hover:file:bg-black">

                        <button type="submit"
                            class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                            Upload Attachment(s)
                        </button>

                        <p class="text-xs text-gray-500">Max 5MB each. Allowed: PDF/DOC/DOCX/JPG/PNG</p>
                    </form>

                    <div class="mt-5 space-y-3">
                        @if($resume->attachments->count() === 0)
                            <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4 text-sm text-gray-600">
                                No attachments uploaded yet.
                            </div>
                        @else
                            @foreach($resume->attachments as $att)
                                @php
                                    $url = asset('storage/' . $att->file_path);
                                    $mime = strtolower($att->mime ?? '');
                                    $isImage = str_starts_with($mime, 'image/');
                                    $isPdf = $mime === 'application/pdf';
                                @endphp

                                <div class="rounded-2xl border border-gray-200 bg-white p-4 space-y-3">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <p class="text-xs font-semibold text-gray-500">{{ $att->category }}</p>
                                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $att->original_name }}</p>
                                            <p class="text-xs text-gray-500">
                                                {{ number_format(($att->size ?? 0) / 1024, 1) }} KB • {{ $att->mime }}
                                            </p>

                                            <div class="mt-2 flex items-center gap-3">
                                                <a class="text-xs font-semibold text-blue-700 underline" href="{{ $url }}"
                                                    target="_blank">
                                                    Open
                                                </a>
                                                <a class="text-xs font-semibold text-gray-700 underline" href="{{ $url }}" download>
                                                    Download
                                                </a>
                                            </div>
                                        </div>

                                        <form action="{{ route('candidate.resume.attachments.delete', $att->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex h-10 items-center justify-center rounded-xl border border-gray-200 px-4 text-sm font-semibold hover:bg-gray-50">
                                                Delete
                                            </button>
                                        </form>
                                    </div>

                                    {{-- PREVIEW --}}
                                    @if($isImage)
                                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-2">
                                            <img src="{{ $url }}" class="w-full max-h-72 object-contain rounded-lg">
                                        </div>
                                    @elseif($isPdf)
                                        <div class="rounded-xl border border-gray-200 bg-gray-50 overflow-hidden">
                                            <iframe src="{{ $url }}#toolbar=0" class="w-full h-72" title="PDF Preview"></iframe>
                                        </div>
                                    @else
                                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 text-sm text-gray-600">
                                            Preview not available. Click Open.
                                        </div>
                                    @endif
                                </div>
                            @endforeach

                        @endif
                    </div>
                </div>

            </div>

            {{-- Right column --}}
            <div class="lg:col-span-7 space-y-6">

                {{-- Work Experience --}}
                <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-sm font-semibold text-gray-900">Work Experience</h2>
                    </div>

                    <form class="mt-4 space-y-4" action="{{ route('candidate.resume.exp.store') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label for="exp_role" class="text-xs font-semibold text-gray-700">Position/Role</label>
                                <input id="exp_role" name="role" required
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300">
                            </div>

                            <div class="space-y-1">
                                <label for="exp_company" class="text-xs font-semibold text-gray-700">Company</label>
                                <input id="exp_company" name="company" required
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label for="exp_start" class="text-xs font-semibold text-gray-700">Start</label>
                                <input id="exp_start" name="start"
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300">
                            </div>

                            <div class="space-y-1">
                                <label for="exp_end" class="text-xs font-semibold text-gray-700">End</label>
                                <input id="exp_end" name="end"
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300">
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label for="exp_description" class="text-xs font-semibold text-gray-700">Description</label>
                            <textarea id="exp_description" name="description" rows="3"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300"></textarea>
                        </div>

                        <button
                            class="rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700">
                            Add Experience
                        </button>
                    </form>


                    <div class="mt-6 space-y-4">
                        @if($resume->experiences->count() === 0)
                            <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4 text-sm text-gray-600">
                                No experience yet.
                            </div>
                        @else
                            @foreach($resume->experiences as $exp)
                                <div class="rounded-2xl border border-gray-200 bg-white p-4">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-gray-900">{{ $exp->role }}</p>
                                            <p class="text-sm font-semibold text-blue-600 mt-1">{{ $exp->company }}</p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                {{ $exp->start ?? '—' }} - {{ $exp->end ?? '—' }}
                                            </p>
                                            <p class="text-sm text-gray-600 mt-3">{{ $exp->description ?? '—' }}</p>
                                        </div>

                                        <form action="{{ route('candidate.resume.exp.delete', $exp->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                class="rounded-xl border border-gray-200 px-4 py-2 text-sm font-semibold hover:bg-gray-50">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                {{-- Education --}}
                <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-sm font-semibold text-gray-900">Education</h2>
                    </div>

                    <form class="mt-4 space-y-4" action="{{ route('candidate.resume.edu.store') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label for="edu_degree" class="text-xs font-semibold text-gray-700">Degree</label>
                                <input id="edu_degree" name="degree" required
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300">
                            </div>

                            <div class="space-y-1">
                                <label for="edu_school" class="text-xs font-semibold text-gray-700">School</label>
                                <input id="edu_school" name="school" required
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label for="edu_year" class="text-xs font-semibold text-gray-700">Year</label>
                                <input id="edu_year" name="year"
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300">
                            </div>

                            <div class="space-y-1">
                                <label for="edu_notes" class="text-xs font-semibold text-gray-700">Achievements</label>
                                <input id="edu_notes" name="notes"
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300">
                            </div>
                        </div>

                        <button
                            class="rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700">
                            Add Education
                        </button>
                    </form>

                    <div class="mt-6 space-y-4">
                        @if($resume->educations->count() === 0)
                            <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4 text-sm text-gray-600">
                                No education yet.
                            </div>
                        @else
                            @foreach($resume->educations as $edu)
                                <div class="rounded-2xl border border-gray-200 bg-white p-4">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-gray-900">{{ $edu->degree }}</p>
                                            <p class="text-sm font-semibold text-emerald-600 mt-1">{{ $edu->school }}</p>
                                            <p class="text-xs text-gray-500 mt-1">{{ $edu->year ?? '—' }}</p>
                                            <p class="text-sm text-gray-600 mt-3">{{ $edu->notes ?? '—' }}</p>
                                        </div>

                                        <form action="{{ route('candidate.resume.edu.delete', $edu->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                class="rounded-xl border border-gray-200 px-4 py-2 text-sm font-semibold hover:bg-gray-50">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection