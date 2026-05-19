@extends('layouts.auth')
@section('title', 'Register – HealNest')

@section('content')
<h2 class="font-heading text-2xl font-bold text-forest mb-6 text-center">Create Account</h2>

<form method="POST" action="{{ route('register') }}" class="space-y-4">
    @csrf

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
        <input type="text" name="name" value="{{ old('name') }}" required
               class="w-full border border-tan/40 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-midgreen bg-cream"
               placeholder="Your name">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required
               class="w-full border border-tan/40 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-midgreen bg-cream"
               placeholder="you@example.com">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Age <span class="text-gray-400">(15–30)</span></label>
        <input type="number" name="age" value="{{ old('age') }}" min="15" max="30" required
               class="w-full border border-tan/40 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-midgreen bg-cream">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
        <select name="role" class="w-full border border-tan/40 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-midgreen bg-cream">
            <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>User (Self-tracking)</option>
            <option value="counselor" {{ old('role') === 'counselor' ? 'selected' : '' }}>Counselor</option>
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
        <input type="password" name="password" required
               class="w-full border border-tan/40 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-midgreen bg-cream"
               placeholder="Min. 8 characters">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
        <input type="password" name="password_confirmation" required
               class="w-full border border-tan/40 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-midgreen bg-cream">
    </div>

    <button type="submit"
            class="w-full bg-forest text-white py-3 rounded-xl font-semibold hover:bg-midgreen transition-colors mt-2">
        Create Account
    </button>
</form>

<p class="text-center text-sm text-gray-500 mt-4">
    Already have an account?
    <a href="{{ route('login') }}" class="text-midgreen font-semibold hover:underline">Sign in</a>
</p>
@endsection
