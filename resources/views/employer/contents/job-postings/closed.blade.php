@extends('employer.layout')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-semibold text-gray-900">Closed Job Postings</h1>
    <button
        type="button"
        class="inline-flex items-center gap-2 rounded-2xl border border-emerald-500 bg-emerald-500 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-600 transition"
        @click="alert('Frontend-only: Open Post Job Modal')"
    >
        <i data-lucide="plus" class="h-4 w-4"></i>
        Post New Job
    </button>
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
            {{-- Dummy closed job postings --}}
            @foreach(range(1,5) as $job)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 line-through">Software Engineer {{ $job }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">Manila, Philippines</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">{{ now()->subDays($job*10)->format('M d, Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">{{ rand(5,50) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex rounded-full bg-gray-200 px-2 py-1 text-xs font-semibold text-gray-600">Closed</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center flex justify-center gap-2">
                        <button class="text-blue-600 hover:underline" @click="alert('Frontend-only: Reopen Job')">Reopen</button>
                        <button class="text-gray-600 hover:underline" @click="alert('Frontend-only: View Job')">View</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection