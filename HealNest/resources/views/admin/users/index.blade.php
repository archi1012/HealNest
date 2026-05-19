@extends('layouts.app')
@section('title', 'User Management – HealNest')
@section('page-title', 'User Management')

@section('content')
<div class="space-y-4">

    {{-- Header --}}
    <div class="flex flex-wrap items-center justify-between gap-3">
        <form method="GET" class="flex flex-wrap gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name..."
                   class="border border-tan/40 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-midgreen bg-cream">
            <select name="role" class="border border-tan/40 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-midgreen bg-cream">
                <option value="">All Roles</option>
                @foreach(['user','parent','counselor','admin'] as $r)
                    <option value="{{ $r }}" {{ request('role') === $r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>
                @endforeach
            </select>
            <button type="submit" class="bg-forest text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-midgreen transition-colors">
                Filter
            </button>
        </form>
        <a href="{{ route('admin.users.create') }}"
           class="bg-tan text-forest px-5 py-2 rounded-xl text-sm font-bold hover:bg-lightgreen transition-colors">
            + Create User
        </a>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-tan/20 p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-tan/20 text-left text-gray-500 text-xs uppercase">
                        <th class="pb-3 pr-4">User</th>
                        <th class="pb-3 pr-4">Age</th>
                        <th class="pb-3 pr-4">Role</th>
                        <th class="pb-3 pr-4">Joined</th>
                        <th class="pb-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-tan/10">
                    @forelse($users as $user)
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
                            @php
                                $roleColors = ['admin'=>'bg-red-100 text-red-700','counselor'=>'bg-blue-100 text-blue-700','parent'=>'bg-purple-100 text-purple-700','user'=>'bg-green-100 text-green-700'];
                            @endphp
                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $roleColors[$user->role] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="py-3 pr-4 text-gray-500 text-xs">
                            {{ \Carbon\Carbon::parse($user->created_at)->format('M d, Y') }}
                        </td>
                        <td class="py-3">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('admin.users.edit', $user->_id) }}"
                                   class="text-xs text-midgreen font-semibold hover:underline">Edit</a>
                                <form method="POST" action="{{ route('admin.users.delete', $user->_id) }}"
                                      onsubmit="return confirm('Delete {{ $user->name }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-semibold">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                        <tr><td colspan="5" class="py-10 text-center text-gray-400">No users found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $users->withQueryString()->links() }}</div>
    </div>
</div>
@endsection
