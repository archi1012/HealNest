@extends('layouts.app')
@section('title', 'Resources – HealNest')
@section('page-title', 'Resources & Support')

@section('content')
<div class="space-y-6">

    {{-- Crisis Banner --}}
    <div class="bg-red-50 border border-red-200 rounded-2xl p-5 flex items-start gap-4">
        <span class="text-3xl">📞</span>
        <div>
            <h3 class="font-heading font-bold text-red-700 text-lg">In Crisis? Get Help Now</h3>
            <p class="text-red-600 text-sm mt-1">
                If you're in immediate danger, call <strong>988</strong> (Suicide & Crisis Lifeline) or
                text <strong>HOME</strong> to <strong>741741</strong> (Crisis Text Line).
            </p>
        </div>
    </div>

    {{-- Category Filter --}}
    @php $categories = collect($resources)->pluck('category')->unique()->values(); @endphp
    <div x-data="{ active: 'All' }" class="space-y-4">
        <div class="flex flex-wrap gap-2">
            <button @click="active = 'All'"
                    :class="active === 'All' ? 'bg-forest text-white' : 'border border-forest text-forest'"
                    class="px-4 py-1.5 rounded-full text-sm font-semibold transition-colors hover:bg-forest hover:text-white">
                All
            </button>
            @foreach($categories as $cat)
            <button @click="active = '{{ $cat }}'"
                    :class="active === '{{ $cat }}' ? 'bg-forest text-white' : 'border border-forest text-forest'"
                    class="px-4 py-1.5 rounded-full text-sm font-semibold transition-colors hover:bg-forest hover:text-white">
                {{ $cat }}
            </button>
            @endforeach
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($resources as $resource)
            <div x-show="active === 'All' || active === '{{ $resource['category'] }}'"
                 class="bg-white rounded-2xl border border-tan/20 p-5 hover:border-midgreen hover:shadow-md transition-all">
                <div class="flex items-start gap-3">
                    <span class="text-3xl">{{ $resource['icon'] }}</span>
                    <div>
                        <span class="text-xs bg-lightgreen/20 text-midgreen px-2 py-0.5 rounded-full font-semibold">
                            {{ $resource['category'] }}
                        </span>
                        <h3 class="font-heading font-semibold text-forest mt-1">{{ $resource['title'] }}</h3>
                        <p class="text-gray-500 text-sm mt-1">{{ $resource['desc'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Self-Assessment CTA --}}
    <div class="bg-forest text-white rounded-2xl p-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h3 class="font-heading text-xl font-bold">Not sure how you're feeling?</h3>
            <p class="text-green-200 text-sm mt-1">Take a quick evidence-based assessment to understand your mental state.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('assessment.show', 'PHQ9') }}"
               class="bg-tan text-forest px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-lightgreen transition-colors">
                PHQ-9
            </a>
            <a href="{{ route('assessment.show', 'GAD7') }}"
               class="border border-tan text-tan px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-tan hover:text-forest transition-colors">
                GAD-7
            </a>
        </div>
    </div>
</div>
@endsection
