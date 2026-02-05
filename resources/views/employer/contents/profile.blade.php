
@extends('employer.layout')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 rounded-xl shadow">
    <h1 class="text-2xl font-semibold mb-6">Company Profile</h1>

    <form>
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

        {{-- Company Name --}}
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Company Name</label>
            <input type="text" value="John Company" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
        </div>

        {{-- Description --}}
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Description</label>
            <textarea rows="4" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200" placeholder="Describe your company...">Lorem ipsum</textarea>
        </div>

        {{-- Contact Info --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" value="contact@company.com" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Phone</label>
                <input type="text" value="+63 912 345 6789" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
            </div>
        </div>

        {{-- Location --}}
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Location</label>
            <input type="text" value="Makati City, Philippines" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
        </div>

        {{-- Website --}}
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700">Website</label>
            <input type="url" value="https://www.company.com" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
        </div>

        {{-- Save Button --}}
        <div class="flex justify-end">
            <button type="button" class="px-6 py-2 rounded-xl bg-emerald-500 text-white font-semibold hover:bg-emerald-600 transition cursor-pointer">
                Save Changes
            </button>
        </div>
    </form>
</div>
@endsection