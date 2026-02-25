@extends('employer.layout')

@section('content')
<div class="max-w-lg mx-auto p-6 bg-white rounded-xl shadow-lg mt-10">

    <h2 class="text-2xl font-bold mb-4">
        Pay for Plan: <span class="text-indigo-600">{{ $subscription->plan->name }}</span>
    </h2>

    <p class="text-gray-700 mb-6">
        Amount to Pay: 
        <span class="text-2xl font-semibold text-green-600">₱{{ $subscription->plan->price }}</span>
    </p>

    <form action="{{ route('employer.subscription.pay', $subscription->id) }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label for="payment_method" class="block font-medium mb-1">Select Payment Method</label>
            <select name="payment_method" id="payment_method" class="w-full border rounded-lg px-3 py-2">
                <option value="paypal">PayPal</option>
                <option value="gcash">GCash</option>
                <option value="bank_transfer">Bank Transfer</option>
            </select>
        </div>

        <div>
            <label for="reference" class="block font-medium mb-1">Reference / Notes (optional)</label>
            <input type="text" name="reference" id="reference" class="w-full border rounded-lg px-3 py-2" placeholder="Transaction ID or notes">
        </div>

        <button type="submit" 
                class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition-colors">
            Pay Now
        </button>
    </form>

    <p class="mt-6 text-gray-500 text-sm">
        Note: After submitting, your payment will be reviewed by admin. Your subscription will be activated once verified.
    </p>
</div>
@endsection