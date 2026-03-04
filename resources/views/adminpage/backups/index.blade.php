@extends('adminpage.layout')
@section('title', 'Backups')
@section('page_title', 'Generate Backups')
@section('content')
<div class="max-w-6xl mx-auto p-4">
    

        <form method="POST" action="{{ route('admin.backups.run') }}">
            @csrf
            <button class="inline-flex items-center rounded-lg bg-gray-900 px-4 py-2 text-white text-sm hover:bg-gray-800">
                Run Backup
            </button>
        </form>
    </div>

    {{-- Flash --}}
    @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-50 p-3 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 rounded-lg bg-red-50 p-3 text-red-800">
            {{ session('error') }}
        </div>
    @endif

    {{-- Validation errors (for restore form) --}}
    @if ($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 p-3 text-red-800">
            <div class="font-semibold mb-1">Please fix the following:</div>
            <ul class="list-disc pl-5 text-sm">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Restore --}}
    <div class="mb-6 rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
        <h2 class="text-sm font-semibold text-gray-900">Restore Database</h2>
        <p class="mt-1 text-xs text-gray-500">
            This will overwrite the current database. Use with extreme caution.
        </p>

        <form method="POST" action="{{ route('admin.backups.restore') }}" class="mt-4 space-y-3">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700">Select backup</label>
                <select name="backup_run_id" class="mt-1 w-full rounded-lg border-gray-300">
                    @foreach(($restoreCandidates ?? collect()) as $b)
                        <option value="{{ $b->id }}">
                            #{{ $b->id }} • {{ ($b->finished_at ?? $b->created_at)->format('Y-m-d H:i') }} • {{ $b->file_path }}
                        </option>
                    @endforeach
                </select>
                @if(($restoreCandidates ?? collect())->isEmpty())
                    <p class="mt-2 text-sm text-gray-500">No restorable backups found yet.</p>
                @endif
            </div>

            <label class="flex items-center gap-2 text-sm text-gray-700">
                <input type="checkbox" name="confirm" value="1" class="rounded border-gray-300">
                I understand this will overwrite the database.
            </label>

            <button type="submit"
                class="inline-flex items-center rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700 disabled:opacity-60"
                {{ (($restoreCandidates ?? collect())->isEmpty()) ? 'disabled' : '' }}>
                Restore Now
            </button>

            <p class="text-xs text-gray-500">
                You will be asked to confirm your password before restore runs.
            </p>
        </form>
    </div>

    {{-- History --}}
    <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium">ID</th>
                        <th class="px-4 py-3 text-left font-medium">Type</th>
                        <th class="px-4 py-3 text-left font-medium">Status</th>
                        <th class="px-4 py-3 text-left font-medium">Started</th>
                        <th class="px-4 py-3 text-left font-medium">Finished</th>
                        <th class="px-4 py-3 text-left font-medium">Requested By</th>
                        <th class="px-4 py-3 text-left font-medium">Log</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($runs as $run)
                        <tr>
                            <td class="px-4 py-3">#{{ $run->id }}</td>
                            <td class="px-4 py-3">{{ $run->type }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $badge = match($run->status) {
                                        'success' => 'bg-green-50 text-green-700 border-green-200',
                                        'failed' => 'bg-red-50 text-red-700 border-red-200',
                                        'running' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                        default => 'bg-gray-50 text-gray-700 border-gray-200',
                                    };
                                @endphp
                                <span class="inline-flex items-center rounded-full border px-2 py-0.5 text-xs {{ $badge }}">
                                    {{ $run->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3">{{ optional($run->started_at)->format('Y-m-d H:i') ?? '-' }}</td>
                            <td class="px-4 py-3">{{ optional($run->finished_at)->format('Y-m-d H:i') ?? '-' }}</td>
                            <td class="px-4 py-3">{{ optional($run->requester)->name ?? '-' }}</td>
                            <td class="px-4 py-3">
                                @if($run->log)
                                    <details class="cursor-pointer">
                                        <summary class="text-gray-700 hover:text-gray-900">View</summary>
                                        <pre class="mt-2 max-h-56 overflow-auto rounded-lg bg-gray-900 p-3 text-xs text-gray-100 whitespace-pre-wrap">{{ $run->log }}</pre>
                                    </details>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-6 text-center text-gray-500">
                                No backups yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-4 py-3 border-t border-gray-100">
            {{ $runs->links() }}
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('admin.system.index', ['tab' => 'backups']) }}"
           class="text-sm text-gray-700 hover:text-gray-900 underline">
            ← Back to System Configuration
        </a>
    </div>
</div>
@endsection