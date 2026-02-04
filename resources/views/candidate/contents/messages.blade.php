@extends('candidate.layout')

@section('content')
<div class="space-y-6" x-data="messagesApp()" x-init="init()">

    <div class="flex items-center justify-between">
        <h1 class="text-xl sm:text-2xl font-semibold text-gray-900">Messages</h1>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
        {{-- Left: conversation list --}}
        <section class="xl:col-span-4">
            <div class="rounded-2xl bg-white border border-gray-200 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-gray-100">
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i data-lucide="search" class="h-4 w-4"></i>
                        </span>
                        <input
                            type="text"
                            x-model="search"
                            placeholder="Search messages..."
                            class="w-full rounded-2xl border border-gray-200 bg-gray-50 pl-9 pr-3 py-2 text-sm text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300"
                        />
                    </div>
                </div>

                <div class="divide-y divide-gray-100">
                    <template x-for="t in filteredThreads()" :key="t.id">
                        <button type="button"
                            @click="openThread(t.id)"
                            class="w-full text-left p-4 hover:bg-gray-50 transition"
                            :class="activeThreadId === t.id ? 'bg-blue-50/70 hover:bg-blue-50' : ''"
                        >
                            <div class="flex items-start gap-3">
                                <div class="relative">
                                    <img
                                        :src="t.avatar"
                                        :alt="t.name"
                                        class="h-10 w-10 rounded-full object-cover ring-2 ring-white"
                                    />

                                    {{-- Red dot (unread) --}}
                                    <template x-if="t.unread">
                                        <span class="absolute -top-1 -right-1 h-3.5 w-3.5 rounded-full bg-rose-500 ring-2 ring-white"></span>
                                    </template>
                                </div>

                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center justify-between gap-3">
                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-gray-900 truncate" x-text="t.name"></p>
                                            <p class="text-xs text-gray-500 truncate" x-text="t.company"></p>
                                        </div>
                                        <p class="text-xs text-gray-500 whitespace-nowrap" x-text="t.timeLabel"></p>
                                    </div>

                                    <p class="mt-2 text-sm text-gray-600 line-clamp-2" x-text="t.preview"></p>
                                </div>
                            </div>
                        </button>
                    </template>

                    <template x-if="filteredThreads().length === 0">
                        <div class="p-6 text-sm text-gray-600">
                            No conversations found.
                        </div>
                    </template>
                </div>
            </div>
        </section>

        {{-- Right: chat --}}
        <section class="xl:col-span-8">
            <div class="rounded-2xl bg-white border border-gray-200 shadow-sm overflow-hidden flex flex-col min-h-[560px]">

                {{-- Chat header --}}
                <div class="p-4 sm:p-5 border-b border-gray-100 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3 min-w-0" x-show="activeThread()" style="display:none;">
                        <img
                            :src="activeThread().avatar"
                            :alt="activeThread().name"
                            class="h-11 w-11 rounded-full object-cover ring-2 ring-gray-100"
                        />
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-gray-900 truncate" x-text="activeThread().name"></p>
                            <p class="text-xs text-gray-500 truncate" x-text="activeThread().company"></p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2" x-show="activeThread()" style="display:none;">
                        {{-- Call --}}
                        <button
                            type="button"
                            @click="doCall()"
                            class="inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-gray-200 bg-white hover:bg-gray-50 transition"
                            title="Call"
                        >
                            <i data-lucide="phone" class="h-5 w-5 text-gray-600"></i>
                        </button>

                        {{-- Video --}}
                        <button
                            type="button"
                            @click="doVideo()"
                            class="inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-gray-200 bg-white hover:bg-gray-50 transition"
                            title="Video"
                        >
                            <i data-lucide="video" class="h-5 w-5 text-gray-600"></i>
                        </button>

                        {{-- More --}}
                        <div class="relative" x-data="{ open:false }">
                            <button
                                type="button"
                                @click="open = !open"
                                class="inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-gray-200 bg-white hover:bg-gray-50 transition"
                                title="More"
                            >
                                <i data-lucide="more-vertical" class="h-5 w-5 text-gray-600"></i>
                            </button>

                            <div
                                x-show="open"
                                x-transition.opacity
                                @click.outside="open=false"
                                class="absolute right-0 mt-2 w-48 rounded-2xl bg-white border border-gray-200 shadow-lg overflow-hidden z-20"
                                style="display:none;"
                            >
                                <button
                                    type="button"
                                    class="w-full text-left px-4 py-3 text-sm hover:bg-gray-50"
                                    @click="toggleMute(); open=false"
                                >
                                    <span x-text="activeThread().muted ? 'Unmute (Notifications ON)' : 'Mute (Notifications OFF)'"></span>
                                </button>

                                <button
                                    type="button"
                                    class="w-full text-left px-4 py-3 text-sm hover:bg-gray-50 text-red-600"
                                    @click="deleteThread(); open=false"
                                >
                                    Delete Conversation
                                </button>
                            </div>
                        </div>
                    </div>

                    <div x-show="!activeThread()" class="text-sm text-gray-600">
                        Select a conversation
                    </div>
                </div>

                {{-- Messages --}}
                <div class="flex-1 p-4 sm:p-6 bg-white space-y-4 overflow-y-auto" x-ref="chatBox">
                    <template x-if="!activeThread()">
                        <div class="text-sm text-gray-600">
                            Choose a message on the left to view the conversation.
                        </div>
                    </template>

                    <template x-if="activeThread()">
                        <div class="space-y-4">
                            <template x-for="m in activeThread().messages" :key="m.id">
                                <div>
                                    {{-- Incoming --}}
                                    <template x-if="m.type === 'in'">
                                        <div class="max-w-[640px]">
                                            <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4 sm:p-5 shadow-sm">
                                                <p class="text-sm text-gray-700 leading-relaxed" x-text="m.text"></p>
                                                <p class="mt-3 text-xs text-gray-500" x-text="m.time"></p>
                                            </div>
                                        </div>
                                    </template>

                                    {{-- Outgoing --}}
                                    <template x-if="m.type === 'out'">
                                        <div class="flex justify-end">
                                            <div class="max-w-[640px]">
                                                <div class="rounded-2xl bg-blue-600 p-4 sm:p-5 shadow-sm">
                                                    <p class="text-sm text-white leading-relaxed" x-text="m.text"></p>
                                                    <p class="mt-3 text-xs text-blue-100" x-text="m.time"></p>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>

                {{-- Composer --}}
                <div class="p-4 sm:p-5 border-t border-gray-100 bg-white">
                    <div class="flex items-center gap-3">
                        <input
                            type="text"
                            x-model="draft"
                            @keydown.enter.prevent="sendMessage()"
                            placeholder="Type your message..."
                            class="flex-1 rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300"
                            :disabled="!activeThread()"
                        />
                        <button
                            type="button"
                            @click="sendMessage()"
                            class="inline-flex items-center gap-2 rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
                            :disabled="!activeThread()"
                        >
                            <span>Send</span>
                            <i data-lucide="send" class="h-4 w-4"></i>
                        </button>
                    </div>
                </div>

            </div>
        </section>
    </div>

    {{-- Toast notifications --}}
    <div class="fixed right-4 top-4 z-[60] space-y-2">
        <template x-for="t in toasts" :key="t.id">
            <div x-show="t.show" x-transition.opacity class="w-80 rounded-2xl border border-gray-200 bg-white shadow-lg p-4">
                <p class="text-sm font-semibold text-gray-900" x-text="t.title"></p>
                <p class="mt-1 text-xs text-gray-600" x-text="t.message"></p>
            </div>
        </template>
    </div>

    {{-- Alpine Logic --}}
    <script>
        function messagesApp() {
            return {
                storageKey: 'worksite_messages_v1',

                threads: [],
                activeThreadId: null,
                search: '',
                draft: '',
                toasts: [],

                init() {
                    this.load();

                    // Seed default threads if empty
                    if (this.threads.length === 0) {
                        this.threads = [
                            {
                                id: crypto.randomUUID(),
                                name: 'Alex Morgan',
                                company: 'TechFlow',
                                avatar: 'https://images.unsplash.com/photo-1568602471122-7832951cc4c5?auto=format&fit=crop&w=96&h=96&q=80',
                                unread: true,
                                muted: false,
                                timeLabel: '10:30 AM',
                                preview: 'Hi Keith, thanks for confirming the time. We look forward to speaking with you...',
                                messages: [
                                    { id: crypto.randomUUID(), type:'in', text:'Hi Keith, thanks for confirming the time. We look forward to speaking with you... Let\'s schedule a time to chat.', time:'10:30 AM' },
                                    { id: crypto.randomUUID(), type:'out', text:'Hi Alex, thanks for reaching out! I\'m available tomorrow afternoon.', time:'Just now' },
                                ]
                            },
                            {
                                id: crypto.randomUUID(),
                                name: 'Emily Chen',
                                company: 'Creative Studio',
                                avatar: 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=96&h=96&q=80',
                                unread: false,
                                muted: false,
                                timeLabel: 'Yesterday',
                                preview: 'Hello Keith, we have reviewed your portfolio and would like to discuss next steps.',
                                messages: [
                                    { id: crypto.randomUUID(), type:'in', text:'Hello Keith, we have reviewed your portfolio and would like to discuss next steps.', time:'Yesterday' },
                                ]
                            }
                        ];
                        this.save();
                    }

                    // Auto-open first thread
                    if (!this.activeThreadId && this.threads.length) {
                        this.openThread(this.threads[0].id);
                    }

                    this.refreshIcons();
                },

                refreshIcons() {
                    this.$nextTick(() => { if (window.lucide) window.lucide.createIcons(); });
                },

                load() {
                    try {
                        const raw = localStorage.getItem(this.storageKey);
                        this.threads = raw ? JSON.parse(raw) : [];
                    } catch (e) {
                        this.threads = [];
                    }
                },

                save() {
                    localStorage.setItem(this.storageKey, JSON.stringify(this.threads));
                    this.refreshIcons();
                },

                filteredThreads() {
                    const q = this.search.trim().toLowerCase();
                    if (!q) return this.threads;
                    return this.threads.filter(t =>
                        (t.name || '').toLowerCase().includes(q) ||
                        (t.company || '').toLowerCase().includes(q) ||
                        (t.preview || '').toLowerCase().includes(q)
                    );
                },

                activeThread() {
                    return this.threads.find(t => t.id === this.activeThreadId) || null;
                },

                openThread(id) {
                    this.activeThreadId = id;

                    
                    const t = this.activeThread();
                    if (t) {
                        t.unread = false;
                        this.save();
                    }

                    // Scroll to bottom
                    this.$nextTick(() => {
                        if (this.$refs.chatBox) this.$refs.chatBox.scrollTop = this.$refs.chatBox.scrollHeight;
                    });
                },

                nowTime() {
                    const d = new Date();
                    return d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                },

                sendMessage() {
                    const text = (this.draft || '').trim();
                    const t = this.activeThread();
                    if (!t || !text) return;

                    t.messages.push({
                        id: crypto.randomUUID(),
                        type: 'out',
                        text,
                        time: 'Just now'
                    });

                   
                    t.preview = text;
                    t.timeLabel = this.nowTime();

                    this.draft = '';
                    this.save();

                    // scroll
                    this.$nextTick(() => {
                        if (this.$refs.chatBox) this.$refs.chatBox.scrollTop = this.$refs.chatBox.scrollHeight;
                    });
                },

                doCall() {
                    const t = this.activeThread();
                    if (!t) return;
                    this.toast('Calling…', `Calling ${t.name} (demo)`);
                },

                doVideo() {
                    const t = this.activeThread();
                    if (!t) return;
                    this.toast('Starting video…', `Video call with ${t.name} (demo)`);
                },

                toggleMute() {
                    const t = this.activeThread();
                    if (!t) return;

                    t.muted = !t.muted;
                    this.save();

                    if (t.muted) this.toast('Muted', 'Notifications are OFF for this conversation.');
                    else this.toast('Unmuted', 'Notifications are ON for this conversation.');
                },

                deleteThread() {
                    const t = this.activeThread();
                    if (!t) return;

                    if (!confirm('Delete this conversation?')) return;

                    this.threads = this.threads.filter(x => x.id !== t.id);

                    // Select next available thread
                    this.activeThreadId = this.threads.length ? this.threads[0].id : null;

                    this.save();

                    this.toast('Deleted', 'Conversation removed.');
                },

                
                toast(title, message) {
                    const id = crypto.randomUUID();
                    this.toasts.unshift({ id, title, message, show: true });

                    setTimeout(() => {
                        const i = this.toasts.findIndex(x => x.id === id);
                        if (i !== -1) this.toasts[i].show = false;
                    }, 2200);

                    setTimeout(() => {
                        this.toasts = this.toasts.filter(x => x.id !== id);
                    }, 2600);
                },
            }
        }
    </script>

</div>
@endsection
