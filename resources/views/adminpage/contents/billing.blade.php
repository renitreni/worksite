@extends('adminpage.layout')
@section('title','Subscription & Payment Management')
@section('page_title','Subscription & Payment Management')

@section('content')
@php
  $plans = [
    [
      'id'=>1, 'name'=>'Starter', 'interval'=>'monthly', 'price'=>499, 'currency'=>'PHP',
      'features'=>['Post up to 3 jobs','Basic support','1 recruiter seat'],
      'is_active'=>true, 'badge'=>'Most picked'
    ],
    [
      'id'=>2, 'name'=>'Pro', 'interval'=>'monthly', 'price'=>1299, 'currency'=>'PHP',
      'features'=>['Unlimited job posts','Featured listing','Priority support','3 recruiter seats'],
      'is_active'=>true, 'badge'=>'Best value'
    ],
    [
      'id'=>3, 'name'=>'Enterprise', 'interval'=>'yearly', 'price'=>14999, 'currency'=>'PHP',
      'features'=>['Custom onboarding','Dedicated manager','Team seats (10)','API access (later)'],
      'is_active'=>false, 'badge'=>'Hidden'
    ],
  ];

  $payments = [
    [
      'id'=>'PAY-10021','employer'=>'ACME Corp','plan'=>'Pro','amount'=>1299,'currency'=>'PHP',
      'method'=>'GCash','status'=>'pending','created_at'=>'2026-02-02 10:14','ref'=>'GC-88421'
    ],
    [
      'id'=>'PAY-10020','employer'=>'QuickShip PH','plan'=>'Starter','amount'=>499,'currency'=>'PHP',
      'method'=>'Bank Transfer','status'=>'completed','created_at'=>'2026-02-01 18:40','ref'=>'BT-55210'
    ],
    [
      'id'=>'PAY-10019','employer'=>'TechTalent Hub','plan'=>'Pro','amount'=>1299,'currency'=>'PHP',
      'method'=>'Card','status'=>'failed','created_at'=>'2026-02-01 12:22','ref'=>'CC-90112'
    ],
  ];

  $subs = [
    [
      'id'=>'SUB-20031','employer'=>'ACME Corp','plan'=>'Pro','status'=>'pending_verification',
      'start'=>'—','end'=>'—','last_payment'=>'PAY-10021'
    ],
    [
      'id'=>'SUB-20030','employer'=>'QuickShip PH','plan'=>'Starter','status'=>'active',
      'start'=>'2026-02-01','end'=>'2026-03-01','last_payment'=>'PAY-10020'
    ],
    [
      'id'=>'SUB-20029','employer'=>'Mark Reyes Co','plan'=>'Pro','status'=>'expired',
      'start'=>'2025-12-01','end'=>'2026-01-01','last_payment'=>'PAY-09981'
    ],
    [
      'id'=>'SUB-20028','employer'=>'OldCo Inc','plan'=>'Starter','status'=>'suspended',
      'start'=>'2026-01-05','end'=>'2026-02-05','last_payment'=>'PAY-09990'
    ],
  ];

  $reminders = [
    ['id'=>'REM-30011','employer'=>'Mark Reyes Co','plan'=>'Pro','expired_on'=>'2026-01-01','days_over'=>33,'email'=>'billing@markreyes.co'],
    ['id'=>'REM-30010','employer'=>'Some Agency','plan'=>'Starter','expired_on'=>'2026-01-15','days_over'=>19,'email'=>'accounts@someagency.com'],
  ];
@endphp

<div class="space-y-6"
  x-data="subsUI({
    plans: @js($plans),
    payments: @js($payments),
    subs: @js($subs),
    reminders: @js($reminders),
  })"
  x-init="init()"
>

  <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
      <div class="min-w-0">
        <div class="text-sm font-semibold text-slate-900">Monetized plans + subscription monitoring</div>
        <div class="mt-1 text-xs text-slate-500">
          Frontend-only demo: create plans, verify payments, activate/suspend subscriptions, send reminders.
        </div>
      </div>

      <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
        <div class="flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2">
          <span class="text-slate-400">⌕</span>
          <input x-model.trim="q"
            class="w-full bg-transparent text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none sm:w-80"
            placeholder="Search employer, plan, payment ID, status…" />
        </div>

        <button type="button"
          @click="openPlanModal(); toast('info','Create a new plan')"
          class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
          + New Plan
        </button>
      </div>
    </div>

    <div class="mt-4 flex flex-wrap gap-2">
      <button type="button" @click="tab='plans'"
        class="rounded-xl px-4 py-2 text-sm font-semibold ring-1"
        :class="tab==='plans' ? 'bg-emerald-600 text-white ring-emerald-600' : 'bg-white text-slate-700 ring-slate-200 hover:bg-slate-50'">
        Plans
      </button>

      <button type="button" @click="tab='payments'"
        class="rounded-xl px-4 py-2 text-sm font-semibold ring-1"
        :class="tab==='payments' ? 'bg-emerald-600 text-white ring-emerald-600' : 'bg-white text-slate-700 ring-slate-200 hover:bg-slate-50'">
        Payments
      </button>

      <button type="button" @click="tab='subs'"
        class="rounded-xl px-4 py-2 text-sm font-semibold ring-1"
        :class="tab==='subs' ? 'bg-emerald-600 text-white ring-emerald-600' : 'bg-white text-slate-700 ring-slate-200 hover:bg-slate-50'">
        Employer Subscriptions
      </button>

      <button type="button" @click="tab='reminders'"
        class="rounded-xl px-4 py-2 text-sm font-semibold ring-1"
        :class="tab==='reminders' ? 'bg-emerald-600 text-white ring-emerald-600' : 'bg-white text-slate-700 ring-slate-200 hover:bg-slate-50'">
        Reminders
      </button>
    </div>
  </div>

  <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
      <div class="text-xs text-slate-500">Active plans</div>
      <div class="mt-2 text-3xl font-bold text-slate-900" x-text="kpi.activePlans"></div>
      <div class="mt-2 text-xs text-slate-500">Visible to employers</div>
    </div>
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
      <div class="text-xs text-slate-500">Payments pending</div>
      <div class="mt-2 text-3xl font-bold text-slate-900" x-text="kpi.pendingPayments"></div>
      <div class="mt-2 text-xs text-slate-500">Need admin verification</div>
    </div>
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
      <div class="text-xs text-slate-500">Active subscriptions</div>
      <div class="mt-2 text-3xl font-bold text-slate-900" x-text="kpi.activeSubs"></div>
      <div class="mt-2 text-xs text-slate-500">Currently enabled</div>
    </div>
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
      <div class="text-xs text-slate-500">Expired / suspended</div>
      <div class="mt-2 text-3xl font-bold text-slate-900" x-text="kpi.riskSubs"></div>
      <div class="mt-2 text-xs text-slate-500">Restricted access</div>
    </div>
  </div>

  {{-- Plans --}}
  <div x-show="tab==='plans'" x-transition class="rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="border-b border-slate-200 p-5">
      <div class="flex items-start justify-between gap-3">
        <div>
          <div class="text-sm font-semibold text-slate-900">Subscription Plans</div>
          <div class="mt-1 text-xs text-slate-500">Create, edit, delete plans and control visibility</div>
        </div>
        <button type="button" @click="openPlanModal(); toast('info','Create a new plan')"
          class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold hover:bg-slate-50">
          Add Plan
        </button>
      </div>
    </div>

    <div class="p-5">
      <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
        <template x-for="p in planFiltered" :key="p.id">
          <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
            <div class="flex items-start justify-between gap-3">
              <div class="min-w-0">
                <div class="flex items-center gap-2">
                  <div class="text-sm font-semibold text-slate-900" x-text="p.name"></div>
                  <template x-if="p.badge">
                    <span class="rounded-full bg-white px-2 py-0.5 text-[11px] font-semibold text-slate-700 ring-1 ring-slate-200"
                      x-text="p.badge"></span>
                  </template>
                </div>
                <div class="mt-1 text-xs text-slate-500">
                  <span x-text="formatMoney(p.price, p.currency)"></span>
                  <span class="text-slate-400">•</span>
                  <span class="uppercase" x-text="p.interval"></span>
                </div>
              </div>

              <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1"
                :class="p.is_active ? chip('good') : chip('bad')">
                <span x-text="p.is_active ? 'Active' : 'Hidden'"></span>
              </span>
            </div>

            <div class="mt-3 space-y-1">
              <template x-for="(f, i) in p.features" :key="p.id+'f'+i">
                <div class="text-xs text-slate-700">• <span x-text="f"></span></div>
              </template>
            </div>

            <div class="mt-4 flex flex-wrap gap-2">
              <button type="button" @click="openPlanModal(p)"
                class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold hover:bg-slate-50">
                Edit
              </button>
              <button type="button" @click="togglePlan(p.id)"
                class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold hover:bg-slate-50">
                <span x-text="p.is_active ? 'Hide' : 'Activate'"></span>
              </button>
              <button type="button" @click="deletePlan(p.id)"
                class="rounded-xl bg-rose-600 px-4 py-2 text-xs font-semibold text-white hover:bg-rose-700">
                Delete
              </button>
            </div>
          </div>
        </template>
      </div>

      <template x-if="planFiltered.length === 0">
        <div class="mt-4 rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">
          No matching plans.
        </div>
      </template>
    </div>
  </div>

  {{-- Payments --}}
  <div x-show="tab==='payments'" x-transition class="rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="border-b border-slate-200 p-5">
      <div class="text-sm font-semibold text-slate-900">Employer Payments</div>
      <div class="mt-1 text-xs text-slate-500">Track pending, completed, and failed payments</div>
    </div>

    <div class="p-5 overflow-x-auto">
      <table class="min-w-full text-left">
        <thead>
          <tr class="text-xs text-slate-500">
            <th class="py-2 pr-4">Payment</th>
            <th class="py-2 pr-4">Employer</th>
            <th class="py-2 pr-4">Plan</th>
            <th class="py-2 pr-4">Amount</th>
            <th class="py-2 pr-4">Method</th>
            <th class="py-2 pr-4">Status</th>
            <th class="py-2 pr-4">Created</th>
            <th class="py-2">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
          <template x-for="pay in paymentFiltered" :key="pay.id">
            <tr class="text-sm">
              <td class="py-3 pr-4">
                <div class="font-semibold text-slate-900" x-text="pay.id"></div>
                <div class="text-xs text-slate-500">Ref: <span x-text="pay.ref"></span></div>
              </td>
              <td class="py-3 pr-4 font-semibold text-slate-800" x-text="pay.employer"></td>
              <td class="py-3 pr-4 text-slate-700" x-text="pay.plan"></td>
              <td class="py-3 pr-4 text-slate-700" x-text="formatMoney(pay.amount, pay.currency)"></td>
              <td class="py-3 pr-4 text-slate-700" x-text="pay.method"></td>
              <td class="py-3 pr-4">
                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1"
                  :class="pay.status==='completed' ? chip('good') : (pay.status==='pending' ? chip('warn') : chip('bad'))">
                  <span class="capitalize" x-text="pay.status.replace('_',' ')"></span>
                </span>
              </td>
              <td class="py-3 pr-4 text-xs text-slate-600" x-text="pay.created_at"></td>
              <td class="py-3">
                <div class="flex flex-wrap gap-2">
                  <button type="button" @click="openPaymentDrawer(pay)"
                    class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold hover:bg-slate-50">
                    View
                  </button>

                  <button type="button" @click="verifyPayment(pay.id)"
                    class="rounded-xl bg-emerald-600 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-700"
                    :disabled="pay.status!=='pending'"
                    :class="pay.status!=='pending' ? 'opacity-50 cursor-not-allowed' : ''">
                    Verify
                  </button>
                </div>
              </td>
            </tr>
          </template>
        </tbody>
      </table>

      <template x-if="paymentFiltered.length === 0">
        <div class="mt-4 rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">
          No matching payments.
        </div>
      </template>
    </div>
  </div>

  {{-- Subscriptions --}}
  <div x-show="tab==='subs'" x-transition class="rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="border-b border-slate-200 p-5">
      <div class="text-sm font-semibold text-slate-900">Employer Subscriptions</div>
      <div class="mt-1 text-xs text-slate-500">Approve/activate, suspend, and monitor expiration</div>
    </div>

    <div class="p-5 overflow-x-auto">
      <table class="min-w-full text-left">
        <thead>
          <tr class="text-xs text-slate-500">
            <th class="py-2 pr-4">Subscription</th>
            <th class="py-2 pr-4">Employer</th>
            <th class="py-2 pr-4">Plan</th>
            <th class="py-2 pr-4">Status</th>
            <th class="py-2 pr-4">Start</th>
            <th class="py-2 pr-4">End</th>
            <th class="py-2 pr-4">Last Payment</th>
            <th class="py-2">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
          <template x-for="s in subFiltered" :key="s.id">
            <tr class="text-sm">
              <td class="py-3 pr-4 font-semibold text-slate-900" x-text="s.id"></td>
              <td class="py-3 pr-4 font-semibold text-slate-800" x-text="s.employer"></td>
              <td class="py-3 pr-4 text-slate-700" x-text="s.plan"></td>
              <td class="py-3 pr-4">
                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1"
                  :class="subChip(s.status)">
                  <span class="capitalize" x-text="prettyStatus(s.status)"></span>
                </span>
              </td>
              <td class="py-3 pr-4 text-xs text-slate-600" x-text="s.start"></td>
              <td class="py-3 pr-4 text-xs text-slate-600" x-text="s.end"></td>
              <td class="py-3 pr-4 text-xs text-slate-600" x-text="s.last_payment"></td>
              <td class="py-3">
                <div class="flex flex-wrap gap-2">
                  <button type="button" @click="activateSub(s.id)"
                    class="rounded-xl bg-emerald-600 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-700"
                    :disabled="!(s.status==='pending_verification' || s.status==='expired')"
                    :class="(s.status==='pending_verification' || s.status==='expired') ? '' : 'opacity-50 cursor-not-allowed'">
                    Activate
                  </button>
                  <button type="button" @click="suspendSub(s.id)"
                    class="rounded-xl bg-rose-600 px-3 py-2 text-xs font-semibold text-white hover:bg-rose-700"
                    :disabled="s.status==='suspended'"
                    :class="s.status==='suspended' ? 'opacity-50 cursor-not-allowed' : ''">
                    Suspend
                  </button>
                  <button type="button" @click="openSubDrawer(s)"
                    class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold hover:bg-slate-50">
                    Details
                  </button>
                </div>
              </td>
            </tr>
          </template>
        </tbody>
      </table>

      <template x-if="subFiltered.length === 0">
        <div class="mt-4 rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">
          No matching subscriptions.
        </div>
      </template>
    </div>
  </div>

  {{-- Reminders --}}
  <div x-show="tab==='reminders'" x-transition class="rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="border-b border-slate-200 p-5">
      <div class="text-sm font-semibold text-slate-900">Expired Plan Reminders</div>
      <div class="mt-1 text-xs text-slate-500">Send reminders to employers with expired subscriptions</div>
    </div>

    <div class="p-5 space-y-3">
      <template x-for="r in reminderFiltered" :key="r.id">
        <div class="flex flex-col gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-4 sm:flex-row sm:items-center sm:justify-between">
          <div class="min-w-0">
            <div class="text-sm font-semibold text-slate-900" x-text="r.employer"></div>
            <div class="mt-1 text-xs text-slate-600">
              Plan: <span class="font-semibold" x-text="r.plan"></span>
              <span class="text-slate-400">•</span>
              Expired: <span class="font-semibold" x-text="r.expired_on"></span>
              <span class="text-slate-400">•</span>
              <span class="text-rose-700 font-semibold" x-text="r.days_over + ' days overdue'"></span>
            </div>
            <div class="mt-1 text-xs text-slate-500">Email: <span x-text="r.email"></span></div>
          </div>

          <div class="flex gap-2">
            <button type="button" @click="sendReminder(r.id)"
              class="rounded-xl bg-emerald-600 px-4 py-2 text-xs font-semibold text-white hover:bg-emerald-700">
              Send Reminder
            </button>
            <button type="button" @click="dismissReminder(r.id)"
              class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold hover:bg-slate-50">
              Dismiss
            </button>
          </div>
        </div>
      </template>

      <template x-if="reminderFiltered.length === 0">
        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">
          No matching reminders.
        </div>
      </template>
    </div>
  </div>

  {{-- Plan modal --}}
  <div x-show="modal.plan" x-transition.opacity class="fixed inset-0 z-50">
    <div class="absolute inset-0 bg-black/40" @click="closePlanModal()"></div>

    <div class="relative mx-auto mt-10 w-[92%] max-w-xl rounded-2xl bg-white p-5 shadow-xl">
      <div class="flex items-start justify-between gap-3">
        <div>
          <div class="text-sm font-semibold text-slate-900" x-text="form.plan.id ? 'Edit Plan' : 'New Plan'"></div>
          <div class="mt-1 text-xs text-slate-500">Frontend demo only — no database yet.</div>
        </div>
        <button class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold hover:bg-slate-50"
          @click="closePlanModal()">Close</button>
      </div>

      <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2">
        <div class="sm:col-span-2">
          <label class="text-xs font-semibold text-slate-700">Plan name</label>
          <input x-model.trim="form.plan.name"
            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100"
            placeholder="e.g., Starter" />
        </div>

        <div>
          <label class="text-xs font-semibold text-slate-700">Interval</label>
          <select x-model="form.plan.interval"
            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm">
            <option value="monthly">Monthly</option>
            <option value="yearly">Yearly</option>
          </select>
        </div>

        <div>
          <label class="text-xs font-semibold text-slate-700">Price (PHP)</label>
          <input x-model.number="form.plan.price" type="number" min="0"
            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100"
            placeholder="e.g., 499" />
        </div>

        <div class="sm:col-span-2">
          <label class="text-xs font-semibold text-slate-700">Features (comma separated)</label>
          <textarea x-model.trim="form.plan.featuresText" rows="3"
            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100"
            placeholder="e.g., Unlimited job posts, Priority support, 3 recruiter seats"></textarea>
        </div>

        <div class="sm:col-span-2 flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-3 py-3">
          <div>
            <div class="text-sm font-semibold text-slate-800">Visible to employers</div>
            <div class="text-xs text-slate-500">Inactive plans are hidden in employer checkout.</div>
          </div>
          <button type="button" @click="form.plan.is_active = !form.plan.is_active"
            class="rounded-full px-3 py-2 text-xs font-semibold ring-1"
            :class="form.plan.is_active ? 'bg-emerald-600 text-white ring-emerald-600' : 'bg-white text-slate-700 ring-slate-200'">
            <span x-text="form.plan.is_active ? 'Active' : 'Hidden'"></span>
          </button>
        </div>
      </div>

      <div class="mt-4 flex flex-col gap-2 sm:flex-row sm:justify-end">
        <button type="button" @click="closePlanModal()"
          class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50">
          Cancel
        </button>
        <button type="button" @click="savePlan()"
          class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
          Save Plan
        </button>
      </div>
    </div>
  </div>

  {{-- Drawer: payment --}}
  <div x-show="drawer.payment" x-transition.opacity class="fixed inset-0 z-40">
    <div class="absolute inset-0 bg-black/40" @click="closePaymentDrawer()"></div>
    <div class="absolute right-0 top-0 h-full w-[92%] max-w-md bg-white p-5 shadow-xl">
      <div class="flex items-start justify-between gap-3">
        <div>
          <div class="text-sm font-semibold text-slate-900">Payment Details</div>
          <div class="mt-1 text-xs text-slate-500">Frontend demo</div>
        </div>
        <button class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold hover:bg-slate-50"
          @click="closePaymentDrawer()">Close</button>
      </div>

      <div class="mt-4 space-y-3 text-sm">
        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
          <div class="text-xs text-slate-500">Payment ID</div>
          <div class="font-semibold text-slate-900" x-text="selectedPayment?.id || ''"></div>
          <div class="mt-2 text-xs text-slate-500">Reference</div>
          <div class="font-semibold text-slate-900" x-text="selectedPayment?.ref || ''"></div>
        </div>

        <div class="rounded-xl border border-slate-200 p-4">
          <div class="text-xs text-slate-500">Employer</div>
          <div class="font-semibold text-slate-900" x-text="selectedPayment?.employer || ''"></div>

          <div class="mt-3 grid grid-cols-2 gap-3">
            <div>
              <div class="text-xs text-slate-500">Plan</div>
              <div class="font-semibold text-slate-900" x-text="selectedPayment?.plan || ''"></div>
            </div>
            <div>
              <div class="text-xs text-slate-500">Amount</div>
              <div class="font-semibold text-slate-900"
                x-text="selectedPayment ? formatMoney(selectedPayment.amount, selectedPayment.currency) : ''"></div>
            </div>
            <div>
              <div class="text-xs text-slate-500">Method</div>
              <div class="font-semibold text-slate-900" x-text="selectedPayment?.method || ''"></div>
            </div>
            <div>
              <div class="text-xs text-slate-500">Status</div>
              <div class="font-semibold"
                :class="selectedPayment?.status==='completed' ? 'text-emerald-700' : (selectedPayment?.status==='pending' ? 'text-amber-700' : 'text-rose-700')"
                x-text="selectedPayment ? prettyStatus(selectedPayment.status) : ''"></div>
            </div>
          </div>
        </div>

        <div class="flex gap-2">
          <button type="button" @click="selectedPayment && verifyPayment(selectedPayment.id)"
            class="w-full rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700"
            :disabled="!selectedPayment || selectedPayment.status!=='pending'"
            :class="(!selectedPayment || selectedPayment.status!=='pending') ? 'opacity-50 cursor-not-allowed' : ''">
            Verify Payment
          </button>
          <button type="button" @click="closePaymentDrawer()"
            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50">
            Done
          </button>
        </div>
      </div>
    </div>
  </div>

  {{-- Drawer: subscription --}}
  <div x-show="drawer.sub" x-transition.opacity class="fixed inset-0 z-40">
    <div class="absolute inset-0 bg-black/40" @click="closeSubDrawer()"></div>
    <div class="absolute right-0 top-0 h-full w-[92%] max-w-md bg-white p-5 shadow-xl">
      <div class="flex items-start justify-between gap-3">
        <div>
          <div class="text-sm font-semibold text-slate-900">Subscription Details</div>
          <div class="mt-1 text-xs text-slate-500">Frontend demo</div>
        </div>
        <button class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold hover:bg-slate-50"
          @click="closeSubDrawer()">Close</button>
      </div>

      <div class="mt-4 space-y-3 text-sm">
        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
          <div class="text-xs text-slate-500">Subscription</div>
          <div class="font-semibold text-slate-900" x-text="selectedSub?.id || ''"></div>
          <div class="mt-2 text-xs text-slate-500">Employer</div>
          <div class="font-semibold text-slate-900" x-text="selectedSub?.employer || ''"></div>
        </div>

        <div class="rounded-xl border border-slate-200 p-4">
          <div class="grid grid-cols-2 gap-3">
            <div>
              <div class="text-xs text-slate-500">Plan</div>
              <div class="font-semibold text-slate-900" x-text="selectedSub?.plan || ''"></div>
            </div>
            <div>
              <div class="text-xs text-slate-500">Status</div>
              <div class="font-semibold" :class="selectedSub ? subTextColor(selectedSub.status) : 'text-slate-700'"
                x-text="selectedSub ? prettyStatus(selectedSub.status) : ''"></div>
            </div>
            <div>
              <div class="text-xs text-slate-500">Start</div>
              <div class="font-semibold text-slate-900" x-text="selectedSub?.start || ''"></div>
            </div>
            <div>
              <div class="text-xs text-slate-500">End</div>
              <div class="font-semibold text-slate-900" x-text="selectedSub?.end || ''"></div>
            </div>
          </div>

          <div class="mt-3 text-xs text-slate-500">Last payment</div>
          <div class="font-semibold text-slate-900" x-text="selectedSub?.last_payment || ''"></div>
        </div>

        <div class="flex gap-2">
          <button type="button" @click="selectedSub && activateSub(selectedSub.id)"
            class="w-full rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
            Activate
          </button>
          <button type="button" @click="selectedSub && suspendSub(selectedSub.id)"
            class="w-full rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700">
            Suspend
          </button>
        </div>
      </div>
    </div>
  </div>

</div>

<script>
  function subsUI(seed){
    return {
      tab: 'plans',
      q: '',

      plans: [],
      payments: [],
      subs: [],
      reminders: [],

      kpi: { activePlans: 0, pendingPayments: 0, activeSubs: 0, riskSubs: 0 },

      modal: { plan: false },
      drawer: { payment: false, sub: false },

      selectedPayment: null,
      selectedSub: null,

      form: {
        plan: { id: null, name:'', interval:'monthly', price:0, currency:'PHP', featuresText:'', is_active:true, badge:'' }
      },

      // ✅ uses your layout toast (window.notify) instead of window.notyf
      toast(type, msg, title = ''){
        if (!window.notify) return;
        const allowed = ['success','info','warning','error'];
        const safeType = allowed.includes(type) ? type : 'info';
        window.notify(safeType, String(msg || ''), String(title || ''));
      },

      init(){
        this.plans = (seed.plans || []).map(p => ({...p}));
        this.payments = (seed.payments || []).map(p => ({...p}));
        this.subs = (seed.subs || []).map(s => ({...s}));
        this.reminders = (seed.reminders || []).map(r => ({...r}));
        this.computeKpi();
      },

      matchQ(text){
        const q = (this.q || '').toLowerCase();
        if(!q) return true;
        return String(text || '').toLowerCase().includes(q);
      },

      get planFiltered(){
        return this.plans.filter(p =>
          this.matchQ(p.name) || this.matchQ(p.interval) || this.matchQ(p.is_active ? 'active' : 'hidden')
        );
      },

      get paymentFiltered(){
        return this.payments.filter(p =>
          this.matchQ(p.id) || this.matchQ(p.employer) || this.matchQ(p.plan) || this.matchQ(p.status) || this.matchQ(p.method) || this.matchQ(p.ref)
        );
      },

      get subFiltered(){
        return this.subs.filter(s =>
          this.matchQ(s.id) || this.matchQ(s.employer) || this.matchQ(s.plan) || this.matchQ(s.status) || this.matchQ(s.last_payment)
        );
      },

      get reminderFiltered(){
        return this.reminders.filter(r =>
          this.matchQ(r.id) || this.matchQ(r.employer) || this.matchQ(r.plan) || this.matchQ(r.email)
        );
      },

      chip(tone){
        if(tone === 'good') return 'bg-emerald-50 text-emerald-700 ring-emerald-200';
        if(tone === 'warn') return 'bg-amber-50 text-amber-700 ring-amber-200';
        if(tone === 'bad')  return 'bg-rose-50 text-rose-700 ring-rose-200';
        return 'bg-slate-100 text-slate-700 ring-slate-200';
      },

      subChip(status){
        if(status === 'active') return this.chip('good');
        if(status === 'pending_verification') return this.chip('warn');
        if(status === 'expired') return this.chip('bad');
        if(status === 'suspended') return this.chip('bad');
        return this.chip('default');
      },

      subTextColor(status){
        if(status === 'active') return 'text-emerald-700';
        if(status === 'pending_verification') return 'text-amber-700';
        if(status === 'expired') return 'text-rose-700';
        if(status === 'suspended') return 'text-rose-700';
        return 'text-slate-700';
      },

      prettyStatus(s){
        return String(s || '').replaceAll('_',' ');
      },

      formatMoney(amount, currency){
        const n = Number(amount || 0);
        if((currency || '').toUpperCase() === 'PHP') return '₱ ' + n.toLocaleString();
        return (currency || '').toUpperCase() + ' ' + n.toLocaleString();
      },

      computeKpi(){
        this.kpi.activePlans = this.plans.filter(p => p.is_active).length;
        this.kpi.pendingPayments = this.payments.filter(p => p.status === 'pending').length;
        this.kpi.activeSubs = this.subs.filter(s => s.status === 'active').length;
        this.kpi.riskSubs = this.subs.filter(s => s.status === 'expired' || s.status === 'suspended').length;
      },

      openPlanModal(plan=null){
        if(plan){
          this.form.plan = {
            id: plan.id,
            name: plan.name,
            interval: plan.interval,
            price: plan.price,
            currency: plan.currency || 'PHP',
            is_active: !!plan.is_active,
            badge: plan.badge || '',
            featuresText: (plan.features || []).join(', '),
          };
          this.toast('info', 'Editing plan: ' + plan.name);
        } else {
          this.form.plan = { id:null, name:'', interval:'monthly', price:0, currency:'PHP', featuresText:'', is_active:true, badge:'' };
          this.toast('info', 'Create a new plan');
        }
        this.modal.plan = true;
      },

      closePlanModal(){
        this.modal.plan = false;
      },

      savePlan(){
        const name = String(this.form.plan.name || '').trim();
        if(!name){
          this.toast('error', 'Plan name is required');
          return;
        }

        const price = Number(this.form.plan.price || 0);
        if(Number.isNaN(price)){
          this.toast('error', 'Price must be a number');
          return;
        }
        if(price < 0){
          this.toast('error', 'Price cannot be negative');
          return;
        }

        const features = String(this.form.plan.featuresText || '')
          .split(',')
          .map(x => x.trim())
          .filter(x => x.length > 0);

        if(this.form.plan.id){
          const idx = this.plans.findIndex(p => p.id === this.form.plan.id);
          if(idx !== -1){
            this.plans[idx] = {
              ...this.plans[idx],
              name,
              interval: this.form.plan.interval,
              price,
              currency: 'PHP',
              features,
              is_active: !!this.form.plan.is_active,
              badge: String(this.form.plan.badge || '').trim(),
            };
          }
          this.toast('success', 'Plan updated');
        } else {
          const nextId = Math.max(0, ...this.plans.map(p => Number(p.id || 0))) + 1;
          this.plans.unshift({
            id: nextId,
            name,
            interval: this.form.plan.interval,
            price,
            currency: 'PHP',
            features,
            is_active: !!this.form.plan.is_active,
            badge: String(this.form.plan.badge || '').trim(),
          });
          this.toast('success', 'Plan created');
        }

        this.modal.plan = false;
        this.computeKpi();
      },

      togglePlan(id){
        const idx = this.plans.findIndex(p => p.id === id);
        if(idx === -1) return;

        this.plans[idx].is_active = !this.plans[idx].is_active;
        this.plans[idx].badge = this.plans[idx].is_active ? (this.plans[idx].badge || '') : 'Hidden';

        this.computeKpi();
        this.toast('success', this.plans[idx].is_active ? 'Plan activated' : 'Plan hidden');
      },

      deletePlan(id){
        if(!confirm('Delete this plan?')) return;
        this.plans = this.plans.filter(p => p.id !== id);
        this.computeKpi();
        this.toast('warning', 'Plan deleted');
      },

      openPaymentDrawer(pay){
        this.selectedPayment = {...pay};
        this.drawer.payment = true;
        this.toast('info', 'Viewing payment: ' + pay.id);
      },
      closePaymentDrawer(){
        this.drawer.payment = false;
        this.selectedPayment = null;
      },

      verifyPayment(paymentId){
        const pIdx = this.payments.findIndex(p => p.id === paymentId);
        if(pIdx === -1) return;

        if(this.payments[pIdx].status !== 'pending'){
          this.toast('warning', 'Only pending payments can be verified');
          return;
        }

        this.payments[pIdx].status = 'completed';

        const employer = this.payments[pIdx].employer;
        const plan = this.payments[pIdx].plan;

        const sIdx = this.subs.findIndex(s => s.employer === employer && s.status === 'pending_verification');
        if(sIdx !== -1){
          const start = this.todayISO();
          const end = this.addDaysISO(30);
          this.subs[sIdx].status = 'active';
          this.subs[sIdx].plan = plan;
          this.subs[sIdx].start = start;
          this.subs[sIdx].end = end;
          this.subs[sIdx].last_payment = paymentId;

          this.toast('success', 'Subscription activated for ' + employer);
        }

        if(this.selectedPayment && this.selectedPayment.id === paymentId){
          this.selectedPayment.status = 'completed';
        }

        this.computeKpi();
        this.toast('success', 'Payment verified');
      },

      openSubDrawer(sub){
        this.selectedSub = {...sub};
        this.drawer.sub = true;
        this.toast('info', 'Viewing subscription: ' + sub.id);
      },
      closeSubDrawer(){
        this.drawer.sub = false;
        this.selectedSub = null;
      },

      activateSub(subId){
        const idx = this.subs.findIndex(s => s.id === subId);
        if(idx === -1) return;

        const curStatus = this.subs[idx].status;
        if(!(curStatus === 'pending_verification' || curStatus === 'expired')){
          this.toast('warning', 'Only pending/expired subscriptions can be activated');
          return;
        }

        this.subs[idx].status = 'active';
        this.subs[idx].start = this.todayISO();
        this.subs[idx].end = this.addDaysISO(30);

        if(this.selectedSub && this.selectedSub.id === subId){
          this.selectedSub.status = 'active';
          this.selectedSub.start = this.subs[idx].start;
          this.selectedSub.end = this.subs[idx].end;
        }

        this.computeKpi();
        this.toast('success', 'Subscription activated');
      },

      suspendSub(subId){
        const idx = this.subs.findIndex(s => s.id === subId);
        if(idx === -1) return;

        if(this.subs[idx].status === 'suspended'){
          this.toast('info', 'Already suspended');
          return;
        }

        this.subs[idx].status = 'suspended';

        if(this.selectedSub && this.selectedSub.id === subId){
          this.selectedSub.status = 'suspended';
        }

        this.computeKpi();
        this.toast('warning', 'Subscription suspended');
      },

      sendReminder(remId){
        const r = this.reminders.find(x => x.id === remId);
        this.toast('success', r ? ('Reminder sent to ' + r.employer) : 'Reminder sent');
      },

      dismissReminder(remId){
        if(!confirm('Dismiss this reminder?')) return;
        this.reminders = this.reminders.filter(r => r.id !== remId);
        this.toast('info', 'Reminder dismissed');
      },

      todayISO(){
        const d = new Date();
        const y = d.getFullYear();
        const m = String(d.getMonth()+1).padStart(2,'0');
        const day = String(d.getDate()).padStart(2,'0');
        return `${y}-${m}-${day}`;
      },

      addDaysISO(days){
        const d = new Date();
        d.setDate(d.getDate() + Number(days || 0));
        const y = d.getFullYear();
        const m = String(d.getMonth()+1).padStart(2,'0');
        const day = String(d.getDate()).padStart(2,'0');
        return `${y}-${m}-${day}`;
      },
    }
  }
</script>

@endsection
