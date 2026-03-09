 {{-- ✅ SAVE SUCCESS MODAL --}}
 <div x-cloak x-show="saveSuccessOpen" x-transition.opacity
     class="fixed inset-0 z-[9999] flex items-center justify-center px-4">
     <div class="absolute inset-0 bg-black/40" @click="closeSaveModal()"></div>

     <div class="relative w-full max-w-sm rounded-2xl bg-white p-5 shadow-xl border border-slate-200">
         <div class="flex items-start gap-3">
             <div class="h-10 w-10 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center justify-center">
                 <i data-lucide="check" class="w-5 h-5 text-emerald-700"></i>
             </div>
             <div class="flex-1">
                 <div class="text-sm font-semibold text-slate-900">Success</div>
                 <div class="mt-1 text-sm text-slate-600">
                     {{ session('success') }}
                 </div>
             </div>
             <button type="button" @click="closeSaveModal()" class="rounded-lg p-2 hover:bg-slate-100">
                 <i data-lucide="x" class="w-4 h-4 text-slate-500"></i>
             </button>
         </div>
     </div>
 </div>

 {{-- ✅ REPORT MODAL --}}
 <div x-cloak x-show="reportOpen" x-transition.opacity
     class="fixed inset-0 z-[9999] flex items-center justify-center px-4">
     <div class="absolute inset-0 bg-black/40" @click="closeReport()"></div>

     <div class="relative w-full max-w-lg rounded-2xl bg-white p-6 shadow-xl border border-slate-200">
         <div class="flex items-start justify-between gap-3">
             <div>
                 <div class="text-base font-semibold text-slate-900">Report this job</div>
                 <div class="mt-1 text-sm text-slate-600">Tell us what’s wrong so we can review it.</div>
             </div>
             <button type="button" @click="closeReport()" class="rounded-lg p-2 hover:bg-slate-100">
                 <i data-lucide="x" class="w-4 h-4 text-slate-500"></i>
             </button>
         </div>

         <form class="mt-5 space-y-4" method="POST" action="{{ route('candidate.jobs.report.store', $job->id) }}">
             @csrf

             <div>
                 <label class="block text-sm font-medium text-slate-700">Reason <span
                         class="text-red-500">*</span></label>
                 <select name="reason" x-model="reportReason"
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

             <div>
                 <label class="block text-sm font-medium text-slate-700">Details (optional)</label>
                 <textarea name="details" rows="4" x-model="reportDetails"
                     class="mt-1 w-full rounded-xl border-slate-300 focus:ring-emerald-200 focus:border-emerald-500"
                     placeholder="Add more details..."></textarea>
                 @error('details')
                     <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                 @enderror
             </div>

             <div class="flex justify-end gap-2 pt-2">
                 <button type="button" @click="closeReport()"
                     class="px-4 py-2 rounded-xl border border-slate-300 bg-white text-slate-700 hover:bg-slate-50">
                     Cancel
                 </button>

                 <button type="submit" :disabled="!reportReason"
                     class="px-4 py-2 rounded-xl bg-emerald-600 text-white font-semibold hover:bg-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed">
                     Submit Report
                 </button>
             </div>
         </form>
     </div>
 </div>
