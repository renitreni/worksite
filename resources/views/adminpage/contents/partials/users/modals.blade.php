{{-- =========================
✅ SIMPLE + MODERN MODALS (NO BLUR)
- Centered always
- Cleaner header + spacing
- Better button placement (Cancel left, Action right)
- Consistent styles across all modals
========================= --}}

{{-- APPROVE MODAL --}}
<div x-show="approveOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition.opacity
    @keydown.escape.window="closeAll()" x-cloak>
    {{-- overlay --}}
    <div class="absolute inset-0 bg-black/40" @click="closeAll()"></div>

    {{-- modal --}}
    <div class="relative w-full max-w-md" x-transition.scale.origin.center x-trap.noscroll="approveOpen">
        <div class="rounded-2xl bg-white shadow-xl ring-1 ring-slate-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">Approve employer</h3>
                        <p class="mt-1 text-sm text-slate-600">
                            Approve <span class="font-semibold text-slate-900" x-text="selectedName"></span> to proceed.
                        </p>
                    </div>
                    <button type="button" @click="closeAll()"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg hover:bg-slate-50 text-slate-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="px-6 py-5">
                <div class="flex flex-col-reverse gap-2 sm:flex-row sm:justify-end sm:gap-3">
                    <button type="button" @click="closeAll()"
                        class="inline-flex justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        Cancel
                    </button>

                    <form method="POST" :action="approveAction" class="sm:inline-flex">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                            class="inline-flex w-full justify-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700 focus:outline-none focus:ring-4 focus:ring-emerald-100">
                            Approve
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- REJECT MODAL --}}
<div x-show="rejectOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition.opacity
    @keydown.escape.window="closeAll()" x-cloak>
    <div class="absolute inset-0 bg-black/40" @click="closeAll()"></div>

    <div class="relative w-full max-w-md" x-transition.scale.origin.center x-trap.noscroll="rejectOpen">
        <div class="rounded-2xl bg-white shadow-xl ring-1 ring-slate-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">Reject employer</h3>
                        <p class="mt-1 text-sm text-slate-600">
                            Reject <span class="font-semibold text-slate-900" x-text="selectedName"></span> with a
                            reason.
                        </p>
                    </div>
                    <button type="button" @click="closeAll()"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg hover:bg-slate-50 text-slate-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="px-6 py-5 space-y-4">
                <div>
                    <label class="text-xs font-semibold text-slate-700">Reason</label>
                    <textarea x-model="rejectReason" rows="4" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-800 placeholder:text-slate-400
                               focus:border-rose-400 focus:ring-4 focus:ring-rose-100"
                        placeholder="Write the reason for rejection..."></textarea>

                    <div class="mt-2 text-[12px] text-rose-600" x-show="rejectError">
                        Please enter a reason before rejecting.
                    </div>
                </div>

                <div class="flex flex-col-reverse gap-2 sm:flex-row sm:justify-end sm:gap-3">
                    <button type="button" @click="closeAll()"
                        class="inline-flex justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        Cancel
                    </button>

                    <form method="POST" :action="rejectAction" @submit.prevent="submitReject($event)"
                        class="sm:inline-flex">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="reason" :value="rejectReason">
                        <button type="submit"
                            class="inline-flex w-full justify-center rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-rose-700 focus:outline-none focus:ring-4 focus:ring-rose-100">
                            Reject
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SUSPEND MODAL (Employer Verification) --}}
<div x-show="suspendOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition.opacity
    @keydown.escape.window="closeAll()" x-cloak>
    <div class="absolute inset-0 bg-black/40" @click="closeAll()"></div>

    <div class="relative w-full max-w-md" x-transition.scale.origin.center x-trap.noscroll="suspendOpen">
        <div class="rounded-2xl bg-white shadow-xl ring-1 ring-slate-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">Suspend employer</h3>
                        <p class="mt-1 text-sm text-slate-600">
                            Provide a reason to suspend <span class="font-semibold text-slate-900"
                                x-text="selectedName"></span>.
                        </p>
                    </div>
                    <button type="button" @click="closeAll()"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg hover:bg-slate-50 text-slate-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="px-6 py-5 space-y-4">
                <div>
                    <label class="text-xs font-semibold text-slate-700">Reason</label>
                    <textarea x-model="suspendReason" rows="4" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-800 placeholder:text-slate-400
                               focus:border-slate-400 focus:ring-4 focus:ring-slate-100"
                        placeholder="Write the reason for suspension..."></textarea>

                    <div class="mt-2 text-[12px] text-rose-600" x-show="suspendError">
                        Please enter a reason before suspending.
                    </div>

                    <label class="mt-3 flex items-center gap-2 text-xs text-slate-600">
                        <input type="checkbox" x-model="suspendAlsoHold" class="rounded border-slate-300">
                        Also put the user account on hold
                    </label>
                </div>

                <div class="flex flex-col-reverse gap-2 sm:flex-row sm:justify-end sm:gap-3">
                    <button type="button" @click="closeAll()"
                        class="inline-flex justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        Cancel
                    </button>

                    <form method="POST" :action="suspendAction" @submit.prevent="submitSuspend($event)"
                        class="sm:inline-flex">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="suspended_reason" :value="suspendReason">
                        <input type="hidden" name="also_hold_account" :value="suspendAlsoHold ? 1 : 0">
                        <button type="submit"
                            class="inline-flex w-full justify-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800 focus:outline-none focus:ring-4 focus:ring-slate-200">
                            Suspend
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ARCHIVE/RESTORE MODAL --}}
<div x-show="archiveOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition.opacity
    @keydown.escape.window="closeAll()" x-cloak>
    <div class="absolute inset-0 bg-black/40" @click="closeAll()"></div>

    <div class="relative w-full max-w-md" x-transition.scale.origin.center x-trap.noscroll="archiveOpen">
        <div class="rounded-2xl bg-white shadow-xl ring-1 ring-slate-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h3 class="text-base font-semibold text-slate-900"
                            x-text="archiveMode==='archive' ? 'Archive user' : 'Restore user'"></h3>
                        <p class="mt-1 text-sm text-slate-600" x-text="archiveMode==='archive'
                                ? ('Archive ' + selectedName + '? This will hide the account.')
                                : ('Restore ' + selectedName + '? This will make the account active again.')"></p>
                    </div>
                    <button type="button" @click="closeAll()"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg hover:bg-slate-50 text-slate-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="px-6 py-5">
                <div class="flex flex-col-reverse gap-2 sm:flex-row sm:justify-end sm:gap-3">
                    <button type="button" @click="closeAll()"
                        class="inline-flex justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        Cancel
                    </button>

                    <form method="POST" :action="archiveAction" class="sm:inline-flex">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                            class="inline-flex w-full justify-center rounded-xl px-4 py-2.5 text-sm font-semibold text-white focus:outline-none focus:ring-4"
                            :class="archiveMode==='archive'
                                ? 'bg-rose-600 hover:bg-rose-700 focus:ring-rose-100'
                                : 'bg-emerald-600 hover:bg-emerald-700 focus:ring-emerald-100'">
                            <span x-text="archiveMode==='archive' ? 'Archive' : 'Restore'"></span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- TOGGLE MODAL --}}
<div x-show="toggleOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition.opacity
    @keydown.escape.window="closeAll()" x-cloak>
    <div class="absolute inset-0 bg-black/40" @click="closeAll()"></div>

    <div class="relative w-full max-w-md" x-transition.scale.origin.center x-trap.noscroll="toggleOpen">
        <div class="rounded-2xl bg-white shadow-xl ring-1 ring-slate-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h3 class="text-base font-semibold text-slate-900"
                            x-text="toggleNextActive ? 'Enable user' : 'Disable user'"></h3>
                        <p class="mt-1 text-sm text-slate-600" x-text="toggleNextActive
                                ? ('Enable ' + selectedName + ' to allow access.')
                                : ('Disable ' + selectedName + ' to prevent access.')"></p>
                    </div>
                    <button type="button" @click="closeAll()"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg hover:bg-slate-50 text-slate-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="px-6 py-5">
                <div class="flex flex-col-reverse gap-2 sm:flex-row sm:justify-end sm:gap-3">
                    <button type="button" @click="closeAll()"
                        class="inline-flex justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        Cancel
                    </button>

                    <form method="POST" :action="toggleAction" class="sm:inline-flex">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                            class="inline-flex w-full justify-center rounded-xl px-4 py-2.5 text-sm font-semibold text-white focus:outline-none focus:ring-4"
                            :class="toggleNextActive
                                ? 'bg-emerald-600 hover:bg-emerald-700 focus:ring-emerald-100'
                                : 'bg-slate-900 hover:bg-slate-800 focus:ring-slate-200'">
                            <span x-text="toggleNextActive ? 'Enable' : 'Disable'"></span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SUBSCRIPTION MODAL --}}
<div x-show="subOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition.opacity
    @keydown.escape.window="closeAll()" x-cloak>
    <div class="absolute inset-0 bg-black/40" @click="closeAll()"></div>

    <div class="relative w-full max-w-lg" x-transition.scale.origin.center x-trap.noscroll="subOpen">
        <div class="rounded-2xl bg-white shadow-xl ring-1 ring-slate-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">Manage subscription</h3>
                        <p class="mt-1 text-sm text-slate-600">
                            Update subscription for <span class="font-semibold text-slate-900"
                                x-text="selectedName"></span>.
                        </p>
                    </div>
                    <button type="button" @click="closeAll()"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg hover:bg-slate-50 text-slate-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <form method="POST" :action="subAction" class="px-6 py-5 space-y-4">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <div>
                        <label class="text-xs font-semibold text-slate-700">Plan</label>
                        <select name="plan" x-model="subPlan" class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm
                                   focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100">
                            <option value="">—</option>
                            <option value="standard">Standard</option>
                            <option value="gold">Gold</option>
                            <option value="platinum">Platinum</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-slate-700">Subscription status</label>
                        <select name="subscription_status" x-model="subStatus" class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm
                                   focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100">
                            <option value="inactive">Inactive</option>
                            <option value="active">Active</option>
                            <option value="expired">Expired</option>
                            <option value="canceled">Canceled</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-slate-700">Starts at</label>
                        <input type="date" name="starts_at" x-model="subStarts" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm
                                   focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100">
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-slate-700">Ends at</label>
                        <input type="date" name="ends_at" x-model="subEnds" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm
                                   focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100">
                    </div>
                </div>



                <div class="flex flex-col-reverse gap-2 sm:flex-row sm:justify-end sm:gap-3 pt-1">
                    <button type="button" @click="closeAll()"
                        class="inline-flex justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        Cancel
                    </button>

                    <button type="submit"
                        class="inline-flex w-full justify-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800 focus:outline-none focus:ring-4 focus:ring-slate-200">
                        Save changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ✅ ACTIONS MODAL --}}
<div x-show="actionsOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition.opacity
    @keydown.escape.window="closeAll()" x-cloak>
    <div class="absolute inset-0 bg-black/40" @click="closeAll()"></div>

    <div class="relative w-full max-w-lg" x-transition.scale.origin.center x-trap.noscroll="actionsOpen">
        <div class="rounded-2xl bg-white shadow-xl ring-1 ring-slate-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">User actions</h3>
                        <p class="mt-1 text-sm text-slate-600">
                            For: <span class="font-semibold text-slate-900" x-text="selectedName"></span>
                            <span class="ml-2 text-xs text-slate-500" x-text="selectedRole"></span>
                        </p>
                    </div>
                    <button type="button" @click="closeAll()"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg hover:bg-slate-50 text-slate-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="px-6 py-5">
                {{-- ✅ gate: pending employer shows ONLY approve/reject --}}
                <template x-if="selectedRole === 'employer' && selectedArchived === '0' && selectedEmpStatus === 'pending'">
                    <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                        <form method="POST" :action="approveAction">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="w-full rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700 focus:outline-none focus:ring-4 focus:ring-emerald-100">
                                Approve employer
                            </button>
                        </form>

                        <button type="button" @click="closeAll(); openReject(selectedId, selectedName)"
                            class="w-full rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-rose-700 focus:outline-none focus:ring-4 focus:ring-rose-100">
                            Reject employer
                        </button>
                    </div>
                </template>

                {{-- ✅ everything else (only if NOT pending employer) --}}
                <template x-if="!(selectedRole === 'employer' && selectedArchived === '0' && selectedEmpStatus === 'pending')">
                    <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">

                        {{-- Enable/Disable (only if not archived) --}}
                        <template x-if="selectedArchived === '0'">
                            <form method="POST" :action="toggleAction" @submit.prevent="openToggleConfirm($event)">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="w-full rounded-xl px-4 py-2.5 text-sm font-semibold ring-1 transition"
                                    :class="toggleNextActive
                                        ? 'bg-emerald-600 text-white ring-emerald-600 hover:bg-emerald-700'
                                        : 'bg-white text-slate-700 ring-slate-200 hover:bg-slate-50'">
                                    <span x-text="toggleNextActive ? 'Enable user' : 'Disable user'"></span>
                                </button>
                            </form>
                        </template>

                        {{-- Employer: Suspend/Unsuspend (not archived) --}}
                        <template x-if="selectedRole === 'employer' && selectedArchived === '0' && selectedEmpStatus !== 'suspended'">
                            <button type="button" @click="closeAll(); openSuspend(selectedId, selectedName)"
                                class="w-full rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800 focus:outline-none focus:ring-4 focus:ring-slate-200">
                                Suspend employer
                            </button>
                        </template>

                        <template x-if="selectedRole === 'employer' && selectedArchived === '0' && selectedEmpStatus === 'suspended'">
                            <form method="POST" :action="unsuspendAction">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="w-full rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700 focus:outline-none focus:ring-4 focus:ring-emerald-100">
                                    Unsuspend employer
                                </button>
                            </form>
                        </template>

                        {{-- Employer: Subscription --}}
                        <template x-if="selectedRole === 'employer' && selectedArchived === '0'">
                            <button type="button"
                                @click="closeAll(); openSubscription(selectedId, selectedName, selectedPlan, selectedSubStatus, selectedSubStarts, selectedSubEnds)"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                Manage subscription
                            </button>
                        </template>

                        {{-- Archive / Restore --}}
                        <template x-if="selectedArchived === '0'">
                            <button type="button" @click="closeAll(); openArchive(selectedId, selectedName, 'archive')"
                                class="w-full rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-rose-700 focus:outline-none focus:ring-4 focus:ring-rose-100">
                                Archive user
                            </button>
                        </template>

                        <template x-if="selectedArchived === '1'">
                            <button type="button" @click="closeAll(); openArchive(selectedId, selectedName, 'restore')"
                                class="w-full rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700 focus:outline-none focus:ring-4 focus:ring-emerald-100">
                                Restore user
                            </button>
                        </template>

                    </div>
                </template>

                <div class="mt-5 flex justify-end">
                    <button type="button" @click="closeAll()"
                        class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>