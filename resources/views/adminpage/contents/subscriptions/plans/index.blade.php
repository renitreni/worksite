@extends('adminpage.layout')
@section('title','Subscription Plans')
@section('page_title','Subscription Plans')

@section('content')
@php
  $q = $q ?? request('q', '');
@endphp

<div class="space-y-6"
     x-data="{ confirmOpen:false, deleteUrl:'' }"
     x-cloak
     @keydown.escape.window="confirmOpen=false">

  @include('adminpage.components.flash')

  <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <form method="GET"
          action="{{ route('admin.subscriptions.plans.index') }}"
          class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">

      <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:flex-wrap">

        <div class="flex w-full items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 sm:w-96">
          <span class="text-slate-400">⌕</span>
          <input
            name="q"
            value="{{ $q }}"
            class="w-full bg-transparent text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none"
            placeholder="Search plan name or code..."
          />
        </div>

      </div>

      <div class="flex items-center gap-2">
        <button class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
          Search
        </button>

        @if($q)
          <a
            href="{{ route('admin.subscriptions.plans.index') }}"
            class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50"
          >
            Clear
          </a>
        @endif

        <a href="{{ route('admin.subscriptions.plans.create') }}"
           class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
          + Create Plan
        </a>
      </div>

    </form>
  </div>

  <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="border-b border-slate-200 px-5 py-4">
      <div class="flex items-center justify-between">
        <div>
          <h2 class="text-sm font-semibold text-slate-900">Plans</h2>
          <p class="mt-0.5 text-xs text-slate-500">Manage pricing tiers and feature limits.</p>
        </div>
        <p class="text-xs text-slate-500">Total: {{ $plans->total() }}</p>
      </div>
    </div>

    <div class="overflow-x-auto">
      <table class="min-w-full text-left text-sm">
        <thead class="bg-slate-50 text-xs font-semibold text-slate-600">
          <tr>
            <th class="px-5 py-3">Code</th>
            <th class="px-5 py-3">Name</th>
            <th class="px-5 py-3">Price</th>
            <th class="px-5 py-3">Status</th>
            <th class="px-5 py-3">Updated</th>
            <th class="px-5 py-3 text-right">Actions</th>
          </tr>
        </thead>

        <tbody class="divide-y divide-slate-200">
          @forelse($plans as $plan)
            <tr class="hover:bg-slate-50">

              <td class="px-5 py-3">
                <span class="font-mono text-xs text-slate-600 bg-slate-50 border border-slate-200 px-2 py-1 rounded-lg">
                  {{ $plan->code }}
                </span>
              </td>

              <td class="px-5 py-3">
                <div class="font-semibold text-slate-900">{{ $plan->name }}</div>
              </td>

              <td class="px-5 py-3 text-slate-700">
                ₱{{ number_format((int) $plan->price) }}
              </td>

              <td class="px-5 py-3">
                @if($plan->is_active)
                  <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 border border-emerald-200">
                    Active
                  </span>
                @else
                  <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600 border border-slate-200">
                    Inactive
                  </span>
                @endif
              </td>

              <td class="px-5 py-3 text-slate-600">
                {{ optional($plan->updated_at)->format('M d, Y') }}
              </td>

              <td class="px-5 py-3">
                <div class="flex justify-end gap-2">

                  <a href="{{ route('admin.subscriptions.plans.show', $plan) }}"
                     class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                    View
                  </a>

                  <a href="{{ route('admin.subscriptions.plans.edit', $plan) }}"
                     class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                    Edit
                  </a>

                  <button type="button"
                          class="rounded-xl border border-rose-200 bg-white px-3 py-2 text-xs font-semibold text-rose-700 hover:bg-rose-50"
                          @click="confirmOpen=true; deleteUrl='{{ route('admin.subscriptions.plans.destroy', $plan) }}'">
                    Delete
                  </button>

                </div>
              </td>

            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-5 py-10 text-center text-slate-500">
                No plans found.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="border-t border-slate-200 px-5 py-4">
      {{ $plans->links() }}
    </div>
  </div>

  {{-- ✅ DELETE MODAL (matched with your admin modals) --}}
  <div x-show="confirmOpen" x-transition.opacity
       class="fixed inset-0 z-50 flex items-center justify-center p-4"
       @click.self="confirmOpen=false">

    <div class="absolute inset-0 bg-black/40"></div>

    <div class="relative w-full max-w-md">
      <div class="rounded-2xl bg-white shadow-xl ring-1 ring-slate-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100">
          <div class="text-base font-semibold text-slate-900">Delete plan?</div>
          <p class="mt-1 text-sm text-slate-600">
            This will soft-delete the plan (if you enabled soft deletes). You can restore it later.
          </p>
        </div>

        <div class="p-6">
          <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-2">
            <button type="button"
                    class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50"
                    @click="confirmOpen=false">
              Cancel
            </button>

            <form method="POST" :action="deleteUrl">
              @csrf
              @method('DELETE')
              <button type="submit"
                      class="rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-rose-700">
                Delete
              </button>
            </form>
          </div>
        </div>

      </div>
    </div>
  </div>

</div>
@endsection