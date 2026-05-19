@extends('layouts.app')
@section('title', 'User Management – HealNest')
@section('page-title', 'User Management')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-tan/20 p-6">

    {{-- Filters --}}
    <form method="GET" class="flex flex-wrap gap-3 mb-6">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name..."
               class="border border-tan/40 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-midgreen bg-cream">
        <select name="role" class="border border-tan/40 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-midgreen bg-cream">
            <option value="">All Roles</option>
            @foreach(['user','counselor','admin'] as $r)
                <option value="{{ $r }}" {{ request('role') === $r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>
            @endforeach
        </select>
        <button type="submit" class="bg-forest text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-midgreen transition-colors">
            Filter
        </button>
    </form>

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
                        <form method="POST" action="{{ route('admin.users.role', $user->_id) }}" class="flex items-center gap-2">
                            @csrf
                            <select name="role" class="border border-tan/30 rounded px-2 py-1 text-xs bg-cream">
                                @foreach(['user','counselor','admin'] as $r)
                                    <option value="{{ $r }}" {{ $user->role === $r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="text-xs text-midgreen hover:underline">Save</button>
                        </form>
                    </td>
                    <td class="py-3 pr-4 text-gray-500 text-xs">
                        {{ \Carbon\Carbon::parse($user->created_at)->format('M d, Y') }}
                    </td>
                    <td class="py-3">
                        <form method="POST" action="{{ route('admin.users.delete', $user->_id) }}"
                              onsubmit="return confirm('Delete this user?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-semibold">Delete</button>
                        </form>
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
@endsection
