@props(['href', 'icon', 'label', 'badge' => null])
<a href="{{ $href }}"
   class="flex items-center gap-3 px-4 py-2.5 text-sm transition-colors relative
          {{ request()->url() === $href ? 'bg-midgreen text-white' : 'text-green-100 hover:bg-midgreen/50 hover:text-white' }}">
    <span class="text-lg flex-shrink-0">{{ $icon }}</span>
    <span x-show="open" x-cloak>{{ $label }}</span>
    @if(! is_null($badge) && $badge !== '')
        <span class="absolute right-4 top-1/2 -translate-y-1/2 min-w-5 h-5 px-1.5 rounded-full bg-red-500 text-white text-[10px] font-bold flex items-center justify-center">
            {{ $badge }}
        </span>
    @endif
</a>
