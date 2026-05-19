@extends('layouts.auth')
@section('title', 'Login – HealNest')

@section('content')
<h2 class="font-heading text-2xl font-bold text-forest mb-6 text-center">Welcome Back</h2>

<form method="POST" action="{{ route('login') }}" class="space-y-4">
    @csrf

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required autofocus
               class="w-full border border-tan/40 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-midgreen bg-cream"
               placeholder="you@example.com">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
        <input type="password" name="password" required
               class="w-full border border-tan/40 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-midgreen bg-cream">
    </div>

    <div class="flex items-center gap-2">
        <input type="checkbox" name="remember" id="remember" class="accent-midgreen">
        <label for="remember" class="text-sm text-gray-600">Remember me</label>
    </div>

    <button type="submit"
            class="w-full bg-forest text-white py-3 rounded-xl font-semibold hover:bg-midgreen transition-colors">
        Sign In
    </button>
</form>

<p class="text-center text-sm text-gray-500 mt-4">
    Don't have an account?
    <a href="{{ route('register') }}" class="text-midgreen font-semibold hover:underline">Register</a>
</p>
@endsection
