@extends('layouts.app')
@section('title', 'Assessment Result – HealNest')
@section('page-title', 'Assessment Result')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-tan/20 p-8 text-center">

        @php
            $icons = ['minimal' => '🌱', 'mild' => '🌤️', 'moderate' => '⛅', 'severe' => '⛈️'];
            $messages = [
                'minimal'  => 'Your score suggests minimal symptoms. Keep up your self-care routine!',
                'mild'     => 'Mild symptoms detected. Consider monitoring your mood and using our resources.',
                'moderate' => 'Moderate symptoms detected. We recommend speaking with a counselor.',
                'severe'   => 'Severe symptoms detected. Please reach out to a mental health professional.',
            ];
        @endphp

        <div class="text-6xl mb-4">{{ $icons[$assessment->risk_level] ?? '📋' }}</div>
        <h2 class="font-heading text-2xl font-bold text-forest mb-2">{{ $assessment->type }} Complete</h2>

        <div class="flex items-center justify-center gap-6 my-6">
            <div class="text-center">
                <p class="text-4xl font-bold text-forest font-heading">{{ $assessment->score }}</p>
                <p class="text-xs text-gray-500 mt-1">Total Score</p>
            </div>
            <div class="text-center">
                <x-risk-badge :level="$assessment->risk_level"/>
                <p class="text-xs text-gray-500 mt-1">Risk Level</p>
            </div>
        </div>

        <p class="text-gray-600 text-sm mb-6 max-w-sm mx-auto">
            {{ $messages[$assessment->risk_level] ?? '' }}
        </p>

        @if(in_array($assessment->risk_level, ['moderate', 'severe']))
        <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6 text-left">
            <p class="text-red-700 text-sm font-semibold">⚠️ Alert Generated</p>
            <p class="text-red-600 text-xs mt-1">A counselor has been notified and will follow up with you.</p>
        </div>
        @endif

        <div class="flex gap-3 justify-center">
            <a href="{{ route('dashboard') }}"
               class="bg-forest text-white px-6 py-2.5 rounded-xl text-sm font-semibold hover:bg-midgreen transition-colors">
                Back to Dashboard
            </a>
            <a href="{{ route('resources') }}"
               class="border border-forest text-forest px-6 py-2.5 rounded-xl text-sm font-semibold hover:bg-forest hover:text-white transition-colors">
                View Resources
            </a>
        </div>
    </div>
</div>
@endsection
