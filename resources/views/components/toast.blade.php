{{-- resources/views/components/toast.blade.php --}}
<div
  x-cloak
  class="pointer-events-none fixed right-4 top-4 z-[9999] flex w-[22rem] max-w-[calc(100vw-2rem)] flex-col gap-3"
>
  <template x-for="t in $store.toast.items" :key="t.id">
    <div
      class="pointer-events-auto overflow-hidden rounded-2xl border border-white/20 bg-slate-900/80 text-white shadow-2xl backdrop-blur-xl"
      @mouseenter="$store.toast.pause(t.id)"
      @mouseleave="$store.toast.resume(t.id)"
    >
      <div class="flex items-start gap-3 p-4">
        <div class="mt-0.5 text-lg">
          <span x-show="t.type==='success'">✅</span>
          <span x-show="t.type==='error'">⛔</span>
          <span x-show="t.type==='warning'">⚠️</span>
          <span x-show="t.type==='info'">ℹ️</span>
        </div>

        <div class="min-w-0 flex-1">
          <div class="flex items-start justify-between gap-3">
            <div class="min-w-0">
              <div class="text-sm font-bold" x-text="t.title"></div>
              <div class="mt-1 text-sm text-white/85" x-text="t.message"></div>
            </div>

            <button
              type="button"
              class="rounded-lg px-2 py-1 text-xs font-semibold text-white/80 hover:bg-white/10"
              @click="$store.toast.remove(t.id)"
            >
              ✕
            </button>
          </div>
        </div>
      </div>

      <div class="h-1 w-full bg-white/10">
        <div class="h-1 bg-emerald-400" :style="`width:${t.progress}%;`"></div>
      </div>
    </div>
  </template>
</div>
