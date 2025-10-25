@extends('layouts.admin')

@section('title', 'Coupon Details')
@section('page-title', 'Coupon: ' . $coupon->name)

@section('content')
<div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl shadow-lg mb-8 p-6 text-white">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold mb-2">Coupon Details</h1>
            <p class="text-orange-100">Comprehensive view of coupon information</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg font-medium transition-all duration-300 hover:scale-105">
                <i class="fas fa-edit mr-2"></i> Edit Coupon
            </a>
            <a href="{{ route('admin.coupons.index') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg font-medium transition-all duration-300 hover:scale-105">
                <i class="fas fa-list mr-2"></i> Back to Coupons
            </a>
        </div>
    </div>
</div>

<!-- Coupon Information -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    <!-- Coupon Overview Card -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="text-center">
                <div class="w-20 h-20 bg-gradient-to-br from-orange-100 to-orange-200 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <i class="fas fa-ticket-alt text-orange-600 text-2xl"></i>
                </div>
                
                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $coupon->name }}</h3>
                <p class="text-gray-600 mb-4 font-mono bg-gray-100 px-3 py-1 rounded">{{ $coupon->code }}</p>
                
                @if($coupon->description)
                    <p class="text-gray-600 mb-4 text-sm">{{ $coupon->description }}</p>
                @endif
                
                <div class="flex justify-center space-x-2 mb-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                        {{ $coupon->type == 'percentage' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                        <i class="fas fa-{{ $coupon->type == 'percentage' ? 'percent' : 'dollar-sign' }} mr-1"></i>
                        {{ $coupon->type == 'percentage' ? 'Percentage' : 'Fixed Amount' }}
                    </span>
                    
                    @if($coupon->is_active)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check mr-1"></i>
                            Active
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            <i class="fas fa-times mr-1"></i>
                            Inactive
                        </span>
                    @endif
                    
                    @if($coupon->is_public)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            <i class="fas fa-globe mr-1"></i>
                            Public
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                            <i class="fas fa-lock mr-1"></i>
                            Private
                        </span>
                    @endif
                </div>
                
                <div class="text-sm text-gray-500">
                    <p><i class="fas fa-calendar mr-2"></i>Created: {{ $coupon->created_at->format('M d, Y') }}</p>
                    @if($coupon->expires_at)
                        <p class="mt-2"><i class="fas fa-clock mr-2"></i>Expires: {{ \Carbon\Carbon::parse($coupon->expires_at)->format('M d, Y') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Coupon Details -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Coupon Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Coupon Code</label>
                    <p class="text-gray-900 font-mono bg-gray-100 px-3 py-2 rounded">{{ $coupon->code }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Coupon Name</label>
                    <p class="text-gray-900">{{ $coupon->name }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Discount Type</label>
                    <p class="text-gray-900">{{ ucfirst($coupon->type) }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Discount Value</label>
                    <p class="text-gray-900 font-semibold">
                        {{ $coupon->type == 'percentage' ? $coupon->value . '%' : '৳' . number_format($coupon->value, 2) }}
                    </p>
                </div>
                
                @if($coupon->minimum_amount)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Amount</label>
                    <p class="text-gray-900">৳{{ number_format($coupon->minimum_amount, 2) }}</p>
                </div>
                @endif
                
                @if($coupon->maximum_discount)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Maximum Discount</label>
                    <p class="text-gray-900">৳{{ number_format($coupon->maximum_discount, 2) }}</p>
                </div>
                @endif
                
                @if($coupon->usage_limit)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Usage Limit</label>
                    <p class="text-gray-900">{{ number_format($coupon->usage_limit) }}</p>
                </div>
                @endif
                
                @if($coupon->usage_limit_per_user)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Per User Limit</label>
                    <p class="text-gray-900">{{ number_format($coupon->usage_limit_per_user) }}</p>
                </div>
                @endif
                
                @if($coupon->starts_at)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                    <p class="text-gray-900">{{ \Carbon\Carbon::parse($coupon->starts_at)->format('M d, Y g:i A') }}</p>
                </div>
                @endif
                
                @if($coupon->expires_at)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Expiry Date</label>
                    <p class="text-gray-900">{{ \Carbon\Carbon::parse($coupon->expires_at)->format('M d, Y g:i A') }}</p>
                </div>
                @endif
            </div>
            
            @if($coupon->description)
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <p class="text-gray-900">{{ $coupon->description }}</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Usage Statistics -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <div class="flex items-center">
            <div class="bg-blue-100 p-3 rounded-lg">
                <i class="fas fa-chart-bar text-blue-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Usage</p>
                <p class="text-2xl font-bold text-gray-900">{{ $coupon->usages->count() }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <div class="flex items-center">
            <div class="bg-green-100 p-3 rounded-lg">
                <i class="fas fa-users text-green-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Unique Users</p>
                <p class="text-2xl font-bold text-gray-900">{{ $coupon->usages->pluck('user_id')->unique()->count() }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <div class="flex items-center">
            <div class="bg-yellow-100 p-3 rounded-lg">
                <i class="fas fa-percentage text-yellow-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Usage Rate</p>
                <p class="text-2xl font-bold text-gray-900">
                    {{ $coupon->usage_limit ? round(($coupon->usages->count() / $coupon->usage_limit) * 100, 1) : '∞' }}%
                </p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <div class="flex items-center">
            <div class="bg-purple-100 p-3 rounded-lg">
                <i class="fas fa-dollar-sign text-purple-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Discount</p>
                <p class="text-2xl font-bold text-gray-900">
                    ৳{{ number_format($coupon->usages->sum('discount_amount'), 2) }}
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Usage History -->
@if($coupon->usages && $coupon->usages->count() > 0)
<div class="bg-white rounded-xl shadow-lg p-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-semibold text-gray-900">Usage History</h3>
        <span class="text-sm text-gray-500">{{ $coupon->usages->count() }} total uses</span>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Discount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($coupon->usages->take(10) as $usage)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-user text-gray-400 text-sm"></i>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $usage->user ? $usage->user->name : 'Guest User' }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $usage->user ? $usage->user->email : 'N/A' }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        @if($usage->order)
                            <a href="{{ route('admin.orders.show', $usage->order->id) }}" class="text-blue-600 hover:text-blue-800">
                                #{{ $usage->order->order_number }}
                            </a>
                        @else
                            <span class="text-gray-400">No Order</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        ৳{{ number_format($usage->discount_amount, 2) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $usage->created_at->format('M d, Y g:i A') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    @if($coupon->usages->count() > 10)
    <div class="mt-4 text-center">
        <span class="text-sm text-gray-500">Showing last 10 uses of {{ $coupon->usages->count() }} total</span>
    </div>
    @endif
</div>
@else
<div class="bg-white rounded-xl shadow-lg p-6">
    <div class="text-center">
        <i class="fas fa-chart-line text-gray-400 text-4xl mb-4"></i>
        <h3 class="text-lg font-medium text-gray-900 mb-2">No Usage Yet</h3>
        <p class="text-gray-500">This coupon hasn't been used by any customers yet.</p>
    </div>
</div>
@endif
@endsection
