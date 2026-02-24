@extends('adminpage.layout')
@section('title','Subscription Plans')
@section('page_title','Subscription Plans')

@section('content')
<div class="w-full max-w-7xl mx-auto space-y-6"
     x-data="{ confirmOpen:false, deleteUrl:'' }"
     x-cloak
     @keydown.escape.window="confirmOpen=false">

  {{-- Top bar --}}
  <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
    <div class="px-6 sm:px-8 py-6 border-b border-slate-200 bg-gradient-to-r from-emerald-50 to-white">
      <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
        <div>
          <h1 class="text-2xl sm:text-3xl font-semibold text-slate-900">Subscription Plans</h1>
          <p class="mt-1 text-sm text-slate-600">Manage pricing tiers and feature limits.</p>
        </div>

        <div class="flex flex-col sm:flex-row gap-2">
          <form method="GET" action="{{ route('admin.subscriptions.plans.index') }}" class="flex gap-2 w-full sm:w-auto">
            <div class="relative w-full sm:w-80">
              <input type="text" name="q" value="{{ $q ?? '' }}"
                     class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm
                            focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400"
                     placeholder="Search plan name or code...">
            </div>
            <button type="submit"
                    class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800">
              Search
            </button>
          </form>

          <a href="{{ route('admin.subscriptions.plans.create') }}"
             class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700">
            + Create Plan
          </a>
        </div>
      </div>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-slate-200">
        <thead class="bg-slate-50">
          <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-600">
            <th class="px-6 py-4">Code</th>
            <th class="px-6 py-4">Name</th>
            <th class="px-6 py-4">Price</th>
            <th class="px-6 py-4">Active</th>
            <th class="px-6 py-4">Updated</th>
            <th class="px-6 py-4 text-right">Actions</th>
          </tr>
        </thead>

        <tbody class="divide-y divide-slate-100 bg-white text-sm">
          @forelse($plans as $plan)
            <tr class="hover:bg-slate-50/70">
              <td class="px-6 py-4">
                <div class="font-mono text-slate-700">{{ $plan->code }}</div>
              </td>

              <td class="px-6 py-4">
                <div class="font-semibold text-slate-900">{{ $plan->name }}</div>
              </td>

              <td class="px-6 py-4 text-slate-700">
                ₱{{ number_format((int)$plan->price) }}
              </td>

              <td class="px-6 py-4">
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

              <td class="px-6 py-4 text-slate-600">
                {{ optional($plan->updated_at)->format('M d, Y') }}
              </td>

              <td class="px-6 py-4">
                <div class="flex justify-end gap-2">
                  <a href="{{ route('admin.subscriptions.plans.show', $plan) }}"
                     class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                    View
                  </a>

                  <a href="{{ route('admin.subscriptions.plans.edit', $plan) }}"
                     class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                    Edit
                  </a>

                  <button type="button"
                          class="inline-flex items-center justify-center rounded-xl border border-red-200 px-3 py-2 text-xs font-semibold text-red-700 hover:bg-red-50"
                          @click="confirmOpen=true; deleteUrl='{{ route('admin.subscriptions.plans.destroy', $plan) }}'">
                    Delete
                  </button>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-6 py-10 text-center text-slate-500">
                No plans found.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Pagination --}}
    <div class="px-6 py-4 border-t border-slate-200 bg-white">
      {{ $plans->links() }}
    </div>
  </div>

  {{-- Delete modal --}}
  <div x-show="confirmOpen"
       x-transition.opacity
       class="fixed inset-0 z-50 grid place-items-center bg-black/40 p-4"
       @click.self="confirmOpen=false">
    <div class="w-full max-w-md rounded-3xl bg-white shadow-xl border border-slate-200 overflow-hidden">
      <div class="p-5 sm:p-6">
        <div class="text-lg font-semibold text-slate-900">Delete plan?</div>
        <p class="mt-2 text-sm text-slate-600">
          This will soft-delete the plan. You can restore it later if you have recovery logic.
        </p>

        <div class="mt-5 flex flex-col-reverse sm:flex-row sm:justify-end gap-2">
          <button type="button"
                  class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50"
                  @click="confirmOpen=false">
            Cancel
          </button>

          <form method="POST" :action="deleteUrl">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="inline-flex items-center justify-center rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-red-700">
              Delete
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection