<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin Login</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body class="min-h-screen text-slate-900">
  <div class="relative min-h-screen overflow-hidden">

    {{-- Background --}}
    <div
      class="absolute inset-0 bg-cover bg-center"
      style="background-image: url('{{ asset('images/background.png') }}');"
      aria-hidden="true"
    ></div>

    <div class="absolute inset-0 bg-slate-950/55" aria-hidden="true"></div>

    <div
      class="absolute inset-0"
      style="background: radial-gradient(circle at center, rgba(0,0,0,0.05) 0%, rgba(0,0,0,0.65) 70%, rgba(0,0,0,0.80) 100%);"
      aria-hidden="true"
    ></div>

    <div class="absolute inset-0 flex items-center justify-center" aria-hidden="true">
      <div class="h-[420px] w-[420px] rounded-full bg-emerald-400/15 blur-3xl"></div>
    </div>

    <div class="absolute inset-0 opacity-[0.06]" aria-hidden="true">
      <div class="h-full w-full bg-[radial-gradient(circle_at_1px_1px,_#fff_1px,_transparent_0)] [background-size:18px_18px]"></div>
    </div>

    <div class="relative z-10 flex min-h-screen items-center justify-center px-4 sm:px-6 lg:px-8">
      <div class="w-full max-w-md">

        <div class="mb-4 flex justify-center">
          <span class="inline-flex items-center rounded-full border border-white/15 bg-white/10 px-3 py-1 text-xs font-semibold text-white/90">
            Secure Admin Access
          </span>
        </div>

        <div class="rounded-3xl border border-white/20 bg-white/15 p-7 shadow-[0_20px_60px_rgba(0,0,0,0.45)] backdrop-blur-xl sm:p-8">
          <div class="flex flex-col items-center text-center">
            <div class="h-20 flex items-center justify-center">
              <img
                src="{{ asset('images/logo.png') }}"
                alt="WorkSITE"
                class="h-40 w-auto object-contain drop-shadow-lg"
              />
            </div>

            <div class="mt-4 h-px w-20 bg-white/25"></div>

            <h1 class="mt-4 text-xl font-bold tracking-tight text-white">Admin Login</h1>
            <p class="mt-1 text-sm text-white/80">Please sign in to manage the system.</p>
          </div>

          @if ($errors->any())
            <div class="mt-5 rounded-xl border border-red-500/30 bg-red-500/10 p-3 text-sm text-red-100">
              <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form class="mt-6 space-y-4" method="POST" action="{{ route('admin.login.submit') }}">
            @csrf

            <div>
              <label class="text-sm font-semibold text-white/90">Email</label>
              <div class="mt-1 flex items-center gap-2 rounded-xl border border-white/20 bg-white/10 px-3 py-2 focus-within:ring-4 focus-within:ring-emerald-300/20">
                <span class="text-white/70">👤</span>
                <input
                  type="email"
                  name="email"
                  value="{{ old('email') }}"
                  class="w-full bg-transparent text-sm text-white placeholder:text-white/60 focus:outline-none"
                  placeholder="admin@worksite.com"
                  autocomplete="username"
                  required
                />
              </div>
            </div>

            <div>
              <label class="text-sm font-semibold text-white/90">Password</label>

              <div class="mt-1 flex items-center gap-2 rounded-xl border border-white/20 bg-white/10 px-3 py-2 focus-within:ring-4 focus-within:ring-emerald-300/20">
                <span class="text-white/70">🔒</span>

                <input
                  id="passwordInput"
                  type="password"
                  name="password"
                  class="w-full bg-transparent text-sm text-white placeholder:text-white/60 focus:outline-none"
                  placeholder="••••••••"
                  autocomplete="current-password"
                  required
                />

                <button
                  id="togglePassBtn"
                  type="button"
                  class="rounded-lg px-2 py-1 text-white/80 hover:bg-white/10 flex items-center relative"
                  aria-label="Toggle password visibility"
                >
                  <!-- OPEN EYE (visible password) -->
                  <svg class="pw-eye h-4 w-4 transition-all duration-200 ease-out opacity-0 scale-90"
                       xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                       stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"/>
                    <circle cx="12" cy="12" r="3"/>
                  </svg>

                  <!-- CLOSED EYE (hidden password) - default -->
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

              <div class="mt-2 flex items-center justify-between">
                <label class="inline-flex items-center gap-2 text-xs font-semibold text-white/80">
                  <input
                    type="checkbox"
                    name="remember"
                    class="h-4 w-4 rounded border-white/30 bg-transparent text-emerald-500 focus:ring-emerald-300/30"
                    {{ old('remember') ? 'checked' : '' }}
                  />
                  Remember me
                </label>

                <a href="#" class="text-xs font-semibold text-emerald-200 hover:underline">
                  Forgot password?
                </a>
              </div>
            </div>

            <button
              type="submit"
              class="mt-2 w-full rounded-xl bg-emerald-500 px-4 py-2.5 text-sm font-semibold text-white shadow-sm
                     hover:bg-emerald-600 focus:outline-none focus:ring-4 focus:ring-emerald-300/30"
            >
              Login
            </button>

            <p class="pt-2 text-center text-xs text-white/70">Authorized personnel only.</p>
          </form>
        </div>

        <p class="mt-5 text-center text-xs text-white/70">WorkSITE • Admin Panel</p>
      </div>
    </div>
  </div>

<script>
  const passInput = document.getElementById('passwordInput');
  const toggleBtn = document.getElementById('togglePassBtn');

  toggleBtn?.addEventListener('click', () => {
    // If currently hidden (password), make it visible (text)
    const willShow = passInput.type === 'password';
    passInput.type = willShow ? 'text' : 'password';

    const eye = toggleBtn.querySelector('.pw-eye');      // open eye
    const eyeOff = toggleBtn.querySelector('.pw-eyeoff'); // closed/slash

    if (willShow) {
      // Now VISIBLE -> show OPEN eye
      eye.classList.add('opacity-100','scale-100');
      eye.classList.remove('opacity-0','scale-90');

      eyeOff.classList.add('opacity-0','scale-90');
      eyeOff.classList.remove('opacity-100','scale-100');
    } else {
      // Now HIDDEN -> show CLOSED eye
      eye.classList.add('opacity-0','scale-90');
      eye.classList.remove('opacity-100','scale-100');

      eyeOff.classList.add('opacity-100','scale-100');
      eyeOff.classList.remove('opacity-0','scale-90');
    }
  });
</script>

</body>
</html>
