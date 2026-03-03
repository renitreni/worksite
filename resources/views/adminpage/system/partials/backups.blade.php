{{-- resources/views/admin/system/partials/backups.blade.php --}}
@section('title', 'System Configuration')
@section('page_title', 'System Configuration')
<h2 class="text-lg font-semibold mb-4">Backups</h2>

<div class="rounded-lg border border-gray-200 p-4">
    <p class="text-sm text-gray-600 mb-3">
        Run a database backup and view backup history.
    </p>

    <a href="{{ route('admin.backups.index') }}"
       class="inline-flex items-center rounded-lg bg-gray-900 px-4 py-2 text-white text-sm hover:bg-gray-800">
        Open Backups Page
    </a>
</div>