@extends('layouts.admin')
@section('title', 'Inventory Management')
@section('page-title', 'Inventory Management')

@section('content')
<!-- Header -->
<div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl shadow-lg mb-8 p-6 text-white">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold mb-2">Inventory Management</h1>
            <p class="text-orange-100">Track and manage your product inventory</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.inventory.history') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg font-medium transition-all duration-300 hover:scale-105">
                <i class="fas fa-history mr-2"></i> History
            </a>
            <button onclick="exportInventory()" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg font-medium transition-all duration-300 hover:scale-105">
                <i class="fas fa-download mr-2"></i> Export
            </button>
            <button onclick="bulkUpdateModal()" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg font-medium transition-all duration-300 hover:scale-105">
                <i class="fas fa-edit mr-2"></i> Bulk Update
            </button>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Products -->
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Total Products</p>
                <p class="text-3xl font-bold text-gray-900">{{ $stats['total_products'] }}</p>
            </div>
            <div class="bg-blue-100 p-3 rounded-lg">
                <i class="fas fa-boxes text-2xl text-blue-600"></i>
            </div>
        </div>
    </div>

    <!-- Low Stock -->
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Low Stock</p>
                <p class="text-3xl font-bold text-gray-900">{{ $stats['low_stock_products'] }}</p>
            </div>
            <div class="bg-yellow-100 p-3 rounded-lg">
                <i class="fas fa-exclamation-triangle text-2xl text-yellow-600"></i>
            </div>
        </div>
    </div>

    <!-- Out of Stock -->
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Out of Stock</p>
                <p class="text-3xl font-bold text-gray-900">{{ $stats['out_of_stock_products'] }}</p>
            </div>
            <div class="bg-red-100 p-3 rounded-lg">
                <i class="fas fa-times-circle text-2xl text-red-600"></i>
            </div>
        </div>
    </div>

    <!-- Stock Value -->
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Stock Value</p>
                <p class="text-3xl font-bold text-gray-900">৳{{ number_format($stats['total_stock_value'], 2) }}</p>
            </div>
            <div class="bg-green-100 p-3 rounded-lg">
                <i class="fas fa-dollar-sign text-2xl text-green-600"></i>
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
                    <h6 class="text-lg font-semibold text-gray-800 mb-0">Advanced Filters</h6>
                    <p class="text-sm text-gray-600 mb-0">Filter inventory by various criteria</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <span class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm font-medium">
                    {{ $inventory->total() }} Total Items
                </span>
                <button onclick="toggleFilters()" class="bg-orange-100 hover:bg-orange-200 text-orange-700 px-3 py-1 rounded-lg text-sm font-medium transition-all duration-300">
                    <i class="fas fa-chevron-down mr-1" id="filterToggleIcon"></i> Toggle Filters
                </button>
            </div>
        </div>
    </div>
    
    <div id="filtersContent" class="p-6">
        <form method="GET" action="{{ route('admin.inventory.index') }}" id="filterForm">
            <!-- Basic Filters Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search Products</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Product name or SKU" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300">
                        <i class="fas fa-search absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Stock Status</label>
                    <select name="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300">
                        <option value="">All Status</option>
                        <option value="in_stock" {{ request('status') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                        <option value="low_stock" {{ request('status') == 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                        <option value="out_of_stock" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                    </select>
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                    <select name="category" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300">
                        <option value="">All Categories</option>
                        @foreach(\App\Models\Category::all() as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Brand -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Brand</label>
                    <select name="brand" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300">
                        <option value="">All Brands</option>
                        @foreach(\App\Models\Brand::all() as $brand)
                            <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Advanced Filters Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <!-- Stock From -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Stock From</label>
                    <input type="number" name="stock_from" value="{{ request('stock_from') }}" 
                           placeholder="Minimum stock" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300">
                </div>

                <!-- Stock To -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Stock To</label>
                    <input type="number" name="stock_to" value="{{ request('stock_to') }}" 
                           placeholder="Maximum stock" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300">
                </div>

                <!-- Value From -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Value From</label>
                    <input type="number" name="value_from" value="{{ request('value_from') }}" 
                           placeholder="Minimum value" step="0.01"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300">
                </div>

                <!-- Value To -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Value To</label>
                    <input type="number" name="value_to" value="{{ request('value_to') }}" 
                           placeholder="Maximum value" step="0.01"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300">
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center space-x-3">
                    <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-3 rounded-lg font-medium transition-all duration-300 hover:scale-105 shadow-lg">
                        <i class="fas fa-search mr-2"></i> Apply Filters
                    </button>
                    
                    <a href="{{ route('admin.inventory.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-medium transition-all duration-300 hover:scale-105">
                        <i class="fas fa-times mr-2"></i> Clear All
                    </a>
                    
                    <button type="button" onclick="showLowStock()" class="bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-3 rounded-lg font-medium transition-all duration-300 hover:scale-105">
                        <i class="fas fa-exclamation-triangle mr-2"></i> Low Stock
                    </button>
                    
                    <button type="button" onclick="showOutOfStock()" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-medium transition-all duration-300 hover:scale-105">
                        <i class="fas fa-times-circle mr-2"></i> Out of Stock
                    </button>
                </div>
                
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-500">Sort By:</span>
                    <select name="sort_by" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        <option value="product_name" {{ request('sort_by') == 'product_name' ? 'selected' : '' }}>Product Name</option>
                        <option value="current_stock" {{ request('sort_by') == 'current_stock' ? 'selected' : '' }}>Stock Level</option>
                        <option value="stock_value" {{ request('sort_by') == 'stock_value' ? 'selected' : '' }}>Stock Value</option>
                        <option value="updated_at" {{ request('sort_by') == 'updated_at' ? 'selected' : '' }}>Last Updated</option>
                    </select>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Inventory Table -->
<div class="bg-white rounded-xl shadow-lg border border-gray-200">
    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="bg-orange-100 p-2 rounded-lg mr-3">
                    <i class="fas fa-warehouse text-orange-600"></i>
                </div>
                <div>
                    <h6 class="text-lg font-semibold text-gray-800 mb-0">Inventory Items</h6>
                    <p class="text-sm text-gray-600 mb-0">Manage your product inventory</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <span class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm font-medium">
                    {{ $inventory->total() }} Items
                </span>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Stock</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Min Stock</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cost Price</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock Value</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Updated</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($inventory as $item)
                <tr class="hover:bg-gray-50 transition-colors duration-200">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            @if($item->product->thumbnail)
                                <img src="{{ asset('admin-assets/products/' . $item->product->thumbnail) }}" 
                                     class="w-12 h-12 rounded-lg object-cover mr-3 shadow-md" alt="Product"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="w-12 h-12 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center mr-3 shadow-md" style="display: none;">
                                    <i class="fas fa-image text-gray-400"></i>
                                </div>
                            @elseif($item->product->images && $item->product->images->count() > 0 && $item->product->images->first())
                                <img src="{{ asset('admin-assets/products/' . $item->product->images->first()->image) }}" 
                                     class="w-12 h-12 rounded-lg object-cover mr-3 shadow-md" alt="Product"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="w-12 h-12 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center mr-3 shadow-md" style="display: none;">
                                    <i class="fas fa-image text-gray-400"></i>
                                </div>
                            @else
                                <div class="w-12 h-12 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center mr-3 shadow-md">
                                    <i class="fas fa-image text-gray-400"></i>
                                </div>
                            @endif
                            <div>
                                <div class="text-sm font-bold text-gray-900">{{ $item->product->name }}</div>
                                <div class="text-xs text-gray-500">{{ $item->product->category->name ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $item->sku }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <span class="text-sm font-bold text-gray-900">{{ $item->current_stock }}</span>
                            @if($item->reserved_stock > 0)
                                <span class="ml-2 text-xs text-gray-500">({{ $item->reserved_stock }} reserved)</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $item->minimum_stock }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $statusConfig = match($item->stock_status) {
                                'out_of_stock' => ['class' => 'bg-red-100 text-red-800', 'icon' => 'fas fa-times-circle'],
                                'low_stock' => ['class' => 'bg-yellow-100 text-yellow-800', 'icon' => 'fas fa-exclamation-triangle'],
                                'in_stock' => ['class' => 'bg-green-100 text-green-800', 'icon' => 'fas fa-check-circle'],
                                default => ['class' => 'bg-gray-100 text-gray-800', 'icon' => 'fas fa-question-circle']
                            };
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $statusConfig['class'] }}">
                            <i class="{{ $statusConfig['icon'] }} mr-1"></i>
                            {{ ucfirst(str_replace('_', ' ', $item->stock_status)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">৳{{ number_format($item->cost_price, 2) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">৳{{ number_format($item->stock_value, 2) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $item->updated_at->format('M d, Y') }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center space-x-2">
                            <button onclick="viewInventory({{ $item->id }})" class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-300 hover:scale-105">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button onclick="updateStock({{ $item->id }})" class="bg-yellow-100 hover:bg-yellow-200 text-yellow-700 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-300 hover:scale-105">
                                <i class="fas fa-edit"></i>
                            </button>
                            <!-- <button onclick="viewMovements({{ $item->id }})" class="bg-purple-100 hover:bg-purple-200 text-purple-700 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-300 hover:scale-105">
                                <i class="fas fa-history"></i>
                            </button> -->
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
        <div class="flex justify-center">
            {{ $inventory->links() }}
        </div>
    </div>
</div>

<!-- Update Stock Modal -->
<div id="updateStockModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4">
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4 rounded-t-xl">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="bg-white bg-opacity-20 p-2 rounded-lg mr-3">
                        <i class="fas fa-edit text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-white">Update Stock</h3>
                        <p class="text-orange-100 text-sm">Adjust inventory levels</p>
                    </div>
                </div>
                <button onclick="closeUpdateModal()" class="text-white hover:text-orange-200 transition-colors duration-300">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>

        <form id="updateStockForm" class="p-6">
            <input type="hidden" id="inventoryId" name="inventory_id">
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Movement Type</label>
                    <select id="movementType" name="type" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300">
                        <option value="in">📥 Stock In</option>
                        <option value="out">📤 Stock Out</option>
                        <option value="adjustment">⚖️ Adjustment</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                    <input type="number" id="quantity" name="quantity" required min="1" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reason</label>
                    <input type="text" id="reason" name="reason" placeholder="Optional reason for this movement" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea id="notes" name="notes" rows="3" placeholder="Additional notes (optional)" 
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300"></textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Unit Cost (Optional)</label>
                    <input type="number" id="unitCost" name="unit_cost" step="0.01" min="0" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300">
                </div>
            </div>

            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="closeUpdateModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-medium transition-all duration-300 hover:scale-105">
                    <i class="fas fa-times mr-2"></i> Cancel
                </button>
                <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-3 rounded-lg font-medium transition-all duration-300 hover:scale-105 shadow-lg">
                    <i class="fas fa-save mr-2"></i> Update Stock
                </button>
            </div>
        </form>
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
    const inputs = form.querySelectorAll('select, input[type="number"]');
    
    inputs.forEach(input => {
        input.addEventListener('change', function() {
            form.submit();
        });
    });
});

// Update stock function
function updateStock(inventoryId) {
    document.getElementById('inventoryId').value = inventoryId;
    document.getElementById('updateStockModal').classList.remove('hidden');
    document.getElementById('updateStockModal').classList.add('flex');
}

// Close update modal
function closeUpdateModal() {
    document.getElementById('updateStockModal').classList.add('hidden');
    document.getElementById('updateStockModal').classList.remove('flex');
}

// Update stock form submission
document.getElementById('updateStockForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const inventoryId = formData.get('inventory_id');
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Updating...';
    submitBtn.disabled = true;
    
    fetch(`/admin/inventory/${inventoryId}/update-stock`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            quantity: parseInt(formData.get('quantity')),
            type: formData.get('type'),
            reason: formData.get('reason'),
            notes: formData.get('notes'),
            unit_cost: parseFloat(formData.get('unit_cost')) || null
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Stock updated successfully!', 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            showNotification('Error: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error updating stock', 'error');
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

// View inventory details
function viewInventory(inventoryId) {
    window.location.href = `/admin/inventory/${inventoryId}`;
}

// View stock movements
function viewMovements(inventoryId) {
    window.location.href = `/admin/inventory/${inventoryId}/movements`;
}

// Show low stock products
function showLowStock() {
    window.location.href = '/admin/inventory?status=low_stock';
}

// Show out of stock products
function showOutOfStock() {
    window.location.href = '/admin/inventory?status=out_of_stock';
}

// Bulk update modal
function bulkUpdateModal() {
    showNotification('Bulk update feature will be implemented', 'info');
}

// Export inventory
function exportInventory() {
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