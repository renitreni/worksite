@extends('employer.layout')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-semibold text-gray-900">Closed Job Postings</h1>
</div>

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
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 line-through">{{ $job->title }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">{{ $job->location }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">{{ $job->created_at->format('M d, Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">{{ $job->applications()->count() ?? 0 }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex rounded-full bg-gray-200 px-2 py-1 text-xs font-semibold text-gray-600">Closed</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center flex justify-center gap-2">
                        {{-- Reopen button --}}
                        <form action="{{ route('employer.job-postings.reopen', $job->id) }}" method="POST" class="inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="text-blue-600 hover:underline cursor-pointer">Reopen</button>
                        </form>

                        {{-- View job --}}
                        <a href="{{ route('employer.job-postings.show', $job->id) }}" class="text-gray-600 hover:underline">View</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No closed jobs found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection