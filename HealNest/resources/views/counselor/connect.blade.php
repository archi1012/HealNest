@extends('layouts.app')
@section('title', 'Connect to a Counselor – HealNest')
@section('page-title', 'Connect to a Counselor')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-2xl shadow-sm border border-tan/20 p-6">
        <p class="text-sm text-gray-600">If you'd like to talk to someone, you can message a counselor inside HealNest or still use WhatsApp as a quick fallback.</p>
        <div class="mt-4">
            <a href="{{ route('messages.index') }}" class="inline-flex items-center bg-forest text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-midgreen transition-colors">Open Messages</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach($counselors as $c)
        <div class="bg-white border border-tan/20 rounded-xl p-4 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-midgreen flex items-center justify-center text-white font-semibold">{{ strtoupper(substr($c['name'],0,1)) }}</div>
                <div>
                    <p class="font-medium text-forest">{{ $c['name'] }}</p>
                    <p class="text-xs text-gray-500">{{ $c['specialty'] }}</p>
                </div>
            </div>
            <p class="text-sm text-gray-600 mt-3">{{ $c['bio'] }}</p>

            <div class="mt-4 flex items-center gap-2">
                {{-- Chat via WhatsApp --}}
                <a href="https://api.whatsapp.com/send?phone={{ urlencode($c['phone']) }}&text={{ urlencode('Hello, I would like to connect with a counselor from HealNest.') }}" target="_blank" rel="noopener noreferrer"
                   class="flex-1 text-center bg-forest text-white py-2 rounded-lg font-semibold hover:bg-midgreen transition-colors">
                    Chat
                </a>

                {{-- Placeholder: view profile --}}
                <a href="#" class="text-center border border-tan/20 px-3 py-2 rounded-lg text-sm text-forest hover:bg-cream transition-colors">Profile</a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
