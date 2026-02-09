{{-- resources/views/employer/contents/subscription.blade.php --}}
@extends('employer.layout')

@section('content')
<div class="space-y-6" x-data="{
        plans: [
            { name: 'Standard Plan', description: '10 job posts, analytics access', daysLeft: 21, totalDays: 30 },
            { name: 'Premium Plan', description: 'Unlimited posts, featured listings, advanced analytics', daysLeft: 41, totalDays: 45 }
        ]
    }">

    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-900">Subscription & Plans</h1>
        <p class="text-gray-500">Manage your subscription and track expiration</p>
    </div>

    {{-- Subscription Plans --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <template x-for="plan in plans" :key="plan.name">
            <div class="border rounded-2xl p-6 shadow-sm hover:shadow-md transition bg-white">
                <h2 class="text-xl font-semibold mb-2" x-text="plan.name"></h2>
                <p class="text-gray-600 mb-4" x-text="plan.description"></p>

                {{-- Progress Bar --}}
                <div class="w-full bg-gray-200 rounded-full h-3 mb-2">
                    <div class="bg-emerald-500 h-3 rounded-full"
                         :style="`width: ${Math.round((plan.daysLeft / plan.totalDays) * 100)}%`"></div>
                </div>
                <p class="text-gray-500 mb-4 text-sm">
                    Expires in: <span class="font-medium text-gray-900" x-text="plan.daysLeft + ' of ' + plan.totalDays + ' days left'"></span>
                </p>

                <div class="flex gap-2">
                    <button
                        type="button"
                        class="px-4 py-2 rounded-xl border border-emerald-500 bg-emerald-500 text-white font-semibold hover:bg-emerald-600 transition cursor-pointer"
                        @click="alert('Frontend only: Purchase / Renew ' + plan.name)"
                    >
                        Purchase / Renew
                    </button>
                    <button
                        type="button"
                        class="px-4 py-2 rounded-xl border border-gray-300 bg-gray-50 hover:bg-gray-100 transition cursor-pointer"
                        @click="alert('Frontend only: View Details of ' + plan.name)"
                    >
                        Details
                    </button>
                </div>
            </div>
        </template>
    </div>

    {{-- Expiration Notice --}}
    <div class="mt-6 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-lg text-yellow-700">
        Your current subscription will expire soon. Renew now to continue accessing premium features.
    </div>

</div>
@endsection