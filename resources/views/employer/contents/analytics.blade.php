@extends('employer.layout')

@section('content')
<div class="space-y-6">

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl p-6 shadow flex items-center justify-between">
            <div>
                <p class="text-2xl font-bold text-gray-900">16</p>
                <p class="text-sm text-gray-500">Active Job Posts</p>
            </div>
            <i data-lucide="briefcase" class="h-8 w-8 text-emerald-500"></i>
        </div>

        <div class="bg-white rounded-xl p-6 shadow flex items-center justify-between">
            <div>
                <p class="text-2xl font-bold text-gray-900">61</p>
                <p class="text-sm text-gray-500">Total Applications</p>
            </div>
            <i data-lucide="users" class="h-8 w-8 text-blue-500"></i>
        </div>

        <div class="bg-white rounded-xl p-6 shadow flex items-center justify-between">
            <div>
                <p class="text-2xl font-bold text-gray-900">7</p>
                <p class="text-sm text-gray-500">Hires This Month</p>
            </div>
            <i data-lucide="check-circle" class="h-8 w-8 text-yellow-500"></i>
        </div>
    </div>

    {{-- Charts --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Applications per Job --}}
        <div class="bg-white rounded-xl p-6 shadow">
            <h2 class="text-lg font-semibold mb-4">Applications per Job</h2>
            <div class="h-40">
                <canvas id="applicationsChart"></canvas>
            </div>
        </div>

        {{-- Hires per Month --}}
        <div class="bg-white rounded-xl p-6 shadow">
            <h2 class="text-lg font-semibold mb-4">Hires per Month</h2>
            <div class="h-40">
                <canvas id="hiresChart"></canvas>
            </div>
        </div>

        {{-- Active vs Closed Jobs --}}
        <div class="bg-white rounded-xl p-6 shadow">
            <h2 class="text-lg font-semibold mb-4">Active vs Closed Jobs</h2>
            <div class="h-40">
                <canvas id="jobsChart"></canvas>
            </div>
        </div>

    </div>
</div>

{{-- Chart.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const commonOptions = {
        responsive: true,
        maintainAspectRatio: false, // fit container height
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
    };

    // Applications per Job - Bar Chart
    new Chart(document.getElementById('applicationsChart'), {
        type: 'bar',
        data: {
            labels: ['Frontend Developer', 'Backend Developer', 'UI/UX Designer', 'QA Tester'],
            datasets: [{
                label: 'Applications',
                data: [15, 22, 8, 12],
                backgroundColor: '#10B981'
            }]
        },
        options: commonOptions
    });

    // Hires per Month - Line Chart
    new Chart(document.getElementById('hiresChart'), {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
            datasets: [{
                label: 'Hires',
                data: [2, 5, 3, 6, 4],
                borderColor: '#3B82F6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                fill: true,
                tension: 0.3
            }]
        },
        options: commonOptions
    });

    // Active vs Closed Jobs - Doughnut Chart
    new Chart(document.getElementById('jobsChart'), {
        type: 'doughnut',
        data: {
            labels: ['Active', 'Closed'],
            datasets: [{
                label: 'Jobs',
                data: [12, 4],
                backgroundColor: ['#FBBF24', '#EF4444']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } }
        }
    });

    if(window.lucide) window.lucide.createIcons();
});
</script>
@endsection
