@extends('layouts.app')
@section('title', 'Claude AI – HealNest')
@section('page-title', 'Claude AI Suggestions')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-2xl shadow-sm border border-tan/20 p-6">
        <div class="flex items-start justify-between gap-4 flex-wrap">
            <div>
                <h3 class="font-heading text-forest font-semibold text-lg">Project Snapshot</h3>
                <p class="text-sm text-gray-500 mt-1">Claude reads a compact summary of the current HealNest codebase and suggests unique features.</p>
            </div>
            <form method="POST" action="{{ route('admin.ai.analyze') }}" class="w-full max-w-xl space-y-4">
                @csrf
                <label class="block text-sm font-medium text-gray-700">Extra instructions for Claude</label>
                <textarea name="extra_prompt" rows="4" placeholder="Example: prioritize features for a college presentation, keep implementation within 4-6 weeks, and focus on novelty."
                          class="w-full border border-tan/30 rounded-xl px-4 py-3 bg-cream focus:outline-none focus:ring-2 focus:ring-midgreen">{{ old('extra_prompt', $extraPrompt) }}</textarea>
                <button type="submit" class="bg-forest text-white px-5 py-2.5 rounded-lg text-sm font-semibold hover:bg-midgreen transition-colors">
                    Analyze with Claude
                </button>
            </form>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 bg-white rounded-2xl shadow-sm border border-tan/20 p-6">
            <h3 class="font-heading text-forest font-semibold text-lg mb-4">Files Included</h3>
            <div class="space-y-3 text-sm">
                @foreach($files as $file)
                    <div class="flex items-center justify-between gap-3 border border-tan/15 rounded-lg px-3 py-2">
                        <span class="text-gray-700 break-all">{{ $file['path'] }}</span>
                        <span class="text-xs text-gray-500">{{ number_format($file['chars']) }} chars</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-tan/20 p-6">
            <h3 class="font-heading text-forest font-semibold text-lg mb-4">Repository Summary Sent to Claude</h3>
            <pre class="whitespace-pre-wrap text-xs leading-6 text-gray-700 bg-cream rounded-xl p-4 border border-tan/15 max-h-[520px] overflow-auto">{{ $repoSummary }}</pre>
        </div>
    </div>

    @if($result)
    <div class="bg-white rounded-2xl shadow-sm border border-tan/20 p-6 space-y-4">
        <h3 class="font-heading text-forest font-semibold text-lg">Claude Suggested Features</h3>
        <div class="grid md:grid-cols-2 gap-4">
            @foreach($result['features'] ?? [] as $feature)
                <div class="border border-tan/20 rounded-xl p-4">
                    <div class="flex items-start justify-between gap-3">
                        <h4 class="font-semibold text-forest">{{ $feature['title'] ?? 'Untitled Feature' }}</h4>
                        <span class="text-xs bg-cream text-forest px-2 py-1 rounded-full border border-tan/20">{{ $feature['effort_hours'] ?? 'N/A' }} hrs</span>
                    </div>
                    <p class="text-sm text-gray-600 mt-2">{{ $feature['description'] ?? '' }}</p>
                    <p class="text-sm text-midgreen mt-2"><strong>Why unique:</strong> {{ $feature['uniqueness'] ?? '' }}</p>
                    <p class="text-sm text-gray-500 mt-2"><strong>Plan:</strong> {{ $feature['implementation_plan'] ?? '' }}</p>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
