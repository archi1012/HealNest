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
                    <a href="{{ route('assessment.show', 'PHQ9') }}"
                       class="block text-center border border-forest text-forest py-2 rounded-lg text-sm hover:bg-forest hover:text-white transition-colors">
                        PHQ-9 (Depression)
                    </a>
                    <a href="{{ route('assessment.show', 'GAD7') }}"
                       class="block text-center border border-midgreen text-midgreen py-2 rounded-lg text-sm hover:bg-midgreen hover:text-white transition-colors">
                        GAD-7 (Anxiety)
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

    {{-- Connect to Counselor --}}
    <div class="bg-white rounded-xl p-6 shadow-sm border border-tan/20">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-heading text-forest font-semibold text-lg">Connect to a Counselor</h3>
            <a href="{{ route('connect.counselor') }}" class="text-sm text-midgreen underline">See all</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Show a few sample/dummy counselor cards inline --}}
            @php
                $sample = [
                    ['name' => 'Dr. Maya Alvarez', 'specialty' => 'Depression & Anxiety', 'phone' => '+10000000001'],
                    ['name' => 'Samuel Okoye', 'specialty' => 'Adolescent Counseling', 'phone' => '+10000000002'],
                    ['name' => 'Aisha Rahman', 'specialty' => 'Crisis Support', 'phone' => '+10000000003'],
                ];
            @endphp
            @foreach($sample as $c)
            <div class="border border-tan/20 rounded-lg p-3 flex items-start gap-3">
                <div class="w-10 h-10 rounded-full bg-midgreen flex items-center justify-center text-white font-semibold">{{ strtoupper(substr($c['name'],0,1)) }}</div>
                <div class="flex-1">
                    <p class="font-medium text-forest">{{ $c['name'] }}</p>
                    <p class="text-xs text-gray-500">{{ $c['specialty'] }}</p>
                </div>
                <div>
                    <a href="https://api.whatsapp.com/send?phone={{ urlencode($c['phone']) }}&text={{ urlencode('Hello, I would like to connect with a counselor from HealNest.') }}" target="_blank" rel="noopener noreferrer"
                       class="text-xs bg-forest text-white px-3 py-1.5 rounded-lg hover:bg-midgreen transition-colors">Chat</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>

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
