@extends('adminpage.layout') {{-- change if needed --}}
@section('title', 'Edit Emails')
@section('page_title', 'Edit Email Template')
@section('content')
<div class="max-w-6xl mx-auto p-4">
    <div class="mb-6 flex items-start justify-between gap-4">
        <div>
            <p class="text-sm text-gray-500">
                <span class="font-medium text-gray-900">{{ $template->name }}</span>
            </p>
        </div>

        <a href="{{ route('admin.email_templates.index') }}"
           class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm hover:bg-gray-50">
            Back to list
        </a>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-50 border border-green-200 p-3 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 rounded-lg bg-red-50 border border-red-200 p-3 text-red-800">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid lg:grid-cols-3 gap-6">
        {{-- Form --}}
        <div class="lg:col-span-2 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route('admin.email_templates.update', $template) }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-gray-700">Subject</label>
                    <input
                        name="subject"
                        value="{{ old('subject', $template->subject) }}"
                        class="mt-1 w-full rounded-lg border-gray-300 focus:border-emerald-600 focus:ring-emerald-600"
                    />
                    @error('subject') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Body (HTML)</label>
                    <textarea
                        name="body_html"
                        rows="10"
                        class="mt-1 w-full rounded-lg border-gray-300 font-mono text-sm focus:border-emerald-600 focus:ring-emerald-600"
                    >{{ old('body_html', $template->body_html) }}</textarea>
                    @error('body_html') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Body (Text - optional)</label>
                    <textarea
                        name="body_text"
                        rows="6"
                        class="mt-1 w-full rounded-lg border-gray-300 font-mono text-sm focus:border-emerald-600 focus:ring-emerald-600"
                    >{{ old('body_text', $template->body_text) }}</textarea>
                    @error('body_text') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Divider --}}
                <div class="pt-2 border-t border-gray-100"></div>

                {{-- Active checkbox (with fallback so uncheck works) --}}
                <input type="hidden" name="is_active" value="0">
                <label class="flex items-center gap-3">
                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-600"
                        {{ old('is_active', $template->is_active) ? 'checked' : '' }}
                    >
                    <span class="text-sm font-medium text-gray-700">Active</span>
                </label>

                {{-- Action bar --}}
                <div class="pt-3 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-end">
                    <button
                        type="button"
                        id="btnPreview"
                        class="inline-flex justify-center items-center rounded-lg border border-gray-300 bg-white px-5 py-2 text-sm font-medium hover:bg-gray-50"
                    >
                        Preview (sample data)
                    </button>

                    <button
                        type="submit"
                        class="inline-flex justify-center items-center rounded-lg bg-emerald-600 px-6 py-2 text-sm font-semibold text-white hover:bg-emerald-700"
                    >
                        Save Changes
                    </button>
                </div>
            </form>
        </div>

        {{-- Placeholders + Preview --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="text-sm font-semibold text-gray-900 mb-2">Placeholders</h2>
            <p class="text-xs text-gray-500 mb-3">You can use these tokens in subject/body.</p>

            <div class="flex flex-wrap gap-2 mb-6">
                @forelse(($template->placeholders ?? []) as $ph)
                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-mono text-gray-700">{{ $ph }}</span>
                @empty
                    <span class="text-sm text-gray-400">No placeholders defined.</span>
                @endforelse
            </div>

            <h2 class="text-sm font-semibold text-gray-900 mb-2">Preview</h2>
            <div id="previewBox" class="rounded-lg border border-gray-200 bg-gray-50 p-3 text-sm text-gray-800">
                Click “Preview” to load sample output.
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('btnPreview')?.addEventListener('click', async () => {
    const url = @json(route('admin.email_templates.preview', $template));
    const previewBox = document.getElementById('previewBox');

    previewBox.textContent = 'Loading preview...';

    try {
        const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
        const data = await res.json();

        previewBox.innerHTML =
            `<div class="mb-2">
                <div class="text-xs text-gray-500">Subject</div>
                <div class="font-medium">${escapeHtml(data.subject ?? '')}</div>
            </div>` +
            `<div class="text-xs text-gray-500">Body (HTML)</div>
             <div class="prose max-w-none">${data.body_html ?? ''}</div>`;
    } catch (e) {
        previewBox.textContent = 'Failed to load preview.';
    }
});

function escapeHtml(str) {
    return String(str).replace(/[&<>"']/g, (m) => ({
        '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'
    }[m]));
}
</script>
@endsection