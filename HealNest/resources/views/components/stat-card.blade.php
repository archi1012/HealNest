@props(['label', 'value', 'icon' => '📊', 'color' => 'forest', 'sub' => null])
<div class="bg-white rounded-xl p-5 shadow-sm border border-tan/20 flex items-start gap-4">
    <div class="text-3xl">{{ $icon }}</div>
    <div>
        <p class="text-gray-500 text-xs uppercase tracking-wide">{{ $label }}</p>
        <p class="text-2xl font-bold text-forest font-heading">{{ $value }}</p>
        @if($sub)<p class="text-xs text-earthbrown mt-0.5">{{ $sub }}</p>@endif
    </div>
</div>
