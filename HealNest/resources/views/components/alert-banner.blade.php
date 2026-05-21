@props(['type' => 'success', 'message'])
@php
    $styles = [
        'success' => 'bg-lightgreen/20 border-lightgreen text-forest',
        'error'   => 'bg-red-50 border-red-300 text-red-700',
        'warning' => 'bg-tan/20 border-tan text-earthbrown',
    ];
    $icons = ['success' => '✅', 'error' => '❌', 'warning' => '⚠️'];
@endphp
<div x-data="{ show: true }" x-show="show" x-transition
     class="flex items-center justify-between p-3 mb-4 rounded-lg border text-sm {{ $styles[$type] ?? $styles['success'] }}">
    <span>{{ $icons[$type] ?? '✅' }} {{ $message }}</span>
    <button @click="show = false" class="ml-4 opacity-60 hover:opacity-100">✕</button>
</div>
