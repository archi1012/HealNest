@extends('layouts.auth')
@section('title', 'Login – HealNest')

@section('content')
<h2 class="font-heading text-2xl font-bold text-forest mb-6 text-center">Welcome Back</h2>

<form method="POST" action="/login" class="space-y-4" id="loginForm">
    @csrf

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <input type="email" name="email" id="emailInput" value="{{ old('email') }}" required autofocus
               class="w-full border border-tan/40 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-midgreen bg-cream"
               placeholder="you@example.com">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
        <input type="password" name="password" id="passwordInput" required
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
    <a href="/forgot-password" class="text-midgreen font-semibold hover:underline">Forgot your password?</a>
</p>

{{-- Quick Login Buttons --}}
<div class="mt-6">
    <div class="relative flex items-center gap-2 mb-3">
        <div class="flex-1 h-px bg-tan/30"></div>
        <span class="text-xs text-gray-400 font-medium">Quick Login</span>
        <div class="flex-1 h-px bg-tan/30"></div>
    </div>
    <div class="grid grid-cols-3 gap-2">
        <button type="button" onclick="fillCredentials('admin@healnest.com','admin1234')"
                class="flex flex-col items-center gap-1 p-3 rounded-xl border-2 border-red-200 bg-red-50 hover:border-red-400 hover:bg-red-100 transition-all group">
            <span class="text-xl">⚙️</span>
            <span class="text-xs font-bold text-red-700">Admin</span>
        </button>
        <button type="button" onclick="fillCredentials('counselor@healnest.com','counselor1234')"
                class="flex flex-col items-center gap-1 p-3 rounded-xl border-2 border-blue-200 bg-blue-50 hover:border-blue-400 hover:bg-blue-100 transition-all group">
            <span class="text-xl">👩‍⚕️</span>
            <span class="text-xs font-bold text-blue-700">Counselor</span>
        </button>
        <button type="button" onclick="fillCredentials('user@healnest.com','user1234')"
                class="flex flex-col items-center gap-1 p-3 rounded-xl border-2 border-green-200 bg-green-50 hover:border-green-400 hover:bg-green-100 transition-all group">
            <span class="text-xl">👤</span>
            <span class="text-xs font-bold text-green-700">User</span>
        </button>
    </div>
    <p class="text-center text-xs text-gray-400 mt-2">Click a role to auto-fill credentials</p>
</div>

<p class="text-center text-sm text-gray-500 mt-4">
    Don't have an account?
    <a href="/register" class="text-midgreen font-semibold hover:underline">Register</a>
</p>

<script>
function fillCredentials(email, password) {
    document.getElementById('emailInput').value = email;
    document.getElementById('passwordInput').value = password;
    document.getElementById('loginForm').requestSubmit();
}
</script>
@endsection
