<div x-show="editOpen" x-transition.opacity
    class="fixed inset-0 z-[999] flex items-start justify-center p-3 sm:p-6"
    aria-modal="true" role="dialog"
    @keydown.escape.window="editOpen=false">

    <div class="absolute inset-0 bg-gray-900/40" @click="editOpen=false"></div>

    <div x-transition @click.stop
        class="relative w-full max-w-6xl max-h-[92vh] overflow-y-auto rounded-2xl bg-white border border-gray-200 shadow-xl">

        <div class="sticky top-0 z-10 bg-white/95 backdrop-blur border-b border-gray-200">
            <div class="px-4 sm:px-6 py-4 flex items-start justify-between gap-4">
                <div class="space-y-1">
                    <button type="button" @click="editOpen=false"
                        class="inline-flex items-center gap-2 text-sm font-semibold text-gray-700 hover:text-gray-900">
                        <i data-lucide="arrow-left" class="h-4 w-4"></i>
                        Back to Profile
                    </button>

                    <div class="pt-2">
                        <h2 class="text-2xl font-bold text-gray-900">Edit Profile</h2>
                        <p class="text-sm text-gray-500">Update your personal and professional information</p>
                    </div>
                </div>

                <button type="button" @click="editOpen=false"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 bg-white hover:bg-gray-50"
                    title="Close">
                    <i data-lucide="x" class="h-5 w-5 text-gray-700"></i>
                </button>
            </div>
        </div>

        <form class="p-4 sm:p-6 space-y-6" method="POST" action="{{ route('candidate.profile.update') }}"
            enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                {{-- Left --}}
                <div class="lg:col-span-4 space-y-6">
                    <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-6">
                        <h3 class="text-sm font-semibold text-gray-900">Profile Photo</h3>

                        <div class="mt-5 flex flex-col items-center text-center">
                            <div class="relative">
                                <template x-if="photoPreview || '{{ $photo }}'">
                                    <img :src="photoPreview ? photoPreview : '{{ $photo ? asset('storage/' . $photo) : '' }}'"
                                        alt="Profile"
                                        class="h-28 w-28 rounded-full object-cover ring-4 ring-gray-100" />
                                </template>

                                <template x-if="!photoPreview && !'{{ $photo }}'">
                                    <div class="h-28 w-28 rounded-full bg-emerald-600 text-white flex items-center justify-center text-2xl font-bold ring-4 ring-gray-100">
                                        {{ $first }}{{ $last }}
                                    </div>
                                </template>

                                <label class="absolute -right-1 -bottom-1 inline-flex h-10 w-10 items-center justify-center rounded-full bg-emerald-600 text-white shadow cursor-pointer hover:bg-emerald-700">
                                    <input name="photo" type="file" class="hidden" accept="image/*"
                                        @change="
                                            const file = $event.target.files?.[0];
                                            if(!file) return;
                                            const reader = new FileReader();
                                            reader.onload = (e) => photoPreview = e.target.result;
                                            reader.readAsDataURL(file);
                                        " />
                                    <i data-lucide="camera" class="h-5 w-5"></i>
                                </label>
                            </div>

                            <p class="mt-4 text-xs text-gray-500">JPG, PNG or GIF (max. 5MB)</p>
                            @error('photo') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-6">
                        <h3 class="text-sm font-semibold text-gray-900">Social Links</h3>

                        <div class="mt-4 space-y-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">WhatsApp</label>
                                <input name="whatsapp" type="text" value="{{ old('whatsapp', $profile->whatsapp) }}"
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300" />
                                @error('whatsapp') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Facebook</label>
                                <input name="facebook" type="text" value="{{ old('facebook', $profile->facebook) }}"
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300" />
                                @error('facebook') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">LinkedIn</label>
                                <input name="linkedin" type="text" value="{{ old('linkedin', $profile->linkedin) }}"
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300" />
                                @error('linkedin') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Telegram</label>
                                <input name="telegram" type="text" value="{{ old('telegram', $profile->telegram) }}"
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300" />
                                @error('telegram') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
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
                                <label class="block text-xs font-semibold text-gray-700 mb-1">First Name <span class="text-red-500">*</span></label>
                                <input name="first_name" type="text" value="{{ old('first_name', $user->first_name) }}"
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300" />
                                @error('first_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Last Name <span class="text-red-500">*</span></label>
                                <input name="last_name" type="text" value="{{ old('last_name', $user->last_name) }}"
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300" />
                                @error('last_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">
                                    Email <span class="text-gray-400">(cannot be changed)</span>
                                </label>
                                <input type="email" value="{{ $user->email }}" disabled
                                    class="w-full rounded-xl border border-gray-200 bg-gray-100 px-4 py-3 text-sm text-gray-700 cursor-not-allowed" />
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Phone</label>
                                <input name="phone" type="text" value="{{ old('phone', $user->phone) }}"
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300" />
                                @error('phone') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Birth Date</label>
                                <input name="birth_date" type="date"
                                    value="{{ old('birth_date', optional($profile->birth_date)->format('Y-m-d')) }}"
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300" />
                                @error('birth_date') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Experience (Years)</label>
                                <input name="experience_years" type="number" min="0" max="80"
                                    value="{{ old('experience_years', $profile->experience_years) }}"
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300" />
                                @error('experience_years') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Address</label>
                                <input name="address" type="text" value="{{ old('address', $profile->address) }}"
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300" />
                                @error('address') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Bio</label>
                            <textarea name="bio" rows="4"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300">{{ old('bio', $profile->bio) }}</textarea>
                            @error('bio') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-6">
                        <h3 class="text-sm font-semibold text-gray-900">Professional Details</h3>

                        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Highest Qualification</label>
                                <select name="highest_qualification"
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300">
                                    <option value="">—</option>
                                    @foreach($qualificationOptions as $opt)
                                        <option value="{{ $opt }}" @selected(old('highest_qualification', $profile->highest_qualification) === $opt)>
                                            {{ $opt }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('highest_qualification') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Current Salary</label>
                                <input name="current_salary" type="text"
                                    value="{{ old('current_salary', $profile->current_salary) }}"
                                    placeholder="e.g. ₱20,000 - ₱30,000"
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300" />
                                @error('current_salary') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer buttons --}}
            <div class="sticky bottom-0 bg-white/95 backdrop-blur border-t border-gray-200">
                <div class="px-4 sm:px-6 py-4 flex items-center justify-end gap-3">
                    <button type="button" @click="editOpen=false"
                        class="rounded-xl border border-gray-200 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>

                    <button type="submit"
                        class="rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700">
                        Save Changes
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

