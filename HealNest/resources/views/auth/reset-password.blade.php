@extends('layouts.auth')
@section('title', 'Reset Password – HealNest')

@section('content')
<h2 class="font-heading text-2xl font-bold text-forest mb-3 text-center">Create a New Password</h2>
<p class="text-sm text-gray-500 mb-6 text-center">Choose a strong password for your account.</p>

<form method="POST" action="{{ route('password.update') }}" class="space-y-4">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <input type="email" name="email" value="{{ old('email', $email) }}" required
               class="w-full border border-tan/40 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-midgreen bg-cream"
               placeholder="you@example.com">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
        <input type="password" name="password" required
               class="w-full border border-tan/40 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-midgreen bg-cream">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
        <input type="password" name="password_confirmation" required
               class="w-full border border-tan/40 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-midgreen bg-cream">
    </div>

    <button type="submit" class="w-full bg-forest text-white py-3 rounded-xl font-semibold hover:bg-midgreen transition-colors">
        Reset Password
    </button>
</form>
@endsection