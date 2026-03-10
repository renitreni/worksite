@extends('adminpage.layout')
@section('title', 'View Emails')
@section('page_title', 'View Email Templates')
@section('content')

<div class="max-w-6xl mx-auto p-4">

    @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-50 p-3 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
        <div class="flex items-center justify-between border-b border-gray-100 px-4 py-4">
            <div>
                <h2 class="text-base font-semibold text-gray-900">Email Templates</h2>
                <p class="text-sm text-gray-500">Manage system email subjects and content.</p>
            </div>

            <a href="{{ route('admin.email_templates.create') }}"
               class="inline-flex items-center rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">
                + New Template
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium">Name</th>
                        <th class="px-4 py-3 text-left font-medium">Subject</th>
                        <th class="px-4 py-3 text-left font-medium">Active</th>
                        <th class="px-4 py-3 text-right font-medium">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($templates as $t)
                        <tr>
                            <td class="px-4 py-3 font-medium text-gray-900">{{ $t->name }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $t->subject }}</td>
                            <td class="px-4 py-3">
                                @if($t->is_active)
                                    <span class="inline-flex items-center rounded-full border border-green-200 bg-green-50 px-2 py-0.5 text-xs text-green-700">Active</span>
                                @else
                                    <span class="inline-flex items-center rounded-full border border-gray-200 bg-gray-50 px-2 py-0.5 text-xs text-gray-700">Disabled</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('admin.email_templates.edit', $t) }}"
                                   class="inline-flex items-center rounded-lg border border-gray-300 px-3 py-1.5 text-sm hover:bg-gray-50">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-gray-500">No templates found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-4 py-3 border-t border-gray-100">
            {{ $templates->links() }}
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('admin.system.index') }}"
           class="text-sm text-gray-700 hover:text-gray-900 underline">
            ← Back to System Configuration
        </a>
    </div>
</div>
@endsection