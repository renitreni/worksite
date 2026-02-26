@php
  $showLimitModal = session('candidate_view_limit_modal') === true;
  $limitData = session('candidate_view_limit_data', []);
@endphp

<div
  x-data="{
    open: @js($showLimitModal),
    limit: @js($limitData['limit'] ?? null),
    usedToday: @js($limitData['usedToday'] ?? 0),
  }"
>
  {{-- DAILY VIEW LIMIT MODAL --}}
  <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition.opacity>
    <div class="absolute inset-0 bg-black/50" @click="open = false"></div>

    <div class="relative w-full max-w-lg" x-transition.scale.origin.center>
      <div class="overflow-hidden rounded-2xl bg-white shadow-xl ring-1 ring-slate-200">

        {{-- Header --}}
        <div class="px-6 py-5 border-b border-slate-100">
          <div class="flex items-start justify-between gap-4">
            <div>
              <h3 class="text-lg font-semibold text-slate-900">Daily view limit reached</h3>
              <p class="mt-1 text-sm text-slate-600">
                You can’t view more applicant details right now.
              </p>
            </div>
            <button @click="open = false" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100">
              ✕
            </button>
          </div>
        </div>

        {{-- Body --}}
        <div class="px-6 py-5 space-y-4">
          <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
            <div class="text-sm text-slate-700 space-y-1">
              <div>
                <span class="font-semibold">Views used today:</span>
                <span x-text="usedToday ?? '-'"></span>
              </div>

              <template x-if="limit !== null">
                <div>
                  <span class="font-semibold">Your plan limit (per day):</span>
                  <span x-text="limit"></span>
                </div>
              </template>

              <template x-if="limit === null">
                <div>
                  <span class="font-semibold">Your plan limit:</span>
                  <span>Unlimited</span>
                </div>
              </template>

              <div class="pt-2 text-xs text-slate-500">
                This limit resets every day.
              </div>
            </div>
          </div>

          <div class="text-sm text-slate-700">
            To view more applicants today, you can:
            <ul class="mt-2 list-disc pl-5 space-y-1">
              <li>Upgrade your subscription plan</li>
              <li>Or wait until the next day when your limit resets</li>
            </ul>
          </div>
        </div>

        {{-- Footer --}}
        <div class="px-6 py-5 border-t border-slate-100 flex flex-col sm:flex-row gap-2 sm:justify-end">
          <button @click="open = false"
                  class="rounded-xl border border-slate-200 px-4 py-2 text-slate-700 hover:bg-slate-50">
            Okay
          </button>

          <a href="{{ route('employer.subscription.dashboard') }}"
             class="rounded-xl bg-emerald-600 px-4 py-2 text-white text-center hover:bg-emerald-700">
            Upgrade Plan
          </a>
        </div>

      </div>
    </div>
  </div>
</div>