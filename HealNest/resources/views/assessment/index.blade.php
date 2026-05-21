@extends('layouts.app')
@section('title', 'Take Assessment – HealNest')
@section('page-title', 'Take Assessment')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Intro --}}
    <div class="bg-white rounded-2xl border border-tan/20 shadow-sm p-6 flex items-start gap-4">
        <span class="text-4xl">🧠</span>
        <div>
            <h2 class="font-heading text-forest font-bold text-xl">How are you feeling?</h2>
            <p class="text-gray-500 text-sm mt-1">
                Choose an assessment below. Each takes 2–5 minutes and helps track your mental well-being over time.
                Your responses are private and secure.
            </p>
        </div>
    </div>

    {{-- Assessment Cards --}}
    <div class="grid md:grid-cols-3 gap-5">

        {{-- General Wellbeing --}}
        <a href="{{ route('assessment.show', 'GENERAL') }}"
           class="group bg-white rounded-2xl border-2 border-tan/20 shadow-sm p-6 hover:border-lightgreen hover:shadow-md transition-all duration-200 flex flex-col">
            <div class="w-14 h-14 rounded-2xl bg-lightgreen/20 flex items-center justify-center text-3xl mb-4 group-hover:bg-lightgreen/40 transition-colors">
                🌱
            </div>
            <h3 class="font-heading font-bold text-forest text-lg">General Wellbeing</h3>
            <p class="text-gray-500 text-sm mt-2 flex-1">
                A broad check-in on your overall mental health, energy, sleep, and daily functioning.
            </p>
            <div class="mt-4 space-y-1.5 text-xs text-gray-400">
                <div class="flex items-center gap-2"><span>📝</span> 10 questions</div>
                <div class="flex items-center gap-2"><span>⏱️</span> ~2 minutes</div>
                <div class="flex items-center gap-2"><span>🎯</span> General wellness</div>
            </div>
            @if(isset($lastTaken['GENERAL']))
            <div class="mt-4 pt-4 border-t border-tan/20 flex items-center justify-between">
                <span class="text-xs text-gray-400">Last taken</span>
                <div class="flex items-center gap-2">
                    <x-risk-badge :level="$lastTaken['GENERAL']->risk_level"/>
                    <span class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($lastTaken['GENERAL']->taken_at)->diffForHumans() }}</span>
                </div>
            </div>
            @else
            <div class="mt-4 pt-4 border-t border-tan/20">
                <span class="text-xs text-gray-400 italic">Not taken yet</span>
            </div>
            @endif
            <div class="mt-4 w-full bg-forest text-white text-center py-2.5 rounded-xl text-sm font-semibold group-hover:bg-midgreen transition-colors">
                Start Assessment →
            </div>
        </a>

        {{-- PHQ-9 --}}
        <a href="{{ route('assessment.show', 'PHQ9') }}"
           class="group bg-white rounded-2xl border-2 border-tan/20 shadow-sm p-6 hover:border-blue-300 hover:shadow-md transition-all duration-200 flex flex-col">
            <div class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center text-3xl mb-4 group-hover:bg-blue-100 transition-colors">
                😔
            </div>
            <h3 class="font-heading font-bold text-forest text-lg">PHQ-9</h3>
            <p class="text-gray-500 text-sm mt-2 flex-1">
                Patient Health Questionnaire — a clinically validated screening tool for depression symptoms.
            </p>
            <div class="mt-4 space-y-1.5 text-xs text-gray-400">
                <div class="flex items-center gap-2"><span>📝</span> 9 questions</div>
                <div class="flex items-center gap-2"><span>⏱️</span> ~3 minutes</div>
                <div class="flex items-center gap-2"><span>🎯</span> Depression screening</div>
            </div>
            <div class="mt-3 text-xs text-gray-400 bg-blue-50 rounded-lg px-3 py-2">
                Score 0–27 &bull; Minimal / Mild / Moderate / Severe
            </div>
            @if(isset($lastTaken['PHQ9']))
            <div class="mt-4 pt-4 border-t border-tan/20 flex items-center justify-between">
                <span class="text-xs text-gray-400">Last taken</span>
                <div class="flex items-center gap-2">
                    <x-risk-badge :level="$lastTaken['PHQ9']->risk_level"/>
                    <span class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($lastTaken['PHQ9']->taken_at)->diffForHumans() }}</span>
                </div>
            </div>
            @else
            <div class="mt-4 pt-4 border-t border-tan/20">
                <span class="text-xs text-gray-400 italic">Not taken yet</span>
            </div>
            @endif
            <div class="mt-4 w-full bg-blue-600 text-white text-center py-2.5 rounded-xl text-sm font-semibold group-hover:bg-blue-700 transition-colors">
                Start Assessment →
            </div>
        </a>

        {{-- GAD-7 --}}
        <a href="{{ route('assessment.show', 'GAD7') }}"
           class="group bg-white rounded-2xl border-2 border-tan/20 shadow-sm p-6 hover:border-purple-300 hover:shadow-md transition-all duration-200 flex flex-col">
            <div class="w-14 h-14 rounded-2xl bg-purple-50 flex items-center justify-center text-3xl mb-4 group-hover:bg-purple-100 transition-colors">
                😰
            </div>
            <h3 class="font-heading font-bold text-forest text-lg">GAD-7</h3>
            <p class="text-gray-500 text-sm mt-2 flex-1">
                Generalized Anxiety Disorder scale — a validated tool for measuring anxiety severity.
            </p>
            <div class="mt-4 space-y-1.5 text-xs text-gray-400">
                <div class="flex items-center gap-2"><span>📝</span> 7 questions</div>
                <div class="flex items-center gap-2"><span>⏱️</span> ~2 minutes</div>
                <div class="flex items-center gap-2"><span>🎯</span> Anxiety screening</div>
            </div>
            <div class="mt-3 text-xs text-gray-400 bg-purple-50 rounded-lg px-3 py-2">
                Score 0–21 &bull; Minimal / Mild / Moderate / Severe
            </div>
            @if(isset($lastTaken['GAD7']))
            <div class="mt-4 pt-4 border-t border-tan/20 flex items-center justify-between">
                <span class="text-xs text-gray-400">Last taken</span>
                <div class="flex items-center gap-2">
                    <x-risk-badge :level="$lastTaken['GAD7']->risk_level"/>
                    <span class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($lastTaken['GAD7']->taken_at)->diffForHumans() }}</span>
                </div>
            </div>
            @else
            <div class="mt-4 pt-4 border-t border-tan/20">
                <span class="text-xs text-gray-400 italic">Not taken yet</span>
            </div>
            @endif
            <div class="mt-4 w-full bg-purple-600 text-white text-center py-2.5 rounded-xl text-sm font-semibold group-hover:bg-purple-700 transition-colors">
                Start Assessment →
            </div>
        </a>

    </div>

    {{-- Info Note --}}
    <div class="bg-tan/10 border border-tan/30 rounded-2xl p-5 flex items-start gap-3">
        <span class="text-2xl">ℹ️</span>
        <p class="text-sm text-gray-600">
            These assessments are <strong>not a diagnosis</strong>. If your score indicates moderate or severe symptoms,
            a counselor will be notified to follow up with you. For emergencies, call <strong>988</strong>.
        </p>
    </div>

</div>
@endsection
