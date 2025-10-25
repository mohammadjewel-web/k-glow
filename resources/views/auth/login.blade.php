@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<h2 class="text-2xl font-bold text-gray-800 mb-6">Login Your Account 👋</h2>

<form method="POST" action="{{ route('login') }}" class="space-y-5">
    @csrf

    <!-- Email -->
    <div>
        <label for="email" class="block text-sm font-medium text-gray-600">Email or Phone</label>
        <input id="email" name="email" type="text" required autofocus
            class="mt-1 w-full rounded-lg border-gray-300 focus:border-brand-orange focus:ring-brand-orange">
    </div>

    <!-- Password -->
    <div>
        <label for="password" class="block text-sm font-medium text-gray-600">Password</label>
        <input id="password" name="password" type="password" required
            class="mt-1 w-full rounded-lg border-gray-300 focus:border-brand-orange focus:ring-brand-orange">
    </div>

    <!-- Remember + Forgot -->
    <div class="flex items-center justify-between">
        <label class="flex items-center space-x-2">
            <input type="checkbox" name="remember" class="rounded text-brand-orange">
            <span class="text-sm text-gray-600">Remember me</span>
        </label>
        <a href="{{ route('password.request') }}" class="text-sm text-brand-orange hover:underline">Forgot password?</a>
    </div>

    <!-- Submit -->
    <button type="submit"
        class="w-full rounded-lg bg-brand-orange py-3 text-white font-semibold shadow hover:bg-orange-600 transition">
        Sign In
    </button>

    <!-- Redirect -->
    <p class="text-sm text-gray-600 text-center mt-4">
        Don’t have an account?
        <a href="{{ route('register') }}" class="font-semibold text-brand-orange hover:underline">Register</a>
    </p>
</form>
@endsection