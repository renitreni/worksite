@extends('candidate.layout')

@section('content')
<div class="space-y-6" x-data="meetingsApp()" x-init="init()">

    <div class="flex items-center justify-between">
        <h1 class="text-xl sm:text-2xl font-semibold text-gray-900">My Meetings</h1>
    </div>

    {{-- Tabs --}}
    <div class="border-b border-gray-200">
        <div class="flex items-center gap-10">
            <button type="button"
                @click="activeTab='upcoming'"
                class="relative pb-4 text-sm font-semibold"
                :class="activeTab === 'upcoming' ? 'text-blue-600' : 'text-gray-500 hover:text-gray-700'"
            >
                <span>Upcoming</span>
                <span class="ml-2 inline-flex items-center justify-center rounded-full text-xs font-semibold px-2 py-0.5"
                      :class="activeTab === 'upcoming' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700'"
                      x-text="upcomingCount()">
                </span>

                <span
                    x-show="activeTab === 'upcoming'"
                    class="absolute left-0 -bottom-[1px] h-0.5 w-full bg-blue-600 rounded-full"
                    style="display:none;"
                ></span>
            </button>

            <button type="button"
                @click="activeTab='past'"
                class="relative pb-4 text-sm font-semibold"
                :class="activeTab === 'past' ? 'text-blue-600' : 'text-gray-500 hover:text-gray-700'"
            >
                <span>Past Meetings</span>
                <span class="ml-2 inline-flex items-center justify-center rounded-full text-xs font-semibold px-2 py-0.5"
                      :class="activeTab === 'past' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700'"
                      x-text="pastCount()">
                </span>

                <span
                    x-show="activeTab === 'past'"
                    class="absolute left-0 -bottom-[1px] h-0.5 w-full bg-blue-600 rounded-full"
                    style="display:none;"
                ></span>
            </button>
        </div>
    </div>

    {{-- Upcoming list --}}
    <div x-show="activeTab === 'upcoming'" style="display:none;" class="space-y-4">
        <template x-if="upcomingMeetings().length === 0">
            <div class="rounded-2xl bg-white border border-gray-200 p-8 text-center text-sm text-gray-600">
                No upcoming meetings.
            </div>
        </template>

        <template x-for="m in upcomingMeetings()" :key="m.id">
            <div class="rounded-2xl bg-white border border-gray-200 shadow-sm">
                <div class="p-5 sm:p-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
                    <div class="flex items-center gap-4 min-w-0">
                        {{-- Date --}}
                        <div class="h-14 w-14 rounded-2xl bg-blue-50 border border-blue-100 flex flex-col items-center justify-center leading-none">
                            <span class="text-xs font-semibold text-blue-600" x-text="m.month"></span>
                            <span class="text-lg font-extrabold text-blue-700" x-text="m.day"></span>
                        </div>

                        {{-- Details --}}
                        <div class="min-w-0">
                            <p class="text-base font-semibold text-gray-900 truncate" x-text="m.title"></p>
                            <p class="text-sm font-semibold text-blue-600" x-text="'with ' + m.company"></p>

                            <div class="mt-2 flex flex-wrap items-center gap-4 text-sm text-gray-500">
                                <div class="inline-flex items-center gap-2">
                                    <i data-lucide="clock" class="h-4 w-4"></i>
                                    <span x-text="m.time"></span>
                                </div>
                                <div class="inline-flex items-center gap-2">
                                    <i data-lucide="video" class="h-4 w-4"></i>
                                    <span x-text="m.type"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-3 lg:justify-end">
                        <button type="button"
                            @click="cancelMeeting(m.id)"
                            class="inline-flex items-center justify-center rounded-2xl border border-red-200 bg-white px-5 py-2.5 text-sm font-semibold text-red-600 hover:bg-red-50 transition">
                            Cancel
                        </button>

                        <button type="button"
                            @click="joinMeeting(m.id)"
                            class="inline-flex items-center gap-2 rounded-2xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700 transition">
                            <span>Join Meeting</span>
                            <i data-lucide="external-link" class="h-4 w-4"></i>
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>

    {{-- Past list --}}
    <div x-show="activeTab === 'past'" style="display:none;" class="space-y-4">
        <template x-if="pastMeetings().length === 0">
            <div class="rounded-2xl bg-white border border-gray-200 p-8 text-center text-sm text-gray-600">
                No past meetings.
            </div>
        </template>

        <template x-for="m in pastMeetings()" :key="m.id">
            <div class="rounded-2xl bg-white border border-gray-200 shadow-sm">
                <div class="p-5 sm:p-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
                    <div class="flex items-center gap-4 min-w-0">
                        {{-- Date --}}
                        <div class="h-14 w-14 rounded-2xl bg-gray-50 border border-gray-200 flex flex-col items-center justify-center leading-none">
                            <span class="text-xs font-semibold text-gray-600" x-text="m.month"></span>
                            <span class="text-lg font-extrabold text-gray-800" x-text="m.day"></span>
                        </div>

                        {{-- Details --}}
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <p class="text-base font-semibold text-gray-900 truncate" x-text="m.title"></p>

                                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold border"
                                    :class="m.status === 'Completed'
                                        ? 'bg-emerald-50 text-emerald-700 border-emerald-100'
                                        : 'bg-rose-50 text-rose-700 border-rose-100' "
                                    x-text="m.status">
                                </span>
                            </div>

                            <p class="text-sm font-semibold text-blue-600" x-text="'with ' + m.company"></p>

                            <div class="mt-2 flex flex-wrap items-center gap-4 text-sm text-gray-500">
                                <div class="inline-flex items-center gap-2">
                                    <i data-lucide="clock" class="h-4 w-4"></i>
                                    <span x-text="m.time"></span>
                                </div>
                                <div class="inline-flex items-center gap-2">
                                    <i data-lucide="video" class="h-4 w-4"></i>
                                    <span x-text="m.type"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 lg:justify-end">
                        <button type="button"
                            @click="removePast(m.id)"
                            class="inline-flex items-center justify-center rounded-2xl border border-gray-200 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                            Remove
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>

    {{-- DEMO CALL MODAL --}}
    <div
        x-show="callModalOpen"
        x-transition.opacity
        class="fixed inset-0 z-50 flex items-center justify-center px-4"
        style="display:none;"
    >
        <div class="absolute inset-0 bg-black/50"></div>

        <div class="relative w-full max-w-md rounded-2xl bg-white shadow-xl border border-gray-200 p-6 text-center">
            <div class="mx-auto mb-4 h-14 w-14 rounded-full bg-emerald-50 flex items-center justify-center">
                <i data-lucide="phone-call" class="h-6 w-6 text-emerald-600"></i>
            </div>

            <h2 class="text-lg font-semibold text-gray-900" x-text="callStatus"></h2>

            <p class="mt-1 text-sm text-gray-600">
                <span x-text="callMeetingTitle"></span><br>
                <span class="font-medium" x-text="callCompany"></span>
            </p>

            <div class="mt-6 flex items-center justify-center gap-4">
                <button type="button"
                    @click="toggleMuteCall()"
                    class="inline-flex items-center gap-2 rounded-xl border border-gray-200 px-4 py-2 text-sm font-semibold hover:bg-gray-50 transition">
                    <i data-lucide="mic-off" class="h-4 w-4" x-show="callMuted"></i>
                    <i data-lucide="mic" class="h-4 w-4" x-show="!callMuted"></i>
                    <span x-text="callMuted ? 'Unmute' : 'Mute'"></span>
                </button>

                <button type="button"
                    @click="leaveCall()"
                    class="inline-flex items-center gap-2 rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700 transition">
                    <i data-lucide="phone-off" class="h-4 w-4"></i>
                    Leave Call
                </button>
            </div>

            <p class="mt-4 text-xs text-gray-400">
                Demo call only – no real meeting connected
            </p>
        </div>
    </div>

    {{-- Toast --}}
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
        function meetingsApp() {
            return {
                storageKey: 'worksite_meetings_v1',
                activeTab: 'upcoming',
                meetings: [],
                toasts: [],

                // Demo call states
                callModalOpen: false,
                callMuted: false,
                callStatus: '',
                callMeetingTitle: '',
                callCompany: '',

                init() {
                    this.load();

                    // seed if empty
                    if (this.meetings.length === 0) {
                        this.meetings = [
                            {
                                id: crypto.randomUUID(),
                                title: 'Technical Interview',
                                company: 'TechFlow',
                                month: 'Oct',
                                day: '24',
                                time: '2:00 PM - 3:00 PM',
                                type: 'Video Call',
                                status: 'Upcoming',
                            },
                            {
                                id: crypto.randomUUID(),
                                title: 'Initial Screening',
                                company: 'Creative Studio',
                                month: 'Sep',
                                day: '12',
                                time: '10:00 AM - 10:30 AM',
                                type: 'Video Call',
                                status: 'Completed',
                            }
                        ];
                        this.save();
                    }

                    this.refreshIcons();
                },

                refreshIcons() {
                    this.$nextTick(() => { if (window.lucide) window.lucide.createIcons(); });
                },

                load() {
                    try {
                        const raw = localStorage.getItem(this.storageKey);
                        this.meetings = raw ? JSON.parse(raw) : [];
                    } catch {
                        this.meetings = [];
                    }
                },

                save() {
                    localStorage.setItem(this.storageKey, JSON.stringify(this.meetings));
                    this.refreshIcons();
                },

                upcomingMeetings() {
                    return this.meetings.filter(m => m.status === 'Upcoming');
                },

                pastMeetings() {
                    return this.meetings.filter(m => m.status === 'Completed' || m.status === 'Cancelled');
                },

                upcomingCount() {
                    return this.upcomingMeetings().length;
                },

                pastCount() {
                    return this.pastMeetings().length;
                },

                cancelMeeting(id) {
                    const m = this.meetings.find(x => x.id === id);
                    if (!m) return;

                    if (!confirm('Cancel this meeting?')) return;

                    m.status = 'Cancelled';
                    this.save();

                    this.toast('Meeting cancelled', 'Moved to Past Meetings.');
                    this.activeTab = 'past';
                },

                // DEMO JOIN MEETING (no real link)
                joinMeeting(id) {
                    const m = this.meetings.find(x => x.id === id);
                    if (!m) return;

                    this.callMeetingTitle = m.title;
                    this.callCompany = 'with ' + m.company;
                    this.callStatus = 'Joining meeting…';
                    this.callMuted = false;
                    this.callModalOpen = true;

                    this.refreshIcons();

                    setTimeout(() => {
                        this.callStatus = 'Connected (Demo)';
                        this.toast('Connected', 'You joined the meeting (demo).');
                        this.refreshIcons();
                    }, 2000);
                },

                toggleMuteCall() {
                    this.callMuted = !this.callMuted;
                    this.toast(
                        this.callMuted ? 'Muted' : 'Unmuted',
                        this.callMuted ? 'Microphone is off (demo).' : 'Microphone is on (demo).'
                    );
                    this.refreshIcons();
                },

                leaveCall() {
                    this.callModalOpen = false;
                    this.toast('Left call', 'You left the meeting (demo).');
                },

                removePast(id) {
                    if (!confirm('Remove this past meeting?')) return;
                    this.meetings = this.meetings.filter(m => m.id !== id);
                    this.save();
                    this.toast('Removed', 'Past meeting was removed.');
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
                }
            }
        }
    </script>
</div>
@endsection
