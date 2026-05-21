@extends('layouts.app')
@section('title', 'Edit User – HealNest')
@section('page-title', 'Edit User')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-tan/20 p-8">

        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('admin.users') }}" class="text-midgreen hover:underline text-sm">← Back to Users</a>
        </div>

        {{-- User Avatar Header --}}
        <div class="flex items-center gap-4 mb-6 p-4 bg-cream rounded-xl">
            <div class="w-12 h-12 rounded-full bg-midgreen flex items-center justify-center text-white text-lg font-bold">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <p class="font-heading font-semibold text-forest">{{ $user->name }}</p>
                <p class="text-xs text-gray-500">{{ $user->email }}</p>
                <p class="text-xs text-gray-400">Member since {{ \Carbon\Carbon::parse($user->created_at)->format('M d, Y') }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.users.update', $user->_id) }}" class="space-y-5">
            @csrf @method('PUT')

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full border border-tan/40 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-midgreen bg-cream">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Age</label>
                    <input type="number" name="age" value="{{ old('age', $user->age) }}" min="10" max="100" required
                           class="w-full border border-tan/40 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-midgreen bg-cream">
                    @error('age')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                       class="w-full border border-tan/40 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-midgreen bg-cream">
                @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                <select name="role" required class="w-full border border-tan/40 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-midgreen bg-cream">
                    @foreach(['user','parent','counselor','admin'] as $r)
                        <option value="{{ $r }}" {{ old('role', $user->role) === $r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    New Password <span class="text-gray-400 font-normal">(leave blank to keep current)</span>
                </label>
                <input type="password" name="password" minlength="8"
                       class="w-full border border-tan/40 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-midgreen bg-cream"
                       placeholder="Min. 8 characters">
                @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="flex-1 bg-forest text-white py-3 rounded-xl font-semibold hover:bg-midgreen transition-colors">
                    Save Changes
                </button>
                <a href="{{ route('admin.users') }}"
                   class="flex-1 text-center border border-gray-300 text-gray-600 py-3 rounded-xl font-semibold hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
