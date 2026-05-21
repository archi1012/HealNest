@extends('layouts.app')
@section('title', 'Appointments – HealNest')
@section('page-title', 'Appointments')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        <x-stat-card label="Upcoming" :value="$upcomingCount" icon="📅" sub="Pending or confirmed"/>
        <x-stat-card label="Completed" :value="$completedCount" icon="✅" sub="Finished sessions"/>
        <x-stat-card label="Available Counselors" :value="$counselors->count()" icon="👩‍⚕️" sub="Ready to book"/>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 bg-white rounded-2xl shadow-sm border border-tan/20 p-6">
            <h3 class="font-heading text-forest font-semibold text-lg mb-4">Book an Appointment</h3>
            <form method="POST" action="{{ route('appointments.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Counselor</label>
                    <select name="counselor_id" class="w-full border border-tan/30 rounded-lg px-4 py-2.5 bg-cream focus:outline-none focus:ring-2 focus:ring-midgreen">
                        <option value="">Select a counselor</option>
                        @foreach($counselors as $counselor)
                            <option value="{{ $counselor->_id }}">{{ $counselor->name }}{{ $counselor->age ? ' • Age ' . $counselor->age : '' }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Preferred Time</label>
                    <input type="datetime-local" name="scheduled_at"
                           class="w-full border border-tan/30 rounded-lg px-4 py-2.5 bg-cream focus:outline-none focus:ring-2 focus:ring-midgreen">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Meeting Type</label>
                    <select name="meeting_type" class="w-full border border-tan/30 rounded-lg px-4 py-2.5 bg-cream focus:outline-none focus:ring-2 focus:ring-midgreen">
                        <option value="virtual">Virtual</option>
                        <option value="in-person">In person</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Reason</label>
                    <textarea name="reason" rows="4" placeholder="Tell the counselor why you'd like to meet..."
                              class="w-full border border-tan/30 rounded-lg px-4 py-2.5 bg-cream focus:outline-none focus:ring-2 focus:ring-midgreen"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Additional Notes</label>
                    <textarea name="notes" rows="3" placeholder="Any extra context or preferences?"
                              class="w-full border border-tan/30 rounded-lg px-4 py-2.5 bg-cream focus:outline-none focus:ring-2 focus:ring-midgreen"></textarea>
                </div>

                <button type="submit" class="w-full bg-forest text-white px-4 py-2.5 rounded-lg text-sm font-semibold hover:bg-midgreen transition-colors">
                    Request Appointment
                </button>
            </form>
        </div>

        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-tan/20 p-6">
            <div class="flex items-center justify-between mb-4 gap-3">
                <div>
                    <h3 class="font-heading text-forest font-semibold text-lg">Your Appointment Requests</h3>
                    <p class="text-sm text-gray-500">Track your upcoming and past sessions.</p>
                </div>
                <a href="{{ route('connect.counselor') }}" class="text-sm text-midgreen font-semibold hover:underline">Need help choosing?</a>
            </div>

            @if($appointments->count())
                <div class="space-y-3">
                    @foreach($appointments as $appointment)
                        @php
                            $counselor = $counselors->firstWhere('_id', $appointment->counselor_id);
                        @endphp
                        <div class="border border-tan/20 rounded-xl p-4 hover:border-midgreen/40 transition-colors">
                            <div class="flex flex-wrap items-start justify-between gap-4">
                                <div>
                                    <div class="flex flex-wrap items-center gap-2 mb-1">
                                        <p class="font-semibold text-forest">{{ $counselor->name ?? 'Counselor' }}</p>
                                        <span class="text-xs px-2 py-0.5 rounded-full font-semibold {{ $appointment->status === 'completed' ? 'bg-green-100 text-green-700' : ($appointment->status === 'confirmed' ? 'bg-blue-100 text-blue-700' : ($appointment->status === 'cancelled' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700')) }}">
                                            {{ ucfirst($appointment->status) }}
                                        </span>
                                        <span class="text-xs px-2 py-0.5 rounded-full bg-cream text-forest border border-tan/20">{{ ucfirst($appointment->meeting_type) }}</span>
                                    </div>
                                    <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('M d, Y h:i A') }}</p>
                                    <p class="text-sm text-gray-500 mt-2 max-w-2xl">{{ $appointment->reason }}</p>
                                    @if($appointment->notes)
                                        <p class="text-xs text-gray-400 mt-2">Notes: {{ $appointment->notes }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 text-gray-400">
                    <p class="text-4xl mb-2">📅</p>
                    <p>No appointments booked yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
