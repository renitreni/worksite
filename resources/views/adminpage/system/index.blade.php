{{-- resources/views/admin/system/index.blade.php --}}
@extends('adminpage.layout') {{-- change if your layout is different --}}
@section('title', 'System Configuration')
@section('page_title', 'System Configuration')
@section('content')
<div class="max-w-6xl mx-auto p-4">
    
    {{-- Flash messages --}}
    @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-50 p-3 text-green-800">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-4 rounded-lg bg-red-50 p-3 text-red-800">
            {{ session('error') }}
        </div>
    @endif

    {{-- Tabs --}}
    @php
        $tab = request('tab', 'general');
        $tabs = [
            'general' => 'General',
            'notifications' => 'Notifications',
            'security' => 'Security',
            'backups' => 'Backups',
        ];
    @endphp

    <div class="border-b border-gray-200 mb-6">
        <nav class="-mb-px flex gap-6">
            @foreach($tabs as $key => $label)
                <a
                    href="{{ route('admin.system.index', ['tab' => $key]) }}"
                    class="py-3 text-sm font-medium border-b-2
                        {{ $tab === $key ? 'border-gray-900 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-800 hover:border-gray-300' }}">
                    {{ $label }}
                </a>
            @endforeach
        </nav>
    </div>

    {{-- Tab content --}}
    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
        @if($tab === 'general')
            @include('adminpage.system.partials.general')
        @elseif($tab === 'notifications')
            @include('adminpage.system.partials.notifications')
        @elseif($tab === 'security')
            @include('adminpage.system.partials.security')
        @elseif($tab === 'backups')
            @include('adminpage.system.partials.backups')
        @endif
    </div>
</div>
@endsection