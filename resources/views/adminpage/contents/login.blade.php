<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin Login</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50 text-slate-900">
  <div class="mx-auto flex min-h-screen max-w-7xl items-center px-4 sm:px-6 lg:px-8">
    <div class="grid w-full grid-cols-1 gap-10 lg:grid-cols-2">
      <div class="hidden lg:block">
        <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
          <div class="flex items-center gap-3">
            <div class="grid h-12 w-12 place-items-center rounded-2xl bg-emerald-600 text-white font-bold">WS</div>
            <div>
              <div class="text-lg font-semibold">WorkSITE Admin</div>
              <div class="text-sm text-slate-600">Secure access for authorized administrators.</div>
            </div>
          </div>

          <div class="mt-6 rounded-2xl border border-slate-200 bg-slate-50 p-5">
            <div class="text-sm font-semibold text-slate-800">Admin capabilities</div>
            <ul class="mt-3 list-disc space-y-1 pl-5 text-sm text-slate-700">
              <li>Approve employers and job postings</li>
              <li>Manage subscriptions and payments</li>
              <li>Generate reports and exports</li>
              <li>Configure system security and settings</li>
            </ul>
          </div>

          <p class="mt-4 text-xs text-slate-500">
            This is frontend-only UI for now. Authentication will be connected later.
          </p>
        </div>
      </div>

      <div class="flex items-center">
        <div class="w-full rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
          <div class="flex items-center gap-3">
            <div class="grid h-11 w-11 place-items-center rounded-2xl bg-emerald-600 text-white">🔒</div>
            <div>
              <div class="text-lg font-semibold">Admin Login</div>
              <div class="text-sm text-slate-600">Enter credentials to continue.</div>
            </div>
          </div>

          <form class="mt-6 space-y-4" action="{{ route('admin.dashboard') }}" method="get">
            <div>
              <label class="text-sm font-medium text-slate-700">Email / Username</label>
              <input type="text"
                     class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm outline-none focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100"
                     placeholder="admin@worksite.com" />
            </div>

            <div>
              <label class="text-sm font-medium text-slate-700">Password</label>
              <input type="password"
                     class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm outline-none focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100"
                     placeholder="••••••••" />
            </div>

            <button type="submit"
                    class="w-full rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-4 focus:ring-emerald-100">
              Login
            </button>

            <p class="text-center text-xs text-slate-500">Authorized personnel only.</p>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
