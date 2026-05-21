@extends('layouts.app')
@section('title', 'Admin Dashboard – HealNest')
@section('page-title', 'Admin Dashboard')

@section('content')
<div class="space-y-6">

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <x-stat-card label="Total Users"    :value="$totalUsers"       icon="👥"/>
        <x-stat-card label="Counselors"     :value="$totalCounselors"  icon="👩⚕️"/>
        <x-stat-card label="Assessments"    :value="$totalAssessments" icon="📋"/>
        <x-stat-card label="Open Alerts"    :value="$openAlerts"       icon="🚨"/>
        <x-stat-card label="Mood Logs"      :value="$totalMoodLogs"    icon="😊"/>
    </div>

    {{-- Charts Row 1 --}}
    <div class="grid md:grid-cols-2 gap-6">

        {{-- Registrations Trend --}}
        <div class="bg-white rounded-2xl shadow-sm border border-tan/20 p-6">
            <h3 class="font-heading text-forest font-semibold text-lg mb-4">New Registrations (7 days)</h3>
            <canvas id="regChart" height="120"></canvas>
        </div>

        {{-- Assessments Trend --}}
        <div class="bg-white rounded-2xl shadow-sm border border-tan/20 p-6">
            <h3 class="font-heading text-forest font-semibold text-lg mb-4">Assessments Taken (7 days)</h3>
            <canvas id="assChart" height="120"></canvas>
        </div>
    </div>

    {{-- Charts Row 2 --}}
    <div class="grid md:grid-cols-2 gap-6">

        {{-- Risk Distribution --}}
        <div class="bg-white rounded-2xl shadow-sm border border-tan/20 p-6">
            <h3 class="font-heading text-forest font-semibold text-lg mb-4">Risk Level Distribution</h3>
            <div class="flex items-center gap-6">
                <div class="flex-1"><canvas id="riskChart" height="180"></canvas></div>
                <div class="space-y-2 text-sm">
                    @foreach(['minimal'=>['✅','text-green-700','bg-green-50'],'mild'=>['⚠️','text-yellow-700','bg-yellow-50'],'moderate'=>['🔶','text-orange-700','bg-orange-50'],'severe'=>['🚨','text-red-700','bg-red-50']] as $level=>$s)
                    <div class="flex items-center justify-between gap-4 px-3 py-1.5 rounded-lg {{ $s[2] }}">
                        <span class="{{ $s[1] }} font-medium">{{ $s[0] }} {{ ucfirst($level) }}</span>
                        <span class="font-bold {{ $s[1] }}">{{ $riskCounts[$level] ?? 0 }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Role Distribution --}}
        <div class="bg-white rounded-2xl shadow-sm border border-tan/20 p-6">
            <h3 class="font-heading text-forest font-semibold text-lg mb-4">User Role Distribution</h3>
            <div class="flex items-center gap-6">
                <div class="flex-1"><canvas id="roleChart" height="180"></canvas></div>
                <div class="space-y-2 text-sm">
                    @foreach(['user'=>['👤','text-green-700','bg-green-50'],'parent'=>['👨‍👩‍👧','text-purple-700','bg-purple-50'],'counselor'=>['👩⚕️','text-blue-700','bg-blue-50'],'admin'=>['⚙️','text-red-700','bg-red-50']] as $role=>$s)
                    <div class="flex items-center justify-between gap-4 px-3 py-1.5 rounded-lg {{ $s[2] }}">
                        <span class="{{ $s[1] }} font-medium">{{ $s[0] }} {{ ucfirst($role) }}</span>
                        <span class="font-bold {{ $s[1] }}">{{ $roleCounts[$role] ?? 0 }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="bg-white rounded-2xl shadow-sm border border-tan/20 p-6">
        <h3 class="font-heading text-forest font-semibold text-lg mb-4">Quick Actions</h3>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.users') }}"
               class="bg-forest text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-midgreen transition-colors">
                👥 Manage Users
            </a>
            <a href="{{ route('admin.users.create') }}"
               class="bg-tan text-forest px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-lightgreen transition-colors">
                ➕ Create User
            </a>
            <a href="{{ route('admin.counselors') }}"
               class="border border-forest text-forest px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-forest hover:text-white transition-colors">
                👩⚕️ Counselor Profiles
            </a>
            <a href="{{ route('admin.resources.index') }}"
               class="border border-tan text-earthbrown px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-tan hover:text-forest transition-colors">
                📚 Manage Resources
            </a>
            <a href="{{ route('admin.reports') }}"
               class="border border-midgreen text-midgreen px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-midgreen hover:text-white transition-colors">
                📊 View Reports
            </a>
            <a href="{{ route('counselor.index') }}"
               class="border border-earthbrown text-earthbrown px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-earthbrown hover:text-white transition-colors">
                🚨 Open Alerts
            </a>
        </div>
    </div>

</div>

<script>
// Registrations trend
new Chart(document.getElementById('regChart'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($regLabels->values()) !!},
        datasets: [{
            label: 'New Users',
            data: {!! json_encode($regData->values()) !!},
            backgroundColor: '#4A7C2F',
            borderRadius: 6,
        }]
    },
    options: { responsive: true, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }, plugins: { legend: { display: false } } }
});

// Assessments trend
new Chart(document.getElementById('assChart'), {
    type: 'line',
    data: {
        labels: {!! json_encode($assLabels->values()) !!},
        datasets: [{
            label: 'Assessments',
            data: {!! json_encode($assData->values()) !!},
            borderColor: '#C4A96B',
            backgroundColor: 'rgba(196,169,107,0.15)',
            borderWidth: 2,
            pointBackgroundColor: '#8B6914',
            tension: 0.4,
            fill: true,
        }]
    },
    options: { responsive: true, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }, plugins: { legend: { display: false } } }
});

// Risk doughnut
new Chart(document.getElementById('riskChart'), {
    type: 'doughnut',
    data: {
        labels: ['Minimal', 'Mild', 'Moderate', 'Severe'],
        datasets: [{
            data: [{{ $riskCounts['minimal'] ?? 0 }}, {{ $riskCounts['mild'] ?? 0 }}, {{ $riskCounts['moderate'] ?? 0 }}, {{ $riskCounts['severe'] ?? 0 }}],
            backgroundColor: ['#7AAF52', '#C4A96B', '#f97316', '#ef4444'],
            borderWidth: 0,
        }]
    },
    options: { responsive: true, plugins: { legend: { display: false } } }
});

// Role doughnut
new Chart(document.getElementById('roleChart'), {
    type: 'doughnut',
    data: {
        labels: ['User', 'Parent', 'Counselor', 'Admin'],
        datasets: [{
            data: [{{ $roleCounts['user'] ?? 0 }}, {{ $roleCounts['parent'] ?? 0 }}, {{ $roleCounts['counselor'] ?? 0 }}, {{ $roleCounts['admin'] ?? 0 }}],
            backgroundColor: ['#7AAF52', '#a855f7', '#3b82f6', '#ef4444'],
            borderWidth: 0,
        }]
    },
    options: { responsive: true, plugins: { legend: { display: false } } }
});
</script>
@endsection
