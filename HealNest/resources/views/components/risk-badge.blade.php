@props(['level' => 'minimal'])
@php
    $styles = [
        'minimal'  => 'bg-green-100 text-green-800',
        'mild'     => 'bg-yellow-100 text-yellow-800',
        'moderate' => 'bg-orange-100 text-orange-800',
        'severe'   => 'bg-red-100 text-red-800',
    ];
    $icons = ['minimal' => '✅', 'mild' => '⚠️', 'moderate' => '🔶', 'severe' => '🚨'];
@endphp
<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $styles[$level] ?? $styles['minimal'] }}">
    {{ $icons[$level] ?? '✅' }} {{ ucfirst($level) }}
</span>
