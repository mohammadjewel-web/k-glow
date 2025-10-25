@extends('layouts.app')

@section('title', 'My Wishlist - K-GLOW')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">My Wishlist</h1>
            <p class="text-gray-600 mt-2">Your saved favorite products</p>
        </div>

        @if($wishlistItems->count() > 0)
            <!-- Wishlist Items -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($wishlistItems as $wishlistItem)
                    @php $product = $wishlistItem->product; @endphp
                    <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group">
                        <div class="relative">
                            <a href="{{ route('product.details', $product->slug) }}">
                                <img 
                                    src="{{ asset('admin-assets/products/'.$product->thumbnail) }}" 
                                    alt="{{ $product->name }}" 
                                    class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-300"
                                >
                            </a>
                            
                            <!-- Remove from wishlist button -->
                            <button 
                                onclick="removeFromWishlist({{ $product->id }})"
                                class="absolute top-2 right-2 bg-red-500 text-white p-2 rounded-full hover:bg-red-600 transition-colors opacity-0 group-hover:opacity-100"
                                title="Remove from wishlist"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                            
                            <!-- Sale badge -->
                            @if($product->discount_price)
                                <div class="absolute top-2 left-2 bg-red-500 text-white px-2 py-1 rounded-full text-xs font-semibold">
                                    SALE
                                </div>
                            @endif
                        </div>
                        
                        <div class="p-4">
                            <a href="{{ route('product.details', $product->slug) }}">
                                <h3 class="text-lg font-semibold text-gray-800 hover:text-orange-500 transition-colors line-clamp-2 mb-2">
                                    {{ $product->name }}
                                </h3>
                            </a>
                            
                            <!-- Rating -->
                            <div class="flex items-center mb-3">
                                <div class="flex text-yellow-400">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span>★</span>
                                    @endfor
                                </div>
                                <span class="text-sm text-gray-500 ml-2">(4.5)</span>
                            </div>
                            
                            <!-- Price -->
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    @if($product->discount_price)
                                        <p class="text-orange-500 font-bold text-lg">৳{{ number_format($product->discount_price, 2) }}</p>
                                        <p class="text-gray-400 line-through text-sm">৳{{ number_format($product->price, 2) }}</p>
                                    @else
                                        <p class="text-orange-500 font-bold text-lg">৳{{ number_format($product->price, 2) }}</p>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Actions -->
                            <div class="flex gap-2">
                                <button 
                                    onclick="addToCart({{ $product->id }}, 1)"
                                    class="flex-1 bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition-colors font-medium"
                                    {{ $product->stock <= 0 ? 'disabled' : '' }}
                                >
                                    {{ $product->stock > 0 ? 'Add to Cart' : 'Out of Stock' }}
                                </button>
                                <a 
                                    href="{{ route('product.details', $product->slug) }}"
                                    class="flex-1 bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors font-medium text-center"
                                >
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty Wishlist -->
            <div class="text-center py-16">
                <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Your wishlist is empty</h3>
                <p class="text-gray-600 mb-6">Start adding products you love to your wishlist</p>
                <a 
                    href="{{ route('shop') }}" 
                    class="inline-flex items-center px-6 py-3 bg-orange-500 text-white font-medium rounded-lg hover:bg-orange-600 transition-colors"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    Start Shopping
                </a>
            </div>
        @endif
    </div>
</div>

<script>
function removeFromWishlist(productId) {
    if (confirm('Are you sure you want to remove this product from your wishlist?')) {
        fetch('/customer/wishlist/remove', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                product_id: productId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove the product card from the page
                const productCard = document.querySelector(`[data-product-id="${productId}"]`);
                if (productCard) {
                    productCard.closest('.grid > div').remove();
                }
                
                // Show success message
                showNotification('Product removed from wishlist', 'success');
                
                // Check if wishlist is now empty
                const remainingItems = document.querySelectorAll('.grid > div').length;
                if (remainingItems === 0) {
                    location.reload(); // Reload to show empty state
                }
            } else {
                showNotification('Failed to remove product from wishlist', 'error');
            }
        })
        .catch(error => {
            console.error('Error removing from wishlist:', error);
            showNotification('Error removing product from wishlist', 'error');
        });
    }
}

function addToCart(productId, quantity) {
    fetch('/cart/add', {
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
            showNotification('Product added to cart', 'success');
            // Update cart count if the function exists
            if (typeof updateCartCount === 'function') {
                updateCartCount();
            }
        } else {
            showNotification(data.message || 'Failed to add product to cart', 'error');
        }
    })
    .catch(error => {
        console.error('Error adding to cart:', error);
        showNotification('Error adding product to cart', 'error');
    });
}

function showNotification(message, type) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ${
        type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Remove notification after 3 seconds
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script>
@endsection

