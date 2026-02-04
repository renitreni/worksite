@extends('candidate.layout')

@section('content')
<div class="space-y-6" x-data="{ editOpen:false, photoPreview:null }">
    {{-- Header --}}
    <div class="flex items-start sm:items-center justify-between gap-4 flex-col sm:flex-row">
        <div>
            <h1 class="text-xl sm:text-2xl font-semibold text-gray-900">My Profile</h1>
        </div>

        <button
            type="button"
            @click="editOpen=true"
            class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700 transition"
        >
            Edit Profile
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        {{-- Left column --}}
        <div class="lg:col-span-4 space-y-6">
            {{-- Profile card --}}
            <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-6">
                <div class="flex flex-col items-center text-center">
                    <img
                        src="https://images.unsplash.com/photo-1527980965255-d3b416303d12?auto=format&fit=crop&w=240&h=240&q=80"
                        alt="Keith Pelonio"
                        class="h-28 w-28 rounded-full object-cover ring-4 ring-gray-100"
                    />
                    <p class="mt-4 text-base font-semibold text-gray-900">Keith Pelonio</p>
                    <p class="text-sm text-gray-500">Senior UX Designer</p>
                </div>
            </div>

            {{-- Social Links --}}
            <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-6">
                <h2 class="text-sm font-semibold text-gray-900">Social Links</h2>

                <div class="mt-4 space-y-4">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 mb-1">LinkedIn</p>
                        <div class="rounded-xl bg-gray-50 border border-gray-200 px-4 py-2.5 text-sm text-gray-700">
                            linkedin.com/in/keithpelonio
                        </div>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-500 mb-1">GitHub</p>
                        <div class="rounded-xl bg-gray-50 border border-gray-200 px-4 py-2.5 text-sm text-gray-700">
                            github.com/keithpelonio
                        </div>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-500 mb-1">Portfolio</p>
                        <div class="rounded-xl bg-gray-50 border border-gray-200 px-4 py-2.5 text-sm text-gray-700">
                            keith.design
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right column --}}
        <div class="lg:col-span-8 space-y-6">
            {{-- Personal Information --}}
            <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-6">
                <h2 class="text-sm font-semibold text-gray-900">Personal Information</h2>

                <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 mb-1">Full Name</p>
                        <div class="rounded-xl bg-gray-50 border border-gray-200 px-4 py-2.5 text-sm text-gray-700">
                            Keith Pelonio
                        </div>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-500 mb-1">Job Title</p>
                        <div class="rounded-xl bg-gray-50 border border-gray-200 px-4 py-2.5 text-sm text-gray-700">
                            Senior UX Designer
                        </div>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-500 mb-1">Email</p>
                        <div class="rounded-xl bg-gray-50 border border-gray-200 px-4 py-2.5 text-sm text-gray-700">
                            keithpelonio@example.com
                        </div>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-500 mb-1">Phone</p>
                        <div class="rounded-xl bg-gray-50 border border-gray-200 px-4 py-2.5 text-sm text-gray-700">
                            +1 (555) 123-4567
                        </div>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-500 mb-1">Experience</p>
                        <div class="rounded-xl bg-gray-50 border border-gray-200 px-4 py-2.5 text-sm text-gray-700">
                            5 years
                        </div>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-500 mb-1">Location</p>
                        <div class="rounded-xl bg-gray-50 border border-gray-200 px-4 py-2.5 text-sm text-gray-700">
                            San Francisco, CA
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <p class="text-xs font-semibold text-gray-500 mb-1">Bio</p>
                    <div class="rounded-xl bg-gray-50 border border-gray-200 px-4 py-3 text-sm text-gray-700 leading-relaxed">
                        Passionate UX Designer with a focus on accessible and inclusive design. Experienced in building enterprise applications and consumer-facing products.
                    </div>
                </div>
            </div>

            {{-- Professional Details --}}
            <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-6">
                <h2 class="text-sm font-semibold text-gray-900">Professional Details</h2>

                <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 mb-1">Highest Qualification</p>
                        <div class="rounded-xl bg-gray-50 border border-gray-200 px-4 py-2.5 text-sm text-gray-700">
                            Bachelor's Degree
                        </div>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-500 mb-1">Current Salary</p>
                        <div class="rounded-xl bg-gray-50 border border-gray-200 px-4 py-2.5 text-sm text-gray-700">
                            $50k - $80k
                        </div>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-500 mb-1">Expected Salary</p>
                        <div class="rounded-xl bg-gray-50 border border-gray-200 px-4 py-2.5 text-sm text-gray-700">
                            $80k - $100k
                        </div>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-500 mb-1">Job Type</p>
                        <div class="rounded-xl bg-gray-50 border border-gray-200 px-4 py-2.5 text-sm text-gray-700">
                            Full Time
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--  EDIT PROFILE MODAL  --}}
    <div
        x-show="editOpen"
        x-transition.opacity
        class="fixed inset-0 z-[999] flex items-start justify-center p-3 sm:p-6"
        aria-modal="true"
        role="dialog"
        @keydown.escape.window="editOpen=false"
    >
        <div class="absolute inset-0 bg-gray-900/40" @click="editOpen=false"></div>

        <div
            x-transition
            @click.stop
            class="relative w-full max-w-6xl max-h-[92vh] overflow-y-auto rounded-2xl bg-white border border-gray-200 shadow-xl"
        >
            {{-- Top --}}
            <div class="sticky top-0 z-10 bg-white/95 backdrop-blur border-b border-gray-200">
                <div class="px-4 sm:px-6 py-4 flex items-start justify-between gap-4">
                    <div class="space-y-1">
                        <button
                            type="button"
                            @click="editOpen=false"
                            class="inline-flex items-center gap-2 text-sm font-semibold text-gray-700 hover:text-gray-900"
                        >
                            <i data-lucide="arrow-left" class="h-4 w-4"></i>
                            Back to Profile
                        </button>

                        <div class="pt-2">
                            <h2 class="text-2xl font-bold text-gray-900">Edit Profile</h2>
                            <p class="text-sm text-gray-500">Update your personal and professional information</p>
                        </div>
                    </div>

                    <button
                        type="button"
                        @click="editOpen=false"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 bg-white hover:bg-gray-50"
                        title="Close"
                    >
                        <i data-lucide="x" class="h-5 w-5 text-gray-700"></i>
                    </button>
                </div>
            </div>

            <form class="p-4 sm:p-6 space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    {{-- Left --}}
                    <div class="lg:col-span-4 space-y-6">
                        <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-6">
                            <h3 class="text-sm font-semibold text-gray-900">Profile Photo</h3>

                            <div class="mt-5 flex flex-col items-center text-center">
                                <div class="relative">
                                    <img
                                        :src="photoPreview ?? 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=240&h=240&q=80'"
                                        alt="Profile"
                                        class="h-28 w-28 rounded-full object-cover ring-4 ring-gray-100"
                                    />
                                    <label class="absolute -right-1 -bottom-1 inline-flex h-10 w-10 items-center justify-center rounded-full bg-emerald-600 text-white shadow cursor-pointer hover:bg-emerald-700">
                                        <input
                                            type="file"
                                            class="hidden"
                                            accept="image/*"
                                            @change="
                                                const file = $event.target.files?.[0];
                                                if(!file) return;
                                                const reader = new FileReader();
                                                reader.onload = (e) => photoPreview = e.target.result;
                                                reader.readAsDataURL(file);
                                            "
                                        />
                                        <i data-lucide="camera" class="h-5 w-5"></i>
                                    </label>
                                </div>

                                <p class="mt-4 text-xs text-gray-500">JPG, PNG or GIF (max. 5MB)</p>
                            </div>
                        </div>

                        <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-6">
                            <h3 class="text-sm font-semibold text-gray-900">Social Links</h3>

                            <div class="mt-4 space-y-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">LinkedIn</label>
                                    <input
                                        type="text"
                                        value="linkedin.com/in/keithpelonio"
                                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300"
                                    />
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">GitHub</label>
                                    <input
                                        type="text"
                                        value="github.com/keithpelonio"
                                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300"
                                    />
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Portfolio</label>
                                    <input
                                        type="text"
                                        value="keith.design"
                                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Right --}}
                    <div class="lg:col-span-8 space-y-6">
                        <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-6">
                            <h3 class="text-sm font-semibold text-gray-900">Personal Information</h3>

                            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                                    <input
                                        type="text"
                                        value="Keith Pelonio"
                                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300"
                                    />
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Job Title <span class="text-red-500">*</span></label>
                                    <input
                                        type="text"
                                        value="Senior UX Designer"
                                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300"
                                    />
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                                    <input
                                        type="email"
                                        value="keith@email.com"
                                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300"
                                    />
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Phone</label>
                                    <input
                                        type="text"
                                        value="+1 (555) 123-4567"
                                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300"
                                    />
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Experience</label>
                                    <input
                                        type="text"
                                        value="6 years"
                                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300"
                                    />
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Location</label>
                                    <input
                                        type="text"
                                        value="San Francisco, CA"
                                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300"
                                    />
                                </div>
                            </div>

                            <div class="mt-4">
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Bio</label>
                                <textarea
                                    rows="4"
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300"
                                >Passionate UX Designer with a focus on accessible and inclusive design. Experienced in building enterprise applications and consumer-facing products.</textarea>
                            </div>
                        </div>

                        <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-6">
                            <h3 class="text-sm font-semibold text-gray-900">Professional Details</h3>

                            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Highest Qualification</label>
                                    <select class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300">
                                        <option selected>Bachelor's Degree</option>
                                        <option>Master's Degree</option>
                                        <option>Doctorate</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Current Salary</label>
                                    <select class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300">
                                        <option>$50k - $80k</option>
                                        <option selected>$90k - $95k</option>
                                        <option>$100k - $130k</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Expected Salary</label>
                                    <select class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300">
                                        <option>$80k - $100k</option>
                                        <option selected>$95k - $110k</option>
                                        <option>$130k - $160k</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Job Type</label>
                                    <select class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300">
                                        <option selected>Full-Time</option>
                                        <option>Part-Time</option>
                                        <option>Contract</option>
                                        <option>Freelance</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Footer buttons --}}
                <div class="sticky bottom-0 bg-white/95 backdrop-blur border-t border-gray-200">
                    <div class="px-4 sm:px-6 py-4 flex items-center justify-end gap-3">
                        <button
                            type="button"
                            @click="editOpen=false"
                            class="rounded-xl border border-gray-200 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                        >
                            Cancel
                        </button>

                        <button
                            type="button"
                            @click="editOpen=false"
                            class="rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700"
                        >
                            Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    {{-- EDIT PROFILE MODAL  --}}
</div>
@endsection