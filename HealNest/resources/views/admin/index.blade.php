@extends('layouts.app')
@section('title', 'Admin Dashboard – HealNest')
@section('page-title', 'Admin Dashboard')

@section('content')
<div class="space-y-6">

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <x-stat-card label="Total Users" :value="$totalUsers" icon="👥"/>
        <x-stat-card label="Assessments" :value="$totalAssessments" icon="📋"/>
        <x-stat-card label="Open Alerts" :value="$openAlerts" icon="🚨"/>
        <x-stat-card label="Mood Logs" :value="$totalMoodLogs" icon="😊"/>
    </div>

    {{-- Risk Distribution Chart --}}
    <div class="grid md:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl shadow-sm border border-tan/20 p-6">
            <h3 class="font-heading text-forest font-semibold text-lg mb-4">Risk Level Distribution</h3>
            <canvas id="riskChart" height="200"></canvas>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-tan/20 p-6">
            <h3 class="font-heading text-forest font-semibold text-lg mb-4">Risk Breakdown</h3>
            <div class="space-y-3">
                @foreach(['minimal' => ['✅','bg-green-100 text-green-800'], 'mild' => ['⚠️','bg-yellow-100 text-yellow-800'], 'moderate' => ['🔶','bg-orange-100 text-orange-800'], 'severe' => ['🚨','bg-red-100 text-red-800']] as $level => $style)
                <div class="flex items-center justify-between p-3 rounded-lg {{ $style[1] }}">
                    <span class="text-sm font-medium">{{ $style[0] }} {{ ucfirst($level) }}</span>
                    <span class="font-bold">{{ $riskCounts[$level] ?? 0 }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Quick Links --}}
    <div class="bg-white rounded-2xl shadow-sm border border-tan/20 p-6">
        <h3 class="font-heading text-forest font-semibold text-lg mb-4">Quick Actions</h3>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.users') }}"
               class="bg-forest text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-midgreen transition-colors">
                👥 Manage Users
            </a>
            <a href="{{ route('counselor.index') }}"
               class="border border-forest text-forest px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-forest hover:text-white transition-colors">
                👩‍⚕️ Counselor View
            </a>
        </div>
    </div>
</div>

<script>
new Chart(document.getElementById('riskChart'), {
    type: 'doughnut',
    data: {
        labels: ['Minimal', 'Mild', 'Moderate', 'Severe'],
        datasets: [{
            data: [
                {{ $riskCounts['minimal'] ?? 0 }},
                {{ $riskCounts['mild'] ?? 0 }},
                {{ $riskCounts['moderate'] ?? 0 }},
                {{ $riskCounts['severe'] ?? 0 }},
            ],
            backgroundColor: ['#7AAF52', '#C4A96B', '#f97316', '#ef4444'],
            borderWidth: 0,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } }
    }
});
</script>
@endsection
