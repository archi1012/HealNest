@props(['mood' => 3])
@php
    $labels = [1 => 'Very Low', 2 => 'Low', 3 => 'Neutral', 4 => 'Good', 5 => 'Great'];
    $colors = [1 => 'bg-red-400', 2 => 'bg-orange-400', 3 => 'bg-yellow-400', 4 => 'bg-lightgreen', 5 => 'bg-forest'];
    $emojis = [1 => '😢', 2 => '😕', 3 => '😐', 4 => '🙂', 5 => '😄'];
    $pct = ($mood / 5) * 100;
@endphp
<div class="flex items-center gap-3">
    <span class="text-2xl">{{ $emojis[$mood] ?? '😐' }}</span>
    <div class="flex-1">
        <div class="flex justify-between text-xs text-gray-500 mb-1">
            <span>{{ $labels[$mood] ?? 'Neutral' }}</span>
            <span>{{ $mood }}/5</span>
        </div>
        <div class="h-3 bg-gray-100 rounded-full overflow-hidden">
            <div class="{{ $colors[$mood] ?? 'bg-yellow-400' }} h-full rounded-full transition-all duration-500"
                 style="width: {{ $pct }}%"></div>
        </div>
    </div>
</div>
