@extends('adminpage.layout')
@section('title','Reports')
@section('page_title','Generate Reports')

@section('content')
@php
  $reportTypes = [
    ['key'=>'user_activity', 'name'=>'User Activity', 'desc'=>'Registered users, active employers, growth'],
    ['key'=>'job_postings', 'name'=>'Job Postings', 'desc'=>'Active, pending, removed, approvals'],
    ['key'=>'revenue', 'name'=>'Subscription & Revenue', 'desc'=>'Plan revenue, payments, churn'],
    ['key'=>'applications', 'name'=>'Applications & Hires', 'desc'=>'Applications per job and hires'],
  ];

  $demoRows = [
    'user_activity' => [
      ['date'=>'2026-01-05', 'registered_users'=>52, 'active_employers'=>11, 'notes'=>'Spike from campaign'],
      ['date'=>'2026-01-12', 'registered_users'=>40, 'active_employers'=>9, 'notes'=>'Normal week'],
      ['date'=>'2026-01-19', 'registered_users'=>61, 'active_employers'=>13, 'notes'=>'New employers onboarded'],
      ['date'=>'2026-01-26', 'registered_users'=>45, 'active_employers'=>10, 'notes'=>'Stable'],
    ],
    'job_postings' => [
      ['date'=>'2026-01-05', 'active'=>300, 'pending'=>22, 'removed'=>4, 'approved'=>18],
      ['date'=>'2026-01-12', 'active'=>312, 'pending'=>28, 'removed'=>6, 'approved'=>21],
      ['date'=>'2026-01-19', 'active'=>305, 'pending'=>19, 'removed'=>3, 'approved'=>16],
      ['date'=>'2026-01-26', 'active'=>318, 'pending'=>25, 'removed'=>5, 'approved'=>20],
    ],
    'revenue' => [
      ['date'=>'2026-01-05', 'payments_completed'=>18, 'payments_failed'=>2, 'revenue'=>46200, 'plan_top'=>'Pro'],
      ['date'=>'2026-01-12', 'payments_completed'=>22, 'payments_failed'=>1, 'revenue'=>53800, 'plan_top'=>'Pro'],
      ['date'=>'2026-01-19', 'payments_completed'=>16, 'payments_failed'=>3, 'revenue'=>40100, 'plan_top'=>'Starter'],
      ['date'=>'2026-01-26', 'payments_completed'=>24, 'payments_failed'=>2, 'revenue'=>58900, 'plan_top'=>'Pro'],
    ],
    'applications' => [
      ['date'=>'2026-01-05', 'applications'=>410, 'hires'=>28, 'top_job'=>'Delivery Rider', 'conversion'=>'6.8%'],
      ['date'=>'2026-01-12', 'applications'=>382, 'hires'=>24, 'top_job'=>'Customer Support', 'conversion'=>'6.3%'],
      ['date'=>'2026-01-19', 'applications'=>448, 'hires'=>30, 'top_job'=>'Warehouse Staff', 'conversion'=>'6.7%'],
      ['date'=>'2026-01-26', 'applications'=>395, 'hires'=>26, 'top_job'=>'Factory Worker', 'conversion'=>'6.6%'],
    ],
  ];
@endphp

<div class="space-y-6"
  x-data="reportsUI({
    types: @js($reportTypes),
    demoRows: @js($demoRows),
  })"
  x-init="init()"
>

  {{-- Header --}}
  <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
      <div class="min-w-0">
        <div class="text-sm font-semibold text-slate-900">Reports Overview</div>
        <div class="mt-1 text-xs text-slate-500">
          Demo UI only. Filters update the preview rows (date-based).
        </div>
      </div>

      <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
        <button type="button" @click="reset()"
          class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50">
          Reset
        </button>

        <button type="button" @click="generate()"
          class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
          Apply Filters
        </button>
      </div>
    </div>

    <div class="mt-5 grid grid-cols-1 gap-3 lg:grid-cols-12">
      <div class="lg:col-span-5">
        <label class="text-xs font-semibold text-slate-700">Jump to report</label>
        <select x-model="filters.type" @change="jumpTo(filters.type)"
          class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm">
          <template x-for="t in types" :key="t.key">
            <option :value="t.key" x-text="t.name"></option>
          </template>
        </select>
        <div class="mt-1 text-[11px] text-slate-500" x-text="typeDesc"></div>
      </div>

      <div class="lg:col-span-3">
        <label class="text-xs font-semibold text-slate-700">From</label>
        <input type="date" x-model="filters.from"
          class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm" />
      </div>

      <div class="lg:col-span-3">
        <label class="text-xs font-semibold text-slate-700">To</label>
        <input type="date" x-model="filters.to"
          class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm" />
      </div>

      <div class="lg:col-span-1 flex items-end">
        <button type="button" @click="quickThisMonth()"
          class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold hover:bg-slate-50">
          This mo.
        </button>
      </div>
    </div>
  </div>

  {{-- Reports --}}
  <div class="space-y-4">
    <template x-for="t in types" :key="t.key">
      <section :id="`report-${t.key}`" class="rounded-2xl border border-slate-200 bg-white shadow-sm">

        {{-- Report header --}}
        <div class="flex flex-col gap-3 border-b border-slate-200 p-5 sm:flex-row sm:items-start sm:justify-between">
          <div class="min-w-0">
            <div class="flex flex-wrap items-center gap-2">
              <div class="text-sm font-semibold text-slate-900" x-text="t.name"></div>
              <span class="rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-semibold text-slate-700"
                    x-text="rangeLabel"></span>
            </div>
            <div class="mt-1 text-xs text-slate-500" x-text="t.desc"></div>
          </div>

          <div class="flex flex-wrap gap-2">
            <button type="button" @click="toggleOpen(t.key)"
              class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold hover:bg-slate-50">
              <span x-text="open[t.key] ? 'Collapse' : 'Expand'"></span>
            </button>

            <button type="button" @click="exportPDF(t.key)"
              class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold hover:bg-slate-50">
              Export PDF
            </button>

            <button type="button" @click="exportExcel(t.key)"
              class="rounded-xl bg-emerald-600 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-700">
              Export Excel
            </button>
          </div>
        </div>

        {{-- Report body --}}
        <div class="p-5" x-show="open[t.key]" x-transition>

          <div class="grid grid-cols-1 gap-4 xl:grid-cols-5">
            {{-- Summary --}}
            <div class="xl:col-span-2 space-y-4">
              <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                <div class="text-sm font-semibold text-slate-900">Summary</div>
                <div class="mt-1 text-xs text-slate-500" x-text="panels[t.key].summary.subtitle"></div>

                <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2">
                  <template x-for="c in panels[t.key].summary.cards" :key="c.label">
                    <div class="rounded-2xl border border-slate-200 bg-white p-4">
                      <div class="text-xs text-slate-500" x-text="c.label"></div>
                      <div class="mt-1 text-2xl font-bold text-slate-900" x-text="c.value"></div>
                      <div class="mt-1 text-xs font-semibold text-slate-600" x-text="c.hint"></div>
                    </div>
                  </template>
                </div>

                <div class="mt-4 rounded-xl border border-slate-200 bg-white p-4 text-xs text-slate-600">
                  Demo values only. Backend will calculate real totals and exports.
                </div>
              </div>
            </div>

            {{-- Table --}}
            <div class="xl:col-span-3 rounded-2xl border border-slate-200 bg-white p-5">
              <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <div>
                  <div class="text-sm font-semibold text-slate-900">Results</div>
                  <div class="mt-1 text-xs text-slate-500">
                    <span x-text="panels[t.key].tableMeta.label"></span>
                  </div>
                </div>

                <div class="flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2">
                  <span class="text-slate-400">⌕</span>
                  <input
                    x-model.trim="panels[t.key].tableSearch"
                    @input="onSearch(t.key)"
                    class="w-56 bg-transparent text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none"
                    placeholder="Search in table…" />
                </div>
              </div>

              <div class="mt-4 overflow-x-auto">
                <table class="min-w-full text-left">
                  <thead>
                    <tr class="text-xs text-slate-500">
                      <template x-for="h in panels[t.key].table.headers" :key="h">
                        <th class="py-2 pr-4" x-text="h"></th>
                      </template>
                    </tr>
                  </thead>

                  <tbody class="divide-y divide-slate-200">
                    <template x-for="(row, idx) in filteredRows(t.key)" :key="idx">
                      <tr class="text-sm">
                        <template x-for="cell in row" :key="cell.key">
                          <td class="py-3 pr-4 text-slate-800" x-text="cell.value"></td>
                        </template>
                      </tr>
                    </template>

                    <tr x-show="filteredRows(t.key).length === 0">
                      <td :colspan="panels[t.key].table.headers.length"
                          class="py-8 text-center text-sm text-slate-500">
                        No rows match your filters/search.
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <div class="mt-4 flex items-center justify-between text-xs text-slate-500">
                <div x-text="`${filteredRows(t.key).length} row(s)`"></div>
                <div>Preview only</div>
              </div>
            </div>
          </div>

        </div>
      </section>
    </template>
  </div>

</div>

<script>
  function reportsUI(seed){
    return {
      types: seed.types || [],
      demoRows: seed.demoRows || {},

      filters: { type: 'user_activity', from: '', to: '' },

      panels: {},
      open: {},
      _lastSearchToastAt: {},

      toast(type, msg, title = ''){
  if (!window.toast) return;

  const allowed = ['success','info','warning','error'];
  const safeType = allowed.includes(type) ? type : 'info';

  const message = String(msg || '');
  const ttl = String(title || '');
  const text = ttl ? `${ttl}: ${message}` : message;

  window.toast(safeType, text);
},

      init(){
        this.quickThisMonth(true);

        this.types.forEach(t => {
          this.open[t.key] = true;
          this.panels[t.key] = {
            tableSearch: '',
            tableMeta: { label: 'Demo rows' },
            summary: { subtitle: '', cards: [] },
            table: { headers: [], rows: [] },
          };

          const rows = this.getRowsForRange(t.key);
          this.buildTable(t.key, rows);
          this.buildSummary(t.key, rows);
        });

        this.toast('info', 'Reports ready');
      },

      get typeDesc(){
        const t = this.types.find(x => x.key === this.filters.type);
        return t ? t.desc : '';
      },

      get rangeLabel(){
        const f = this.filters.from || '—';
        const t = this.filters.to || '—';
        return `${f} → ${t}`;
      },

      quickThisMonth(silent=false){
        const d = new Date();
        const y = d.getFullYear();
        const m = String(d.getMonth()+1).padStart(2,'0');

        this.filters.from = `${y}-${m}-01`;
        this.filters.to = `${y}-${m}-28`; // demo

        if(!silent) this.toast('info', 'Set range to this month');
      },

      reset(){
        this.filters.type = 'user_activity';
        this.quickThisMonth(true);

        this.types.forEach(t => {
          this.panels[t.key].tableSearch = '';
          this.open[t.key] = true;

          const rows = this.getRowsForRange(t.key);
          this.buildTable(t.key, rows);
          this.buildSummary(t.key, rows);
        });

        this.toast('info', 'Filters reset');
        this.jumpTo(this.filters.type);
      },

      generate(){
        const v = this.validateRange();
        if(!v.ok){
          this.toast('error', v.msg);
          return;
        }

        this.types.forEach(t => {
          const rows = this.getRowsForRange(t.key);
          this.buildTable(t.key, rows);
          this.buildSummary(t.key, rows);
        });

        this.toast('success', 'Filters applied');
        this.jumpTo(this.filters.type);
      },

      validateRange(){
        const f = this.filters.from;
        const t = this.filters.to;

        if(!f || !t) return { ok:false, msg:'Please select both From and To dates.' };

        const fd = new Date(f);
        const td = new Date(t);
        if(Number.isNaN(fd.getTime()) || Number.isNaN(td.getTime())) return { ok:false, msg:'Invalid date range.' };
        if(fd > td) return { ok:false, msg:'From date cannot be later than To date.' };

        return { ok:true, msg:'' };
      },

      getRowsForRange(key){
        const rows = (this.demoRows[key] || []).map(r => ({...r}));

        const f = this.filters.from ? new Date(this.filters.from) : null;
        const t = this.filters.to ? new Date(this.filters.to) : null;

        const filtered = rows.filter(r => {
          if(!r.date) return true;
          const d = new Date(r.date);
          if(f && d < f) return false;
          if(t && d > t) return false;
          return true;
        });

        this.panels[key].tableMeta.label = `Demo rows • ${filtered.length} match(es)`;
        return filtered;
      },

      jumpTo(key){
        const el = document.getElementById(`report-${key}`);
        if(!el) return;
        el.scrollIntoView({ behavior: 'smooth', block: 'start' });
        this.toast('info', 'Jumped to ' + (this.types.find(t => t.key === key)?.name || key));
      },

      toggleOpen(key){
        this.open[key] = !this.open[key];
        this.toast('info', this.open[key] ? 'Expanded section' : 'Collapsed section');
      },

      exportPDF(key){
        const name = this.types.find(t => t.key === key)?.name || key;
        this.toast('info', `Export PDF: ${name} (demo)`);
      },

      exportExcel(key){
        const name = this.types.find(t => t.key === key)?.name || key;
        this.toast('info', `Export Excel: ${name} (demo)`);
      },

      onSearch(key){
        const now = Date.now();
        const last = this._lastSearchToastAt[key] || 0;
        if(now - last < 900) return;

        const count = this.filteredRows(key).length;
        const q = (this.panels[key].tableSearch || '').trim();
        if(q && count === 0){
          this._lastSearchToastAt[key] = now;
          this.toast('warning', 'No matching rows');
        }
      },

      filteredRows(key){
        const panel = this.panels[key];
        const q = (panel.tableSearch || '').toLowerCase().trim();
        if(!q) return panel.table.rows;

        return panel.table.rows.filter(r =>
          r.some(cell => String(cell.value ?? '').toLowerCase().includes(q))
        );
      },

      buildSummary(key, rows){
        const name = (this.types.find(t => t.key === key)?.name) || key;
        const from = this.filters.from || '—';
        const to = this.filters.to || '—';

        const fmt = (n) => String(n ?? 0).replace(/\B(?=(\d{3})+(?!\d))/g, ",");

        this.panels[key].summary.subtitle = `${name} • ${from} to ${to}`;

        if(!rows.length){
          this.panels[key].summary.cards = [
            { label:'No data', value:'—', hint:'No rows in range' },
            { label:'No data', value:'—', hint:'No rows in range' },
            { label:'No data', value:'—', hint:'No rows in range' },
            { label:'No data', value:'—', hint:'No rows in range' },
          ];
          return;
        }

        if(key === 'user_activity'){
          const reg = rows.reduce((a,r)=>a + (Number(r.registered_users)||0), 0);
          const emp = rows.reduce((a,r)=>a + (Number(r.active_employers)||0), 0);
          this.panels[key].summary.cards = [
            { label:'Total registered users', value: fmt(reg), hint:'Sum of rows' },
            { label:'Total active employers', value: fmt(emp), hint:'Sum of rows' },
            { label:'Avg weekly registrations', value: fmt(Math.round(reg / Math.max(1, rows.length))), hint:'Average' },
            { label:'Avg active employers', value: fmt(Math.round(emp / Math.max(1, rows.length))), hint:'Average' },
          ];
          return;
        }

        if(key === 'job_postings'){
          const active = rows.reduce((a,r)=>a + (Number(r.active)||0), 0);
          const pending = rows.reduce((a,r)=>a + (Number(r.pending)||0), 0);
          const removed = rows.reduce((a,r)=>a + (Number(r.removed)||0), 0);
          const approved = rows.reduce((a,r)=>a + (Number(r.approved)||0), 0);
          this.panels[key].summary.cards = [
            { label:'Active (sum)', value: fmt(active), hint:'Sum of rows' },
            { label:'Pending (sum)', value: fmt(pending), hint:'Sum of rows' },
            { label:'Approved (sum)', value: fmt(approved), hint:'Sum of rows' },
            { label:'Removed (sum)', value: fmt(removed), hint:'Sum of rows' },
          ];
          return;
        }

        if(key === 'revenue'){
          const completed = rows.reduce((a,r)=>a + (Number(r.payments_completed)||0), 0);
          const failed = rows.reduce((a,r)=>a + (Number(r.payments_failed)||0), 0);
          const revenue = rows.reduce((a,r)=>a + (Number(r.revenue)||0), 0);

          const counts = {};
          rows.forEach(r => {
            const p = String(r.plan_top || '').trim();
            if(!p) return;
            counts[p] = (counts[p] || 0) + 1;
          });
          let topPlan = '—', topCount = -1;
          Object.keys(counts).forEach(k => {
            if(counts[k] > topCount){ topCount = counts[k]; topPlan = k; }
          });

          this.panels[key].summary.cards = [
            { label:'Payments completed', value: fmt(completed), hint:'Sum of rows' },
            { label:'Payments failed', value: fmt(failed), hint:'Sum of rows' },
            { label:'Revenue (₱)', value: '₱ ' + fmt(revenue), hint:'Sum of rows' },
            { label:'Top plan', value: topPlan, hint:'Most frequent' },
          ];
          return;
        }

        const apps = rows.reduce((a,r)=>a + (Number(r.applications)||0), 0);
        const hires = rows.reduce((a,r)=>a + (Number(r.hires)||0), 0);
        const conv = apps ? ((hires / apps) * 100) : 0;
        this.panels[key].summary.cards = [
          { label:'Applications', value: fmt(apps), hint:'Sum of rows' },
          { label:'Hires', value: fmt(hires), hint:'Sum of rows' },
          { label:'Overall conversion', value: conv.toFixed(1) + '%', hint:'hires / applications' },
          { label:'Rows', value: fmt(rows.length), hint:'Periods' },
        ];
      },

      buildTable(key, rows){
        if(!rows.length){
          this.panels[key].table.headers = ['No data'];
          this.panels[key].table.rows = [];
          return;
        }

        const keys = Object.keys(rows[0]);
        this.panels[key].table.headers = keys.map(k => this.prettyHeader(k));

        this.panels[key].table.rows = rows.map(r => {
          return keys.map(k => {
            let v = r[k];

            if(k === 'revenue'){
              v = '₱ ' + String(v ?? 0).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            }

            return { key:k, value: v };
          });
        });
      },

      prettyHeader(k){
        return String(k)
          .replace(/_/g,' ')
          .replace(/\b\w/g, c => c.toUpperCase());
      },
    }
  }
</script>
@endsection
