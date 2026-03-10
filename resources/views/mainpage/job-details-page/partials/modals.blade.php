{{-- ✅ REPORT MODAL --}}
<div x-cloak x-show="modal === 'report'" x-transition.opacity
    class="fixed inset-0 z-[9999] flex items-center justify-center px-4">

    {{-- Background --}}
    <div class="absolute inset-0 bg-black/40" @click="closeModal()"></div>

    {{-- Modal --}}
    <div class="relative w-full max-w-lg rounded-2xl bg-white p-6 shadow-xl border border-slate-200">

        <div class="flex items-start justify-between gap-3">
            <div>
                <div class="section-title text-base font-semibold text-slate-900">
                    Report this job
                </div>

                <div class="mt-1 text-sm text-slate-600">
                    Tell us what’s wrong so we can review it.
                </div>
            </div>

            <button type="button"
                @click="closeModal()"
                class="rounded-lg p-2 hover:bg-slate-100">

                <i data-lucide="x" class="w-4 h-4 text-slate-500"></i>

            </button>
        </div>

        <form class="mt-5 space-y-4"
            method="POST"
            action="{{ route('candidate.jobs.report.store',$job->id) }}">

            @csrf

            {{-- Reason --}}
            <div>
                <label class="block text-sm font-medium text-slate-700">
                    Reason <span class="text-red-500">*</span>
                </label>

                <select name="reason"
                    x-model="reportReason"
                    class="mt-1 w-full rounded-xl border-slate-300 focus:ring-emerald-200 focus:border-emerald-500">

                    <option value="">Select a reason</option>
                    <option value="misleading">Misleading information</option>
                    <option value="scam">Possible scam</option>
                    <option value="fake">Fake job posting</option>
                    <option value="wrong_contact">Wrong contact/details</option>
                    <option value="other">Other</option>

                </select>

                @error('reason')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror

            </div>

            {{-- Details --}}
            <div>
                <label class="block text-sm font-medium text-slate-700">
                    Details (optional)
                </label>

                <textarea
                    name="details"
                    rows="4"
                    x-model="reportDetails"
                    class="mt-1 w-full rounded-xl border-slate-300 focus:ring-emerald-200 focus:border-emerald-500"
                    placeholder="Add more details..."></textarea>

                @error('details')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Buttons --}}
            <div class="flex justify-end gap-2 pt-2">

                <button type="button"
                    @click="closeModal()"
                    class="px-4 py-2 rounded-xl border border-slate-300 bg-white text-slate-700 hover:bg-slate-50">

                    Cancel

                </button>

                <button type="submit"
                    :disabled="!reportReason"
                    class="px-4 py-2 rounded-xl bg-emerald-600 text-white font-semibold hover:bg-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed">

                    Submit Report

                </button>

            </div>

        </form>

    </div>
</div>

{{-- LOGIN REQUIRED MODAL --}}
<div x-cloak
     x-show="modal === 'login'"
     x-transition.opacity
     class="fixed inset-0 z-[9999] flex items-center justify-center px-4">

    {{-- Background --}}
    <div class="absolute inset-0 bg-black/40" @click="closeModal()"></div>

    {{-- Modal --}}
    <div
        @click.outside="closeModal()"
        class="relative bg-white w-full max-w-md rounded-2xl shadow-xl p-6 text-center border border-gray-100">

        {{-- Icon --}}
        <div class="mx-auto w-14 h-14 rounded-full bg-green-50 flex items-center justify-center mb-4">

            <i data-lucide="lock" class="w-7 h-7 text-green-600"></i>

        </div>

        {{-- Title --}}
        <h3 class="section-title text-lg font-bold text-gray-900">
            Login Required
        </h3>

        {{-- Message --}}
        <p class="mt-2 text-gray-600 text-sm leading-relaxed">
            You need to login first to continue.
        </p>

        {{-- Buttons --}}
        <div class="mt-6 flex gap-3 justify-center">

            <button
                type="button"
                @click="closeModal()"
                class="px-4 py-2 rounded-xl border border-gray-200 hover:bg-gray-50 font-medium">

                Cancel

            </button>

            <a href="{{ route('candidate.login') }}"
               class="px-4 py-2 rounded-xl bg-green-600 hover:bg-green-700 text-white font-semibold">

                Login

            </a>

        </div>

    </div>
</div>