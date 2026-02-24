@extends('adminpage.layout')
@section('title','Subscription Plans')
@section('page_title','Subscription Plans')

@section('content')
<div class="space-y-6" x-data="{ confirmOpen:false, deleteUrl:'' }" x-cloak>

  {{-- Header actions --}}
  <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <form method="GET" action="{{ route('admin.subscriptions.plans.index') }}" class="flex gap-2 w-full sm:w-auto">
      <input type="text" name="q" value="{{ $q ?? '' }}"
             class="w-full sm:w-80 rounded-xl border border-slate-200 bg-white px-3 py-2"
             placeholder="Search plan name or code...">
      <button class="rounded-xl bg-slate-900 px-4 py-2 text-white">Search</button>
    </form>

    <a href="{{ route('admin.subscriptions.plans.create') }}"
       class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2 text-white">
      + Create Plan
    </a>
  </div>

  {{-- Table --}}
  <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-slate-200">
        <thead class="bg-slate-50">
          <tr class="text-left text-sm font-semibold text-slate-700">
            <th class="px-4 py-3">Code</th>
            <th class="px-4 py-3">Name</th>
            <th class="px-4 py-3">Price</th>
            <th class="px-4 py-3">Active</th>
            <th class="px-4 py-3">Updated</th>
            <th class="px-4 py-3 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 text-sm">
          @forelse($plans as $plan)
            <tr>
              <td class="px-4 py-3 font-mono text-slate-700">{{ $plan->code }}</td>
              <td class="px-4 py-3">{{ $plan->name }}</td>
              <td class="px-4 py-3">₱{{ number_format((int)$plan->price) }}</td>
              <td class="px-4 py-3">
                @if($plan->is_active)
                  <span class="rounded-full bg-emerald-50 px-2 py-1 text-xs font-semibold text-emerald-700">Yes</span>
                @else
                  <span class="rounded-full bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-600">No</span>
                @endif
              </td>
              <td class="px-4 py-3 text-slate-600">{{ optional($plan->updated_at)->format('Y-m-d') }}</td>
              <td class="px-4 py-3">
                <div class="flex justify-end gap-2">
                  <a class="rounded-lg border border-slate-200 px-3 py-1.5 hover:bg-slate-50"
                     href="{{ route('admin.subscriptions.plans.show', $plan) }}">View</a>
                  <a class="rounded-lg border border-slate-200 px-3 py-1.5 hover:bg-slate-50"
                     href="{{ route('admin.subscriptions.plans.edit', $plan) }}">Edit</a>

                  <button
                    class="rounded-lg border border-red-200 px-3 py-1.5 text-red-700 hover:bg-red-50"
                    @click="confirmOpen=true; deleteUrl='{{ route('admin.subscriptions.plans.destroy', $plan) }}'">
                    Delete
                  </button>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-4 py-8 text-center text-slate-500">No plans found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="p-4">
      {{ $plans->links() }}
    </div>
  </div>

  {{-- Delete modal --}}
  <div x-show="confirmOpen" class="fixed inset-0 z-50 grid place-items-center bg-black/40 p-4">
    <div class="w-full max-w-md rounded-2xl bg-white p-5 shadow-lg">
      <h3 class="text-lg font-semibold text-slate-900">Delete plan?</h3>
      <p class="mt-2 text-sm text-slate-600">This will soft-delete the plan.</p>

      <div class="mt-4 flex justify-end gap-2">
        <button class="rounded-xl border border-slate-200 px-4 py-2" @click="confirmOpen=false">Cancel</button>
        <form method="POST" :action="deleteUrl">
          @csrf
          @method('DELETE')
          <button class="rounded-xl bg-red-600 px-4 py-2 text-white">Delete</button>
        </form>
      </div>
    </div>
  </div>

</div>
@endsection