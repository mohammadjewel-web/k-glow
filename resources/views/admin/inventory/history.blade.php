@extends('layouts.admin')
@section('title', 'Stock Movement History')
@section('page-title', 'Stock Movement History')

@section('content')
<!-- Header -->
<div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl shadow-lg mb-8 p-6 text-white">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold mb-2">Stock Movement History</h1>
            <p class="text-orange-100">Complete history of all stock movements</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.inventory.index') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg font-medium transition-all duration-300 hover:scale-105">
                <i class="fas fa-arrow-left mr-2"></i> Back to Inventory
            </a>
            <button onclick="exportHistory()" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg font-medium transition-all duration-300 hover:scale-105">
                <i class="fas fa-download mr-2"></i> Export
            </button>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Total Movements</p>
                <p class="text-3xl font-bold text-gray-900">{{ $movements->total() }}</p>
            </div>
            <div class="bg-blue-100 p-3 rounded-lg">
                <i class="fas fa-history text-2xl text-blue-600"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Stock In</p>
                <p class="text-3xl font-bold text-green-600">{{ $stats['total_in'] ?? 0 }}</p>
            </div>
            <div class="bg-green-100 p-3 rounded-lg">
                <i class="fas fa-arrow-down text-2xl text-green-600"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Stock Out</p>
                <p class="text-3xl font-bold text-red-600">{{ $stats['total_out'] ?? 0 }}</p>
            </div>
            <div class="bg-red-100 p-3 rounded-lg">
                <i class="fas fa-arrow-up text-2xl text-red-600"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Adjustments</p>
                <p class="text-3xl font-bold text-blue-600">{{ $stats['total_adjustments'] ?? 0 }}</p>
            </div>
            <div class="bg-blue-100 p-3 rounded-lg">
                <i class="fas fa-edit text-2xl text-blue-600"></i>
            </div>
        </div>
    </div>
</div>

<!-- Filters Section -->
<div class="bg-white rounded-xl shadow-lg mb-8 border border-gray-200">
    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="bg-orange-100 p-2 rounded-lg mr-3">
                    <i class="fas fa-filter text-orange-600"></i>
                </div>
                <div>
                    <h6 class="text-lg font-semibold text-gray-800 mb-0">Filter Movements</h6>
                    <p class="text-sm text-gray-600 mb-0">Filter stock movements by various criteria</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <span class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm font-medium">
                    {{ $movements->total() }} Total Movements
                </span>
                <button onclick="toggleFilters()" class="bg-orange-100 hover:bg-orange-200 text-orange-700 px-3 py-1 rounded-lg text-sm font-medium transition-all duration-300">
                    <i class="fas fa-chevron-down mr-1" id="filterToggleIcon"></i> Toggle Filters
                </button>
            </div>
        </div>
    </div>
    
    <div id="filtersContent" class="p-6">
        <form method="GET" action="{{ route('admin.inventory.history') }}" id="filterForm">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <!-- Product Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search Product</label>
                    <div class="relative">
                        <input type="text" name="product" value="{{ request('product') }}" 
                               placeholder="Product name or SKU" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300">
                        <i class="fas fa-search absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>

                <!-- Movement Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Movement Type</label>
                    <select name="type" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300">
                        <option value="">All Types</option>
                        <option value="in" {{ request('type') == 'in' ? 'selected' : '' }}>Stock In</option>
                        <option value="out" {{ request('type') == 'out' ? 'selected' : '' }}>Stock Out</option>
                        <option value="adjustment" {{ request('type') == 'adjustment' ? 'selected' : '' }}>Adjustment</option>
                    </select>
                </div>

                <!-- Date From -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date From</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300">
                </div>

                <!-- Date To -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date To</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300">
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-3 rounded-lg font-medium transition-all duration-300 hover:scale-105 shadow-lg">
                        <i class="fas fa-search mr-2"></i> Apply Filters
                    </button>
                    
                    <a href="{{ route('admin.inventory.history') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-medium transition-all duration-300 hover:scale-105">
                        <i class="fas fa-times mr-2"></i> Clear All
                    </a>
                </div>
                
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-500">Sort By:</span>
                    <select name="sort_by" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date</option>
                        <option value="quantity" {{ request('sort_by') == 'quantity' ? 'selected' : '' }}>Quantity</option>
                        <option value="type" {{ request('sort_by') == 'type' ? 'selected' : '' }}>Type</option>
                    </select>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Movements Table -->
<div class="bg-white rounded-xl shadow-lg border border-gray-200">
    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="bg-orange-100 p-2 rounded-lg mr-3">
                    <i class="fas fa-history text-orange-600"></i>
                </div>
                <div>
                    <h6 class="text-lg font-semibold text-gray-800 mb-0">Stock Movements</h6>
                    <p class="text-sm text-gray-600 mb-0">Complete history of all stock movements</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <span class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm font-medium">
                    {{ $movements->total() }} Movements
                </span>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($movements as $movement)
                <tr class="hover:bg-gray-50 transition-colors duration-200">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $movement->created_at->format('M d, Y') }}</div>
                        <div class="text-xs text-gray-500">{{ $movement->created_at->format('H:i:s') }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            @if($movement->product->thumbnail)
                                <img src="{{ asset('admin-assets/products/' . $movement->product->thumbnail) }}" 
                                     class="w-10 h-10 rounded-lg object-cover mr-3 shadow-sm" alt="Product"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="w-10 h-10 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center mr-3 shadow-sm" style="display: none;">
                                    <i class="fas fa-image text-gray-400"></i>
                                </div>
                            @else
                                <div class="w-10 h-10 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center mr-3 shadow-sm">
                                    <i class="fas fa-image text-gray-400"></i>
                                </div>
                            @endif
                            <div>
                                <div class="text-sm font-bold text-gray-900">{{ $movement->product->name }}</div>
                                <div class="text-xs text-gray-500">{{ $movement->product->sku }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $typeConfig = match($movement->type) {
                                'in' => ['class' => 'bg-green-100 text-green-800', 'icon' => 'fas fa-arrow-down', 'label' => 'Stock In'],
                                'out' => ['class' => 'bg-red-100 text-red-800', 'icon' => 'fas fa-arrow-up', 'label' => 'Stock Out'],
                                'adjustment' => ['class' => 'bg-blue-100 text-blue-800', 'icon' => 'fas fa-edit', 'label' => 'Adjustment'],
                                default => ['class' => 'bg-gray-100 text-gray-800', 'icon' => 'fas fa-question', 'label' => 'Unknown']
                            };
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $typeConfig['class'] }}">
                            <i class="{{ $typeConfig['icon'] }} mr-1"></i>
                            {{ $typeConfig['label'] }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-semibold {{ $movement->quantity > 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $movement->quantity > 0 ? '+' : '' }}{{ $movement->quantity }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $movement->reason ?? 'N/A' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $movement->user->name ?? 'System' }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">{{ Str::limit($movement->notes, 50) }}</div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
        <div class="flex justify-center">
            {{ $movements->links() }}
        </div>
    </div>
</div>

<script>
// Toggle filters
function toggleFilters() {
    const content = document.getElementById('filtersContent');
    const icon = document.getElementById('filterToggleIcon');
    
    if (content.style.display === 'none') {
        content.style.display = 'block';
        icon.className = 'fas fa-chevron-up mr-1';
    } else {
        content.style.display = 'none';
        icon.className = 'fas fa-chevron-down mr-1';
    }
}

// Auto-submit form on filter change
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('filterForm');
    const inputs = form.querySelectorAll('select, input[type="date"]');
    
    inputs.forEach(input => {
        input.addEventListener('change', function() {
            form.submit();
        });
    });
});

// Export history
function exportHistory() {
    showNotification('Export feature will be implemented', 'info');
}

// Show notification
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-4 rounded-lg shadow-lg z-50 transition-all duration-300 ${
        type === 'success' ? 'bg-green-500 text-white' :
        type === 'error' ? 'bg-red-500 text-white' :
        type === 'warning' ? 'bg-yellow-500 text-white' :
        'bg-blue-500 text-white'
    }`;
    
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'times' : type === 'warning' ? 'exclamation' : 'info'}-circle mr-2"></i>
            ${message}
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}
</script>
@endsection

