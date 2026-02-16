<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    {{-- Left column --}}
    <div class="lg:col-span-4 space-y-6">
        {{-- Profile card --}}
        <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-6">
            <div class="flex flex-col items-center text-center">
                @if($photo)
                    <img src="{{ asset('storage/' . $photo) }}" alt="{{ $user->name }}"
                        class="h-28 w-28 rounded-full object-cover ring-4 ring-gray-100" />
                @else
                    <div
                        class="h-28 w-28 rounded-full bg-emerald-600 text-white flex items-center justify-center text-2xl font-bold ring-4 ring-gray-100">
                        {{ $first }}{{ $last }}
                    </div>
                @endif
                <p class="mt-4 text-base font-semibold text-gray-900">{{ $user->name }}</p>
                <p class="text-sm text-gray-500">Candidate</p>
            </div>
        </div>

        {{-- Social Links --}}
        <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-6">
            <h2 class="text-sm font-semibold text-gray-900">Social Links</h2>

            <div class="mt-4 space-y-4">
                <div>
                    <p class="text-xs font-semibold text-gray-500 mb-1">WhatsApp</p>

                    @if($profile->whatsapp)
                        @php
                            $wa = preg_match('/^https?:\/\//', $profile->whatsapp)
                                ? $profile->whatsapp
                                : 'https://' . $profile->whatsapp;
                        @endphp

                        <a href="{{ $wa }}" target="_blank" class="block rounded-xl bg-gray-50 border border-gray-200 px-4 py-2.5 text-sm
                                      text-gray-700 font-medium hover:underline hover:text-[#16A34A]">
                            {{ $profile->whatsapp }}
                        </a>
                    @else
                        <div class="rounded-xl bg-gray-50 border border-gray-200 px-4 py-2.5 text-sm text-gray-700">
                            —
                        </div>
                    @endif
                </div>


                <div>
                    <p class="text-xs font-semibold text-gray-500 mb-1">Facebook</p>

                    @if($profile->facebook)
                        @php
                            $fb = preg_match('/^https?:\/\//', $profile->facebook)
                                ? $profile->facebook
                                : 'https://' . $profile->facebook;
                        @endphp

                        <a href="{{ $fb }}" target="_blank" class="block rounded-xl bg-gray-50 border border-gray-200 px-4 py-2.5 text-sm
                                  text-gray-700 font-medium hover:underline hover:text-[#16A34A]">
                            {{ $profile->facebook }}
                        </a>
                    @else
                        <div class="rounded-xl bg-gray-50 border border-gray-200 px-4 py-2.5 text-sm text-gray-700">
                            —
                        </div>
                    @endif
                </div>


                <div>
                    <p class="text-xs font-semibold text-gray-500 mb-1">LinkedIn</p>

                    @if($profile->linkedin)
                        @php
                            $li = preg_match('/^https?:\/\//', $profile->linkedin)
                                ? $profile->linkedin
                                : 'https://' . $profile->linkedin;
                        @endphp

                        <a href="{{ $li }}" target="_blank" class="block rounded-xl bg-gray-50 border border-gray-200 px-4 py-2.5 text-sm
                              text-gray-700 font-medium hover:underline hover:text-[#16A34A]">
                            {{ $profile->linkedin }}
                        </a>
                    @else
                        <div class="rounded-xl bg-gray-50 border border-gray-200 px-4 py-2.5 text-sm text-gray-700">
                            —
                        </div>
                    @endif
                </div>


                <div>
                    <p class="text-xs font-semibold text-gray-500 mb-1">Telegram</p>

                    @if($profile->telegram)
                            @php
                                $tg = preg_match('/^https?:\/\//', $profile->telegram)
                                    ? $profile->telegram
                                    : 'https://' . $profile->telegram;
                            @endphp

                            <a href="{{ $tg }}" target="_blank" class="block rounded-xl bg-gray-50 border border-gray-200 px-4 py-2.5 text-sm
                          text-gray-700 font-medium hover:underline hover:text-[#16A34A]">
                                {{ $profile->telegram }}
                            </a>
                    @else
                        <div class="rounded-xl bg-gray-50 border border-gray-200 px-4 py-2.5 text-sm text-gray-700">
                            —
                        </div>
                    @endif
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
                        {{ $user->name }}
                    </div>
                </div>

                <div>
                    <p class="text-xs font-semibold text-gray-500 mb-1">Email</p>
                    <div class="rounded-xl bg-gray-50 border border-gray-200 px-4 py-2.5 text-sm text-gray-700">
                        {{ $user->email }}
                    </div>
                </div>

                <div>
                    <p class="text-xs font-semibold text-gray-500 mb-1">Phone</p>
                    <div class="rounded-xl bg-gray-50 border border-gray-200 px-4 py-2.5 text-sm text-gray-700">
                        {{ $user->phone ?: '—' }}
                    </div>
                </div>

                <div>
                    <p class="text-xs font-semibold text-gray-500 mb-1">Birth Date</p>
                    <div class="rounded-xl bg-gray-50 border border-gray-200 px-4 py-2.5 text-sm text-gray-700">
                        {{ $profile->birth_date ? $profile->birth_date->format('M d, Y') : '—' }}
                    </div>
                </div>

                <div>
                    <p class="text-xs font-semibold text-gray-500 mb-1">Experience (Years)</p>
                    <div class="rounded-xl bg-gray-50 border border-gray-200 px-4 py-2.5 text-sm text-gray-700">
                        {{ is_null($profile->experience_years) ? '—' : $profile->experience_years }}
                    </div>
                </div>

                <div>
                    <p class="text-xs font-semibold text-gray-500 mb-1">Address</p>
                    <div class="rounded-xl bg-gray-50 border border-gray-200 px-4 py-2.5 text-sm text-gray-700">
                        {{ $profile->address ?: '—' }}
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <p class="text-xs font-semibold text-gray-500 mb-1">Bio</p>
                <div
                    class="rounded-xl bg-gray-50 border border-gray-200 px-4 py-3 text-sm text-gray-700 leading-relaxed">
                    {{ $profile->bio ?: '—' }}
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
                        {{ $profile->highest_qualification ?: '—' }}
                    </div>
                </div>

                <div>
                    <p class="text-xs font-semibold text-gray-500 mb-1">Current Salary</p>
                    <div class="rounded-xl bg-gray-50 border border-gray-200 px-4 py-2.5 text-sm text-gray-700">
                        {{ $profile->current_salary ?: '—' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>