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

    {{-- Background image (FIXED) --}}
    <div
      class="absolute inset-0 bg-cover bg-center"
      style="background-image: url('{{ asset('images/background.png') }}');"
      aria-hidden="true"
    ></div>

    {{-- Dark overlay for readability --}}
    <div class="absolute inset-0 bg-slate-950/55" aria-hidden="true"></div>

    {{-- Vignette (focus eyes to center) --}}
    <div
      class="absolute inset-0"
      style="background: radial-gradient(circle at center, rgba(0,0,0,0.05) 0%, rgba(0,0,0,0.65) 70%, rgba(0,0,0,0.80) 100%);"
      aria-hidden="true"
    ></div>

    {{-- Subtle spotlight glow behind the card --}}
    <div class="absolute inset-0 flex items-center justify-center" aria-hidden="true">
      <div class="h-[420px] w-[420px] rounded-full bg-emerald-400/15 blur-3xl"></div>
    </div>

    {{-- Optional subtle “noise” --}}
    <div class="absolute inset-0 opacity-[0.06]" aria-hidden="true">
      <div class="h-full w-full bg-[radial-gradient(circle_at_1px_1px,_#fff_1px,_transparent_0)] [background-size:18px_18px]"></div>
    </div>

    {{-- Page content --}}
    <div class="relative z-10 flex min-h-screen items-center justify-center px-4 sm:px-6 lg:px-8">
      <div class="w-full max-w-md">

        <div class="mb-4 flex justify-center">
          <span class="inline-flex items-center rounded-full border border-white/15 bg-white/10 px-3 py-1 text-xs font-semibold text-white/90">
            Secure Admin Access
          </span>
        </div>

        {{-- GLASS CARD --}}
        <div
          x-data="{ showPass:false }"
          class="rounded-3xl border border-white/20 bg-white/15 p-7 shadow-[0_20px_60px_rgba(0,0,0,0.45)] backdrop-blur-xl sm:p-8"
        >
          {{-- Header --}}
          <div class="flex flex-col items-center text-center">
            {{-- Logo wrapper keeps card size stable --}}
            <div class="h-20 flex items-center justify-center">
              <img
                src="{{ asset('images/logo.png') }}"
                alt="WorkSITE"
                class="h-40 w-auto object-contain drop-shadow-lg"
              />
            </div>

            {{-- OPTIONAL POLISH: divider --}}
            <div class="mt-4 h-px w-20 bg-white/25"></div>

            <h1 class="mt-4 text-xl font-bold tracking-tight text-white">
              Admin Login
            </h1>
            <p class="mt-1 text-sm text-white/80">
              Please sign in to manage the system.
            </p>
          </div>

          {{-- Form --}}
          <form class="mt-6 space-y-4" action="{{ route('admin.dashboard') }}" method="get">

            <div>
              <label class="text-sm font-semibold text-white/90">Email / Username</label>
              <div class="mt-1 flex items-center gap-2 rounded-xl border border-white/20 bg-white/10 px-3 py-2 focus-within:ring-4 focus-within:ring-emerald-300/20">
                <span class="text-white/70">👤</span>
                <input
                  type="text"
                  class="w-full bg-transparent text-sm text-white placeholder:text-white/60 focus:outline-none"
                  placeholder="admin@worksite.com"
                  autocomplete="username"
                />
              </div>
            </div>

            <div>
              <label class="text-sm font-semibold text-white/90">Password</label>

              <div class="mt-1 flex items-center gap-2 rounded-xl border border-white/20 bg-white/10 px-3 py-2 focus-within:ring-4 focus-within:ring-emerald-300/20">
                <span class="text-white/70">🔒</span>

                <input
                  :type="showPass ? 'text' : 'password'"
                  class="w-full bg-transparent text-sm text-white placeholder:text-white/60 focus:outline-none"
                  placeholder="••••••••"
                  autocomplete="current-password"
                />

                <button
                  type="button"
                  class="rounded-lg px-2 py-1 text-xs font-semibold text-white/80 hover:bg-white/10"
                  @click="showPass = !showPass"
                >
                  <span x-text="showPass ? 'Hide' : 'Show'"></span>
                </button>
              </div>

              <div class="mt-2 flex items-center justify-between">
                <label class="inline-flex items-center gap-2 text-xs font-semibold text-white/80">
                  <input type="checkbox" class="h-4 w-4 rounded border-white/30 bg-transparent text-emerald-500 focus:ring-emerald-300/30" />
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

            <p class="pt-2 text-center text-xs text-white/70">
              Authorized personnel only.
            </p>
          </form>
        </div>

        <p class="mt-5 text-center text-xs text-white/70">
          WorkSITE • Admin Panel
        </p>
      </div>
    </div>
  </div>

  {{-- Alpine (if not already bundled in app.js) --}}
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
