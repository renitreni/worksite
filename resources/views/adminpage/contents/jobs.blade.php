@extends('adminpage.layout')
@section('title','Job Postings')
@section('page_title','Manage Job Postings')

@section('content')
@php
  $stats = [
    ['label'=>'Pending Review','value'=>'28'],
    ['label'=>'Active Jobs','value'=>'312'],
    ['label'=>'Rejected','value'=>'16'],
    ['label'=>'Removed (Invalid)','value'=>'9'],
  ];

  $jobs = [
    [
      'id'=>101,
      'title'=>'Frontend Developer (Angular)',
      'company'=>'ACME Corp',
      'company_email'=>'hr@acme.com',
      'location'=>'Makati City',
      'type'=>'Full-time',
      'salary'=>'₱45,000 – ₱70,000',
      'posted'=>'2026-02-02',
      'expires'=>'2026-03-02',
      'status'=>'Pending',
      'flags'=>['New'],
      'category'=>'IT / Software',
      'skills'=>['Angular','Tailwind','REST API'],
      'description'=>'We are looking for a Frontend Developer to build responsive web UI using Angular and Tailwind. You will work closely with backend and QA teams.',
      'notes'=>'',
    ],
    [
      'id'=>102,
      'title'=>'Delivery Rider (Part-time)',
      'company'=>'QuickShip PH',
      'company_email'=>'admin@quickship.ph',
      'location'=>'Quezon City',
      'type'=>'Part-time',
      'salary'=>'₱18,000 – ₱25,000',
      'posted'=>'2026-02-01',
      'expires'=>'2026-02-20',
      'status'=>'Pending',
      'flags'=>['Reported'],
      'category'=>'Logistics',
      'skills'=>['Customer Service','Navigation'],
      'description'=>'Deliver parcels within assigned areas. Must have valid license and own motorcycle.',
      'notes'=>'Multiple reports: verify legitimacy.',
    ],
    [
      'id'=>103,
      'title'=>'HR Assistant',
      'company'=>'TechTalent Hub',
      'company_email'=>'ops@techtalent.io',
      'location'=>'Taguig City',
      'type'=>'Full-time',
      'salary'=>'₱22,000 – ₱30,000',
      'posted'=>'2026-01-20',
      'expires'=>'2026-01-31',
      'status'=>'Expired',
      'flags'=>['Suspicious'],
      'category'=>'Human Resources',
      'skills'=>['Documentation','MS Office'],
      'description'=>'Assist HR operations including onboarding, document processing, and employee support.',
      'notes'=>'Expired — remove or request repost.',
    ],
    [
      'id'=>104,
      'title'=>'Backend Developer (Laravel)',
      'company'=>'QuickShip PH',
      'company_email'=>'admin@quickship.ph',
      'location'=>'Remote',
      'type'=>'Contract',
      'salary'=>'₱60,000 – ₱90,000',
      'posted'=>'2026-01-25',
      'expires'=>'2026-03-10',
      'status'=>'Approved',
      'flags'=>[],
      'category'=>'IT / Software',
      'skills'=>['Laravel','MySQL','API'],
      'description'=>'Build APIs and services using Laravel and MySQL. Maintain code quality and documentation.',
      'notes'=>'Approved after review.',
    ],
    [
      'id'=>105,
      'title'=>'Data Encoder',
      'company'=>'ACME Corp',
      'company_email'=>'hr@acme.com',
      'location'=>'Pasig City',
      'type'=>'Full-time',
      'salary'=>'₱16,000 – ₱20,000',
      'posted'=>'2026-01-28',
      'expires'=>'2026-02-28',
      'status'=>'Rejected',
      'flags'=>['Suspicious'],
      'category'=>'Admin',
      'skills'=>['Typing','Accuracy'],
      'description'=>'Encode documents and maintain daily reports. Must be detail-oriented.',
      'notes'=>'Rejected: incomplete job details.',
    ],
  ];
@endphp

<div x-data="jobModeration(@js($jobs))" x-init="init()" class="space-y-6">

  <div
    x-show="toast.show"
    x-transition
    class="fixed right-5 top-5 z-50 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-lg"
  >
    <span x-text="toast.message"></span>
  </div>

  {{-- Stats --}}
  <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
    @foreach($stats as $s)
      <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="text-sm text-slate-500">{{ $s['label'] }}</div>
        <div class="mt-2 text-3xl font-bold tracking-tight text-slate-900">{{ $s['value'] }}</div>
        <div class="mt-3 h-2 w-full rounded-full bg-slate-100">
          <div class="h-2 w-2/3 rounded-full bg-emerald-600"></div>
        </div>
      </div>
    @endforeach
  </div>

  {{-- Filters --}}
  <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">

      <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:flex-wrap">
        <div class="flex w-full items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 sm:w-80">
          <span class="text-slate-400">⌕</span>
          <input
            x-model="filters.q"
            class="w-full bg-transparent text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none"
            placeholder="Search title, company, location…"
          />
        </div>

        <select x-model="filters.status"
          class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100">
          <option value="All">All Status</option>
          <option value="Pending">Pending</option>
          <option value="Approved">Approved</option>
          <option value="Rejected">Rejected</option>
          <option value="Removed">Removed</option>
          <option value="Expired">Expired</option>
        </select>

        <select x-model="filters.flag"
          class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100">
          <option value="All">All Flags</option>
          <option value="New">New</option>
          <option value="Reported">Reported</option>
          <option value="Suspicious">Suspicious</option>
          <option value="None">No Flags</option>
        </select>

        <div class="flex items-center gap-2">
          <input type="date" x-model="filters.from"
            class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100" />
          <span class="text-sm text-slate-500">to</span>
          <input type="date" x-model="filters.to"
            class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100" />
        </div>
      </div>

      <div class="flex flex-wrap gap-2">
        <button type="button"
          class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50"
          @click="notify('Export')"
        >
          Export
        </button>
        <button type="button"
          class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700"
          @click="notify('Bulk actions')"
        >
          Bulk Actions
        </button>
      </div>
    </div>
  </div>

  {{-- Queue + Review --}}
  <div class="grid grid-cols-1 gap-4 xl:grid-cols-5">

    <div class="xl:col-span-3 rounded-2xl border border-slate-200 bg-white shadow-sm">
      <div class="border-b border-slate-200 p-5">
        <div class="text-sm font-semibold">Moderation Queue</div>
        <div class="mt-1 text-xs text-slate-500">Jobs submitted by employers appear here for review</div>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
          <thead class="bg-slate-50 text-xs font-semibold text-slate-600">
            <tr>
              <th class="px-5 py-3">Job</th>
              <th class="px-5 py-3">Company</th>
              <th class="px-5 py-3">Status</th>
              <th class="px-5 py-3">Posted</th>
              <th class="px-5 py-3">Expires</th>
            </tr>
          </thead>

          <tbody class="divide-y divide-slate-200">
            <template x-for="j in filteredJobs()" :key="j.id">
              <tr
                class="cursor-pointer hover:bg-slate-50"
                :class="selected?.id === j.id ? 'bg-emerald-50/40' : ''"
                @click="select(j)"
              >
                <td class="px-5 py-4">
                  <div class="font-semibold text-slate-900" x-text="j.title"></div>
                  <div class="mt-1 flex flex-wrap items-center gap-2 text-xs text-slate-500">
                    <span class="rounded-full bg-white px-2 py-0.5 ring-1 ring-slate-200" x-text="j.location"></span>
                    <span class="rounded-full bg-white px-2 py-0.5 ring-1 ring-slate-200" x-text="j.type"></span>
                    <template x-for="f in (j.flags || [])" :key="f">
                      <span class="rounded-full bg-amber-50 px-2 py-0.5 text-amber-700 ring-1 ring-amber-200" x-text="f"></span>
                    </template>
                  </div>
                </td>

                <td class="px-5 py-4">
                  <div class="text-slate-800" x-text="j.company"></div>
                  <div class="text-xs text-slate-500" x-text="j.company_email"></div>
                </td>

                <td class="px-5 py-4">
                  <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1"
                        :class="statusPill(j.status)"
                        x-text="j.status">
                  </span>
                </td>

                <td class="px-5 py-4 text-slate-700" x-text="j.posted"></td>
                <td class="px-5 py-4 text-slate-700" x-text="j.expires"></td>
              </tr>
            </template>

            <tr x-show="filteredJobs().length === 0">
              <td colspan="5" class="px-5 py-10 text-center text-sm text-slate-500">
                No results found.
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="flex flex-col gap-2 border-t border-slate-200 p-4 text-sm text-slate-600 sm:flex-row sm:items-center sm:justify-between">
        <span>Showing <span x-text="Math.min(filteredJobs().length, 10)"></span> of <span x-text="filteredJobs().length"></span></span>
        <div class="flex gap-2">
          <button class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm font-semibold hover:bg-slate-50" @click="notify('Prev')">Prev</button>
          <button class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm font-semibold hover:bg-slate-50" @click="notify('Next')">Next</button>
        </div>
      </div>
    </div>

    <div class="xl:col-span-2 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
      <div class="flex items-start justify-between">
        <div>
          <div class="text-sm font-semibold">Job Review</div>
          <div class="mt-1 text-xs text-slate-500">Select a job to view details and take action</div>
        </div>

        <template x-if="selected">
          <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1"
                :class="statusPill(selected.status)">
            <span x-text="selected.status"></span>
          </span>
        </template>
      </div>

      <div x-show="!selected" class="mt-10 rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-8 text-center">
        <div class="text-sm font-semibold text-slate-800">No job selected</div>
        <div class="mt-1 text-xs text-slate-500">Click a row in the queue to review.</div>
      </div>

      <div x-show="selected" x-transition class="mt-5 space-y-4">

        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
          <div class="text-sm font-semibold text-slate-900" x-text="selected?.title"></div>
          <div class="mt-1 text-xs text-slate-600">
            <span class="font-semibold" x-text="selected?.company"></span>
            <span class="mx-1">•</span>
            <span x-text="selected?.location"></span>
          </div>

          <div class="mt-3 grid grid-cols-2 gap-3 text-sm">
            <div class="rounded-xl bg-white p-3 ring-1 ring-slate-200">
              <div class="text-xs text-slate-500">Type</div>
              <div class="mt-1 font-semibold" x-text="selected?.type"></div>
            </div>
            <div class="rounded-xl bg-white p-3 ring-1 ring-slate-200">
              <div class="text-xs text-slate-500">Salary</div>
              <div class="mt-1 font-semibold" x-text="selected?.salary"></div>
            </div>
            <div class="rounded-xl bg-white p-3 ring-1 ring-slate-200">
              <div class="text-xs text-slate-500">Posted</div>
              <div class="mt-1 font-semibold" x-text="selected?.posted"></div>
            </div>
            <div class="rounded-xl bg-white p-3 ring-1 ring-slate-200">
              <div class="text-xs text-slate-500">Expires</div>
              <div class="mt-1 font-semibold" x-text="selected?.expires"></div>
            </div>
          </div>

          <div class="mt-4">
            <div class="text-xs font-semibold text-slate-700">Category</div>
            <div class="mt-1 text-sm text-slate-700" x-text="selected?.category"></div>
          </div>

          <div class="mt-4">
            <div class="text-xs font-semibold text-slate-700">Skills</div>
            <div class="mt-2 flex flex-wrap gap-2">
              <template x-for="s in (selected?.skills || [])" :key="s">
                <span class="rounded-full bg-white px-2 py-0.5 text-xs font-semibold text-slate-700 ring-1 ring-slate-200" x-text="s"></span>
              </template>
            </div>
          </div>

          <div class="mt-4">
            <div class="text-xs font-semibold text-slate-700">Description</div>
            <p class="mt-2 text-sm leading-relaxed text-slate-700" x-text="selected?.description"></p>
          </div>
        </div>

        {{-- Checklist --}}
        <div class="rounded-2xl border border-slate-200 p-4">
          <div class="text-sm font-semibold">Platform Standards</div>
          <div class="mt-1 text-xs text-slate-500">Quick checklist before approving</div>

          <div class="mt-4 space-y-2 text-sm">
            <label class="flex items-center gap-2">
              <input type="checkbox" x-model="checks.hasClearTitle" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-200">
              <span>Clear job title and responsibilities</span>
            </label>
            <label class="flex items-center gap-2">
              <input type="checkbox" x-model="checks.hasValidCompany" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-200">
              <span>Company details look valid</span>
            </label>
            <label class="flex items-center gap-2">
              <input type="checkbox" x-model="checks.noScamSignals" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-200">
              <span>No scam signals (fees, suspicious links)</span>
            </label>
            <label class="flex items-center gap-2">
              <input type="checkbox" x-model="checks.correctCategory" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-200">
              <span>Category and skills match the job</span>
            </label>
          </div>
        </div>

        {{-- Notes --}}
        <div class="rounded-2xl border border-slate-200 p-4">
          <div class="text-sm font-semibold">Internal Notes</div>
          <textarea
            class="mt-2 h-24 w-full rounded-xl border border-slate-200 bg-white p-3 text-sm focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100"
            placeholder="Reason for approval/rejection/removal…"
            x-model="selectedNotes"
          ></textarea>
          <button type="button"
            class="mt-3 w-full rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50"
            @click="saveNotes()"
          >
            Save Notes
          </button>
        </div>

        {{-- Actions --}}
        <div class="space-y-2">
          <button
            type="button"
            class="w-full rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700"
            @click="approve()"
            :disabled="!selected || selected.status === 'Approved'"
          >
            Approve Job
          </button>

          <button
            type="button"
            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50"
            @click="reject()"
            :disabled="!selected || selected.status === 'Rejected'"
          >
            Reject Job
          </button>

          <button
            type="button"
            class="w-full rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700"
            @click="remove()"
            :disabled="!selected || selected.status === 'Removed'"
          >
            Remove (Fake/Invalid/Expired)
          </button>

          <button
            type="button"
            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50"
            @click="notifyEmployer()"
          >
            Notify Employer
          </button>
        </div>

      </div>
    </div>

  </div>
</div>

<script>
  function jobModeration(initialJobs){
    return {
      jobs: initialJobs,
      selected: null,
      selectedNotes: '',
      toast: { show:false, message:'' },

      checks: {
        hasClearTitle: false,
        hasValidCompany: false,
        noScamSignals: false,
        correctCategory: false,
      },

      filters: { q:'', status:'All', flag:'All', from:'', to:'' },

      init(){
        // this.select(this.jobs[0] ?? null);
      },

      notify(msg){
        this.toast.message = msg;
        this.toast.show = true;
        setTimeout(() => this.toast.show = false, 1800);
      },

      statusPill(s){
        if(s === 'Pending') return 'bg-amber-50 text-amber-700 ring-amber-200';
        if(s === 'Approved') return 'bg-emerald-50 text-emerald-700 ring-emerald-200';
        if(s === 'Rejected') return 'bg-rose-50 text-rose-700 ring-rose-200';
        if(s === 'Removed') return 'bg-slate-100 text-slate-700 ring-slate-200';
        if(s === 'Expired') return 'bg-slate-100 text-slate-700 ring-slate-200';
        return 'bg-slate-100 text-slate-700 ring-slate-200';
      },

      select(j){
        if(!j) return;
        this.selected = JSON.parse(JSON.stringify(j));
        this.selectedNotes = this.selected.notes || '';
        this.checks = { hasClearTitle:false, hasValidCompany:false, noScamSignals:false, correctCategory:false };
      },

      filteredJobs(){
        const q = (this.filters.q || '').toLowerCase().trim();
        const status = this.filters.status;
        const flag = this.filters.flag;

        const from = this.filters.from ? new Date(this.filters.from) : null;
        const to = this.filters.to ? new Date(this.filters.to) : null;

        return this.jobs.filter(j => {
          const hay = `${j.title} ${j.company} ${j.location}`.toLowerCase();
          const matchesQ = !q || hay.includes(q);
          const matchesStatus = status === 'All' || j.status === status;

          const hasFlags = (j.flags || []);
          const matchesFlag =
            flag === 'All' ||
            (flag === 'None' && hasFlags.length === 0) ||
            hasFlags.includes(flag);

          let matchesDate = true;
          if(from) matchesDate = matchesDate && (new Date(j.posted) >= from);
          if(to) matchesDate = matchesDate && (new Date(j.posted) <= to);

          return matchesQ && matchesStatus && matchesFlag && matchesDate;
        });
      },

      syncSelected(){
        if(!this.selected) return;
        const idx = this.jobs.findIndex(x => x.id === this.selected.id);
        if(idx !== -1) this.jobs[idx] = JSON.parse(JSON.stringify(this.selected));
      },

      saveNotes(){
        if(!this.selected) return;
        this.selected.notes = this.selectedNotes;
        this.syncSelected();
        this.notify('Notes saved');
      },

      approve(){
        if(!this.selected) return;
        this.selected.status = 'Approved';
        this.selected.notes = this.selectedNotes;
        this.syncSelected();
        this.notify('Job approved');
      },

      reject(){
        if(!this.selected) return;
        this.selected.status = 'Rejected';
        this.selected.notes = this.selectedNotes;
        this.syncSelected();
        this.notify('Job rejected');
      },

      remove(){
        if(!this.selected) return;
        this.selected.status = 'Removed';
        this.selected.notes = this.selectedNotes;
        this.syncSelected();
        this.notify('Job removed');
      },

      notifyEmployer(){
        if(!this.selected) return;
        this.notify('Employer notified');
      },
    }
  }
</script>
@endsection
