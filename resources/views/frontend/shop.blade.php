@extends('layouts.app')

@section('title', 'Shop - K-GLOW')

@section('content')
<main class="pt-28">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar -->
            <aside class="lg:w-80 bg-white rounded-lg shadow-sm p-6 h-fit">
                <form method="GET" action="{{ route('shop') }}" id="filterForm">
                    <!-- Search -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-3">Search Products</h3>
                        <div class="relative">
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Search products..."
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                            />
                            <button type="submit" class="absolute right-2 top-2 text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Stock Status -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-3">Stock Status</h3>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" name="on_sale" value="1" {{ request('on_sale') ? 'checked' : '' }} class="mr-2 filter-checkbox">
                                <span class="text-sm">On Sale</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="in_stock" value="1" {{ request('in_stock') ? 'checked' : '' }} class="mr-2 filter-checkbox">
                                <span class="text-sm">In Stock</span>
                            </label>
                        </div>
                    </div>

                    <!-- Price Filter -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-3">Filter by Price</h3>
                        <div class="space-y-3">
                            <div class="flex items-center space-x-2">
                                <input
                                    type="number"
                                    name="min_price"
                                    value="{{ request('min_price', '') }}"
                                    placeholder="Min"
                                    class="w-full px-3 py-2 border border-gray-300 rounded text-sm filter-input"
                                />
                                <span class="text-gray-500">-</span>
                                <input
                                    type="number"
                                    name="max_price"
                                    value="{{ request('max_price', '') }}"
                                    placeholder="Max"
                                    class="w-full px-3 py-2 border border-gray-300 rounded text-sm filter-input"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Categories -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-3">Categories</h3>
                        <div class="space-y-2 max-h-48 overflow-y-auto">
                            @foreach($categories as $category)
                                <label class="flex items-center">
                                    <input type="checkbox" name="categories[]" value="{{ $category->id }}" 
                                        {{ in_array($category->id, request('categories', [])) ? 'checked' : '' }} 
                                        class="mr-2 filter-checkbox">
                                    <span class="text-sm">{{ $category->name }} ({{ $category->products_count ?? 0 }})</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Brands -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-3">Brands</h3>
                        <div class="space-y-2 max-h-48 overflow-y-auto">
                            @foreach($brands as $brand)
                                <label class="flex items-center">
                                    <input type="checkbox" name="brands[]" value="{{ $brand->id }}" 
                                        {{ in_array($brand->id, request('brands', [])) ? 'checked' : '' }} 
                                        class="mr-2 filter-checkbox">
                                    <span class="text-sm">{{ $brand->name }} ({{ $brand->products_count ?? 0 }})</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Apply Filters Button -->
                    <div class="mb-4">
                        <button type="submit" class="w-full bg-orange-500 text-white py-2 rounded-lg hover:bg-orange-600 transition-colors text-sm font-medium">
                            Apply Filters
                        </button>
                    </div>

                    <!-- Clear Filters -->
                    <div class="pt-4 border-t">
                        <a href="{{ route('shop') }}" class="w-full bg-gray-500 text-white py-2 rounded-lg hover:bg-gray-600 transition-colors text-sm font-medium text-center block">
                            Clear All Filters
                        </a>
                    </div>
                </form>
            </aside>

            <!-- Main Content -->
            <main class="flex-1">
                <!-- Breadcrumb -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
                    <nav class="text-sm text-gray-600 mb-4 sm:mb-0">
                        <a href="{{ route('home') }}" class="hover:text-orange-500">Home</a> / 
                        <span class="text-gray-800">Shop</span>
                        @if(request('search'))
                            / <span class="text-gray-800">Search: "{{ request('search') }}"</span>
                        @endif
                    </nav>
                    
                    <form method="GET" action="{{ route('shop') }}" class="flex items-center space-x-4">
                        <!-- Preserve existing filters -->
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        @if(request('categories'))
                            @foreach(request('categories') as $category)
                                <input type="hidden" name="categories[]" value="{{ $category }}">
                            @endforeach
                        @endif
                        @if(request('brands'))
                            @foreach(request('brands') as $brand)
                                <input type="hidden" name="brands[]" value="{{ $brand }}">
                            @endforeach
                        @endif
                        @if(request('min_price'))
                            <input type="hidden" name="min_price" value="{{ request('min_price') }}">
                        @endif
                        @if(request('max_price'))
                            <input type="hidden" name="max_price" value="{{ request('max_price') }}">
                        @endif
                        @if(request('in_stock'))
                            <input type="hidden" name="in_stock" value="{{ request('in_stock') }}">
                        @endif
                        @if(request('on_sale'))
                            <input type="hidden" name="on_sale" value="{{ request('on_sale') }}">
                        @endif
                        
                        <span class="text-sm text-gray-600">Sort by:</span>
                        <select name="sort" onchange="this.form.submit()" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name: A to Z</option>
                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                        </select>
                        
                        <span class="text-sm text-gray-600">Show:</span>
                        <select name="per_page" onchange="this.form.submit()" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                            <option value="12" {{ request('per_page', 12) == 12 ? 'selected' : '' }}>12</option>
                            <option value="24" {{ request('per_page') == 24 ? 'selected' : '' }}>24</option>
                            <option value="48" {{ request('per_page') == 48 ? 'selected' : '' }}>48</option>
                        </select>
                    </form>
                </div>

                <!-- Active Filters -->
                @if(request()->hasAny(['search', 'categories', 'brands', 'min_price', 'max_price', 'in_stock', 'on_sale']))
                    <div class="mb-4">
                        <div class="flex flex-wrap gap-2">
                            <span class="text-sm text-gray-600">Active filters:</span>
                            @if(request('search'))
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-orange-100 text-orange-800">
                                    Search: "{{ request('search') }}"
                                    <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="ml-1 text-orange-600 hover:text-orange-800">×</a>
                                </span>
                            @endif
                            @if(request('categories'))
                                @foreach(request('categories') as $categoryId)
                                    @php $category = $categories->find($categoryId) @endphp
                                    @if($category)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800">
                                            {{ $category->name }}
                                            <a href="{{ request()->fullUrlWithQuery(['categories' => array_diff(request('categories', []), [$categoryId])]) }}" class="ml-1 text-blue-600 hover:text-blue-800">×</a>
                                        </span>
                                    @endif
                                @endforeach
                            @endif
                            @if(request('brands'))
                                @foreach(request('brands') as $brandId)
                                    @php $brand = $brands->find($brandId) @endphp
                                    @if($brand)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-green-100 text-green-800">
                                            {{ $brand->name }}
                                            <a href="{{ request()->fullUrlWithQuery(['brands' => array_diff(request('brands', []), [$brandId])]) }}" class="ml-1 text-green-600 hover:text-green-800">×</a>
                                        </span>
                                    @endif
                                @endforeach
                            @endif
                            @if(request('min_price') || request('max_price'))
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-purple-100 text-purple-800">
                                    Price: {{ request('min_price', '0') }} - {{ request('max_price', '∞') }}
                                    <a href="{{ request()->fullUrlWithQuery(['min_price' => null, 'max_price' => null]) }}" class="ml-1 text-purple-600 hover:text-purple-800">×</a>
                                </span>
                            @endif
                            @if(request('in_stock'))
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-green-100 text-green-800">
                                    In Stock
                                    <a href="{{ request()->fullUrlWithQuery(['in_stock' => null]) }}" class="ml-1 text-green-600 hover:text-green-800">×</a>
                                </span>
                            @endif
                            @if(request('on_sale'))
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-red-100 text-red-800">
                                    On Sale
                                    <a href="{{ request()->fullUrlWithQuery(['on_sale' => null]) }}" class="ml-1 text-red-600 hover:text-red-800">×</a>
                                </span>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Results Count -->
                <div class="mb-6">
                    <p class="text-sm text-gray-600">
                        Showing {{ $products->firstItem() ?? 0 }} to {{ $products->lastItem() ?? 0 }} of {{ $products->total() }} products
                    </p>
                </div>

                <!-- Product Grid -->
                @if($products->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        @foreach($products as $product)
                            <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-lg transition-shadow duration-300 group">
                                <!-- Product Image -->
                                <div class="relative">
                                    @if($product->discount_price && $product->discount_price < $product->price)
                                        @php
                                            $discountPercentage = round((($product->price - $product->discount_price) / $product->price) * 100);
                                        @endphp
                                        <span class="absolute top-2 left-2 bg-orange-500 text-white text-xs px-2 py-1 rounded-full font-semibold z-10">
                                            -{{ $discountPercentage }}%
                                        </span>
                                    @endif
                                    
                                    <a href="{{ route('product.details', $product->slug) }}">
                                        <img
                                            src="/admin-assets/products/{{ $product->thumbnail }}"
                                            alt="{{ $product->name }}"
                                            class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-300"
                                        />
                                    </a>
                                    
                                    <!-- Quick Actions -->
                                    <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col space-y-2">
                                        <button
                                            onclick="openQuickView({{ $product->id }})"
                                            class="bg-white p-2 rounded-full shadow-md hover:bg-gray-50 transition-colors"
                                            title="Quick View"
                                        >
                                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>
                                        <button
                                            onclick="toggleWishlist({{ $product->id }})"
                                            class="bg-white p-2 rounded-full shadow-md hover:bg-gray-50 transition-colors"
                                            title="Add to Wishlist"
                                        >
                                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Product Info -->
                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2">
                                        <a href="{{ route('product.details', $product->slug) }}" class="hover:text-orange-500 transition-colors">
                                            {{ $product->name }}
                                        </a>
                                    </h3>
                                    
                                    <!-- Price -->
                                    <div class="mb-3">
                                        @if($product->discount_price && $product->discount_price < $product->price)
                                            <span class="text-lg font-bold text-orange-500">৳{{ number_format($product->discount_price, 2) }}</span>
                                            <span class="text-sm text-gray-400 line-through ml-2">৳{{ number_format($product->price, 2) }}</span>
                                        @else
                                            <span class="text-lg font-bold text-orange-500">৳{{ number_format($product->price, 2) }}</span>
                                        @endif
                                    </div>

                                    <!-- Stock Status -->
                                    <div class="mb-3">
                                        @if($product->stock > 0)
                                            <span class="text-xs text-green-600 font-medium">In Stock ({{ $product->stock }})</span>
                                        @else
                                            <span class="text-xs text-red-600 font-medium">Out of Stock</span>
                                        @endif
                                    </div>

                                    <!-- Add to Cart Button -->
                                    <button
                                        onclick="addToCart({{ $product->id }}, 1)"
                                        class="w-full bg-orange-500 text-white py-2 rounded-lg hover:bg-orange-600 transition-colors font-medium text-sm"
                                        {{ $product->stock <= 0 ? 'disabled' : '' }}
                                    >
                                        {{ $product->stock > 0 ? 'Add to Cart' : 'Out of Stock' }}
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $products->appends(request()->query())->links() }}
                    </div>
                @else
                    <!-- No Products Found -->
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No products found</h3>
                        <p class="mt-1 text-sm text-gray-500">Try adjusting your search or filter criteria.</p>
                        <div class="mt-6">
                            <a href="{{ route('shop') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700">
                                View All Products
                            </a>
                        </div>
                    </div>
                @endif
            </main>
        </div>
    </div>

    <!-- Quick View Modal -->
    <div id="quickViewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold">Quick View</h2>
                    <button onclick="closeQuickView()" class="text-gray-600 hover:text-gray-900 text-2xl font-bold">
                        &times;
                    </button>
                </div>
                <div id="quickViewContent">
                    <!-- Quick view content will be populated by JavaScript -->
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
// Global functions for shop page
window.addToCart = function(productId, quantity = 1) {
    fetch('{{ route("cart.add") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: quantity
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Product added to cart!', 'success');
            updateCartCount();
        } else {
            showNotification(data.message || 'Failed to add product to cart', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error adding product to cart', 'error');
    });
};

window.toggleWishlist = function(productId) {
    // Get current wishlist from localStorage
    let wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
    
    // Check if product is already in wishlist
    const index = wishlist.indexOf(productId);
    
    if (index > -1) {
        // Remove from wishlist
        wishlist.splice(index, 1);
        showNotification('Removed from wishlist', 'info');
    } else {
        // Add to wishlist
        wishlist.push(productId);
        showNotification('Added to wishlist', 'success');
    }
    
    // Save to localStorage
    localStorage.setItem('wishlist', JSON.stringify(wishlist));
};

// Show notification function
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-20 right-4 px-6 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-300 ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 'bg-blue-500'
    } text-white`;
    notification.textContent = message;
    notification.style.transform = 'translateX(100%)';
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 10);
    
    // Animate out and remove
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}

// Update cart count
function updateCartCount() {
    fetch('{{ route("cart.data") }}')
        .then(response => response.json())
        .then(data => {
            const cartCount = document.getElementById('cartCount');
            if (cartCount) {
                cartCount.textContent = data.cart_count;
            }
        })
        .catch(error => {
            console.error('Error updating cart count:', error);
        });
}

// Quick view functionality
window.openQuickView = function(productId) {
    // Show loading state
    document.getElementById('quickViewContent').innerHTML = `
        <div class="text-center py-8">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-orange-500 mx-auto"></div>
            <p class="mt-2 text-gray-600">Loading product details...</p>
        </div>
    `;
    document.getElementById('quickViewModal').classList.remove('hidden');
    
    // Fetch product details
    fetch(`/api/products/${productId}`)
        .then(response => response.json())
        .then(product => {
            document.getElementById('quickViewContent').innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <img src="/admin-assets/products/${product.thumbnail}" alt="${product.name}" class="w-full h-96 object-cover rounded-lg">
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold mb-4">${product.name}</h3>
                        <p class="text-gray-600 mb-4">${product.description}</p>
                        <div class="flex items-center mb-4">
                            <div class="flex text-yellow-400">
                                ${'★'.repeat(5)}
                            </div>
                            <span class="text-gray-500 text-sm ml-2">(25 reviews)</span>
                        </div>
                        <div class="mb-6">
                            <span class="text-3xl font-bold text-orange-500">৳${product.price}</span>
                            ${product.discount_price ? `<span class="text-gray-400 line-through ml-2">৳${product.discount_price}</span>` : ''}
                        </div>
                        <div class="flex items-center space-x-4 mb-6">
                            <label class="font-semibold">Quantity:</label>
                            <div class="flex items-center border rounded-lg">
                                <button onclick="decreaseQuickViewQuantity()" class="px-3 py-1 hover:bg-gray-100">-</button>
                                <input type="number" id="quickViewQuantity" value="1" min="1" max="10" class="w-16 text-center border-0">
                                <button onclick="increaseQuickViewQuantity()" class="px-3 py-1 hover:bg-gray-100">+</button>
                            </div>
                        </div>
                        <button onclick="addToCart(${product.id}, parseInt(document.getElementById('quickViewQuantity').value))" class="w-full bg-orange-500 text-white py-3 rounded-lg hover:bg-orange-600 transition-colors">
                            Add to Cart
                        </button>
                    </div>
                </div>
            `;
        })
        .catch(error => {
            console.error('Error fetching product:', error);
            document.getElementById('quickViewContent').innerHTML = `
                <div class="text-center py-8">
                    <p class="text-red-500">Error loading product details</p>
                </div>
            `;
        });
};

window.closeQuickView = function() {
    document.getElementById('quickViewModal').classList.add('hidden');
};

window.increaseQuickViewQuantity = function() {
    const input = document.getElementById('quickViewQuantity');
    const currentValue = parseInt(input.value);
    if (currentValue < 10) {
        input.value = currentValue + 1;
    }
};

window.decreaseQuickViewQuantity = function() {
    const input = document.getElementById('quickViewQuantity');
    const currentValue = parseInt(input.value);
    if (currentValue > 1) {
        input.value = currentValue - 1;
    }
};

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    // Initialize cart count
    updateCartCount();
    
    // Auto-submit form when filters change
    const filterForm = document.getElementById('filterForm');
    const filterInputs = document.querySelectorAll('.filter-checkbox, .filter-input');
    
    filterInputs.forEach(input => {
        input.addEventListener('change', function() {
            // Add a small delay to prevent too many requests
            setTimeout(() => {
                filterForm.submit();
            }, 500);
        });
    });
    
    // Handle search input with debouncing
    const searchInput = document.querySelector('input[name="search"]');
    let searchTimeout;
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                filterForm.submit();
            }, 1000);
        });
    }
});
</script>
@endpush

