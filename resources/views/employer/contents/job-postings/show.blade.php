@extends('employer.layout')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 rounded-xl shadow">

    {{-- Flash message --}}
    @if(session('success'))
        <div class="mb-4 p-4 rounded-xl bg-emerald-100 text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">{{ $job->title }}</h1>
        <div class="flex gap-2">
            @if($job->status === 'open')
                {{-- Open jobs: show Close only --}}
                <form action="{{ route('employer.job-postings.destroy', $job->id) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="px-4 py-2 rounded-xl bg-red-600 text-white hover:bg-red-700 transition cursor-pointer">
                        Close Job
                    </button>
                </form>
            @elseif($job->status === 'closed')
                {{-- Closed jobs: show Reopen only --}}
                <form action="{{ route('employer.job-postings.reopen', $job->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="open">
                    <button type="submit"
                            class="px-4 py-2 rounded-xl bg-green-600 text-white hover:bg-green-700 transition cursor-pointer">
                        Reopen Job
                    </button>
                </form>
            @endif
            {{-- Cancel / Back button --}}
            <a href="{{ url()->previous() }}"
               class="px-4 py-2 rounded-xl bg-gray-300 text-gray-700 hover:bg-gray-400 transition cursor-pointer">
                Cancel
            </a>
        </div>
    </div>

    {{-- Job Details --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div>
            <p class="text-gray-700"><span class="font-semibold">Location:</span> {{ $job->location }}</p>
            <p class="text-gray-700"><span class="font-semibold">Salary:</span> 
                {{ $job->salary ? '₱' . number_format($job->salary) : 'Not specified' }}
            </p>
            <p class="text-gray-700"><span class="font-semibold">Job Type:</span> {{ $job->job_type }}</p>
            <p class="text-gray-700"><span class="font-semibold">Required Skills:</span> {{ $job->required_skills }}</p>
            <p class="text-gray-700"><span class="font-semibold">Posted on:</span> {{ $job->created_at->format('M d, Y') }}</p>
        </div>
        <div>
            <p class="text-gray-700"><span class="font-semibold">Applications:</span> {{ $job->applications()->count() }}</p>
        </div>
    </div>

    {{-- Job Description --}}
    <div class="mb-6">
        <h2 class="text-lg font-semibold mb-2">Job Description</h2>
        <p class="text-gray-700">{{ $job->description }}</p>
    </div>

    {{-- Applicants List --}}
    <div>
        <h2 class="text-lg font-semibold mb-2">Applicants</h2>
        @if($job->applications()->count())
            <ul class="divide-y divide-gray-200">
                @foreach($job->applications as $application)
                    <li class="py-3 flex justify-between items-center">
                        <span>{{ $application->candidate->name ?? 'Unnamed Candidate' }}</span>
                        <span class="text-sm text-gray-500">{{ $application->created_at->format('M d, Y') }}</span>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-gray-500">No applicants yet.</p>
        @endif
    </div>

</div>
@endsection