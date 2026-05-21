@extends('layouts.app')
@section('title', 'My Profile – HealNest')
@section('page-title', 'My Profile')

@section('content')
<div class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-tan/20">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-full bg-midgreen flex items-center justify-center text-white text-2xl font-bold">
                    {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                </div>
                <div>
                    <h2 class="font-heading text-forest text-2xl font-semibold">{{ $user->name }}</h2>
                    <p class="text-sm text-gray-500">{{ ucfirst($user->role ?? 'user') }} account</p>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-2 gap-3 text-center">
                <div class="rounded-lg bg-cream p-3">
                    <p class="text-xs text-gray-500">Mood Logs</p>
                    <p class="text-xl font-semibold text-forest">{{ $moodCount }}</p>
                </div>
                <div class="rounded-lg bg-cream p-3">
                    <p class="text-xs text-gray-500">Assessments</p>
                    <p class="text-xl font-semibold text-forest">{{ $assessmentCount }}</p>
                </div>
                <div class="rounded-lg bg-cream p-3 col-span-2">
                    <p class="text-xs text-gray-500">Open Alerts</p>
                    <p class="text-xl font-semibold text-forest">{{ $openAlerts }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-tan/20">
            <h3 class="font-heading text-forest font-semibold text-lg mb-4">Account Details</h3>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between gap-4">
                    <dt class="text-gray-500">User ID</dt>
                    <dd class="text-forest font-medium break-all text-right">{{ (string) $user->_id }}</dd>
                </div>
                <div class="flex justify-between gap-4">
                    <dt class="text-gray-500">Email</dt>
                    <dd class="text-forest font-medium text-right">{{ $user->email }}</dd>
                </div>
                <div class="flex justify-between gap-4">
                    <dt class="text-gray-500">Age</dt>
                    <dd class="text-forest font-medium text-right">{{ $user->age ?? 'Not set' }}</dd>
                </div>
                <div class="flex justify-between gap-4">
                    <dt class="text-gray-500">Member Since</dt>
                    <dd class="text-forest font-medium text-right">{{ $joinedAt ? $joinedAt->format('M d, Y') : 'Unknown' }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-tan/20">
            <h3 class="font-heading text-forest font-semibold text-lg mb-4">Edit Profile</h3>
            <form method="POST" action="{{ route('profile.update') }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                               class="w-full rounded-lg border border-tan/30 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-midgreen/40 focus:border-midgreen">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                               class="w-full rounded-lg border border-tan/30 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-midgreen/40 focus:border-midgreen">
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Age</label>
                        <input type="number" name="age" value="{{ old('age', $user->age) }}" min="1" max="120"
                               class="w-full rounded-lg border border-tan/30 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-midgreen/40 focus:border-midgreen">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                        <input type="text" value="{{ ucfirst($user->role ?? 'user') }}" readonly
                               class="w-full rounded-lg border border-tan/30 bg-gray-50 px-4 py-2.5 text-gray-500">
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                        <input type="password" name="current_password" autocomplete="current-password"
                               class="w-full rounded-lg border border-tan/30 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-midgreen/40 focus:border-midgreen">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                        <input type="password" name="new_password" autocomplete="new-password"
                               class="w-full rounded-lg border border-tan/30 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-midgreen/40 focus:border-midgreen">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                    <input type="password" name="new_password_confirmation" autocomplete="new-password"
                           class="w-full rounded-lg border border-tan/30 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-midgreen/40 focus:border-midgreen">
                </div>

                <div class="flex items-center justify-between gap-4 pt-2">
                    <p class="text-sm text-gray-500">Keep your profile updated for better tracking and support.</p>
                    <button type="submit"
                            class="bg-forest text-white px-5 py-2.5 rounded-lg text-sm font-semibold hover:bg-midgreen transition-colors">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-tan/20">
            <h3 class="font-heading text-forest font-semibold text-lg mb-4">Recent Mood Logs</h3>
            @if($recentMoods->count())
                <div class="space-y-3">
                    @foreach($recentMoods as $mood)
                        <div class="flex items-center justify-between rounded-lg border border-tan/20 px-4 py-3">
                            <div>
                                <p class="font-semibold text-forest">Mood {{ $mood->mood }}/5</p>
                                <p class="text-xs text-gray-500">{{ optional($mood->logged_at)->format('M d, Y h:i A') ?? 'Logged recently' }}</p>
                            </div>
                            <div class="text-right text-sm text-gray-600">
                                {{ $mood->note ? \Illuminate\Support\Str::limit($mood->note, 40) : 'No note' }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500">No mood logs yet. Start by logging your first mood from the dashboard.</p>
            @endif
        </div>

        @if($latestAssessment)
        <div class="bg-white rounded-xl p-6 shadow-sm border border-tan/20">
            <h3 class="font-heading text-forest font-semibold text-lg mb-4">Latest Assessment</h3>
            <div class="grid md:grid-cols-3 gap-4 text-sm">
                <div class="rounded-lg bg-cream p-4">
                    <p class="text-gray-500">Type</p>
                    <p class="font-semibold text-forest">{{ $latestAssessment->type }}</p>
                </div>
                <div class="rounded-lg bg-cream p-4">
                    <p class="text-gray-500">Score</p>
                    <p class="font-semibold text-forest">{{ $latestAssessment->score }}</p>
                </div>
                <div class="rounded-lg bg-cream p-4">
                    <p class="text-gray-500">Risk Level</p>
                    <p class="font-semibold text-forest">{{ ucfirst($latestAssessment->risk_level ?? 'n/a') }}</p>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
