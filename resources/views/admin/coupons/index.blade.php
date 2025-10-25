@extends('layouts.admin')

@section('title', 'Coupon Management')
@section('page-title', 'Coupons')

@section('content')
<style>
    .btn-add-coupon {
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
    
    .btn-add-coupon:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.05);
        color: white;
        text-decoration: none;
    }
    
    .btn-export-coupon {
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
    
    .btn-export-coupon:hover {
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
            <h1 class="text-3xl font-bold mb-2">Coupon Management</h1>
            <p class="text-orange-100">Manage discount coupons and promotional codes</p>
        </div>
        <div class="flex space-x-3">
            <button class="btn-export-coupon" onclick="exportCoupons()">
                <i class="fas fa-download mr-2"></i> Export Coupons
            </button>
            <a href="{{ route('admin.coupons.create') }}" class="btn-add-coupon">
                <i class="fas fa-plus mr-2"></i> Add Coupon
            </a>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <div class="flex items-center">
            <div class="bg-blue-100 p-3 rounded-lg">
                <i class="fas fa-ticket-alt text-blue-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Coupons</p>
                <p class="text-2xl font-bold text-gray-900">{{ $coupons->total() }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <div class="flex items-center">
            <div class="bg-green-100 p-3 rounded-lg">
                <i class="fas fa-check-circle text-green-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Active Coupons</p>
                <p class="text-2xl font-bold text-gray-900">{{ $coupons->where('is_active', true)->count() }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <div class="flex items-center">
            <div class="bg-yellow-100 p-3 rounded-lg">
                <i class="fas fa-globe text-yellow-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Public Coupons</p>
                <p class="text-2xl font-bold text-gray-900">{{ $coupons->where('is_public', true)->count() }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <div class="flex items-center">
            <div class="bg-purple-100 p-3 rounded-lg">
                <i class="fas fa-chart-line text-purple-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Usage</p>
                <p class="text-2xl font-bold text-gray-900">{{ $coupons->sum(function($coupon) { return $coupon->usages->count(); }) }}</p>
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

    <form method="GET" action="{{ route('admin.coupons.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
            <div class="flex">
                <input type="text" id="search" name="search" placeholder="Search coupons..." 
                       class="flex-1 px-3 py-2 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                       value="{{ request('search') }}">
                <button type="submit" 
                        class="px-4 py-2 bg-orange-600 text-white rounded-r-lg hover:bg-orange-700 transition-colors duration-200">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
            <select id="type" name="type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                <option value="">All Types</option>
                <option value="percentage" {{ request('type') == 'percentage' ? 'selected' : '' }}>Percentage</option>
                <option value="fixed" {{ request('type') == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
            <select id="status" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                <option value="">All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Visibility</label>
            <select id="visibility" name="visibility" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                <option value="">All Visibility</option>
                <option value="public" {{ request('visibility') == 'public' ? 'selected' : '' }}>Public</option>
                <option value="private" {{ request('visibility') == 'private' ? 'selected' : '' }}>Private</option>
            </select>
        </div>
    </form>
</div>

<!-- Coupons Table -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <i class="fas fa-ticket-alt mr-1"></i> Coupon
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <i class="fas fa-percentage mr-1"></i> Type & Value
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <i class="fas fa-chart-bar mr-1"></i> Usage
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <i class="fas fa-calendar mr-1"></i> Validity
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <i class="fas fa-toggle-on mr-1"></i> Status
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <i class="fas fa-cog mr-1"></i> Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($coupons as $coupon)
                <tr class="hover:bg-gray-50 transition-colors duration-200">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $coupon->name }}</div>
                            <div class="text-sm text-gray-500">Code: <span class="font-mono bg-gray-100 px-2 py-1 rounded">{{ $coupon->code }}</span></div>
                            @if($coupon->description)
                                <div class="text-xs text-gray-400 mt-1">{{ Str::limit($coupon->description, 50) }}</div>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $coupon->type == 'percentage' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                <i class="fas fa-{{ $coupon->type == 'percentage' ? 'percent' : 'dollar-sign' }} mr-1"></i>
                                {{ $coupon->type == 'percentage' ? 'Percentage' : 'Fixed' }}
                            </span>
                            <span class="ml-2 text-sm font-medium text-gray-900">
                                {{ $coupon->type == 'percentage' ? $coupon->value . '%' : '৳' . number_format($coupon->value, 2) }}
                            </span>
                        </div>
                        @if($coupon->minimum_amount)
                            <div class="text-xs text-gray-500 mt-1">Min: ৳{{ number_format($coupon->minimum_amount, 2) }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">
                            <span class="font-medium">{{ $coupon->usages->count() }}</span> uses
                        </div>
                        @if($coupon->usage_limit)
                            <div class="text-xs text-gray-500">Limit: {{ $coupon->usage_limit }}</div>
                        @endif
                        @if($coupon->usage_limit_per_user)
                            <div class="text-xs text-gray-500">Per user: {{ $coupon->usage_limit_per_user }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        @if($coupon->starts_at)
                            <div>From: {{ \Carbon\Carbon::parse($coupon->starts_at)->format('M d, Y') }}</div>
                        @endif
                        @if($coupon->expires_at)
                            <div>Until: {{ \Carbon\Carbon::parse($coupon->expires_at)->format('M d, Y') }}</div>
                        @else
                            <div class="text-gray-400">No expiry</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex flex-col space-y-1">
                            @if($coupon->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-1"></i>
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times mr-1"></i>
                                    Inactive
                                </span>
                            @endif
                            
                            @if($coupon->is_public)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-globe mr-1"></i>
                                    Public
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <i class="fas fa-lock mr-1"></i>
                                    Private
                                </span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('admin.coupons.show', $coupon->id) }}" 
                               class="text-blue-600 hover:text-blue-900 transition-colors duration-200" 
                               title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.coupons.edit', $coupon->id) }}" 
                               class="text-yellow-600 hover:text-yellow-900 transition-colors duration-200" 
                               title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button onclick="deleteCoupon({{ $coupon->id }})" 
                                    class="text-red-600 hover:text-red-900 transition-colors duration-200" 
                                    title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-ticket-alt text-gray-400 text-4xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No coupons found</h3>
                            <p class="text-gray-500 mb-4">Get started by creating your first coupon</p>
                            <a href="{{ route('admin.coupons.create') }}" class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 transition-colors duration-200">
                                <i class="fas fa-plus mr-2"></i> Add Coupon
                            </a>
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
            @if($coupons->previousPageUrl())
                <a href="{{ $coupons->previousPageUrl() }}" 
                   class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Previous
                </a>
            @else
                <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-400 bg-gray-100 cursor-not-allowed">
                    Previous
                </span>
            @endif
            
            @if($coupons->nextPageUrl())
                <a href="{{ $coupons->nextPageUrl() }}" 
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
                    <span class="font-medium">{{ $coupons->firstItem() ?? 0 }}</span>
                    to
                    <span class="font-medium">{{ $coupons->lastItem() ?? 0 }}</span>
                    of
                    <span class="font-medium">{{ $coupons->total() }}</span>
                    results
                </p>
            </div>
            <div>
                {{ $coupons->links() }}
            </div>
        </div>
    </div>
</div>

<script>
// Delete coupon function
function deleteCoupon(couponId) {
    if (confirm('Are you sure you want to delete this coupon? This action cannot be undone.')) {
        fetch(`/admin/coupons/${couponId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Coupon deleted successfully!');
                location.reload();
            } else {
                alert('Error: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting coupon');
        });
    }
}

// Export coupons function
function exportCoupons() {
    window.open('/admin/coupons/export', '_blank');
}

// Clear filters function
function clearFilters() {
    document.getElementById('search').value = '';
    document.getElementById('type').value = '';
    document.getElementById('status').value = '';
    document.getElementById('visibility').value = '';
    
    // Reload page to show all coupons
    window.location.href = window.location.pathname;
}

// Filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const typeFilter = document.getElementById('type');
    const statusFilter = document.getElementById('status');
    const visibilityFilter = document.getElementById('visibility');
    
    // Only add event listeners to dropdown filters (not search input)
    [typeFilter, statusFilter, visibilityFilter].forEach(element => {
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
