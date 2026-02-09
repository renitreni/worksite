@extends('employer.layout')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-semibold text-gray-900">Rejected Applicants</h1>
    <button
        type="button"
        class="inline-flex items-center gap-2 rounded-2xl border border-red-500 bg-red-500 px-4 py-2 text-sm font-semibold text-white hover:bg-red-600 transition"
        @click="alert('Frontend-only: Export Rejected Applicants')"
    >
        <i data-lucide="download" class="h-4 w-4"></i>
        Export List
    </button>
</div>

<div class="overflow-x-auto bg-white rounded-xl shadow border border-gray-200">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applicant Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applied Position</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach(range(1,4) as $applicant)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Applicant {{ $applicant }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Frontend Developer</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">rejected{{ $applicant }}@example.com</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">+63 {{ rand(900000000, 999999999) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex rounded-full bg-red-100 px-2 py-1 text-xs font-semibold text-red-700">Rejected</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center flex justify-center gap-2">
                        <button class="text-blue-600 hover:underline" @click="alert('Frontend-only: View Applicant')">View</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection