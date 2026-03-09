@extends('adminpage.layout')
@section('title', 'Create Email Template')
@section('page_title', 'Create Email Template')

@section('content')
<div class="max-w-5xl mx-auto p-4" x-data="emailTemplateCreate()">
    @if ($errors->any())
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <div class="mb-2 font-semibold">Please fix the following:</div>
            <ul class="list-disc space-y-1 pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-3">
        <form method="POST" action="{{ route('admin.email_templates.store') }}"
              class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm lg:col-span-2">
            @csrf

            <div class="border-b border-gray-100 px-6 py-4">
                <h2 class="text-base font-semibold text-gray-900">New Template</h2>
                <p class="text-sm text-gray-500">
                    Create a reusable email template. Plain text is the main content; HTML is optional.
                </p>
            </div>

            <div class="space-y-6 p-6">
                <div>
                    <label for="name" class="mb-1 block text-sm font-medium text-gray-700">Name</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="e.g. admin_invitation"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100"
                    >
                    <p class="mt-1 text-xs text-gray-500">
                        Use lowercase with underscores, like <span class="font-mono">admin_invitation</span>.
                    </p>
                </div>

                <div>
                    <label for="subject" class="mb-1 block text-sm font-medium text-gray-700">Subject</label>
                    <input
                        type="text"
                        id="subject"
                        name="subject"
                        value="{{ old('subject') }}"
                        placeholder="Email subject"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100"
                    >
                </div>

                <div>
                    <label for="body_text" class="mb-1 block text-sm font-medium text-gray-700">Email Content</label>
                    <p class="mb-2 text-xs text-gray-500">
                        This is the main message admins should write. Use placeholders from the right panel.
                    </p>
                    <textarea
                        id="body_text"
                        name="body_text"
                        rows="12"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm leading-6 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100"
                        placeholder="Write your email message here..."
                    >{{ old('body_text') }}</textarea>
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
                            Optional. Leave blank unless you need custom HTML formatting.
                        </p>
                        <textarea
                            id="body_html"
                            name="body_html"
                            rows="10"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 font-mono text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100"
                            placeholder="<p>Hello {FULL_NAME},</p>"
                        >{{ old('body_html') }}</textarea>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <input type="hidden" name="is_active" value="0">
                    <input
                        type="checkbox"
                        id="is_active"
                        name="is_active"
                        value="1"
                        {{ old('is_active', 1) ? 'checked' : '' }}
                        class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500"
                    >
                    <label for="is_active" class="text-sm font-medium text-gray-700">Active</label>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-gray-100 px-6 py-4">
                <a href="{{ route('admin.email_templates.index') }}"
                   class="inline-flex items-center rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>

                <button type="submit"
                        class="inline-flex items-center rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">
                    Save Template
                </button>
            </div>
        </form>

        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="mb-2 text-sm font-semibold text-gray-900">Suggested Placeholders</h2>
            <p class="mb-3 text-xs text-gray-500">Click a placeholder to insert it into the content.</p>

            @php
                $suggested = [
                    '{FULL_NAME}',
                    '{USER_NAME}',
                    '{USER_EMAIL}',
                    '{SITE_NAME}',
                    '{INVITE_LINK}',
                    '{VERIFY_LINK}',
                    '{RESET_LINK}',
                    '{EXPIRES_IN_HOURS}',
                    '{SUPERADMIN_NAME}',
                    '{COMPANY_NAME}',
                    '{JOB_TITLE}',
                    '{APPLICATION_LINK}',
                    '{STATUS}',
                ];
            @endphp

            <div class="mb-6 flex flex-wrap gap-2">
                @foreach($suggested as $ph)
                    <button
                        type="button"
                        class="rounded-full bg-gray-100 px-3 py-1 text-xs font-mono text-gray-700 hover:bg-gray-200"
                        @click="insertPlaceholder('{{ $ph }}')"
                    >
                        {{ $ph }}
                    </button>
                @endforeach
            </div>

            <div class="rounded-lg border border-amber-200 bg-amber-50 p-3 text-xs text-amber-800">
                Use plain text for normal templates. Only use Advanced HTML when you need special formatting.
            </div>
        </div>
    </div>
</div>

<script>
function emailTemplateCreate() {
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
</script>
@endsection