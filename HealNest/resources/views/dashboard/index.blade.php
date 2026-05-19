@extends('layouts.app')
@section('title', 'Dashboard – HealNest')
@section('page-title', 'My Dashboard')

@section('content')
<div class="space-y-6">

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <x-stat-card label="Avg Mood (7d)" :value="number_format($avgMood, 1) . '/5'" icon="😊"
                     sub="Based on last 7 logs"/>
        <x-stat-card label="Current Streak" :value="$streak . ' days'" icon="🔥"
                     sub="Keep it up!"/>
        <x-stat-card label="Risk Level" icon="🧠"
                     :value="$latestAssessment ? ucfirst($latestAssessment->risk_level) : 'N/A'"
                     :sub="$latestAssessment ? $latestAssessment->type : 'No assessment yet'"/>
        <x-stat-card label="Open Alerts" :value="$openAlerts" icon="🔔"
                     sub="Requires attention"/>
    </div>

    {{-- Mood Bar + Quick Actions --}}
    <div class="grid md:grid-cols-3 gap-6">
        <div class="md:col-span-2 bg-white rounded-xl p-6 shadow-sm border border-tan/20">
            <h3 class="font-heading text-forest font-semibold text-lg mb-4">Mood This Week</h3>
            @if($moodData->count())
                <canvas id="moodChart" height="100"></canvas>
            @else
                <div class="text-center py-10 text-gray-400">
                    <p class="text-4xl mb-2">📭</p>
                    <p>No mood logs yet. <a href="{{ route('mood.create') }}" class="text-midgreen underline">Log your first mood</a></p>
                </div>
            @endif
        </div>

        <div class="space-y-4">
            <div class="bg-white rounded-xl p-5 shadow-sm border border-tan/20">
                <h3 class="font-heading text-forest font-semibold mb-3">Today's Mood</h3>
                @if($moodData->count())
                    <x-mood-bar :mood="(int) $moodData->last()"/>
                @else
                    <p class="text-sm text-gray-400">Not logged yet</p>
                @endif
                <a href="{{ route('mood.create') }}"
                   class="mt-4 block text-center bg-forest text-white py-2 rounded-lg text-sm font-semibold hover:bg-midgreen transition-colors">
                    + Log Mood
                </a>
            </div>

            <div class="bg-white rounded-xl p-5 shadow-sm border border-tan/20">
                <h3 class="font-heading text-forest font-semibold mb-3">Quick Assessment</h3>
                <div class="space-y-2">
                    <a href="{{ route('assessment.index') }}"
                       class="block text-center border border-forest text-forest py-2 rounded-lg text-sm hover:bg-forest hover:text-white transition-colors">
                        Take Assessment
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Latest Assessment --}}
    @if($latestAssessment)
    <div class="bg-white rounded-xl p-6 shadow-sm border border-tan/20">
        <h3 class="font-heading text-forest font-semibold text-lg mb-3">Latest Assessment</h3>
        <div class="flex flex-wrap items-center gap-4">
            <div>
                <p class="text-xs text-gray-500">Type</p>
                <p class="font-semibold text-forest">{{ $latestAssessment->type }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500">Score</p>
                <p class="font-semibold text-forest">{{ $latestAssessment->score }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500">Risk Level</p>
                <x-risk-badge :level="$latestAssessment->risk_level"/>
            </div>
            <div>
                <p class="text-xs text-gray-500">Taken</p>
                <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($latestAssessment->taken_at)->diffForHumans() }}</p>
            </div>
        </div>
    </div>
    @endif

    {{-- Connect section removed — use the dedicated Connect page in the sidebar --}}

</div>

@if($moodData->count())
<script>
new Chart(document.getElementById('moodChart'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($moodLabels->values()) !!},
        datasets: [{
            label: 'Mood Score',
            data: {!! json_encode($moodData->values()) !!},
            backgroundColor: '#7AAF52',
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: { min: 0, max: 5, ticks: { stepSize: 1 } }
        },
        plugins: { legend: { display: false } }
    }
});
</script>
@endif
@endsection
