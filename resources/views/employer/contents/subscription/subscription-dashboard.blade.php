@extends('employer.layout')

@section('content')
<div class="container mx-auto p-6 max-w-6xl">

    <h1 class="text-3xl font-bold mb-8">My Subscription Dashboard</h1>

    {{-- Current Subscription --}}
    @if($currentSubscription ?? false)
        <div class="border-2 rounded-lg p-6 mb-10 bg-gray-50 flex justify-between items-center">
            <div>
                <h2 class="text-xl font-semibold">Current Plan: {{ $currentSubscription->plan->name }}</h2>
                <p>Status: 
                    <strong class="uppercase text-white px-2 py-1 rounded" 
                            style="background-color: 
                                {{ match($currentSubscription->subscription_status) {
                                    'active' => '#4CAF50',
                                    'inactive' => '#FFC107',
                                    'expired' => '#9E9E9E',
                                    'canceled' => '#F44336',
                                    default => '#9E9E9E',
                                } }}">
                        {{ ucfirst($currentSubscription->subscription_status) }}
                    </strong>
                </p>
                <p>Valid Until: {{ $currentSubscription->ends_at?->format('M d, Y') ?? 'N/A' }}</p>

                @if($currentSubscription->ends_at)
                    <p>Days Left: 
                        <span class="px-2 py-1 rounded text-white" 
                              style="background-color: 
                                {{ $currentSubscription->daysLeft() <= 5 ? 'red' : ($currentSubscription->daysLeft() <= 10 ? 'orange' : 'green') }}">
                            {{ $currentSubscription->daysLeft() }} day{{ $currentSubscription->daysLeft() !== 1 ? 's' : '' }}
                        </span>
                    </p>
                @endif
            </div>
            <div>
                <p>Plan Price: <strong>₱{{ $currentSubscription->plan->price }}</strong></p>
            </div>
        </div>
    @endif

    {{-- Subscription History --}}
    <h2 class="text-2xl font-bold mb-4">Subscription History</h2>
    @if($subscriptions->isNotEmpty())
        <table class="w-full border-collapse mb-10">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-2 border">Plan</th>
                    <th class="p-2 border">Status</th>
                    <th class="p-2 border">Start Date</th>
                    <th class="p-2 border">End Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($subscriptions as $sub)
                    <tr>
                        <td>{{ $sub->plan?->name ?? 'N/A' }}</td>
                        <td>
                            <span class="px-2 py-1 rounded text-white" 
                                  style="background-color: 
                                    {{ match($sub->subscription_status) {
                                        'active' => '#4CAF50',
                                        'inactive' => '#FFC107',
                                        'expired' => '#9E9E9E',
                                        'canceled' => '#F44336',
                                        default => '#9E9E9E',
                                    } }}">
                                {{ ucfirst($sub->subscription_status) }}
                            </span>
                        </td>
                        <td>{{ $sub->starts_at?->format('M d, Y') ?? 'N/A' }}</td>
                        <td>{{ $sub->ends_at?->format('M d, Y') ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No subscriptions found.</p>
    @endif

    {{-- Available Plans --}}
    <h2 class="text-2xl font-bold mb-4">Available Plans</h2>
    <div class="flex flex-wrap gap-6">
        @foreach($plans as $plan)
            @php
                $planFeatures = is_array($plan->features) ? $plan->features : json_decode($plan->features, true);
                $existingSub = $subscriptions->firstWhere('plan_id', $plan->id);
                $status = $existingSub?->subscription_status;
                $canSelect = !$existingSub || in_array($status, ['expired', 'canceled']);
            @endphp

            <div class="plan-card flex-1 min-w-[300px] p-5 bg-white rounded-lg shadow relative hover:shadow-lg transition">
                <h3 class="text-lg font-semibold text-blue-600">{{ $plan->name }} - ₱{{ $plan->price }}</h3>

                @if($planFeatures)
                    <ul class="mt-3 text-gray-700 text-sm list-disc pl-5">
                        @foreach($planFeatures as $key => $value)
                            <li>{{ ucfirst(str_replace('_', ' ', $key)) }}: {{ $value === -1 ? 'Unlimited' : $value }}</li>
                        @endforeach
                    </ul>
                @endif

                <div class="mt-4">
                    @if(!$canSelect)
                        <span class="px-3 py-1 rounded text-white bg-yellow-500 font-semibold">{{ ucfirst($status) }}</span>
                    @else
                        <a href="{{ route('employer.subscription.select', $plan->id) }}"
                           class="inline-block px-4 py-2 bg-blue-600 text-white rounded font-semibold hover:bg-blue-700">
                            Select Plan
                        </a>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

</div>
@endsection