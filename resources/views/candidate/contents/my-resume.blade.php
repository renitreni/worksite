@extends('candidate.layout')

@section('content')

    <div class="max-w-7xl mx-auto space-y-8">

        {{-- PAGE HEADER --}}
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">My Resume</h1>
            <p class="text-sm text-gray-500">
                Upload your main resume and supporting documents to apply for jobs.
            </p>
        </div>

        {{-- SUCCESS / ERROR TOAST --}}

        <div x-data="{
            show: @js(session()->has('success') || session()->has('danger')),
            type: @js(session('success') ? 'success' : (session('danger') ? 'danger' : '')),
            message: @js(session('success') ?? (session('danger') ?? '')),
            init() {
                if (this.show) setTimeout(() => this.show = false, 3500);
            }
        }" x-init="init()" x-show="show" x-transition.opacity x-cloak
            class="fixed top-5 right-5 z-[9999] w-[92vw] max-w-sm">

            <div class="rounded-2xl border shadow-lg p-4 text-sm flex items-start gap-3"
                :class="type === 'success' ?
                    'bg-emerald-50 border-emerald-200 text-emerald-800' :
                    'bg-red-50 border-red-200 text-red-800'">

                <div class="flex-1">
                    <p class="font-semibold" x-text="type==='success' ? 'Success' : 'Deleted'"></p>
                    <p class="text-sm" x-text="message"></p>
                </div>

                <button class="text-xs underline" @click="show=false">
                    Close </button>

            </div>
        </div>

        {{-- MAIN RESUME SECTION --}}

        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">

            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Primary Resume</h2>
                    <p class="text-xs text-gray-500">
                        This is the main document employers will view.
                    </p>
                </div>
            </div>

            {{-- UPLOAD FORM --}}

            <form action="{{ route('candidate.resume.upload') }}" method="POST" enctype="multipart/form-data"
                class="space-y-4" x-data="{ fileName: '' }">

                @csrf

                <div class="space-y-2">

                    <label class="text-sm font-medium text-gray-700">
                        Choose Resume File
                    </label>

                    <input type="file" name="resume" accept=".pdf,.doc,.docx" required
                        @change="fileName = $event.target.files[0].name"
                        class="w-full text-sm border border-gray-300 rounded-xl px-3 py-2 bg-white
file:mr-4
file:rounded-lg
file:border
file:border-gray-300
file:bg-gray-100
file:px-3
file:py-1.5
file:text-sm
file:font-medium
file:text-gray-700
hover:file:bg-gray-200" />

                    <p x-show="fileName" class="text-xs text-gray-500">
                        Selected file: <span x-text="fileName"></span>
                    </p>

                </div>

                <button type="submit"
                    class="rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700 transition">

                    Upload Resume </button>

                <p class="text-xs text-gray-500">
                    PDF or Word • Max 5MB
                </p>

            </form>

            {{-- RESUME PREVIEW --}}

            <div class="mt-6">

                @if ($resume->resume_path)
                    @php
                        $resumeUrl = asset('storage/' . $resume->resume_path);
                        $resumeMime = strtolower($resume->resume_mime ?? '');
                    @endphp

                    <div class="border border-gray-200 rounded-xl overflow-hidden">

                        <div class="flex items-center justify-between p-4 bg-gray-50 border-b">

                            <div>
                                <p class="text-sm font-semibold text-gray-900">
                                    {{ $resume->resume_original_name }}
                                </p>

                                <p class="text-xs text-gray-500">
                                    {{ number_format(($resume->resume_size ?? 0) / 1024, 1) }} KB
                                </p>
                            </div>

                            <form action="{{ route('candidate.resume.delete') }}" method="POST">
                                @csrf
                                @method('DELETE')

                                <button class="text-xs border border-gray-200 px-3 py-1 rounded-lg hover:bg-gray-100">
                                    Remove
                                </button>

                            </form>

                        </div>

                        @if ($resumeMime === 'application/pdf')
                            <iframe src="{{ $resumeUrl }}#toolbar=0" class="w-full h-[500px]"></iframe>
                        @else
                            <div class="p-10 text-center text-sm text-gray-500">

                                Preview available only for PDF.

                                <div class="mt-3">
                                    <a href="{{ $resumeUrl }}" target="_blank"
                                        class="text-blue-600 underline font-semibold">
                                        Open Document
                                    </a>
                                </div>

                            </div>
                        @endif

                    </div>
                @else
                    <div class="border border-dashed border-gray-300 rounded-xl p-8 text-center text-sm text-gray-500">
                        No resume uploaded yet.
                    </div>
                @endif

            </div>

        </div>

        {{-- CONTENT GRID --}}

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            {{-- LEFT COLUMN --}}

            <div class="lg:col-span-7 space-y-6">

                {{-- WORK EXPERIENCE --}}

                <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">

                    <h2 class="text-lg font-semibold text-gray-900">
                        Work Experience
                    </h2>

                    <form class="mt-4 space-y-4" action="{{ route('candidate.resume.exp.store') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            <input name="role" required placeholder="Position / Role"
                                class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm">

                            <input name="company" required placeholder="Company"
                                class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm">

                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            <input name="start" placeholder="Start date"
                                class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm">

                            <input name="end" placeholder="End date"
                                class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm">

                        </div>

                        <textarea name="description" rows="3" placeholder="Describe your responsibilities..."
                            class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm"></textarea>

                        <button
                            class="bg-emerald-600 text-white text-sm font-semibold px-4 py-2 rounded-xl hover:bg-emerald-700">

                            Add Experience </button>

                    </form>

                    <div class="mt-6 space-y-4">

                        @foreach ($resume->experiences as $exp)
                            <div class="border border-gray-200 rounded-xl p-4">

                                <div class="flex justify-between">

                                    <div>

                                        <p class="font-semibold text-gray-900">
                                            {{ $exp->role }}
                                        </p>

                                        <p class="text-sm text-blue-600">
                                            {{ $exp->company }}
                                        </p>

                                        <p class="text-xs text-gray-500">
                                            {{ $exp->start }} - {{ $exp->end }}
                                        </p>

                                        <p class="text-sm text-gray-600 mt-2">
                                            {{ $exp->description }}
                                        </p>

                                    </div>

                                    <form action="{{ route('candidate.resume.exp.delete', $exp->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <button class="text-xs border px-3 py-1 rounded-lg hover:bg-gray-100">
                                            Delete
                                        </button>

                                    </form>

                                </div>

                            </div>
                        @endforeach

                    </div>

                </div>

                {{-- EDUCATION --}}

                <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">

                    <h2 class="text-lg font-semibold text-gray-900">
                        Education
                    </h2>

                    <form class="mt-4 space-y-4" action="{{ route('candidate.resume.edu.store') }}" method="POST">
                        @csrf

                        <input name="degree" required placeholder="Degree"
                            class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm">

                        <input name="school" required placeholder="School"
                            class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm">

                        <input name="year" placeholder="Year"
                            class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm">

                        <input name="notes" placeholder="Achievements"
                            class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm">

                        <button
                            class="bg-emerald-600 text-white text-sm font-semibold px-4 py-2 rounded-xl hover:bg-emerald-700">

                            Add Education </button>

                    </form>

                </div>

            </div>

            {{-- RIGHT COLUMN --}}

            <div class="lg:col-span-5 space-y-6">

                {{-- DOCUMENT ATTACHMENTS --}}
                <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">

                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">
                                Supporting Documents
                            </h2>

                            <p class="text-xs text-gray-500">
                                Certificates, IDs, and other proof documents.
                            </p>
                        </div>
                    </div>


                    {{-- UPLOAD FORM --}}
                    <form action="{{ route('candidate.resume.attachments.upload') }}" method="POST"
                        enctype="multipart/form-data" x-data="documentsUpload()" class="mt-5 space-y-4">

                        @csrf


                        {{-- DOCUMENT ROWS --}}
                        <template x-for="(doc,index) in documents" :key="index">

                            <div class="border border-gray-200 rounded-xl p-4 bg-gray-50 space-y-3">

                                {{-- FILE --}}
                                <div>

                                    <label class="text-xs font-medium text-gray-600">
                                        Document File
                                    </label>

                                    <input type="file" :name="'files[' + index + ']'" required
                                        accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                        class="mt-1 w-full text-sm border border-gray-300 rounded-xl px-3 py-2 bg-white
            file:mr-4
            file:rounded-lg
            file:border
            file:border-gray-300
            file:bg-gray-100
            file:px-3
            file:py-1.5
            file:text-sm
            file:font-medium
            file:text-gray-700
            hover:file:bg-gray-200">

                                </div>


                                {{-- CATEGORY --}}
                                <div>

                                    <label class="text-xs font-medium text-gray-600">
                                        Category
                                    </label>

                                    <select :name="'categories[' + index + ']'" x-model="doc.category" required
                                        class="mt-1 w-full border border-gray-300 rounded-xl px-3 py-2 text-sm bg-white">

                                        <option value="">Select category</option>
                                        <option value="Diploma">Diploma</option>
                                        <option value="Transcript of Records">Transcript</option>
                                        <option value="Passport">Passport</option>
                                        <option value="Driver License">Driver License</option>
                                        <option value="NBI Clearance">NBI Clearance</option>
                                        <option value="TESDA Certificate">TESDA Certificate</option>
                                        <option value="other">Other (Specify)</option>

                                    </select>

                                </div>


                                {{-- OTHER FIELD --}}
                                <div x-show="doc.category === 'other'">

                                    <input :name="'categories_custom[' + index + ']'" placeholder="Specify document type"
                                        class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm bg-white">

                                </div>


                                {{-- REMOVE BUTTON --}}
                                <div class="flex justify-end">

                                    <button type="button" @click="remove(index)"
                                        class="text-xs border border-red-300 text-red-600 px-4 py-1.5 rounded-lg hover:bg-red-50">

                                        Remove

                                    </button>

                                </div>

                            </div>

                        </template>

                        {{-- ADD DOCUMENT --}}
                        <button type="button" @click="add()" class="text-sm text-blue-600 hover:underline">

                            + Add another document

                        </button>


                        {{-- SUBMIT --}}
                        <button type="submit"
                            class="w-full text-white bg-emerald-600 border border-gray-200 rounded-xl px-4 py-2 text-sm font-semibold hover:bg-emerald-700">

                            Upload Documents

                        </button>

                    </form>



                    {{-- UPLOADED DOCUMENTS --}}
                    <div class="mt-6 space-y-3">

                        @if ($resume->attachments->count() === 0)
                            <div class="text-sm text-gray-500 border border-gray-200 rounded-xl p-4">
                                No attachments uploaded yet.
                            </div>
                        @endif


                        @foreach ($resume->attachments as $att)
                            @php
                                $url = asset('storage/' . $att->file_path);
                                $mime = strtolower($att->mime ?? '');
                                $isImage = str_starts_with($mime, 'image/');
                                $isPdf = $mime === 'application/pdf';
                            @endphp


                            <div class="border border-gray-200 rounded-xl p-3">

                                <div class="flex items-center justify-between">

                                    <div>

                                        <p class="text-xs text-gray-500">
                                            {{ $att->category }}
                                        </p>

                                        <p class="text-sm font-semibold text-gray-900">
                                            {{ $att->original_name }}
                                        </p>

                                        <div class="flex gap-3 mt-1">

                                            <a href="{{ $url }}" target="_blank"
                                                class="text-xs text-blue-600 underline">
                                                Open
                                            </a>

                                            <a href="{{ $url }}" download
                                                class="text-xs text-gray-700 underline">
                                                Download
                                            </a>

                                        </div>

                                    </div>


                                    <form action="{{ route('candidate.resume.attachments.delete', $att->id) }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            class="text-xs border border-red-200 text-red-600 px-3 py-1 rounded-lg hover:bg-red-50">
                                            Remove
                                        </button>

                                    </form>

                                </div>


                                {{-- PREVIEW --}}
                                @if ($isImage)
                                    <img src="{{ $url }}" class="h-24 mt-2 rounded border" />
                                @elseif($isPdf)
                                    <iframe src="{{ $url }}#toolbar=0"
                                        class="w-full h-32 mt-2 rounded"></iframe>
                                @endif

                            </div>
                        @endforeach

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- ALPINE JS SCRIPT --}}
    <script>
        function fileUpload() {
            return {

                files: [],

                previewFiles(event) {

                    this.files = []

                    Array.from(event.target.files).forEach(file => {

                        this.files.push({
                            name: file.name,
                            type: file.type,
                            url: URL.createObjectURL(file)
                        })

                    })

                },

                removeFile(index) {

                    this.files.splice(index, 1)

                }

            }
        }
    </script>
    <script>
        function documentsUpload() {

            return {

                documents: [{
                    category: ''
                }],

                add() {
                    this.documents.push({
                        category: ''
                    })
                },

                remove(index) {
                    this.documents.splice(index, 1)
                }

            }

        }
    </script>

@endsection
