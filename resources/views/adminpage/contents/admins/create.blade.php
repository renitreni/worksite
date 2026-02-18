@extends('adminpage.layout')
@section('title','Add Admin')
@section('page_title','Add Admin')

@section('content')
<div class="space-y-6">

  @if($errors->any())
    <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800">
      <div class="font-semibold">Please fix the errors:</div>
      <ul class="mt-2 list-disc pl-5">
        @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
      </ul>
    </div>
  @endif

  <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <form method="POST" action="{{ route('admin.admins.store') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
      @csrf

      <div>
        <label class="text-xs font-semibold text-slate-700">First name</label>
        <input name="first_name" value="{{ old('first_name') }}" autocomplete="given-name"
          class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm" />
      </div>

      <div>
        <label class="text-xs font-semibold text-slate-700">Last name</label>
        <input name="last_name" value="{{ old('last_name') }}" autocomplete="family-name"
          class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm" />
      </div>

      <div class="sm:col-span-2">
        <label class="text-xs font-semibold text-slate-700">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" autocomplete="email"
          class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm" />
      </div>

      {{-- Password --}}
      <div class="sm:col-span-2">
        <label class="text-xs font-semibold text-slate-700">Password</label>

        <div class="relative mt-1">
          <input id="password" type="password" name="password" autocomplete="new-password"
            class="w-full rounded-xl border border-slate-200 px-3 py-2 pr-11 text-sm focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100" />

          <button type="button"
            class="absolute inset-y-0 right-3 flex items-center rounded-lg p-1 text-slate-500 hover:text-slate-700"
            aria-label="Toggle password visibility"
            onclick="togglePw('password', this)">

            <svg class="pw-eye h-4 w-4 transition-all duration-200 ease-out opacity-0 scale-90"
                 xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"/>
              <circle cx="12" cy="12" r="3"/>
            </svg>

            <svg class="pw-eyeoff h-4 w-4 transition-all duration-200 ease-out opacity-100 scale-100 absolute"
                 xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M10.58 10.58A2 2 0 0 0 12 15a2 2 0 0 0 1.42-.58"/>
              <path d="M9.88 5.1A10.5 10.5 0 0 1 12 5c6.5 0 10 7 10 7a18.2 18.2 0 0 1-3.05 4.36"/>
              <path d="M6.61 6.61A16.8 16.8 0 0 0 2 12s3.5 7 10 7c1.6 0 3.05-.3 4.36-.83"/>
              <path d="M2 2l20 20"/>
            </svg>
          </button>
        </div>

        <div class="mt-1 text-[11px] text-slate-500">Min 8 characters.</div>
      </div>

      {{-- Confirm Password (NEW) --}}
      <div class="sm:col-span-2">
        <label class="text-xs font-semibold text-slate-700">Confirm password</label>

        <div class="relative mt-1">
          <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password"
            class="w-full rounded-xl border border-slate-200 px-3 py-2 pr-11 text-sm focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100" />

          <button type="button"
            class="absolute inset-y-0 right-3 flex items-center rounded-lg p-1 text-slate-500 hover:text-slate-700"
            aria-label="Toggle password visibility"
            onclick="togglePw('password_confirmation', this)">

            <svg class="pw-eye h-4 w-4 transition-all duration-200 ease-out opacity-0 scale-90"
                 xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"/>
              <circle cx="12" cy="12" r="3"/>
            </svg>

            <svg class="pw-eyeoff h-4 w-4 transition-all duration-200 ease-out opacity-100 scale-100 absolute"
                 xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M10.58 10.58A2 2 0 0 0 12 15a2 2 0 0 0 1.42-.58"/>
              <path d="M9.88 5.1A10.5 10.5 0 0 1 12 5c6.5 0 10 7 10 7a18.2 18.2 0 0 1-3.05 4.36"/>
              <path d="M6.61 6.61A16.8 16.8 0 0 0 2 12s3.5 7 10 7c1.6 0 3.05-.3 4.36-.83"/>
              <path d="M2 2l20 20"/>
            </svg>
          </button>
        </div>
      </div>

      <div class="sm:col-span-2 flex gap-2">
        <a href="{{ route('admin.admins.index') }}"
          class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50">
          Cancel
        </a>
        <button type="submit"
          class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
          Create Admin
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
