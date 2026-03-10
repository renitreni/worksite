@extends('adminpage.layout')
@section('title', 'Set Admin Password')
@section('page_title', 'Activate Admin Account')

@section('content')
<div class="mx-auto max-w-2xl p-4">

    @if ($errors->any())
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <div class="mb-2 font-semibold">Please fix the following:</div>
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="mb-5">
            <h2 class="text-lg font-semibold text-slate-900">Set your password</h2>
            <p class="mt-1 text-sm text-slate-500">
                Welcome, {{ $user->name }}. Create your password to activate your admin account.
            </p>
        </div>

        <form method="POST" action="{{ route('admin.invite.accept.submit') }}" class="space-y-4">
            @csrf

            <input type="hidden" name="email" value="{{ $email }}">
            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label class="text-xs font-semibold text-slate-700">Email</label>
                <input type="email"
                       value="{{ $email }}"
                       disabled
                       class="mt-1 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-500" />
            </div>

            {{-- PASSWORD --}}
            <div>
                <label class="text-xs font-semibold text-slate-700">Password</label>

                <div class="relative mt-1">
                    <input
                        id="password"
                        type="password"
                        name="password"
                        autocomplete="new-password"
                        class="w-full rounded-xl border border-slate-200 px-3 py-2 pr-11 text-sm focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100"
                    />

                    <button
                        type="button"
                        class="absolute inset-y-0 right-3 flex items-center rounded-lg p-1 text-slate-500 hover:text-slate-700"
                        aria-label="Toggle password visibility"
                        onclick="togglePw('password', this)"
                    >
                        <svg class="pw-eye h-4 w-4 transition-all duration-200 ease-out opacity-0 scale-90"
                             xmlns="http://www.w3.org/2000/svg"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2"
                             stroke-linecap="round"
                             stroke-linejoin="round">
                            <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>

                        <svg class="pw-eyeoff h-4 w-4 transition-all duration-200 ease-out opacity-100 scale-100 absolute"
                             xmlns="http://www.w3.org/2000/svg"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2"
                             stroke-linecap="round"
                             stroke-linejoin="round">
                            <path d="M10.58 10.58A2 2 0 0 0 12 15a2 2 0 0 0 1.42-.58"/>
                            <path d="M9.88 5.1A10.5 10.5 0 0 1 12 5c6.5 0 10 7 10 7a18.2 18.2 0 0 1-3.05 4.36"/>
                            <path d="M6.61 6.61A16.8 16.8 0 0 0 2 12s3.5 7 10 7c1.6 0 3.05-.3 4.36-.83"/>
                            <path d="M2 2l20 20"/>
                        </svg>
                    </button>
                </div>

                <div class="mt-1 text-[11px] text-slate-500">Minimum 8 characters.</div>
            </div>

            {{-- CONFIRM PASSWORD --}}
            <div>
                <label class="text-xs font-semibold text-slate-700">Confirm password</label>

                <div class="relative mt-1">
                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        autocomplete="new-password"
                        class="w-full rounded-xl border border-slate-200 px-3 py-2 pr-11 text-sm focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100"
                    />

                    <button
                        type="button"
                        class="absolute inset-y-0 right-3 flex items-center rounded-lg p-1 text-slate-500 hover:text-slate-700"
                        aria-label="Toggle password visibility"
                        onclick="togglePw('password_confirmation', this)"
                    >
                        <svg class="pw-eye h-4 w-4 transition-all duration-200 ease-out opacity-0 scale-90"
                             xmlns="http://www.w3.org/2000/svg"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2"
                             stroke-linecap="round"
                             stroke-linejoin="round">
                            <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>

                        <svg class="pw-eyeoff h-4 w-4 transition-all duration-200 ease-out opacity-100 scale-100 absolute"
                             xmlns="http://www.w3.org/2000/svg"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2"
                             stroke-linecap="round"
                             stroke-linejoin="round">
                            <path d="M10.58 10.58A2 2 0 0 0 12 15a2 2 0 0 0 1.42-.58"/>
                            <path d="M9.88 5.1A10.5 10.5 0 0 1 12 5c6.5 0 10 7 10 7a18.2 18.2 0 0 1-3.05 4.36"/>
                            <path d="M6.61 6.61A16.8 16.8 0 0 0 2 12s3.5 7 10 7c1.6 0 3.05-.3 4.36-.83"/>
                            <path d="M2 2l20 20"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="pt-2">
                <button
                    type="submit"
                    class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700"
                >
                    Activate Account
                </button>
            </div>

        </form>
    </div>
</div>

<script>
function togglePw(inputId, btn) {

    const input = document.getElementById(inputId);
    const eye = btn.querySelector('.pw-eye');
    const eyeOff = btn.querySelector('.pw-eyeoff');

    const willShow = input.type === 'password';
    input.type = willShow ? 'text' : 'password';

    if (willShow) {

        eye.classList.add('opacity-100','scale-100');
        eye.classList.remove('opacity-0','scale-90');

        eyeOff.classList.add('opacity-0','scale-90');
        eyeOff.classList.remove('opacity-100','scale-100');

    } else {

        eye.classList.add('opacity-0','scale-90');
        eye.classList.remove('opacity-100','scale-100');

        eyeOff.classList.add('opacity-100','scale-100');
        eyeOff.classList.remove('opacity-0','scale-90');

    }

}
</script>

@endsection