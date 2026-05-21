@extends('layouts.app')
@section('title', 'Mood Analytics – HealNest')
@section('page-title', 'Mood Analytics')

@section('content')
<div class="space-y-6">
    <div class="grid md:grid-cols-2 xl:grid-cols-4 gap-4">
        <x-stat-card label="All-Time Average" :value="$moodCount ? number_format($averageMood, 1) . '/5' : 'N/A'" icon="📈" sub="Across all mood logs"/>
        <x-stat-card label="7-Day Average" :value="$moodCount ? number_format($weeklyAverage, 1) . '/5' : 'N/A'" icon="📅" sub="Recent mood pattern"/>
        <x-stat-card label="Best Mood" :value="$bestMood ? $bestMood . '/5' : 'N/A'" icon="🌤️" sub="Highest recorded value"/>
        <x-stat-card label="Latest Mood" :value="$latestMood ? $latestMood . '/5' : 'N/A'" icon="🧾" sub="Most recent check-in"/>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-tan/20 p-6">
            <div class="flex items-center justify-between gap-4 mb-4">
                <div>
                    <h3 class="font-heading text-forest font-semibold text-lg">30-Day Mood Trend</h3>
                    <p class="text-sm text-gray-500">Trend line based on the most recent 30 logs.</p>
                </div>
                <a href="{{ route('mood.history') }}"
                   class="bg-forest text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-midgreen transition-colors">
                    View History
                </a>
            </div>

            @if($moodData->count())
                <canvas id="trendChart" height="110"></canvas>
            @else
                <div class="text-center py-16 text-gray-400">
                    <p class="text-5xl mb-3">📊</p>
                    <p>No mood data yet. Log a few moods to see analytics.</p>
                </div>
            @endif
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-tan/20 p-6">
            <h3 class="font-heading text-forest font-semibold text-lg mb-4">Mood Distribution</h3>
            @if($moodCount)
                <canvas id="distributionChart" height="220"></canvas>
            @else
                <p class="text-sm text-gray-400">No data yet.</p>
            @endif
            <div class="mt-4 space-y-2 text-sm text-gray-600">
                <div class="flex justify-between"><span>Total logs</span><span class="font-semibold text-forest">{{ $moodCount }}</span></div>
                <div class="flex justify-between"><span>Worst mood</span><span class="font-semibold text-forest">{{ $worstMood ? $worstMood . '/5' : 'N/A' }}</span></div>
                <div class="flex justify-between"><span>Recent trend</span><span class="font-semibold {{ $trend >= 0 ? 'text-midgreen' : 'text-red-500' }}">{{ $trend >= 0 ? '+' : '' }}{{ $trend }}</span></div>
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl shadow-sm border border-tan/20 p-6">
            <h3 class="font-heading text-forest font-semibold text-lg mb-4">Tag Patterns</h3>
            @if($tagCounts->count())
                <div class="space-y-3">
                    @foreach($tagCounts as $tag => $count)
                        <div class="flex items-center justify-between gap-4">
                            <span class="inline-flex items-center bg-lightgreen/15 text-midgreen px-3 py-1 rounded-full text-sm">#{{ $tag }}</span>
                            <div class="flex-1 mx-4 h-2 rounded-full bg-cream overflow-hidden">
                                <div class="h-full bg-midgreen rounded-full" style="width: {{ max(12, ($count / $tagCounts->max()) * 100) }}%"></div>
                            </div>
                            <span class="text-sm font-semibold text-forest">{{ $count }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-400">No tags have been recorded yet.</p>
            @endif
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-tan/20 p-6">
            <h3 class="font-heading text-forest font-semibold text-lg mb-4">Best Mood Dates</h3>
            @if($peakDates->count())
                <div class="flex flex-wrap gap-2">
                    @foreach($peakDates as $date)
                        <span class="bg-cream text-forest text-sm px-3 py-1.5 rounded-full border border-tan/20">{{ $date }}</span>
                    @endforeach
                </div>
                <p class="mt-4 text-sm text-gray-500">These were the days you recorded your highest mood score.</p>
            @else
                <p class="text-sm text-gray-400">No peak dates available yet.</p>
            @endif
        </div>
    </div>
</div>

@if($moodData->count())
<script>
new Chart(document.getElementById('trendChart'), {
    type: 'line',
    data: {
        labels: {!! json_encode($labels->values()) !!},
        datasets: [{
            label: 'Mood',
            data: {!! json_encode($moodData->values()) !!},
            borderColor: '#2D5016',
            backgroundColor: 'rgba(122,175,82,0.15)',
            borderWidth: 2,
            pointBackgroundColor: '#4A7C2F',
            pointRadius: 4,
            tension: 0.35,
            fill: true,
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

new Chart(document.getElementById('distributionChart'), {
    type: 'doughnut',
    data: {
        labels: ['1', '2', '3', '4', '5'],
        datasets: [{
            data: {!! json_encode($distribution->values()) !!},
            backgroundColor: ['#8B6914', '#C4A96B', '#D9C89A', '#7AAF52', '#2D5016'],
            borderWidth: 0,
        }]
    },
    options: {
        responsive: true,
        cutout: '68%',
        plugins: {
            legend: {
                position: 'bottom',
                labels: { usePointStyle: true, boxWidth: 10 }
            }
        }
    }
});
</script>
@endif
@endsection
