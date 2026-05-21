@extends('layouts.app')
@section('title', 'Manage Appointments – HealNest')
@section('page-title', 'Manage Appointments')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <x-stat-card label="Pending" :value="$pendingCount" icon="⏳" sub="Awaiting review"/>
        <x-stat-card label="Confirmed" :value="$confirmedCount" icon="✅" sub="Scheduled"/>
        <x-stat-card label="Completed" :value="$completedCount" icon="🏁" sub="Finished"/>
        <x-stat-card label="Cancelled" :value="$cancelledCount" icon="❌" sub="Not kept"/>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-tan/20 p-6 space-y-5">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h3 class="font-heading text-forest font-semibold text-lg">All Appointment Requests</h3>
            <form method="GET" class="flex gap-2">
                <select name="status" class="border border-tan/30 rounded-lg px-3 py-2 text-sm bg-cream">
                    <option value="">All Statuses</option>
                    @foreach(['pending','confirmed','completed','cancelled'] as $status)
                        <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
                <button class="bg-forest text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-midgreen transition-colors">Filter</button>
            </form>
        </div>

        @if($appointments->count())
            <div class="space-y-3">
                @foreach($appointments as $appointment)
                    @php
                        $user = $users->get((string) $appointment->user_id);
                        $counselor = $users->get((string) $appointment->counselor_id);
                    @endphp
                    <div class="border border-tan/20 rounded-xl p-4 hover:border-midgreen/40 transition-colors">
                        <div class="flex flex-wrap items-start justify-between gap-4">
                            <div>
                                <div class="flex flex-wrap items-center gap-2 mb-1">
                                    <p class="font-semibold text-forest">{{ $user->name ?? 'User' }} → {{ $counselor->name ?? 'Counselor' }}</p>
                                    <span class="text-xs px-2 py-0.5 rounded-full font-semibold {{ $appointment->status === 'completed' ? 'bg-green-100 text-green-700' : ($appointment->status === 'confirmed' ? 'bg-blue-100 text-blue-700' : ($appointment->status === 'cancelled' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700')) }}">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                    <span class="text-xs px-2 py-0.5 rounded-full bg-cream text-forest border border-tan/20">{{ ucfirst($appointment->meeting_type) }}</span>
                                </div>
                                <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('M d, Y h:i A') }}</p>
                                <p class="text-sm text-gray-600 mt-2">{{ $appointment->reason }}</p>
                                @if($appointment->notes)
                                    <p class="text-xs text-gray-400 mt-2">User notes: {{ $appointment->notes }}</p>
                                @endif
                                @if($appointment->counselor_notes)
                                    <p class="text-xs text-midgreen mt-1">Counselor notes: {{ $appointment->counselor_notes }}</p>
                                @endif
                            </div>

                            <form method="POST" action="{{ route('appointments.status', $appointment->_id) }}" class="min-w-[240px] space-y-2">
                                @csrf
                                @method('PUT')
                                <select name="status" class="w-full border border-tan/30 rounded-lg px-3 py-2 text-sm bg-cream">
                                    @foreach(['pending','confirmed','completed','cancelled'] as $status)
                                        <option value="{{ $status }}" {{ $appointment->status === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                                    @endforeach
                                </select>
                                <textarea name="counselor_notes" rows="2" placeholder="Optional counselor notes..."
                                          class="w-full border border-tan/30 rounded-lg px-3 py-2 text-sm bg-cream"></textarea>
                                <button type="submit" class="w-full bg-forest text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-midgreen transition-colors">
                                    Update Status
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
            <div>{{ $appointments->withQueryString()->links() }}</div>
        @else
            <div class="text-center py-12 text-gray-400">
                <p class="text-4xl mb-2">📅</p>
                <p>No appointment requests found.</p>
            </div>
        @endif
    </div>
</div>
@endsection
