@extends('layouts.app')
@section('title', 'Counselor Dashboard – HealNest')
@section('page-title', 'Counselor Dashboard')

@section('content')
<div class="space-y-6">

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        <x-stat-card label="Open Alerts" :value="$alerts->count()" icon="🚨" sub="Require attention"/>
        <x-stat-card label="Flagged Users" :value="$users->count()" icon="👥" sub="With open alerts"/>
        <x-stat-card label="Resolved Today" value="—" icon="✅" sub="Alerts resolved"/>
    </div>

    {{-- Flagged Users --}}
    <div class="bg-white rounded-2xl shadow-sm border border-tan/20 p-6">
        <h3 class="font-heading text-forest font-semibold text-lg mb-4">Flagged Users</h3>
        @if($users->count())
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-tan/20 text-left text-gray-500 text-xs uppercase">
                        <th class="pb-3 pr-4">User</th>
                        <th class="pb-3 pr-4">Age</th>
                        <th class="pb-3 pr-4">Open Alerts</th>
                        <th class="pb-3">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-tan/10">
                    @foreach($users as $user)
                    @php $userAlerts = $alerts->where('user_id', (string)$user->_id); @endphp
                    <tr class="hover:bg-cream/50">
                        <td class="py-3 pr-4">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-midgreen flex items-center justify-center text-white text-xs font-bold">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-medium text-forest">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 pr-4 text-gray-600">{{ $user->age ?? '—' }}</td>
                        <td class="py-3 pr-4">
                            @foreach($userAlerts as $alert)
                                <x-risk-badge :level="$alert->risk_level"/>
                            @endforeach
                        </td>
                        <td class="py-3">
                            <a href="{{ route('counselor.user', $user->_id) }}"
                               class="bg-forest text-white px-3 py-1.5 rounded-lg text-xs font-semibold hover:bg-midgreen transition-colors">
                                View Profile
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
            <div class="text-center py-12 text-gray-400">
                <p class="text-4xl mb-2">✅</p>
                <p>No flagged users at this time.</p>
            </div>
        @endif
    </div>

    {{-- All Open Alerts --}}
    <div class="bg-white rounded-2xl shadow-sm border border-tan/20 p-6">
        <h3 class="font-heading text-forest font-semibold text-lg mb-4">Open Alerts</h3>
        @if($alerts->count())
        <div class="space-y-3">
            @foreach($alerts as $alert)
            <div class="flex items-center justify-between p-4 border border-tan/20 rounded-xl hover:border-midgreen/40">
                <div class="flex items-center gap-3">
                    <x-risk-badge :level="$alert->risk_level"/>
                    <div>
                        <p class="text-sm font-medium text-forest">User ID: {{ $alert->user_id }}</p>
                        <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($alert->created_at)->diffForHumans() }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('counselor.resolve', $alert->_id) }}">
                    @csrf
                    <button type="submit"
                            class="text-xs bg-lightgreen/20 text-midgreen px-3 py-1.5 rounded-lg hover:bg-lightgreen hover:text-white transition-colors font-semibold">
                        Resolve
                    </button>
                </form>
            </div>
            @endforeach
        </div>
        @else
            <p class="text-gray-400 text-center py-8">No open alerts.</p>
        @endif
    </div>
</div>
@endsection
