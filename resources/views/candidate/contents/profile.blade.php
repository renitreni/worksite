@extends('candidate.layout')

@section('content')
    @php
        $photo = $profile->photo_path ?? null;
        $first = strtoupper(substr($user->first_name ?? '', 0, 1));
        $last = strtoupper(substr($user->last_name ?? '', 0, 1));
    @endphp

    <div class="space-y-6" x-data="{
        editOpen:false,
        passOpen: @json($errors->has('current_password') || $errors->has('password') || $errors->has('password_confirmation')),
        photoPreview:null
    }">

        {{-- Header --}}
        <div class="flex items-start sm:items-center justify-between gap-4 flex-col sm:flex-row">
            <div>
                <h1 class="text-xl sm:text-2xl font-semibold text-gray-900">My Profile</h1>
                @if(session('success'))
                    <p class="mt-2 text-sm text-emerald-700 font-medium">{{ session('success') }}</p>
                @endif
            </div>

            <div class="flex gap-2">
                <button type="button" @click="passOpen=true"
                    class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                    Change Password
                </button>

                <button type="button" @click="editOpen=true"
                    class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700 transition">
                    Edit Profile
                </button>
            </div>
        </div>

        {{-- Profile sections --}}
        @include('candidate.partials.profile._overview', [
            'user' => $user,
            'profile' => $profile,
            'photo' => $photo,
            'first' => $first,
            'last' => $last,
        ])

            {{-- Modals --}}
            @include('candidate.partials.profile._edit_modal', [
                'user' => $user,
                'profile' => $profile,
                'photo' => $photo,
                'first' => $first,
                'last' => $last,
                'qualificationOptions' => $qualificationOptions,
            ])

            @include('candidate.partials.profile._password_modal')
        </div>
@endsection
