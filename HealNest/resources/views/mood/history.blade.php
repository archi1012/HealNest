@extends('layouts.app')
@section('title', 'Progress – HealNest')
@section('page-title', 'Mood Progress')

@section('content')
<div class="space-y-6">

    {{-- Chart --}}
    <div class="bg-white rounded-2xl shadow-sm border border-tan/20 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-heading text-forest font-semibold text-lg">30-Day Mood Trend</h3>
            <a href="{{ route('mood.create') }}"
               class="bg-forest text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-midgreen transition-colors">
                + Log Today
            </a>
        </div>
        @if($data->count())
            <canvas id="progressChart" height="80"></canvas>
        @else
            <div class="text-center py-16 text-gray-400">
                <p class="text-5xl mb-3">📊</p>
                <p>No data yet. Start logging your mood daily!</p>
            </div>
        @endif
    </div>

    {{-- Log History Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-tan/20 p-6">
        <h3 class="font-heading text-forest font-semibold text-lg mb-4">Log History</h3>
        @if($logs->count())
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-tan/20 text-left text-gray-500 text-xs uppercase">
                        <th class="pb-3 pr-4">Date</th>
                        <th class="pb-3 pr-4">Mood</th>
                        <th class="pb-3 pr-4">Level</th>
                        <th class="pb-3 pr-4">Tags</th>
                        <th class="pb-3">Notes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-tan/10">
                    @foreach($logs as $log)
                    <tr class="hover:bg-cream/50">
                        <td class="py-3 pr-4 text-gray-500">
                            {{ \Carbon\Carbon::parse($log->logged_at)->format('M d, Y') }}
                        </td>
                        <td class="py-3 pr-4">
                            <x-mood-bar :mood="(int)$log->mood"/>
                        </td>
                        <td class="py-3 pr-4 font-semibold text-forest">{{ $log->mood }}/5</td>
                        <td class="py-3 pr-4">
                            @foreach($log->tags ?? [] as $tag)
                                <span class="inline-block bg-lightgreen/20 text-midgreen text-xs px-2 py-0.5 rounded-full mr-1">{{ $tag }}</span>
                            @endforeach
                        </td>
                        <td class="py-3 text-gray-500 max-w-xs truncate">{{ $log->note ?? '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
            <p class="text-gray-400 text-center py-8">No mood logs found.</p>
        @endif
    </div>
</div>

@if($data->count())
<script>
new Chart(document.getElementById('progressChart'), {
    type: 'line',
    data: {
        labels: {!! json_encode($labels->values()) !!},
        datasets: [{
            label: 'Mood',
            data: {!! json_encode($data->values()) !!},
            borderColor: '#2D5016',
            backgroundColor: 'rgba(122,175,82,0.15)',
            borderWidth: 2,
            pointBackgroundColor: '#4A7C2F',
            pointRadius: 4,
            tension: 0.4,
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
</script>
@endif
@endsection
