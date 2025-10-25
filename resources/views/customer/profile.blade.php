@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">My Profile</h1>
            <p class="mt-2 text-gray-600">Manage your account information and preferences</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Profile Information -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Personal Information</h3>
                    </div>
                    <form method="POST" action="{{ route('customer.profile.update') }}" class="p-6">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-[--brand-orange] focus:border-[--brand-orange] sm:text-sm">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-[--brand-orange] focus:border-[--brand-orange] sm:text-sm">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-[--brand-orange] focus:border-[--brand-orange] sm:text-sm">
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="created_at" class="block text-sm font-medium text-gray-700">Member Since</label>
                                <input type="text" id="created_at" value="{{ $user->created_at->format('M d, Y') }}" 
                                       disabled class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-50 sm:text-sm">
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit" 
                                    class="px-4 py-2 text-sm font-medium text-white bg-[--brand-orange] border border-transparent rounded-md hover:bg-orange-600">
                                Update Profile
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Change Password -->
                <div class="mt-8 bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Change Password</h3>
                    </div>
                    <form method="POST" action="{{ route('password.update') }}" class="p-6">
                        @csrf
                        @method('PUT')
                        
                        <div class="space-y-6">
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                                <input type="password" name="current_password" id="current_password" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-[--brand-orange] focus:border-[--brand-orange] sm:text-sm">
                                @error('current_password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                                <input type="password" name="password" id="password" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-[--brand-orange] focus:border-[--brand-orange] sm:text-sm">
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-[--brand-orange] focus:border-[--brand-orange] sm:text-sm">
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit" 
                                    class="px-4 py-2 text-sm font-medium text-white bg-[--brand-orange] border border-transparent rounded-md hover:bg-orange-600">
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Account Summary -->
            <div class="space-y-6">
                <!-- Account Stats -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Account Summary</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Total Orders</span>
                                <span class="text-sm font-medium text-gray-900">{{ $user->orders()->count() }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Total Spent</span>
                                <span class="text-sm font-medium text-gray-900">${{ number_format($user->orders()->where('status', '!=', 'cancelled')->sum('total_amount'), 2) }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Member Since</span>
                                <span class="text-sm font-medium text-gray-900">{{ $user->created_at->format('M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Quick Actions</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <a href="{{ route('customer.dashboard') }}" 
                               class="flex items-center p-3 text-sm text-gray-700 hover:bg-gray-50 rounded-md">
                                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                </svg>
                                Dashboard
                            </a>
                            <a href="{{ route('customer.orders') }}" 
                               class="flex items-center p-3 text-sm text-gray-700 hover:bg-gray-50 rounded-md">
                                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                                My Orders
                            </a>
                            <a href="{{ route('shop') }}" 
                               class="flex items-center p-3 text-sm text-gray-700 hover:bg-gray-50 rounded-md">
                                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 7h13l-2-7M16 21a1 1 0 11-2 0 1 1 0 012 0zm-8 0a1 1 0 11-2 0 1 1 0 012 0z"></path>
                                </svg>
                                Continue Shopping
                            </a>
                            <a href="{{ route('contact') }}" 
                               class="flex items-center p-3 text-sm text-gray-700 hover:bg-gray-50 rounded-md">
                                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Contact Support
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Account Security -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Account Security</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Email Verified</p>
                                    <p class="text-sm text-gray-600">
                                        @if($user->email_verified_at)
                                            Verified on {{ $user->email_verified_at->format('M d, Y') }}
                                        @else
                                            Not verified
                                        @endif
                                    </p>
                                </div>
                                @if(!$user->email_verified_at)
                                    <form method="POST" action="{{ route('verification.send') }}">
                                        @csrf
                                        <button type="submit" class="text-sm text-[--brand-orange] hover:underline">
                                            Resend Verification
                                        </button>
                                    </form>
                                @endif
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Two-Factor Authentication</p>
                                    <p class="text-sm text-gray-600">Not enabled</p>
                                </div>
                                <button class="text-sm text-[--brand-orange] hover:underline">
                                    Enable
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
