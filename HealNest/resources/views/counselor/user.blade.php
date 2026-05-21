@extends('layouts.app')
@section('title', $user->name . ' – Counselor View')
@section('page-title', 'User: ' . $user->name)

@section('content')
<div class="space-y-6">

    {{-- User Info --}}
    <div class="bg-white rounded-2xl shadow-sm border border-tan/20 p-6 flex flex-wrap gap-6 items-center">
        <div class="w-16 h-16 rounded-full bg-midgreen flex items-center justify-center text-white text-2xl font-bold font-heading">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
        <div>
            <h2 class="font-heading text-xl font-bold text-forest">{{ $user->name }}</h2>
            <p class="text-gray-500 text-sm">{{ $user->email }}</p>
            <div class="flex gap-3 mt-1">
                <span class="text-xs bg-tan/20 text-earthbrown px-2 py-0.5 rounded-full">Age: {{ $user->age ?? 'N/A' }}</span>
                <span class="text-xs bg-lightgreen/20 text-midgreen px-2 py-0.5 rounded-full">{{ ucfirst($user->role) }}</span>
            </div>
        </div>
        <div class="ml-auto">
            <div class="flex items-center gap-3 justify-end">
                <a href="{{ route('messages.index', ['with' => $user->_id]) }}" class="text-sm bg-forest text-white px-4 py-2 rounded-lg hover:bg-midgreen transition-colors">Message User</a>
                <a href="{{ route('counselor.index') }}" class="text-sm text-midgreen hover:underline">← Back</a>
            </div>
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-6">
        {{-- Assessments --}}
        <div class="bg-white rounded-2xl shadow-sm border border-tan/20 p-6">
            <h3 class="font-heading text-forest font-semibold text-lg mb-4">Assessment History</h3>
            @forelse($assessments as $a)
            <div class="flex items-center justify-between py-3 border-b border-tan/10 last:border-0">
                <div>
                    <p class="text-sm font-medium text-forest">{{ $a->type }}</p>
                    <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($a->taken_at)->format('M d, Y') }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-sm font-bold text-forest">{{ $a->score }}</span>
                    <x-risk-badge :level="$a->risk_level"/>
                </div>
            </div>
            @empty
                <p class="text-gray-400 text-sm">No assessments yet.</p>
            @endforelse
        </div>

        {{-- Alerts --}}
        <div class="bg-white rounded-2xl shadow-sm border border-tan/20 p-6">
            <h3 class="font-heading text-forest font-semibold text-lg mb-4">Alerts</h3>
            @forelse($alerts as $alert)
            <div class="flex items-center justify-between py-3 border-b border-tan/10 last:border-0">
                <div>
                    <x-risk-badge :level="$alert->risk_level"/>
                    <p class="text-xs text-gray-400 mt-1">{{ \Carbon\Carbon::parse($alert->created_at)->diffForHumans() }}</p>
                </div>
                <span class="text-xs px-2 py-1 rounded-full {{ $alert->status === 'open' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                    {{ ucfirst($alert->status) }}
                </span>
            </div>
            @empty
                <p class="text-gray-400 text-sm">No alerts.</p>
            @endforelse
        </div>
    </div>

    {{-- Session Notes --}}
    <div class="bg-white rounded-2xl shadow-sm border border-tan/20 p-6">
        <h3 class="font-heading text-forest font-semibold text-lg mb-4">Session Notes</h3>

        <form method="POST" action="{{ route('counselor.note', $user->_id) }}" class="mb-6">
            @csrf
            <textarea name="content" rows="3" required
                      class="w-full border border-tan/40 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-midgreen bg-cream resize-none"
                      placeholder="Add a session note..."></textarea>
            <button type="submit"
                    class="mt-2 bg-forest text-white px-5 py-2 rounded-lg text-sm font-semibold hover:bg-midgreen transition-colors">
                Save Note
            </button>
        </form>

        @forelse($notes as $note)
        <div class="border-l-4 border-midgreen pl-4 py-2 mb-3">
            <p class="text-sm text-gray-700">{{ $note->content }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ \Carbon\Carbon::parse($note->created_at)->format('M d, Y H:i') }}</p>
        </div>
        @empty
            <p class="text-gray-400 text-sm">No notes yet.</p>
        @endforelse
    </div>
</div>
@endsection
