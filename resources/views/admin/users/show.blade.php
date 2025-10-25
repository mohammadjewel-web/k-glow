@extends('layouts.admin')

@section('title', 'User Details')
@section('page-title', 'User: ' . $user->name)

@section('content')
<div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl shadow-lg mb-8 p-6 text-white">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold mb-2">User Details</h1>
            <p class="text-orange-100">Comprehensive view of user information</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.users.edit-roles', $user->id) }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg font-medium transition-all duration-300 hover:scale-105">
                <i class="fas fa-user-tag mr-2"></i> Edit Roles
            </a>
            <a href="{{ route('admin.users.index') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg font-medium transition-all duration-300 hover:scale-105">
                <i class="fas fa-list mr-2"></i> Back to Users
            </a>
        </div>
    </div>
</div>

<!-- User Information -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    <!-- User Profile Card -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="text-center">
                @if($user->avatar)
                    <img src="{{ asset('admin-assets/avatars/' . $user->avatar) }}" 
                         class="w-24 h-24 rounded-full object-cover mx-auto mb-4 shadow-lg" alt="User Avatar"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg" style="display: none;">
                        <i class="fas fa-user text-gray-400 text-2xl"></i>
                    </div>
                @else
                    <div class="w-24 h-24 bg-gradient-to-br from-orange-100 to-orange-200 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <i class="fas fa-user text-orange-600 text-2xl"></i>
                    </div>
                @endif
                
                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $user->name }}</h3>
                <p class="text-gray-600 mb-4">{{ $user->email }}</p>
                
                @if($user->phone)
                    <p class="text-gray-600 mb-4">
                        <i class="fas fa-phone mr-2"></i>{{ $user->phone }}
                    </p>
                @endif
                
                <div class="flex justify-center space-x-2 mb-4">
                    @if($user->roles->count() > 0)
                        @foreach($user->roles as $role)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                {{ $role->name == 'admin' ? 'bg-red-100 text-red-800' : 
                                   ($role->name == 'manager' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                <i class="fas fa-{{ $role->name == 'admin' ? 'crown' : ($role->name == 'manager' ? 'user-tie' : 'user') }} mr-1"></i>
                                {{ ucfirst($role->name) }}
                            </span>
                        @endforeach
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                            <i class="fas fa-user mr-1"></i>
                            No Role
                        </span>
                    @endif
                </div>
                
                <div class="text-sm text-gray-500">
                    <p><i class="fas fa-calendar mr-2"></i>Joined: {{ $user->created_at->format('M d, Y') }}</p>
                    @if($user->email_verified_at)
                        <p class="text-green-600 mt-2"><i class="fas fa-check-circle mr-2"></i>Email Verified</p>
                    @else
                        <p class="text-yellow-600 mt-2"><i class="fas fa-clock mr-2"></i>Email Not Verified</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- User Details -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">User Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                    <p class="text-gray-900">{{ $user->name }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <p class="text-gray-900">{{ $user->email }}</p>
                </div>
                
                @if($user->phone)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                    <p class="text-gray-900">{{ $user->phone }}</p>
                </div>
                @endif
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">User ID</label>
                    <p class="text-gray-900">#{{ $user->id }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Registration Date</label>
                    <p class="text-gray-900">{{ $user->created_at->format('M d, Y \a\t g:i A') }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Last Updated</label>
                    <p class="text-gray-900">{{ $user->updated_at->format('M d, Y \a\t g:i A') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- User Orders -->
@if($user->orders && $user->orders->count() > 0)
<div class="bg-white rounded-xl shadow-lg p-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-semibold text-gray-900">Recent Orders</h3>
        <span class="text-sm text-gray-500">{{ $user->orders->count() }} total orders</span>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order #</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($user->orders->take(5) as $order)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        #{{ $order->order_number }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ $order->status == 'completed' ? 'bg-green-100 text-green-800' : 
                               ($order->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        ৳{{ number_format($order->total_amount, 2) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $order->created_at->format('M d, Y') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    @if($user->orders->count() > 5)
    <div class="mt-4 text-center">
        <a href="{{ route('admin.orders.index', ['user' => $user->id]) }}" 
           class="text-orange-600 hover:text-orange-800 text-sm font-medium">
            View all {{ $user->orders->count() }} orders
        </a>
    </div>
    @endif
</div>
@else
<div class="bg-white rounded-xl shadow-lg p-6">
    <div class="text-center">
        <i class="fas fa-shopping-cart text-gray-400 text-4xl mb-4"></i>
        <h3 class="text-lg font-medium text-gray-900 mb-2">No Orders Yet</h3>
        <p class="text-gray-500">This user hasn't placed any orders yet.</p>
    </div>
</div>
@endif
@endsection
