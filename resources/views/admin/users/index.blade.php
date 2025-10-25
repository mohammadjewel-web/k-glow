@extends('layouts.admin')

@section('title', 'User Management')
@section('page-title', 'Users')

@section('content')
<style>
    .btn-add-user {
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        text-decoration: none;
    }
    
    .btn-add-user:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.05);
        color: white;
        text-decoration: none;
    }
    
    .btn-export-user {
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        text-decoration: none;
    }
    
    .btn-export-user:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.05);
        color: white;
        text-decoration: none;
    }
</style>

<!-- Header Section -->
<div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl shadow-lg mb-8 p-6 text-white">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold mb-2">User Management</h1>
            <p class="text-orange-100">Manage your users and their roles</p>
        </div>
        <div class="flex space-x-3">
            <button class="btn-export-user" onclick="exportUsers()">
                <i class="fas fa-download mr-2"></i> Export Users
            </button>
            <button class="btn-add-user" onclick="createUser()">
                <i class="fas fa-plus mr-2"></i> Add User
            </button>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <div class="flex items-center">
            <div class="bg-blue-100 p-3 rounded-lg">
                <i class="fas fa-users text-blue-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Users</p>
                <p class="text-2xl font-bold text-gray-900">{{ $users->total() }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <div class="flex items-center">
            <div class="bg-green-100 p-3 rounded-lg">
                <i class="fas fa-user-check text-green-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Active Users</p>
                <p class="text-2xl font-bold text-gray-900">{{ $users->where('email_verified_at', '!=', null)->count() }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <div class="flex items-center">
            <div class="bg-yellow-100 p-3 rounded-lg">
                <i class="fas fa-user-shield text-yellow-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Admins</p>
                <p class="text-2xl font-bold text-gray-900">{{ $users->filter(function($user) { return $user->hasRole('admin'); })->count() }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <div class="flex items-center">
            <div class="bg-purple-100 p-3 rounded-lg">
                <i class="fas fa-user-friends text-purple-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Customers</p>
                <p class="text-2xl font-bold text-gray-900">{{ $users->filter(function($user) { return $user->hasRole('user'); })->count() }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Advanced Filters -->
<div class="bg-white rounded-xl shadow-lg mb-8 p-6">
    <div class="flex flex-wrap items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Advanced Filters</h3>
        <button onclick="clearFilters()" class="text-orange-600 hover:text-orange-800 text-sm font-medium">
            <i class="fas fa-times mr-1"></i> Clear All
        </button>
    </div>

    <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
            <div class="flex">
                <input type="text" id="search" name="search" placeholder="Search users..." 
                       class="flex-1 px-3 py-2 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                       value="{{ request('search') }}">
                <button type="submit" 
                        class="px-4 py-2 bg-orange-600 text-white rounded-r-lg hover:bg-orange-700 transition-colors duration-200">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
            <select id="role" name="role" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                <option value="">All Roles</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="manager" {{ request('role') == 'manager' ? 'selected' : '' }}>Manager</option>
                <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Customer</option>
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
            <select id="status" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                <option value="">All Status</option>
                <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Verified</option>
                <option value="unverified" {{ request('status') == 'unverified' ? 'selected' : '' }}>Unverified</option>
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
            <select id="sort" name="sort" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name A-Z</option>
                <option value="email" {{ request('sort') == 'email' ? 'selected' : '' }}>Email A-Z</option>
            </select>
        </div>
    </form>
</div>

<!-- Users Table -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <i class="fas fa-user mr-1"></i> User
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <i class="fas fa-envelope mr-1"></i> Email
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <i class="fas fa-user-tag mr-1"></i> Role
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <i class="fas fa-shopping-cart mr-1"></i> Orders
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <i class="fas fa-toggle-on mr-1"></i> Status
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <i class="fas fa-calendar mr-1"></i> Joined
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <i class="fas fa-cog mr-1"></i> Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50 transition-colors duration-200">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            @if($user->avatar)
                                <img src="{{ asset('admin-assets/avatars/' . $user->avatar) }}" 
                                     class="w-12 h-12 rounded-full object-cover mr-3 shadow-md" alt="User Avatar"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="w-12 h-12 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mr-3 shadow-md" style="display: none;">
                                    <i class="fas fa-user text-gray-400"></i>
                                </div>
                            @else
                                <div class="w-12 h-12 bg-gradient-to-br from-orange-100 to-orange-200 rounded-full flex items-center justify-center mr-3 shadow-md">
                                    <i class="fas fa-user text-orange-600"></i>
                                </div>
                            @endif
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                <div class="text-sm text-gray-500">ID: {{ $user->id }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $user->email }}</div>
                        @if($user->phone)
                            <div class="text-sm text-gray-500">{{ $user->phone }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($user->roles->count() > 0)
                        @foreach($user->roles as $role)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $role->name == 'admin' ? 'bg-red-100 text-red-800' : 
                                       ($role->name == 'manager' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                    <i class="fas fa-{{ $role->name == 'admin' ? 'crown' : ($role->name == 'manager' ? 'user-tie' : 'user') }} mr-1"></i>
                                    {{ ucfirst($role->name) }}
                                </span>
                        @endforeach
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <i class="fas fa-user mr-1"></i>
                                No Role
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            <i class="fas fa-shopping-cart mr-1"></i>
                            {{ $user->orders->count() ?? 0 }} Orders
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($user->email_verified_at)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check mr-1"></i>
                                Verified
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-clock mr-1"></i>
                                Unverified
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $user->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('admin.users.show', $user->id) }}" 
                               class="text-blue-600 hover:text-blue-900 transition-colors duration-200" 
                               title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                        <a href="{{ route('admin.users.edit-roles', $user->id) }}"
                               class="text-yellow-600 hover:text-yellow-900 transition-colors duration-200" 
                               title="Edit Roles">
                                <i class="fas fa-user-tag"></i>
                            </a>
                            <button onclick="deleteUser({{ $user->id }})" 
                                    class="text-red-600 hover:text-red-900 transition-colors duration-200" 
                                    title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-users text-gray-400 text-4xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No users found</h3>
                            <p class="text-gray-500 mb-4">Get started by adding your first user</p>
                            <button onclick="createUser()" class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 transition-colors duration-200">
                                <i class="fas fa-plus mr-2"></i> Add User
                            </button>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
        <div class="flex-1 flex justify-between sm:hidden">
            @if($users->previousPageUrl())
                <a href="{{ $users->previousPageUrl() }}" 
                   class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Previous
                </a>
            @else
                <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-400 bg-gray-100 cursor-not-allowed">
                    Previous
                </span>
            @endif
            
            @if($users->nextPageUrl())
                <a href="{{ $users->nextPageUrl() }}" 
                   class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Next
                </a>
            @else
                <span class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-400 bg-gray-100 cursor-not-allowed">
                    Next
                </span>
            @endif
        </div>
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700">
                    Showing
                    <span class="font-medium">{{ $users->firstItem() ?? 0 }}</span>
                    to
                    <span class="font-medium">{{ $users->lastItem() ?? 0 }}</span>
                    of
                    <span class="font-medium">{{ $users->total() }}</span>
                    results
                </p>
            </div>
            <div>
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>

<script>
// Create user function
function createUser() {
    alert('Create user functionality will be implemented.');
}

// Delete user function
function deleteUser(userId) {
    if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
        fetch(`/admin/users/${userId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('User deleted successfully!');
                location.reload();
            } else {
                alert('Error: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting user');
        });
    }
}

// Export users function
function exportUsers() {
    window.open('/admin/users/export', '_blank');
}

// Clear filters function
function clearFilters() {
    document.getElementById('search').value = '';
    document.getElementById('role').value = '';
    document.getElementById('status').value = '';
    document.getElementById('sort').value = '';
    
    // Reload page to show all users
    window.location.href = window.location.pathname;
}

// Filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const roleFilter = document.getElementById('role');
    const statusFilter = document.getElementById('status');
    const sortFilter = document.getElementById('sort');
    
    // Only add event listeners to dropdown filters (not search input)
    [roleFilter, statusFilter, sortFilter].forEach(element => {
        if (element) {
            element.addEventListener('change', function() {
                this.form.submit();
            });
        }
    });
    
    // Search input - only trigger on Enter key (no auto-reload)
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                this.form.submit();
            }
        });
    }
});
</script>
@endsection