@extends('layouts.admin')

@section('title', 'Product Management')
@section('page-title', 'Products')

@section('content')
<!-- Header Section -->
<div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl shadow-lg mb-8 p-6 text-white">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold mb-2">Product Management</h1>
            <p class="text-orange-100">Manage and organize your product catalog</p>
        </div>
        <div class="flex space-x-3">
            <button class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg font-medium transition-all duration-300 hover:scale-105" onclick="exportProducts()">
                <i class="fas fa-download mr-2"></i> Export Products
            </button>
            <a href="{{ route('admin.products.create') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg font-medium transition-all duration-300 hover:scale-105">
                <i class="fas fa-plus mr-2"></i> Add Product
            </a>
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
                    <p class="text-sm text-gray-600 mb-0">Filter products by various criteria</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <span class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm font-medium">
                    {{ $products->total() }} Total Products
                </span>
                <button onclick="toggleFilters()" class="bg-orange-100 hover:bg-orange-200 text-orange-700 px-3 py-1 rounded-lg text-sm font-medium transition-all duration-300">
                    <i class="fas fa-chevron-down mr-1" id="filterToggleIcon"></i> Toggle Filters
                </button>
            </div>
        </div>
    </div>
    
    <div id="filtersContent" class="p-6">
        <form method="GET" action="{{ route('admin.products.index') }}" id="filterForm">
            <!-- Basic Filters Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-search mr-1 text-orange-600"></i>Search
                    </label>
                    <div class="relative">
                        <input type="text" class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300" 
                               id="search" name="search" value="{{ request('search') }}" placeholder="Product name, SKU, or description...">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                </div>
                
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-tags mr-1 text-orange-600"></i>Category
                    </label>
                    <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300" id="category" name="category">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="brand" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-award mr-1 text-orange-600"></i>Brand
                    </label>
                    <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300" id="brand" name="brand">
                        <option value="">All Brands</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-toggle-on mr-1 text-orange-600"></i>Status
                    </label>
                    <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300" id="status" name="status">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>
            
            <!-- Advanced Filters Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div>
                    <label for="price_from" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-dollar-sign mr-1 text-orange-600"></i>Min Price
                    </label>
                    <input type="number" step="0.01" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300" 
                           id="price_from" name="price_from" value="{{ request('price_from') }}" placeholder="0.00">
                </div>
                
                <div>
                    <label for="price_to" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-dollar-sign mr-1 text-orange-600"></i>Max Price
                    </label>
                    <input type="number" step="0.01" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300" 
                           id="price_to" name="price_to" value="{{ request('price_to') }}" placeholder="999999.99">
                </div>
                
                <div>
                    <label for="stock_status" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-warehouse mr-1 text-orange-600"></i>Stock Status
                    </label>
                    <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300" id="stock_status" name="stock_status">
                        <option value="">All Stock</option>
                        <option value="in_stock" {{ request('stock_status') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                        <option value="low_stock" {{ request('stock_status') == 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                        <option value="out_of_stock" {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                    </select>
                </div>
                
                <div>
                    <label for="sort_by" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-sort mr-1 text-orange-600"></i>Sort By
                    </label>
                    <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300" id="sort_by" name="sort_by">
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date Created</option>
                        <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Product Name</option>
                        <option value="price" {{ request('sort_by') == 'price' ? 'selected' : '' }}>Price</option>
                        <option value="sku" {{ request('sort_by') == 'sku' ? 'selected' : '' }}>SKU</option>
                    </select>
                </div>
    </div>
            
            <!-- Action Buttons -->
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center space-x-3">
                    <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-3 rounded-lg font-medium transition-all duration-300 hover:scale-105 shadow-lg">
                        <i class="fas fa-search mr-2"></i> Apply Filters
                    </button>
                    
                    <a href="{{ route('admin.products.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-medium transition-all duration-300 hover:scale-105">
                        <i class="fas fa-times mr-2"></i> Clear All
                    </a>
                    
                    <button type="button" onclick="bulkUpdate()" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-medium transition-all duration-300 hover:scale-105">
                        <i class="fas fa-edit mr-2"></i> Bulk Update
                    </button>
    </div>

                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-500">Sort Order:</span>
                    <select name="sort_order" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Descending</option>
                        <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                    </select>
                </div>
            </div>
        </form>
    </div>
    </div>

    <!-- Products Table -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <div class="bg-orange-100 p-2 rounded-lg mr-3">
                    <i class="fas fa-box text-orange-600"></i>
                </div>
                <div>
                    <h6 class="text-lg font-semibold text-gray-800 mb-0">Products List</h6>
                    <p class="text-sm text-gray-600 mb-0">Manage and organize your product catalog</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <span class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm font-medium">
                    {{ $products->count() }} Products
                </span>
            </div>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left">
                        <input type="checkbox" id="selectAll" onchange="toggleSelectAll()" class="rounded border-gray-300 text-orange-600 focus:ring-orange-500">
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Brand</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($products as $product)
                <tr class="hover:bg-gray-50 transition-colors duration-200">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" class="product-checkbox rounded border-gray-300 text-orange-600 focus:ring-orange-500" value="{{ $product->id }}">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            @if($product->thumbnail)
                                <img src="{{ asset('admin-assets/products/' . $product->thumbnail) }}" 
                                     class="w-12 h-12 rounded-lg object-cover mr-3 shadow-md" alt="Product"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="w-12 h-12 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center mr-3 shadow-md" style="display: none;">
                                    <i class="fas fa-image text-gray-400"></i>
                                </div>
                            @elseif($product->images && $product->images->count() > 0 && $product->images->first())
                                <img src="{{ asset('admin-assets/products/' . $product->images->first()->image) }}" 
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
                                <div class="text-sm font-bold text-gray-900">{{ $product->name }}</div>
                                <div class="text-xs text-gray-500">{{ Str::limit($product->description, 50) }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="bg-orange-100 p-1 rounded mr-2">
                                <i class="fas fa-barcode text-orange-600 text-xs"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-900">{{ $product->sku ?? 'N/A' }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="bg-blue-100 p-1 rounded mr-2">
                                <i class="fas fa-tag text-blue-600 text-xs"></i>
                            </div>
                            <span class="text-sm text-gray-900">{{ $product->category ? $product->category->name : 'N/A' }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="bg-green-100 p-1 rounded mr-2">
                                <i class="fas fa-award text-green-600 text-xs"></i>
                            </div>
                            <span class="text-sm text-gray-900">{{ $product->brand ? $product->brand->name : 'N/A' }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                        ৳{{ number_format($product->price, 2) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($product->inventory)
                            <div class="flex items-center">
                                <div class="bg-green-100 p-1 rounded mr-2">
                                    <i class="fas fa-warehouse text-green-600 text-xs"></i>
                                </div>
                                <div>
                                    <span class="text-sm font-medium {{ $product->inventory->current_stock <= $product->inventory->minimum_stock ? 'text-yellow-600' : 'text-green-600' }}">
                                        {{ $product->inventory->current_stock }}
                                    </span>
                                    @if($product->inventory->current_stock <= $product->inventory->minimum_stock)
                                        <div class="text-xs text-yellow-600">Low Stock</div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="flex items-center">
                                <div class="bg-gray-100 p-1 rounded mr-2">
                                    <i class="fas fa-exclamation text-gray-400 text-xs"></i>
                                </div>
                                <span class="text-sm text-gray-500">No Inventory</span>
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full {{ $product->status ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $product->status ? 'Active' : 'Inactive' }}
                        </span>
                        </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $product->created_at->format('M d, Y') }}
                        </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.products.show', $product->id) }}" 
                               class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-1 rounded-lg transition-colors duration-200" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.products.edit', $product->id) }}" 
                               class="bg-yellow-100 hover:bg-yellow-200 text-yellow-700 px-3 py-1 rounded-lg transition-colors duration-200" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="bg-cyan-100 hover:bg-cyan-200 text-cyan-700 px-3 py-1 rounded-lg transition-colors duration-200" 
                                    onclick="viewInventory({{ $product->id }})" title="Inventory">
                                <i class="fas fa-warehouse"></i>
                            </button>
                            <button class="bg-red-100 hover:bg-red-200 text-red-700 px-3 py-1 rounded-lg transition-colors duration-200" 
                                    onclick="deleteProduct({{ $product->id }})" title="Delete">
                                <i class="fas fa-trash"></i>
                                </button>
                        </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-gray-200 flex justify-center">
        {{ $products->links() }}
    </div>
</div>

<script>
// Toggle filters function
function toggleFilters() {
    const filtersContent = document.getElementById('filtersContent');
    const toggleIcon = document.getElementById('filterToggleIcon');
    
    if (filtersContent.style.display === 'none') {
        filtersContent.style.display = 'block';
        toggleIcon.className = 'fas fa-chevron-up mr-1';
    } else {
        filtersContent.style.display = 'none';
        toggleIcon.className = 'fas fa-chevron-down mr-1';
    }
}

// Toggle select all function
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.product-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
}

// View inventory function
function viewInventory(productId) {
    window.location.href = `/admin/inventory?product=${productId}`;
}

// Delete product function
function deleteProduct(productId) {
    if (confirm('Are you sure you want to delete this product?')) {
        fetch(`/admin/products/${productId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Product deleted successfully!', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showNotification('Error: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error deleting product', 'error');
        });
    }
}

// Export products function
function exportProducts() {
    const params = new URLSearchParams(window.location.search);
    window.open(`/admin/products/export?${params.toString()}`, '_blank');
}

// Bulk update function
function bulkUpdate() {
    const selectedProducts = Array.from(document.querySelectorAll('.product-checkbox:checked')).map(cb => cb.value);
    
    if (selectedProducts.length === 0) {
        showNotification('Please select products to update', 'error');
        return;
    }
    
    showNotification('Bulk update functionality will be implemented', 'info');
}

// Show notification function
function showNotification(message, type) {
    const notification = document.createElement('div');
    const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
    const icon = type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle';
    
    notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg text-white font-medium transition-all duration-300 ${bgColor}`;
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-${icon} mr-2"></i>
            ${message}
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Auto-submit form on filter change
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterForm');
    const filterInputs = filterForm.querySelectorAll('select, input[type="number"]');
    
    // Auto-submit on change (with debounce)
    let timeoutId;
    filterInputs.forEach(input => {
        input.addEventListener('change', function() {
            clearTimeout(timeoutId);
            timeoutId = setTimeout(() => {
                filterForm.submit();
            }, 500);
        });
    });
    
    // Real-time search
    const searchInput = document.getElementById('search');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                filterForm.submit();
            }, 1000);
        });
    }
    
    // Initialize filters state
    const urlParams = new URLSearchParams(window.location.search);
    const hasFilters = Array.from(urlParams.keys()).some(key => 
        key !== 'page' && urlParams.get(key) !== ''
    );
    
    if (!hasFilters) {
        document.getElementById('filtersContent').style.display = 'none';
        document.getElementById('filterToggleIcon').className = 'fas fa-chevron-down mr-1';
    }
});
</script>
@endsection