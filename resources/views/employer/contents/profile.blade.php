@extends('employer.layout')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 rounded-xl shadow space-y-6">
    <h1 class="text-2xl font-semibold mb-4">Company Profile</h1>

    {{-- Messages --}}
    @if(session('success'))
        <div class="p-3 bg-green-100 text-green-700 rounded shadow-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="p-3 bg-red-100 text-red-700 rounded shadow-sm">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="p-3 bg-red-100 text-red-700 rounded shadow-sm">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
    @endif

    {{-- Update Profile Form --}}
    <form action="{{ route('employer.company-profile.update') }}" method="POST" class="space-y-4">
        @csrf

        {{-- Company Logo Placeholder --}}
        <div class="mb-6">
            <div class="mt-2 flex items-center gap-4">
                <span class="h-24 w-24 rounded-full border border-gray-200 bg-gray-100 flex items-center justify-center">
                    <i data-lucide="building" class="h-12 w-12 text-gray-400"></i>
                </span>
                <button 
                    type="button" 
                    class="px-4 py-2 rounded-xl border border-gray-300 bg-gray-50 hover:bg-gray-100 transition cursor-pointer text-sm font-medium text-gray-700"
                    @click="alert('Front-end only: Change Logo functionality')"
                >
                    Change Logo
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Company Name</label>
                <input type="text" name="company_name" value="{{ old('company_name', $employerProfile->company_name) }}" required
                       class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-100">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required
                       class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-100">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Phone</label>
                <input type="text" name="company_contact" value="{{ old('company_contact', $employerProfile->company_contact) }}"
                       class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-100">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Location</label>
                <input type="text" name="company_address" value="{{ old('company_address', $employerProfile->company_address) }}"
                       class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-100">
            </div>
        </div>

        {{-- Buttons Row --}}
        <div class="flex flex-col md:flex-row md:justify-end gap-4 mt-4">
            <button type="submit"
                    class="px-6 py-3 bg-emerald-500 text-white font-semibold rounded-xl shadow hover:bg-emerald-600 transition">
                Save Changes
            </button>
        </div>
    </form>

    {{-- Delete Account Form --}}
    <form action="{{ route('employer.delete-account') }}" method="POST"
          onsubmit="return confirm('Are you sure you want to delete your account?');"
          class="flex justify-end mt-2">
        @csrf
        @method('DELETE')
        <button type="submit"
                class="px-6 py-3 bg-red-500 text-white font-semibold rounded-xl shadow hover:bg-red-600 transition">
            Delete Account
        </button>
    </form>
</div>
@endsection