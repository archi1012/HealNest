@extends('layouts.app')
@section('title', 'Log Mood – HealNest')
@section('page-title', 'Log Your Mood')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-tan/20 p-8">
        <p class="text-gray-500 text-sm mb-6">How are you feeling right now? Be honest — this is your safe space.</p>

        <form method="POST" action="{{ route('mood.store') }}" x-data="{ mood: 3 }" class="space-y-6">
            @csrf

            {{-- Mood Scale --}}
            <div>
                <label class="block font-heading font-semibold text-forest mb-4">Mood Level</label>
                <div class="flex justify-between gap-2">
                    @foreach([1 => ['😢','Very Low'], 2 => ['😕','Low'], 3 => ['😐','Neutral'], 4 => ['🙂','Good'], 5 => ['😄','Great']] as $val => $info)
                    <label class="flex-1 cursor-pointer">
                        <input type="radio" name="mood" value="{{ $val }}" x-model="mood" class="sr-only" {{ old('mood', 3) == $val ? 'checked' : '' }}>
                        <div :class="mood == {{ $val }} ? 'border-midgreen bg-midgreen/10 scale-105' : 'border-tan/30'"
                             class="border-2 rounded-xl p-3 text-center transition-all duration-200">
                            <div class="text-2xl">{{ $info[0] }}</div>
                            <div class="text-xs text-gray-500 mt-1">{{ $info[1] }}</div>
                            <div class="text-xs font-bold text-forest">{{ $val }}</div>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- Note --}}
            <div>
                <label class="block font-heading font-semibold text-forest mb-2">Notes <span class="text-gray-400 font-normal text-sm">(optional)</span></label>
                <textarea name="note" rows="3" maxlength="500"
                          class="w-full border border-tan/40 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-midgreen bg-cream resize-none"
                          placeholder="What's on your mind today?">{{ old('note') }}</textarea>
            </div>

            {{-- Tags --}}
            <div>
                <label class="block font-heading font-semibold text-forest mb-2">Tags <span class="text-gray-400 font-normal text-sm">(comma-separated)</span></label>
                <input type="text" name="tags" value="{{ old('tags') }}"
                       class="w-full border border-tan/40 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-midgreen bg-cream"
                       placeholder="e.g. anxious, tired, hopeful">
            </div>

            <button type="submit"
                    class="w-full bg-forest text-white py-3 rounded-xl font-semibold hover:bg-midgreen transition-colors">
                Save Mood Log
            </button>
        </form>
    </div>
</div>
@endsection
