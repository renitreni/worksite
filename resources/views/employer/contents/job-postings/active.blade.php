@extends('employer.layout')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-semibold text-gray-900">Active Job Postings</h1>
    <a href="{{ route('employer.job-postings.create', ['from' => 'listing']) }}"
       class="inline-flex items-center gap-2 rounded-2xl border border-emerald-500 bg-emerald-500 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-600 transition">
        <i data-lucide="plus" class="h-4 w-4"></i>
        Post New Job
    </a>
</div>

{{-- Flash messages --}}
@if(session('success'))
    <div class="mb-4 p-4 rounded-xl bg-emerald-100 text-emerald-800">
        {{ session('success') }}
    </div>
@endif

<div class="overflow-x-auto bg-white rounded-xl shadow border border-gray-200">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Job Title</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Posted Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applications</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($jobs as $job)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $job->title }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $job->location }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $job->created_at->format('M d, Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $job->applications()->count() ?? 0 }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex rounded-full bg-emerald-100 px-2 py-1 text-xs font-semibold text-emerald-700">
                            Active
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center flex justify-center gap-2">
                        <a href="{{ route('employer.job-postings.edit', $job->id) }}" class="text-blue-600 hover:underline">Edit</a>
                        <form action="{{ route('employer.job-postings.destroy', $job->id) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline cursor-pointer">Close</button>
                        </form>
                        <a href="{{ route('employer.job-postings.show', $job->id) }}" class="text-gray-600 hover:underline">View</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No active jobs found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection