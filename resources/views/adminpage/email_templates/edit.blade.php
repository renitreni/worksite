@extends('adminpage.layout')
@section('title', 'Edit Emails')
@section('page_title', 'Edit Email Template')

@section('content')
<div class="max-w-6xl mx-auto p-4" x-data="emailTemplateEditor()">
    <div class="mb-6 flex items-start justify-between gap-4">
        <div>
            <p class="text-sm text-gray-500">
                <span class="font-medium text-gray-900">{{ $template->name }}</span>
            </p>
            <p class="mt-1 text-sm text-gray-500">
                Write the message in plain text. HTML is optional and only for advanced formatting.
            </p>
        </div>

        <a href="{{ route('admin.email_templates.index') }}"
           class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm hover:bg-gray-50">
            Back to list
        </a>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-3 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-red-800">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-3">
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
                        placeholder="Email subject"
                    />
                    @error('subject')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Email Content</label>
                    <p class="mt-1 text-xs text-gray-500">
                        This is the main message admins should edit. Use placeholders from the right side.
                    </p>
                    <textarea
                        id="body_text"
                        name="body_text"
                        rows="12"
                        class="mt-2 w-full rounded-lg border-gray-300 text-sm leading-6 focus:border-emerald-600 focus:ring-emerald-600"
                        placeholder="Write your email message here..."
                    >{{ old('body_text', $template->body_text) }}</textarea>
                    @error('body_text')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="rounded-xl border border-gray-200 bg-gray-50">
                    <button
                        type="button"
                        @click="showHtml = !showHtml"
                        class="flex w-full items-center justify-between px-4 py-3 text-left"
                    >
                        <span class="text-sm font-medium text-gray-800">Advanced HTML Override</span>
                        <span class="text-xs text-gray-500" x-text="showHtml ? 'Hide' : 'Show'"></span>
                    </button>

                    <div x-show="showHtml" x-transition class="border-t border-gray-200 px-4 py-4">
                        <p class="mb-2 text-xs text-gray-500">
                            Optional. Leave this blank unless you need custom HTML formatting.
                        </p>
                        <textarea
                            id="body_html"
                            name="body_html"
                            rows="10"
                            class="w-full rounded-lg border-gray-300 font-mono text-sm focus:border-emerald-600 focus:ring-emerald-600"
                            placeholder="<p>Hello {FULL_NAME},</p>"
                        >{{ old('body_html', $template->body_html) }}</textarea>
                        @error('body_html')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="pt-2 border-t border-gray-100"></div>

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

                <div class="pt-3 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-end">
                    <button
                        type="button"
                        id="btnPreview"
                        class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-5 py-2 text-sm font-medium hover:bg-gray-50"
                    >
                        Preview
                    </button>

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-lg bg-emerald-600 px-6 py-2 text-sm font-semibold text-white hover:bg-emerald-700"
                    >
                        Save Changes
                    </button>
                </div>
            </form>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="mb-2 text-sm font-semibold text-gray-900">Placeholders</h2>
            <p class="mb-3 text-xs text-gray-500">Click a placeholder to insert it into your message.</p>

            @php
                $defaultPlaceholders = match($template->name) {
                    'admin_invitation' => ['{FULL_NAME}', '{SITE_NAME}', '{INVITE_LINK}', '{EXPIRES_IN_HOURS}', '{SUPERADMIN_NAME}'],
                    'verify_email' => ['{USER_NAME}', '{VERIFY_LINK}', '{SITE_NAME}'],
                    'reset_password' => ['{USER_NAME}', '{RESET_LINK}', '{SITE_NAME}'],
                    default => ['{USER_NAME}', '{USER_EMAIL}', '{SITE_NAME}'],
                };

                $placeholders = !empty($template->placeholders ?? null)
                    ? $template->placeholders
                    : $defaultPlaceholders;
            @endphp

            <div class="mb-6 flex flex-wrap gap-2">
                @foreach($placeholders as $ph)
                    <button
                        type="button"
                        class="rounded-full bg-gray-100 px-3 py-1 text-xs font-mono text-gray-700 hover:bg-gray-200"
                        @click="insertPlaceholder('{{ $ph }}')"
                    >
                        {{ $ph }}
                    </button>
                @endforeach
            </div>

            <h2 class="mb-2 text-sm font-semibold text-gray-900">Preview</h2>
            <div id="previewBox" class="rounded-lg border border-gray-200 bg-gray-50 p-3 text-sm text-gray-800">
                Click “Preview” to load sample output.
            </div>
        </div>
    </div>
</div>

<script>
function emailTemplateEditor() {
    return {
        showHtml: false,

        insertPlaceholder(token) {
            const field = document.activeElement && (
                document.activeElement.id === 'body_text' || document.activeElement.id === 'body_html'
            )
                ? document.activeElement
                : document.getElementById('body_text');

            if (!field) return;

            const start = field.selectionStart ?? field.value.length;
            const end = field.selectionEnd ?? field.value.length;
            const value = field.value || '';

            field.value = value.slice(0, start) + token + value.slice(end);
            field.focus();
            field.setSelectionRange(start + token.length, start + token.length);
        }
    }
}

document.getElementById('btnPreview')?.addEventListener('click', async () => {
    const url = @json(route('admin.email_templates.preview', $template));
    const previewBox = document.getElementById('previewBox');

    previewBox.textContent = 'Loading preview...';

    try {
        const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
        const data = await res.json();

        previewBox.innerHTML =
            `<div class="mb-3">
                <div class="text-xs text-gray-500">Subject</div>
                <div class="font-medium">${escapeHtml(data.subject ?? '')}</div>
            </div>
            <div class="text-xs text-gray-500 mb-2">Final Email</div>
            <div class="overflow-hidden rounded-lg border border-gray-200 bg-white">
                ${data.body_html ?? ''}
            </div>`;
    } catch (e) {
        previewBox.textContent = 'Failed to load preview.';
    }
});

function escapeHtml(str) {
    return String(str).replace(/[&<>"']/g, (m) => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    }[m]));
}
</script>
@endsection