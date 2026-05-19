@props(['href', 'icon', 'label'])
<a href="{{ $href }}"
   class="flex items-center gap-3 px-4 py-2.5 text-sm transition-colors
          {{ request()->url() === $href ? 'bg-midgreen text-white' : 'text-green-100 hover:bg-midgreen/50 hover:text-white' }}">
    <span class="text-lg flex-shrink-0">{{ $icon }}</span>
    <span x-show="open" x-cloak>{{ $label }}</span>
</a>
