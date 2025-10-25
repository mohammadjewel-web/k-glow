@extends('layouts.auth')

@section('title', 'Register')

@section('content')
<h2 class="text-2xl font-bold text-gray-800 mb-6">Create Your Account ✨</h2>

<form method="POST" action="{{ route('register') }}" class="space-y-5">
    @csrf

    <!-- Name -->
    <div>
        <label for="name" class="block text-sm font-medium text-gray-600">Full Name</label>
        <input id="name" name="name" type="text" required
            class="mt-1 w-full rounded-lg border-gray-300 focus:border-brand-orange focus:ring-brand-orange">
    </div>

    <!-- Email -->
    <div>
        <label for="email" class="block text-sm font-medium text-gray-600">Email</label>
        <input id="email" name="email" type="email" required
            class="mt-1 w-full rounded-lg border-gray-300 focus:border-brand-orange focus:ring-brand-orange">
    </div>

    <!-- Phone -->
    <div>
        <label for="phone" class="block text-sm font-medium text-gray-600">Phone</label>
        <input id="phone" name="phone" type="text" required
            class="mt-1 w-full rounded-lg border-gray-300 focus:border-brand-orange focus:ring-brand-orange">
    </div>

    <!-- Password -->
    <div>
        <label for="password" class="block text-sm font-medium text-gray-600">Password</label>
        <input id="password" name="password" type="password" required
            class="mt-1 w-full rounded-lg border-gray-300 focus:border-brand-orange focus:ring-brand-orange">
    </div>

    <!-- Confirm Password -->
    <div>
        <label for="password_confirmation" class="block text-sm font-medium text-gray-600">Confirm Password</label>
        <input id="password_confirmation" name="password_confirmation" type="password" required
            class="mt-1 w-full rounded-lg border-gray-300 focus:border-brand-orange focus:ring-brand-orange">
    </div>

    <!-- Submit -->
    <button type="submit"
        class="w-full rounded-lg bg-brand-orange py-3 text-white font-semibold shadow hover:bg-orange-600 transition">
        Register
    </button>

    <!-- Redirect -->
    <p class="text-sm text-gray-600 text-center mt-4">
        Already have an account?
        <a href="{{ route('login') }}" class="font-semibold text-brand-orange hover:underline">Login</a>
    </p>
</form>
@endsection