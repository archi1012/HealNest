@extends('layouts.app')
@section('title', 'Counselor Management – HealNest')
@section('page-title', 'Counselor Management')

@section('content')
<div class="space-y-6">

    {{-- Stats Row --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <x-stat-card label="Total Counselors" :value="$counselors->count()" icon="👩‍⚕️"/>
        <x-stat-card label="Open Alerts" :value="$alertStats['open'] ?? 0" icon="🚨"/>
        <x-stat-card label="Resolved Alerts" :value="$alertStats['resolved'] ?? 0" icon="✅"/>
        <x-stat-card label="Active Cases" :value="$alertStats['open'] ?? 0" icon="📋"/>
    </div>

    {{-- Counselors Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-tan/20 p-6">
        <div class="flex items-center justify-between mb-5">
            <h3 class="font-heading text-forest font-semibold text-lg">All Counselors</h3>
            <a href="{{ route('admin.users.create') }}?role=counselor"
               class="bg-forest text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-midgreen transition-colors">
                + Add Counselor
            </a>
        </div>

        @if($counselors->count())
        <div class="grid md:grid-cols-2 gap-4">
            @foreach($counselors as $counselor)
            <div class="border border-tan/20 rounded-xl p-5 hover:border-midgreen/50 transition-colors">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-midgreen flex items-center justify-center text-white font-bold text-lg">
                            {{ strtoupper(substr($counselor->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-heading font-semibold text-forest">{{ $counselor->name }}</p>
                            <p class="text-xs text-gray-500">{{ $counselor->email }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">
                                Age: {{ $counselor->age ?? 'N/A' }} &bull;
                                Joined {{ \Carbon\Carbon::parse($counselor->created_at)->format('M Y') }}
                            </p>
                        </div>
                    </div>
                    <span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full font-semibold">Counselor</span>
                </div>

                <div class="flex gap-2 mt-4">
                    <a href="{{ route('counselor.user', $counselor->_id) }}"
                       class="flex-1 text-center text-xs bg-forest text-white py-2 rounded-lg font-semibold hover:bg-midgreen transition-colors">
                        View Profile
                    </a>
                    <a href="{{ route('admin.users.edit', $counselor->_id) }}"
                       class="flex-1 text-center text-xs border border-midgreen text-midgreen py-2 rounded-lg font-semibold hover:bg-midgreen hover:text-white transition-colors">
                        Edit
                    </a>
                    <form method="POST" action="{{ route('admin.counselors.demote', $counselor->_id) }}"
                          onsubmit="return confirm('Demote {{ $counselor->name }} to user?')">
                        @csrf
                        <button type="submit"
                                class="text-xs border border-red-300 text-red-500 px-3 py-2 rounded-lg hover:bg-red-50 transition-colors font-semibold">
                            Demote
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-16 text-gray-400">
            <p class="text-5xl mb-3">👩‍⚕️</p>
            <p class="font-medium">No counselors yet.</p>
            <a href="{{ route('admin.users.create') }}" class="text-midgreen text-sm hover:underline mt-1 inline-block">
                Create a counselor account
            </a>
        </div>
        @endif
    </div>

    {{-- Promote Existing Users --}}
    <div class="bg-white rounded-2xl shadow-sm border border-tan/20 p-6">
        <h3 class="font-heading text-forest font-semibold text-lg mb-4">Promote User to Counselor</h3>
        @php $regularUsers = \App\Models\User::where('role', 'user')->orderBy('name')->limit(10)->get(); @endphp
        @if($regularUsers->count())
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-tan/20 text-left text-gray-500 text-xs uppercase">
                        <th class="pb-3 pr-4">User</th>
                        <th class="pb-3 pr-4">Age</th>
                        <th class="pb-3">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-tan/10">
                    @foreach($regularUsers as $u)
                    <tr class="hover:bg-cream/50">
                        <td class="py-3 pr-4">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-lightgreen flex items-center justify-center text-white text-xs font-bold">
                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-medium text-forest text-xs">{{ $u->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $u->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 pr-4 text-gray-500 text-xs">{{ $u->age ?? '—' }}</td>
                        <td class="py-3">
                            <form method="POST" action="{{ route('admin.counselors.promote', $u->_id) }}">
                                @csrf
                                <button type="submit"
                                        class="text-xs bg-blue-100 text-blue-700 px-3 py-1.5 rounded-lg hover:bg-blue-200 transition-colors font-semibold">
                                    Promote
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
            <p class="text-gray-400 text-sm">No regular users available to promote.</p>
        @endif
    </div>
</div>
@endsection
