@extends('layouts.app')
@section('title', 'Reports – HealNest')
@section('page-title', 'Reports & Oversight')

@section('content')
<div class="space-y-6">

    {{-- Period Filter --}}
    <div class="bg-white rounded-2xl shadow-sm border border-tan/20 p-4">
        <form method="GET" class="flex flex-wrap items-center gap-3">
            <span class="text-sm font-medium text-forest">Show data for:</span>
            @foreach(['7' => 'Last 7 days', '14' => 'Last 14 days', '30' => 'Last 30 days', '90' => 'Last 90 days'] as $val => $label)
            <button type="submit" name="period" value="{{ $val }}"
                    class="px-4 py-1.5 rounded-full text-sm font-semibold transition-colors
                           {{ $period == $val ? 'bg-forest text-white' : 'border border-forest text-forest hover:bg-forest hover:text-white' }}">
                {{ $label }}
            </button>
            @endforeach
        </form>
    </div>

    {{-- Summary Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <x-stat-card label="New Users" :value="$newUsers" icon="👤" sub="In period"/>
        <x-stat-card label="Assessments" :value="$newAssessments" icon="📋" sub="In period"/>
        <x-stat-card label="Mood Logs" :value="$newMoodLogs" icon="😊" sub="In period"/>
        <x-stat-card label="New Alerts" :value="$newAlerts" icon="🔔" sub="In period"/>
        <x-stat-card label="High Risk Cases" :value="$highRiskCount" icon="🚨" sub="Moderate + Severe"/>
    </div>

    <div class="grid md:grid-cols-2 gap-6">

        {{-- Recent Assessments --}}
        <div class="bg-white rounded-2xl shadow-sm border border-tan/20 p-6">
            <h3 class="font-heading text-forest font-semibold text-lg mb-4">Recent Assessments</h3>
            @if($recentAssessments->count())
            <div class="space-y-3 max-h-96 overflow-y-auto">
                @foreach($recentAssessments as $a)
                <div class="flex items-center justify-between p-3 border border-tan/20 rounded-xl hover:border-midgreen/40">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-midgreen flex items-center justify-center text-white text-xs font-bold">
                            {{ strtoupper(substr($a->user?->name ?? '?', 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-forest">{{ $a->user?->name ?? 'Unknown' }}</p>
                            <p class="text-xs text-gray-400">{{ $a->type }} &bull; Score: {{ $a->score }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <x-risk-badge :level="$a->risk_level"/>
                        <p class="text-xs text-gray-400 mt-1">{{ \Carbon\Carbon::parse($a->taken_at)->diffForHumans() }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            @else
                <p class="text-gray-400 text-sm text-center py-8">No assessments in this period.</p>
            @endif
        </div>

        {{-- Recent Alerts --}}
        <div class="bg-white rounded-2xl shadow-sm border border-tan/20 p-6">
            <h3 class="font-heading text-forest font-semibold text-lg mb-4">Recent Alerts</h3>
            @if($recentAlerts->count())
            <div class="space-y-3 max-h-96 overflow-y-auto">
                @foreach($recentAlerts as $alert)
                <div class="flex items-center justify-between p-3 border border-tan/20 rounded-xl hover:border-midgreen/40">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center text-red-600 text-xs font-bold">
                            {{ strtoupper(substr($alert->user?->name ?? '?', 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-forest">{{ $alert->user?->name ?? 'Unknown' }}</p>
                            <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($alert->created_at)->diffForHumans() }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <x-risk-badge :level="$alert->risk_level"/>
                        <span class="block text-xs mt-1 {{ $alert->status === 'open' ? 'text-red-500' : 'text-green-600' }} font-semibold">
                            {{ ucfirst($alert->status) }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
            @else
                <p class="text-gray-400 text-sm text-center py-8">No alerts in this period.</p>
            @endif
        </div>
    </div>

    {{-- Export Note --}}
    <div class="bg-tan/10 border border-tan/30 rounded-2xl p-5 flex items-center gap-4">
        <span class="text-3xl">📊</span>
        <div>
            <p class="font-heading font-semibold text-forest">Need a full export?</p>
            <p class="text-sm text-gray-500 mt-0.5">Use MongoDB Atlas Data Explorer or connect a BI tool to your cluster for advanced reporting.</p>
        </div>
    </div>
</div>
@endsection
