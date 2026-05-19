@extends('layouts.app')
@section('title', $type . ' Assessment – HealNest')
@section('page-title', $type === 'PHQ9' ? 'PHQ-9 Depression Screening' : 'GAD-7 Anxiety Screening')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-tan/20 p-8">

        <div class="mb-6 p-4 bg-lightgreen/10 border border-lightgreen/30 rounded-xl">
            <p class="text-sm text-forest">
                <strong>Instructions:</strong> Over the last 2 weeks, how often have you been bothered by the following problems?
                Rate each: <strong>0</strong> = Not at all, <strong>1</strong> = Several days,
                <strong>2</strong> = More than half the days, <strong>3</strong> = Nearly every day.
            </p>
        </div>

        <form method="POST" action="{{ route('assessment.store', $type) }}" x-data class="space-y-5">
            @csrf

            @foreach($questions as $i => $question)
            <div class="border border-tan/20 rounded-xl p-4 hover:border-midgreen/40 transition-colors">
                <p class="text-sm font-medium text-forest mb-3">
                    <span class="text-earthbrown font-bold">{{ $i + 1 }}.</span> {{ $question }}
                </p>
                <div class="grid grid-cols-4 gap-2">
                    @foreach([0 => 'Not at all', 1 => 'Several days', 2 => 'More than half', 3 => 'Nearly every day'] as $val => $lbl)
                    <label class="cursor-pointer">
                        <input type="radio" name="q{{ $i }}" value="{{ $val }}" class="sr-only peer" required>
                        <div class="peer-checked:bg-forest peer-checked:text-white border border-tan/30 peer-checked:border-forest
                                    rounded-lg p-2 text-center text-xs transition-all hover:border-midgreen">
                            <div class="font-bold text-base">{{ $val }}</div>
                            <div class="text-gray-400 peer-checked:text-green-200 leading-tight mt-0.5">{{ $lbl }}</div>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>
            @endforeach

            <button type="submit"
                    class="w-full bg-forest text-white py-3 rounded-xl font-semibold hover:bg-midgreen transition-colors mt-4">
                Submit Assessment
            </button>
        </form>
    </div>
</div>
@endsection
