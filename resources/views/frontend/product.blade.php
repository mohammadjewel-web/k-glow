@extends('layouts.app')

@section('title', $product->name . ' - K-GLOW')

@section('content')
<main class="pt-28">
    <div class="max-w-6xl mx-auto px-6 py-10 grid grid-cols-1 md:grid-cols-2 gap-12">
        <!-- Left - Product Image -->
        <div class="relative">
            @if($product->discount_price && $product->discount_price < $product->price)
                @php
                    $discountPercentage = round((($product->price - $product->discount_price) / $product->price) * 100);
                @endphp
                <span class="absolute top-4 left-4 bg-orange-500 text-white text-sm px-3 py-1 rounded-full">
                    -{{ $discountPercentage }}%
                </span>
            @endif
            
            <!-- Main Product Image -->
            <div class="relative group">
                <img
                    src="/admin-assets/products/{{ $product->thumbnail }}"
                    alt="{{ $product->name }}"
                    class="w-full rounded-lg shadow cursor-zoom-in transition-transform duration-300 group-hover:scale-105"
                    id="mainProductImage"
                    onclick="openImageModal(this.src)"
                />


               



                <!-- Zoom indicator -->
                <div class="absolute top-2 right-2 bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity">
                    Click to zoom
                </div>
                
                <!-- Image Gallery -->
                <div class="mt-4">
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Product Images</h3>
                    <div class="grid grid-cols-4 gap-2">
                        <!-- Main thumbnail (always first) -->
                        <img
                            src="/admin-assets/products/{{ $product->thumbnail }}"
                            alt="{{ $product->name }}"
                            class="w-full h-20 object-cover rounded cursor-pointer border-2 border-orange-500 hover:border-orange-600 transition-colors"
                            onclick="changeMainImage(this.src, this)"
                            data-image-type="thumbnail"
                        />
                        
                        <!-- Additional product images -->
                        @if($productImages && count($productImages) > 0)
                            @foreach($productImages as $index => $image)
                                <img
                                    src="{{ asset('admin-assets/products/'.$image->image) }}"
                                    alt="{{ $product->name }} - Image {{ $index + 1 }}"
                                    class="w-full h-20 object-cover rounded cursor-pointer border-2 border-gray-200 hover:border-orange-500 transition-colors"
                                    onclick="changeMainImage(this.src, this)"
                                    data-image-type="gallery"
                                />
                            @endforeach
                        @endif
                        
                        <!-- If no additional images, show placeholder or repeat main image -->
                        @if(!$productImages || count($productImages) == 0)
                            @for($i = 1; $i < 4; $i++)
                                <img
                                    src="{{ asset('admin-assets/products/'.$product->thumbnail) }}"
                                    alt="{{ $product->name }}"
                                    class="w-full h-20 object-cover rounded cursor-pointer border-2 border-gray-200 hover:border-orange-500 transition-colors"
                                    onclick="changeMainImage(this.src, this)"
                                    data-image-type="duplicate"
                                />
                            @endfor
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Right - Product Info -->
        <div>
            <!-- Breadcrumb -->
            <nav class="text-sm text-gray-600 mb-4">
                <a href="{{ route('home') }}" class="hover:text-orange-500">Home</a> / 
                <a href="{{ route('shop') }}" class="hover:text-orange-500">Shop</a> / 
                <a href="#" class="hover:text-orange-500">{{ $product->category->name ?? 'Category' }}</a> / 
                <span class="text-gray-800">{{ $product->name }}</span>
            </nav>

            <!-- Title -->
            <h1 class="text-2xl font-bold mb-3">{{ $product->name }}</h1>

            <!-- Rating -->
            <div class="flex items-center gap-2 mb-4">
                <div class="flex text-yellow-400">
                    @for($i = 1; $i <= 5; $i++)
                        <span>★</span>
                    @endfor
                </div>
                <span class="text-gray-500 text-sm">(25 reviews)</span>
            </div>

            <!-- Wishlist and Share Buttons -->
            <div class="mb-4 flex gap-3">
                @auth
                <button 
                    class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg hover:border-orange-500 hover:text-orange-500 transition-colors"
                    onclick="toggleWishlist({{ $product->id }})"
                    id="wishlistBtn"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    <span id="wishlistText">Add to Wishlist</span>
                </button>
                @else
                <a href="{{ route('login') }}" class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg hover:border-orange-500 hover:text-orange-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    <span>Add to Wishlist</span>
                </a>
                @endauth
                
                <button 
                    class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg hover:border-blue-500 hover:text-blue-500 transition-colors"
                    onclick="shareProduct()"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                    </svg>
                    <span>Share</span>
                </button>
                
                <button 
                    class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg hover:border-green-500 hover:text-green-500 transition-colors"
                    onclick="addToComparison({{ $product->id }})"
                    id="compareBtn"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span id="compareText">Add to Compare</span>
                </button>
            </div>

            <!-- Price -->
            <div class="flex items-center gap-3 mb-6">
                @if($product->discount_price && $product->discount_price < $product->price)
                    <span class="text-lg text-gray-400 line-through">৳{{ number_format($product->price, 2) }}</span>
                    <span class="text-2xl font-bold text-orange-500">৳{{ number_format($product->discount_price, 2) }}</span>
                @else
                    <span class="text-2xl font-bold text-orange-500">৳{{ number_format($product->price, 2) }}</span>
                @endif
            </div>

            <!-- Description -->
            <div class="mb-6">
                <p class="text-gray-700">{{ $product->description }}</p>
            </div>

            <!-- Product Details -->
            <div class="mb-6 space-y-2 text-sm">
                <div class="flex">
                    <span class="font-semibold w-24">SKU:</span>
                    <span>{{ $product->sku }}</span>
                </div>
                <div class="flex">
                    <span class="font-semibold w-24">Category:</span>
                    <span>{{ $product->category->name ?? 'N/A' }}</span>
                </div>
                <div class="flex">
                    <span class="font-semibold w-24">Brand:</span>
                    <span>{{ $product->brand->name ?? 'N/A' }}</span>
                </div>
                <div class="flex">
                    <span class="font-semibold w-24">Stock:</span>
                    <span class="{{ $product->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $product->stock > 0 ? 'In Stock (' . $product->stock . ')' : 'Out of Stock' }}
                    </span>
                </div>
            </div>

            <!-- Quantity + Buttons -->
            <div class="flex items-center gap-4 mb-8">
                <!-- Quantity -->
                <div class="flex items-center border rounded-lg overflow-hidden">
                    <button class="px-3 py-2 bg-gray-100 text-lg hover:bg-gray-200" onclick="decreaseQuantity()">-</button>
                    <input
                        type="number"
                        id="productQuantity"
                        value="1"
                        min="1"
                        max="{{ $product->stock }}"
                        class="w-16 text-center border-x outline-none"
                    />
                    <button class="px-3 py-2 bg-gray-100 text-lg hover:bg-gray-200" onclick="increaseQuantity()">+</button>
                </div>
                
                <!-- Add to cart -->
                <button
                    class="px-6 py-2 bg-orange-500 text-white font-semibold rounded-full hover:bg-orange-600 transition-colors"
                    onclick="addToCart({{ $product->id }}, parseInt(document.getElementById('productQuantity').value))"
                    {{ $product->stock <= 0 ? 'disabled' : '' }}
                >
                    {{ $product->stock > 0 ? 'Add to Cart' : 'Out of Stock' }}
                </button>
                
                <!-- Buy now -->
                <button
                    class="px-6 py-2 bg-red-600 text-white font-semibold rounded-full hover:bg-red-700 transition-colors"
                    onclick="buyNow({{ $product->id }}, parseInt(document.getElementById('productQuantity').value))"
                    {{ $product->stock <= 0 ? 'disabled' : '' }}
                >
                    Buy Now
                </button>
            </div>

            <!-- Delivery Info -->
            <div class="space-y-4">
                <div class="border rounded-xl p-4">
                    <p class="font-semibold">Pick up from the K-GLOW Store</p>
                    <div class="flex justify-between text-sm text-gray-600 mt-1">
                        <span>To pick up today</span>
                        <span class="font-semibold">Free</span>
                    </div>
                </div>

                <div class="border rounded-xl p-4">
                    <p class="font-semibold">Courier delivery</p>
                    <div class="flex justify-between text-sm text-gray-600 mt-1">
                        <span>2-3 Days</span>
                        <span class="font-semibold">60/120৳</span>
                    </div>
                </div>

                <div class="border rounded-xl p-4">
                    <p class="font-semibold">Authentic Product Guarantee</p>
                    <div class="flex justify-between text-sm text-gray-600 mt-1">
                        <span>Delivery Charge Free Over 5000৳ Purchase</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="max-w-6xl mx-auto px-6 mt-12 border-t">
        <div class="flex gap-8 font-semibold text-gray-600 mt-4">
            <button
                class="relative pb-2 text-orange-500 border-b-2 border-orange-500"
                onclick="showTab('additional-info')"
            >
                ADDITIONAL INFORMATION
            </button>
            <button class="pb-2 hover:text-orange-500" onclick="showTab('reviews')">
                REVIEWS (0)
            </button>
            <button class="pb-2 hover:text-orange-500" onclick="showTab('shipping')">
                SHIPPING & DELIVERY
            </button>
        </div>
        
        <!-- Tab Content -->
        <div class="mt-6 text-gray-700 text-sm">
            <div id="additional-info" class="tab-content">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-semibold mb-2">Product Information</h4>
                        <div class="space-y-2">
                            <div class="flex">
                                <span class="font-medium w-32">Name:</span>
                                <span>{{ $product->name }}</span>
                            </div>
                            <div class="flex">
                                <span class="font-medium w-32">SKU:</span>
                                <span>{{ $product->sku }}</span>
                            </div>
                            <div class="flex">
                                <span class="font-medium w-32">Category:</span>
                                <span>{{ $product->category->name ?? 'N/A' }}</span>
                            </div>
                            <div class="flex">
                                <span class="font-medium w-32">Brand:</span>
                                <span>{{ $product->brand->name ?? 'N/A' }}</span>
                            </div>
                            @if($product->weight)
                                <div class="flex">
                                    <span class="font-medium w-32">Weight:</span>
                                    <span>{{ $product->weight }}</span>
                                </div>
                            @endif
                            @if($product->material)
                                <div class="flex">
                                    <span class="font-medium w-32">Material:</span>
                                    <span>{{ $product->material }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div>
                        <h4 class="font-semibold mb-2">Description</h4>
                        <p>{{ $product->description }}</p>
                        @if($product->short_description)
                            <p class="mt-2 text-sm text-gray-600">{{ $product->short_description }}</p>
                        @endif
                    </div>
                </div>
            </div>
            
            <div id="reviews" class="tab-content hidden">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-4">Customer Reviews</h3>
                    
                    <!-- Review Stats -->
                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="text-3xl font-bold text-gray-900 mr-4" id="averageRating">0.0</div>
                                <div>
                                    <div class="flex text-yellow-400 mb-1" id="ratingStars">
                                        <span>☆</span>
                                        <span>☆</span>
                                        <span>☆</span>
                                        <span>☆</span>
                                        <span>☆</span>
                                    </div>
                                    <p class="text-sm text-gray-600" id="totalReviews">0 reviews</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-600">Based on <span id="reviewsCount">0</span> reviews</p>
                            </div>
                        </div>
                        
                        <!-- Rating Distribution -->
                        <div class="mt-4 space-y-2" id="ratingDistribution">
                            <!-- Will be populated by JavaScript -->
                        </div>
                    </div>

                    <!-- Write Review Button -->
                    @auth
                    <div class="mb-6">
                        <button 
                            onclick="openReviewModal()" 
                            class="bg-orange-500 text-white px-6 py-2 rounded-lg hover:bg-orange-600 transition-colors"
                            id="writeReviewBtn"
                        >
                            Write a Review
                        </button>
                    </div>
                    @else
                    <div class="mb-6">
                        <a 
                            href="{{ route('login') }}" 
                            class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition-colors inline-block"
                        >
                            Login to Write a Review
                        </a>
                    </div>
                    @endauth
                </div>

                <!-- Reviews List -->
                <div id="reviewsList">
                    <!-- Reviews will be loaded here by JavaScript -->
                </div>

                <!-- Load More Button -->
                <div class="text-center mt-6" id="loadMoreContainer" style="display: none;">
                    <button 
                        onclick="loadMoreReviews()" 
                        class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-200 transition-colors"
                        id="loadMoreBtn"
                    >
                        Load More Reviews
                    </button>
                </div>
            </div>
            
            <div id="shipping" class="tab-content hidden">
                <div class="space-y-4">
                    <div>
                        <h4 class="font-semibold mb-2">Shipping Information</h4>
                        <ul class="list-disc list-inside space-y-1 text-sm">
                            <li>Free shipping on orders over ৳5000</li>
                            <li>Standard delivery: 2-3 business days</li>
                            <li>Express delivery: 1-2 business days</li>
                            <li>Same-day delivery available in selected areas</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold mb-2">Return Policy</h4>
                        <ul class="list-disc list-inside space-y-1 text-sm">
                            <li>30-day return policy</li>
                            <li>Items must be in original condition</li>
                            <li>Free return shipping for defective items</li>
                            <li>Refund processed within 5-7 business days</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts && $relatedProducts->count() > 0)
        <div class="max-w-6xl mx-auto px-6 mt-12">
            <h2 class="text-xl font-bold mb-6">Related Products</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                @foreach($relatedProducts as $relatedProduct)
                    <div class="border rounded-xl p-4 hover:shadow-lg transition">
                        <a href="{{ route('product.details', $relatedProduct->slug) }}">
                            <img
                                src="/admin-assets/products/{{ $relatedProduct->thumbnail }}"
                                alt="{{ $relatedProduct->name }}"
                                class="w-full h-48 object-cover rounded-lg mb-4"
                            />
                        </a>
                        <h3 class="font-semibold text-gray-700 mb-2">
                            <a href="{{ route('product.details', $relatedProduct->slug) }}" class="hover:text-orange-500">
                                {{ $relatedProduct->name }}
                            </a>
                        </h3>
                        <div class="flex items-center gap-2 mb-3">
                            @if($relatedProduct->discount_price && $relatedProduct->discount_price < $relatedProduct->price)
                                <span class="line-through text-gray-400 text-sm">৳{{ number_format($relatedProduct->price, 2) }}</span>
                                <span class="text-orange-500 font-bold">৳{{ number_format($relatedProduct->discount_price, 2) }}</span>
                            @else
                                <span class="text-orange-500 font-bold">৳{{ number_format($relatedProduct->price, 2) }}</span>
                            @endif
                        </div>
                        <button
                            class="w-full px-4 py-2 bg-orange-500 text-white rounded-full hover:bg-orange-800 transition-colors"
                            onclick="addToCart({{ $relatedProduct->id }}, 1)"
                        >
                            Add to Cart
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</main>

<!-- Product Modal (for quick view) -->
<div id="productModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">Product Details</h2>
                <button id="closeProductModal" class="text-gray-600 hover:text-gray-900 text-2xl font-bold">
                    &times;
                </button>
            </div>
            <div id="modalContent" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Modal content will be populated by JavaScript -->
            </div>
        </div>
    </div>
</div>
<!-- Review Modal -->
<div id="reviewModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold">Write a Review</h3>
                <button onclick="closeReviewModal()" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form id="reviewForm">
                @csrf
                <input type="hidden" id="reviewProductId" value="{{ $product->id }}">
                
                <!-- Rating -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rating *</label>
                    <div class="flex items-center space-x-1" id="ratingInput">
                        <button type="button" onclick="setRating(1)" class="text-2xl text-gray-300 hover:text-yellow-400 transition-colors" data-rating="1">☆</button>
                        <button type="button" onclick="setRating(2)" class="text-2xl text-gray-300 hover:text-yellow-400 transition-colors" data-rating="2">☆</button>
                        <button type="button" onclick="setRating(3)" class="text-2xl text-gray-300 hover:text-yellow-400 transition-colors" data-rating="3">☆</button>
                        <button type="button" onclick="setRating(4)" class="text-2xl text-gray-300 hover:text-yellow-400 transition-colors" data-rating="4">☆</button>
                        <button type="button" onclick="setRating(5)" class="text-2xl text-gray-300 hover:text-yellow-400 transition-colors" data-rating="5">☆</button>
                    </div>
                    <input type="hidden" id="ratingValue" name="rating" required>
                </div>
                
                <!-- Title -->
                <div class="mb-6">
                    <label for="reviewTitle" class="block text-sm font-medium text-gray-700 mb-2">Review Title</label>
                    <input 
                        type="text" 
                        id="reviewTitle" 
                        name="title" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                        placeholder="Summarize your review"
                        maxlength="255"
                    >
                </div>
                
                <!-- Comment -->
                <div class="mb-6">
                    <label for="reviewComment" class="block text-sm font-medium text-gray-700 mb-2">Your Review</label>
                    <textarea 
                        id="reviewComment" 
                        name="comment" 
                        rows="4" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                        placeholder="Tell others about your experience with this product"
                        maxlength="2000"
                    ></textarea>
                    <div class="text-right text-sm text-gray-500 mt-1">
                        <span id="commentCount">0</span>/2000 characters
                    </div>
                </div>
                
                <!-- Submit Button -->
                <div class="flex justify-end space-x-3">
                    <button 
                        type="button" 
                        onclick="closeReviewModal()" 
                        class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
                    >
                        Cancel
                    </button>
                    <button 
                        type="submit" 
                        class="px-6 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors"
                    >
                        Submit Review
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Global functions for product page
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

window.buyNow = function(productId, quantity = 1) {
    // Add to cart first, then redirect to checkout
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
            window.location.href = '{{ route("checkout") }}';
        } else {
            showNotification(data.message || 'Failed to add product to cart', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error adding product to cart', 'error');
    });
};

window.changeMainImage = function(imageSrc, clickedElement) {
    const mainImage = document.getElementById('mainProductImage');
    if (mainImage) {
        mainImage.src = imageSrc;
        
        // Add loading effect
        mainImage.style.opacity = '0.7';
        mainImage.onload = function() {
            mainImage.style.opacity = '1';
        };
    }
    
    // Update active thumbnail
    document.querySelectorAll('.grid img').forEach(img => {
        img.classList.remove('border-orange-500', 'border-orange-600');
        img.classList.add('border-gray-200');
    });
    
    if (clickedElement) {
        clickedElement.classList.remove('border-gray-200');
        clickedElement.classList.add('border-orange-500');
    }
    
    // Add smooth transition
    if (mainImage) {
        mainImage.style.transition = 'opacity 0.3s ease-in-out';
    }
};

window.increaseQuantity = function() {
    const input = document.getElementById('productQuantity');
    const currentValue = parseInt(input.value);
    const maxValue = parseInt(input.getAttribute('max'));
    
    if (currentValue < maxValue) {
        input.value = currentValue + 1;
    }
};

window.decreaseQuantity = function() {
    const input = document.getElementById('productQuantity');
    const currentValue = parseInt(input.value);
    
    if (currentValue > 1) {
        input.value = currentValue - 1;
    }
};

window.showTab = function(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active class from all buttons
    document.querySelectorAll('.flex button').forEach(button => {
        button.classList.remove('text-orange-500', 'border-b-2', 'border-orange-500');
        button.classList.add('hover:text-orange-500');
    });
    
    // Show selected tab content
    document.getElementById(tabName).classList.remove('hidden');
    
    // Add active class to clicked button
    event.target.classList.add('text-orange-500', 'border-b-2', 'border-orange-500');
    event.target.classList.remove('hover:text-orange-500');
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

// Wishlist functionality
window.toggleWishlist = function(productId) {
    fetch('/customer/wishlist/toggle', {
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
            const wishlistBtn = document.getElementById('wishlistBtn');
            const wishlistText = document.getElementById('wishlistText');
            
            if (data.in_wishlist) {
                wishlistText.textContent = 'Remove from Wishlist';
                wishlistBtn.classList.remove('border-gray-300');
                wishlistBtn.classList.add('border-orange-500', 'text-orange-500');
            } else {
                wishlistText.textContent = 'Add to Wishlist';
                wishlistBtn.classList.remove('border-orange-500', 'text-orange-500');
                wishlistBtn.classList.add('border-gray-300');
            }
            
            showNotification(data.message, 'success');
            
            // Update wishlist count in header
            if (typeof updateWishlistCount === 'function') {
                updateWishlistCount();
            }
        } else {
            showNotification(data.message || 'Failed to update wishlist', 'error');
        }
    })
    .catch(error => {
        console.error('Error updating wishlist:', error);
        showNotification('Error updating wishlist', 'error');
    });
};

// Check wishlist status on page load
function checkWishlistStatus(productId) {
    fetch(`/customer/wishlist/check?product_id=${productId}`)
        .then(response => response.json())
        .then(data => {
            if (data.in_wishlist) {
                document.getElementById('wishlistBtn').classList.add('border-orange-500', 'text-orange-500');
                document.getElementById('wishlistBtn').classList.remove('border-gray-300');
                document.getElementById('wishlistText').textContent = 'Remove from Wishlist';
            }
        })
        .catch(error => {
            console.error('Error checking wishlist status:', error);
        });
}

// Review functionality
let currentPage = 1;
let isLoading = false;

// Load reviews when reviews tab is shown
function loadReviews() {
    const productId = {{ $product->id }};
    const reviewsList = document.getElementById('reviewsList');
    const loadMoreContainer = document.getElementById('loadMoreContainer');
    
    if (isLoading) return;
    isLoading = true;
    
    fetch(`/api/products/${productId}/reviews?page=${currentPage}`)
        .then(response => response.json())
        .then(data => {
            if (currentPage === 1) {
                // Update stats
                document.getElementById('averageRating').textContent = data.product_stats.average_rating;
                document.getElementById('reviewsCount').textContent = data.product_stats.total_reviews;
                document.getElementById('totalReviews').textContent = `${data.product_stats.total_reviews} reviews`;
                
                // Update rating stars
                updateRatingStars(data.product_stats.average_rating);
                
                // Update rating distribution
                updateRatingDistribution(data.product_stats.rating_distribution);
                
                // Clear existing reviews
                reviewsList.innerHTML = '';
            }
            
            // Add new reviews
            if (data.reviews.length > 0) {
                data.reviews.forEach(review => {
                    reviewsList.appendChild(createReviewElement(review));
                });
                
                // Show/hide load more button
                if (currentPage < data.pagination.last_page) {
                    loadMoreContainer.style.display = 'block';
                } else {
                    loadMoreContainer.style.display = 'none';
                }
            } else if (currentPage === 1) {
                reviewsList.innerHTML = '<p class="text-center text-gray-500 py-8">No reviews yet. Be the first to review this product!</p>';
            }
            
            isLoading = false;
        })
        .catch(error => {
            console.error('Error loading reviews:', error);
            isLoading = false;
        });
}

// Load more reviews
function loadMoreReviews() {
    currentPage++;
    loadReviews();
}

// Update rating stars display
function updateRatingStars(rating) {
    const stars = document.getElementById('ratingStars');
    const fullStars = Math.floor(rating);
    const hasHalfStar = rating % 1 >= 0.5;
    
    let starsHTML = '';
    for (let i = 1; i <= 5; i++) {
        if (i <= fullStars) {
            starsHTML += '<span class="text-yellow-400">★</span>';
        } else if (i === fullStars + 1 && hasHalfStar) {
            starsHTML += '<span class="text-yellow-400">☆</span>';
        } else {
            starsHTML += '<span class="text-gray-300">☆</span>';
        }
    }
    stars.innerHTML = starsHTML;
}

// Update rating distribution
function updateRatingDistribution(distribution) {
    const container = document.getElementById('ratingDistribution');
    const total = Object.values(distribution).reduce((sum, count) => sum + count, 0);
    
    let html = '';
    for (let i = 5; i >= 1; i--) {
        const count = distribution[i] || 0;
        const percentage = total > 0 ? (count / total) * 100 : 0;
        
        html += `
            <div class="flex items-center">
                <span class="text-sm w-8">${i}★</span>
                <div class="flex-1 mx-2 bg-gray-200 rounded-full h-2">
                    <div class="bg-yellow-400 h-2 rounded-full" style="width: ${percentage}%"></div>
                </div>
                <span class="text-sm text-gray-600 w-8">${count}</span>
            </div>
        `;
    }
    container.innerHTML = html;
}

// Create review element
function createReviewElement(review) {
    const div = document.createElement('div');
    div.className = 'border-b pb-4 mb-4';
    div.innerHTML = `
        <div class="flex items-start justify-between mb-2">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-gray-300 rounded-full mr-3 flex items-center justify-center text-sm font-medium">
                    ${review.user.name.charAt(0).toUpperCase()}
                </div>
                <div>
                    <p class="font-medium">${review.user.name}</p>
                    <div class="flex items-center">
                        <div class="flex text-yellow-400 mr-2">
                            ${'★'.repeat(review.rating)}${'☆'.repeat(5 - review.rating)}
                        </div>
                        ${review.is_verified_purchase ? '<span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">Verified Purchase</span>' : ''}
                    </div>
                </div>
            </div>
            <span class="text-sm text-gray-500">${new Date(review.created_at).toLocaleDateString()}</span>
        </div>
        ${review.title ? `<h4 class="font-medium mb-2">${review.title}</h4>` : ''}
        <p class="text-gray-700 mb-3">${review.comment || ''}</p>
        <div class="flex items-center justify-between">
            <button onclick="toggleHelpful(${review.id})" class="text-sm text-gray-500 hover:text-orange-500 transition-colors">
                <span class="helpful-count-${review.id}">${review.helpful_votes_count || 0}</span> people found this helpful
            </button>
        </div>
    `;
    return div;
}

// Review modal functions
function openReviewModal() {
    document.getElementById('reviewModal').classList.remove('hidden');
}

function closeReviewModal() {
    document.getElementById('reviewModal').classList.add('hidden');
    document.getElementById('reviewForm').reset();
    document.getElementById('ratingValue').value = '';
    document.getElementById('commentCount').textContent = '0';
    
    // Reset rating stars
    const stars = document.querySelectorAll('#ratingInput button');
    stars.forEach(star => {
        star.classList.remove('text-yellow-400');
        star.classList.add('text-gray-300');
    });
}

// Set rating
function setRating(rating) {
    document.getElementById('ratingValue').value = rating;
    
    const stars = document.querySelectorAll('#ratingInput button');
    stars.forEach((star, index) => {
        if (index < rating) {
            star.classList.remove('text-gray-300');
            star.classList.add('text-yellow-400');
        } else {
            star.classList.remove('text-yellow-400');
            star.classList.add('text-gray-300');
        }
    });
}

// Toggle helpful vote
function toggleHelpful(reviewId) {
    fetch(`/api/reviews/${reviewId}/helpful`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const countElement = document.querySelector(`.helpful-count-${reviewId}`);
            if (countElement) {
                countElement.textContent = data.helpful_count;
            }
        }
    })
    .catch(error => {
        console.error('Error toggling helpful vote:', error);
    });
}

// Update tab function to load reviews
const originalShowTab = window.showTab;
window.showTab = function(tabName) {
    originalShowTab(tabName);
    if (tabName === 'reviews') {
        currentPage = 1;
        loadReviews();
    }
};

// Character count for comment
document.getElementById('reviewComment').addEventListener('input', function() {
    document.getElementById('commentCount').textContent = this.value.length;
});

// Submit review form
document.getElementById('reviewForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const productId = document.getElementById('reviewProductId').value;
    const rating = document.getElementById('ratingValue').value;
    
    if (!rating) {
        showNotification('Please select a rating', 'error');
        return;
    }
    
    // Check if user is authenticated
    @auth
    @else
    showNotification('Please login to submit a review', 'error');
    return;
    @endauth
    
    const data = {
        product_id: parseInt(productId),
        rating: parseInt(rating),
        title: formData.get('title'),
        comment: formData.get('comment')
    };
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        showNotification('CSRF token not found', 'error');
        return;
    }
    
    fetch('/customer/reviews', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken.getAttribute('content')
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Review submitted successfully!', 'success');
            closeReviewModal();
            // Reload reviews
            currentPage = 1;
            loadReviews();
        } else {
            showNotification(data.message || 'Failed to submit review', 'error');
        }
    })
    .catch(error => {
        console.error('Error submitting review:', error);
        showNotification('Error submitting review', 'error');
    });
});

// Share product functionality
window.shareProduct = function() {
    const productName = '{{ $product->name }}';
    const productUrl = window.location.href;
    const shareText = `Check out this amazing product: ${productName}`;
    
    if (navigator.share) {
        // Use native share API if available
        navigator.share({
            title: productName,
            text: shareText,
            url: productUrl
        }).then(() => {
            showNotification('Product shared successfully!', 'success');
        }).catch((error) => {
            console.log('Error sharing:', error);
            fallbackShare(productName, shareText, productUrl);
        });
    } else {
        // Fallback for browsers that don't support native share
        fallbackShare(productName, shareText, productUrl);
    }
};

function fallbackShare(productName, shareText, productUrl) {
    // Create a modal with share options
    const shareModal = document.createElement('div');
    shareModal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4';
    shareModal.innerHTML = `
        <div class="bg-white rounded-lg p-6 max-w-md w-full">
            <h3 class="text-lg font-semibold mb-4">Share Product</h3>
            <div class="space-y-3">
                <button onclick="shareToFacebook('${productUrl}')" class="w-full flex items-center gap-3 p-3 border rounded-lg hover:bg-gray-50">
                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                    <span>Share on Facebook</span>
                </button>
                <button onclick="shareToTwitter('${shareText}', '${productUrl}')" class="w-full flex items-center gap-3 p-3 border rounded-lg hover:bg-gray-50">
                    <svg class="w-6 h-6 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                    </svg>
                    <span>Share on Twitter</span>
                </button>
                <button onclick="copyToClipboard('${productUrl}')" class="w-full flex items-center gap-3 p-3 border rounded-lg hover:bg-gray-50">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                    <span>Copy Link</span>
                </button>
            </div>
            <button onclick="closeShareModal()" class="mt-4 w-full py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                Close
            </button>
        </div>
    `;
    
    document.body.appendChild(shareModal);
}

window.shareToFacebook = function(url) {
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`, '_blank', 'width=600,height=400');
    closeShareModal();
};

window.shareToTwitter = function(text, url) {
    window.open(`https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}&url=${encodeURIComponent(url)}`, '_blank', 'width=600,height=400');
    closeShareModal();
};

window.copyToClipboard = function(text) {
    navigator.clipboard.writeText(text).then(() => {
        showNotification('Link copied to clipboard!', 'success');
        closeShareModal();
    }).catch(() => {
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        showNotification('Link copied to clipboard!', 'success');
        closeShareModal();
    });
};

window.closeShareModal = function() {
    const modal = document.querySelector('.fixed.inset-0.bg-black.bg-opacity-50');
    if (modal) {
        modal.remove();
    }
};

// Product comparison functionality
window.addToComparison = function(productId) {
    // Get current comparison list from localStorage
    let comparison = JSON.parse(localStorage.getItem('comparison')) || [];
    
    // Check if product is already in comparison
    const index = comparison.indexOf(productId);
    
    if (index > -1) {
        // Remove from comparison
        comparison.splice(index, 1);
        document.getElementById('compareBtn').classList.remove('border-green-500', 'text-green-500');
        document.getElementById('compareBtn').classList.add('border-gray-300');
        document.getElementById('compareText').textContent = 'Add to Compare';
        showNotification('Removed from comparison', 'info');
    } else {
        // Check if comparison list is full (max 4 products)
        if (comparison.length >= 4) {
            showNotification('You can compare maximum 4 products', 'error');
            return;
        }
        
        // Add to comparison
        comparison.push(productId);
        document.getElementById('compareBtn').classList.add('border-green-500', 'text-green-500');
        document.getElementById('compareBtn').classList.remove('border-gray-300');
        document.getElementById('compareText').textContent = 'Remove from Compare';
        showNotification('Added to comparison', 'success');
    }
    
    // Save to localStorage
    localStorage.setItem('comparison', JSON.stringify(comparison));
    
    // Update comparison count in header if exists
    updateComparisonCount();
};

// Check comparison status on page load
function checkComparisonStatus(productId) {
    const comparison = JSON.parse(localStorage.getItem('comparison')) || [];
    const isInComparison = comparison.includes(productId);
    
    if (isInComparison) {
        document.getElementById('compareBtn').classList.add('border-green-500', 'text-green-500');
        document.getElementById('compareBtn').classList.remove('border-gray-300');
        document.getElementById('compareText').textContent = 'Remove from Compare';
    }
}

// Update comparison count in header
function updateComparisonCount() {
    const comparison = JSON.parse(localStorage.getItem('comparison')) || [];
    const comparisonCount = document.getElementById('comparisonCount');
    if (comparisonCount) {
        comparisonCount.textContent = comparison.length;
    }
}

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    // Initialize cart count
    updateCartCount();
    
    // Set initial active tab
    showTab('additional-info');
    
    // Check wishlist status
    checkWishlistStatus({{ $product->id }});
    
    // Check comparison status
    checkComparisonStatus({{ $product->id }});
    
    // Update comparison count
    updateComparisonCount();
    
    // Debug: Check if images are loaded
    console.log('Product ID:', {{ $product->id }});
    console.log('Product Images Count:', {{ $productImages ? count($productImages) : 0 }});
    console.log('Product Thumbnail:', '{{ $product->thumbnail }}');
    
    // Check if gallery images exist
    const galleryImages = document.querySelectorAll('[data-image-type="gallery"]');
    console.log('Gallery images found:', galleryImages.length);
});

// Image modal functionality
window.openImageModal = function(imageSrc) {
    // Create modal if it doesn't exist
    let modal = document.getElementById('imageModal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'imageModal';
        modal.className = 'fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4 hidden';
        modal.innerHTML = `
            <div class="relative max-w-4xl max-h-full">
                <img id="modalImage" src="" alt="" class="max-w-full max-h-full object-contain rounded-lg">
                <button onclick="closeImageModal()" class="absolute top-4 right-4 bg-white bg-opacity-20 text-white text-2xl font-bold w-10 h-10 rounded-full flex items-center justify-center hover:bg-opacity-30 transition-colors">
                    ×
                </button>
            </div>
        `;
        document.body.appendChild(modal);
    }
    
    // Set image and show modal
    document.getElementById('modalImage').src = imageSrc;
    modal.classList.remove('hidden');
    
    // Close on background click
    modal.onclick = function(e) {
        if (e.target === modal) {
            closeImageModal();
        }
    };
};

window.closeImageModal = function() {
    const modal = document.getElementById('imageModal');
    if (modal) {
        modal.classList.add('hidden');
    }
};
</script>
@endpush
