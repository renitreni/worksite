@extends('employer.layout')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 rounded-xl shadow">
    <h1 class="text-2xl font-semibold mb-6">Edit Job Posting</h1>

    <form action="{{ route('employer.job-postings.update', $job->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Job Title --}}
        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700">Job Title</label>
            <input type="text" name="title" id="title" value="{{ old('title', $job->title) }}"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
            @error('title') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Description --}}
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">Job Description</label>
            <textarea name="description" id="description" rows="4"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">{{ old('description', $job->description) }}</textarea>
            @error('description') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Location --}}
        <div class="mb-4">
            <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
            <input type="text" name="location" id="location" value="{{ old('location', $job->location) }}"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
            @error('location') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Salary --}}
        <div class="mb-4">
            <label for="salary" class="block text-sm font-medium text-gray-700">Salary (optional)</label>
            <input type="number" name="salary" id="salary" value="{{ old('salary', $job->salary) }}"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
            @error('salary') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Job Type --}}
        <div class="mb-4">
            <label for="job_type" class="block text-sm font-medium text-gray-700">Job Type</label>
            <select name="job_type" id="job_type"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                <option value="">Select Type</option>
                <option value="Full-Time" {{ old('job_type', $job->job_type) == 'Full-Time' ? 'selected' : '' }}>Full-Time</option>
                <option value="Part-Time" {{ old('job_type', $job->job_type) == 'Part-Time' ? 'selected' : '' }}>Part-Time</option>
                <option value="Contract" {{ old('job_type', $job->job_type) == 'Contract' ? 'selected' : '' }}>Contract</option>
                <option value="Internship" {{ old('job_type', $job->job_type) == 'Internship' ? 'selected' : '' }}>Internship</option>
            </select>
            @error('job_type') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Required Skills --}}
        <div class="mb-4">
            <label for="required_skills" class="block text-sm font-medium text-gray-700">Required Skills (comma separated)</label>
            <input type="text" name="required_skills" id="required_skills" value="{{ old('required_skills', $job->required_skills) }}"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
            @error('required_skills') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('employer.job-postings.index') }}"
               class="px-4 py-2 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-100">Cancel</a>
            <button type="submit"
                    class="px-4 py-2 rounded-xl bg-emerald-500 text-white hover:bg-emerald-600 transition cursor-pointer">Update Job</button>
        </div>
    </form>
</div>
@endsection