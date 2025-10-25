@extends('layouts.app')

@section('title', 'K-Glow - Premium Korean Beauty Products')

@section('content')
<!-- Hero slider -->
@if($sliders->count() > 0)
<section class="relative w-full h-[420px] sm:h-[520px]">
    <div class="overflow-hidden w-full h-full">
        <div id="slider" class="flex transition-transform duration-700 h-full">
            @foreach($sliders as $slider)
            <!-- Slide {{ $loop->iteration }} -->
            <div class="min-w-full h-full relative flex items-center justify-center">
                <img src="{{ asset($slider->image) }}" alt="{{ $slider->title }}"
                    class="w-full h-full object-cover" />
                
                <!-- Overlay content -->
                @if($slider->title || $slider->description || ($slider->button_text && $slider->button_link))
                <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center justify-center">
                    <div class="text-center text-white px-4 max-w-3xl">
                        @if($slider->title)
                        <h2 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-4 drop-shadow-lg">
                            {{ $slider->title }}
                        </h2>
                        @endif
                        @if($slider->description)
                        <p class="text-lg md:text-xl mb-6 drop-shadow-md">
                            {{ $slider->description }}
                        </p>
                        @endif
                        @if($slider->button_text && $slider->button_link)
                        <a href="{{ $slider->button_link }}" 
                           class="inline-block px-8 py-3 bg-[var(--brand-orange)] text-white rounded-full font-semibold hover:bg-orange-600 transition-all duration-300 shadow-lg hover:shadow-xl">
                            {{ $slider->button_text }}
                        </a>
                        @endif
            </div>
            </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>

    <!-- Navigation buttons -->
    @if($sliders->count() > 1)
    <button id="prev" class="absolute left-4 top-1/2 -translate-y-1/2 bg-white rounded-full p-3 shadow hover:bg-gray-100 transition">
        ‹
    </button>
    <button id="next" class="absolute right-4 top-1/2 -translate-y-1/2 bg-white rounded-full p-3 shadow hover:bg-gray-100 transition">
        ›
    </button>

    <!-- Dots -->
    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-2">
        @foreach($sliders as $index => $slider)
        <div class="hero-dot bg-white/{{ $index === 0 ? '60' : '30' }}" data-slide="{{ $index }}"></div>
        @endforeach
    </div>
    @endif
</section>
@endif

<!-- Slogan -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <p id="slogan" class="text-center text-2xl font-semibold mb-0 transition-all duration-500"></p>
</section>


<!-- Categories -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <h3 class="text-xl font-semibold mb-6 flex items-center">
        <span class="flex-1 h-px bg-gray-300"></span>
        <span class="px-4 text-center">Shop by Featured Categories</span>
        <span class="flex-1 h-px bg-gray-300"></span>
    </h3>

    <!-- Category Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-6">
        @forelse($categories as $category)
        <div
            class="bg-white rounded-lg shadow p-4 flex flex-col items-center transform transition duration-300 hover:scale-105 hover:shadow-2xl hover:bg-indigo-50 cursor-pointer">
            <div
                class="w-20 h-20 rounded-lg flex items-center justify-center mb-3 bg-gray-50 hover:bg-indigo-100 transition duration-300">
                <a href="{{ route('shop', ['categories' => [$category->id]]) }}">
                <img src="{{ $category->image ? asset('admin-assets/categories/'.$category->image) : 'https://via.placeholder.com/80' }}"
                    alt="{{ $category->name }}" class="w-10 h-10 object-contain" /></a>
            </div>
            <div class="text-sm text-center">{{ $category->name }}</div>
        </div>
        @empty
        <p class="col-span-6 text-center text-gray-500">No featured categories found.</p>
        @endforelse
    </div>
</section>
 <!-- Dynamic Categories with Subcategories Section 1 -->
@php
    $category1 = \App\Models\Category::with(['subcategories' => function($query) {
        $query->where('status', 1)->orderBy('sort_order', 'asc')->take(6);
    }])->where('status', 1)->where('is_featured', 1)->first();
@endphp

@if($category1 && $category1->subcategories->count() > 0)
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h3 class="text-xl font-semibold mb-6 flex items-center">
        <span class="flex-1 h-px bg-gray-300"></span>
        <span class="px-4 text-center">{{ $category1->name }}</span>
        <span class="flex-1 h-px bg-gray-300"></span>
    </h3>
    <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-6">
        @foreach($category1->subcategories as $subcategory)
        <a href="{{ route('shop') }}?subcategory={{ $subcategory->id }}"
            class="bg-white rounded-lg shadow p-1 flex flex-col items-center hover:scale-105 hover:shadow-xl transition-transform cursor-pointer">
            @if($subcategory->image)
            <img
                src="{{ asset('admin-assets/subcategories/' . $subcategory->image) }}"
                class="rounded w-full h-auto object-cover"
                alt="{{ $subcategory->name }}"
            />
            
            @else
            <div class="w-full aspect-square bg-gray-200 rounded flex items-center justify-center">
                <i class="fas fa-image text-gray-400 text-3xl"></i>
            </div>
            @endif
            <div class="text-center text-sm font-medium p-2">
                {{ $subcategory->name }}
            </div>
        </a>
        
        @endforeach
    </div>
</section>
@endif

<!-- Dynamic Categories with Subcategories - Loop through all featured categories -->
@php
    $featuredCategoriesWithSubs = \App\Models\Category::with(['subcategories' => function($query) {
        $query->where('status', 1)->orderBy('sort_order', 'asc');
    }])
    ->where('status', 1)
    ->where('is_featured', 1)
    ->whereHas('subcategories', function($query) {
        $query->where('status', 1);
    })
    ->orderBy('id', 'asc')
    ->skip(1)
    ->take(2)
    ->get();
@endphp

@foreach($featuredCategoriesWithSubs as $category)
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h3 class="text-xl font-semibold mb-6 flex items-center">
        <span class="flex-1 h-px bg-gray-300"></span>
        <span class="px-4 text-center">{{ $category->name }}</span>
        <span class="flex-1 h-px bg-gray-300"></span>
    </h3>
    <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-6">
        @foreach($category->subcategories->take(12) as $subcategory)
        <a href="{{ route('shop') }}?subcategory={{ $subcategory->id }}"
            class="bg-white rounded-lg shadow overflow-hidden cursor-pointer group">
            <div class="overflow-hidden">
                @if($subcategory->image)
                <img
                    src="{{ asset('admin-assets/subcategories/' . $subcategory->image) }}"
                    alt="{{ $subcategory->name }}"
                    class="w-full h-32 object-cover transform transition duration-500 group-hover:scale-110"
                />
                
                @else
                <div class="w-full h-32 bg-gray-200 flex items-center justify-center">
                    <i class="fas fa-image text-gray-400 text-3xl"></i>
                </div>
                @endif
            </div>
            <div class="text-center text-sm font-medium p-2">
                {{ $subcategory->name }}
            </div>
        </a>
        @endforeach
    </div>
</section>
@endforeach

<!-- Featured Products -->
<section id="featured" class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Featured Products</h2>
            <p class="text-gray-600">Handpicked premium Korean beauty essentials</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($featuredProducts as $product)
            <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group">
                <div class="relative">
                <a href="{{ route('product.details', $product->slug) }}">
                        <img src="{{ asset('admin-assets/products/'.$product->thumbnail) }}" alt="{{ $product->name }}" class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-300">
                    </a>
                    @if($product->discount_price)
                        <div class="absolute top-2 left-2 bg-red-500 text-white px-2 py-1 rounded-full text-xs font-semibold">
                            SALE
                        </div>
                    @endif
                    @if($product->is_new)
                        <div class="absolute top-2 right-2 bg-green-500 text-white px-2 py-1 rounded-full text-xs font-semibold">
                            NEW
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-all duration-300 flex items-center justify-center">
                        <button class="quick-view-btn bg-[--brand-orange] text-white px-6 py-2 rounded-lg opacity-0 group-hover:opacity-100 transition-all duration-300 hover:bg-orange-600" data-product-id="{{ $product->id }}">
                            Quick View
                        </button>
                    </div>
                </div>
                <div class="p-6">
                    <a href="{{ route('product.details', $product->slug) }}">
                        <h3 class="text-lg font-semibold text-gray-800 hover:text-[--brand-orange] transition-colors line-clamp-2 mb-2">
                            {{ $product->name }}
    </h3>
                    </a>
                    <div class="flex items-center mb-3">
                        <div class="flex text-yellow-400">
                            @for($i = 0; $i < 5; $i++)
                                <span>★</span>
                            @endfor
                        </div>
                        <span class="text-gray-500 text-sm ml-2">({{ rand(10, 100) }})</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            @if($product->discount_price)
                                <p class="text-[--brand-orange] font-bold text-lg">৳{{ number_format($product->discount_price, 2) }}</p>
                                <p class="text-gray-400 line-through text-sm">৳{{ number_format($product->price, 2) }}</p>
                            @else
                                <p class="text-[--brand-orange] font-bold text-lg">৳{{ number_format($product->price, 2) }}</p>
                            @endif
                        </div>
                        <button class="add-to-cart-btn bg-[--brand-orange] text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition-colors font-medium" data-product-id="{{ $product->id }}">
                            Add to Cart
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- New Arrivals -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">New Arrivals</h2>
            <p class="text-gray-600">Latest additions to our collection</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($newProducts as $product)
            <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group">
                <div class="relative">
                <a href="{{ route('product.details', $product->slug) }}">
                        <img src="{{ asset('admin-assets/products/'.$product->thumbnail) }}" alt="{{ $product->name }}" class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-300">
                    </a>
                    @if($product->discount_price)
                        <div class="absolute top-2 left-2 bg-red-500 text-white px-2 py-1 rounded-full text-xs font-semibold">
                            SALE
                        </div>
                    @endif
                    @if($product->is_new)
                        <div class="absolute top-2 right-2 bg-green-500 text-white px-2 py-1 rounded-full text-xs font-semibold">
                            NEW
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-all duration-300 flex items-center justify-center">
                        <button class="quick-view-btn bg-[--brand-orange] text-white px-6 py-2 rounded-lg opacity-0 group-hover:opacity-100 transition-all duration-300 hover:bg-orange-600" data-product-id="{{ $product->id }}">
                            Quick View
                        </button>
                    </div>
                </div>
                <div class="p-6">
                    <a href="{{ route('product.details', $product->slug) }}">
                        <h3 class="text-lg font-semibold text-gray-800 hover:text-[--brand-orange] transition-colors line-clamp-2 mb-2">
                            {{ $product->name }}
                        </h3>
                    </a>
                    <div class="flex items-center mb-3">
                        <div class="flex text-yellow-400">
                            @for($i = 0; $i < 5; $i++)
                                <span>★</span>
                            @endfor
                        </div>
                        <span class="text-gray-500 text-sm ml-2">({{ rand(10, 100) }})</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            @if($product->discount_price)
                                <p class="text-[--brand-orange] font-bold text-lg">৳{{ number_format($product->discount_price, 2) }}</p>
                                <p class="text-gray-400 line-through text-sm">৳{{ number_format($product->price, 2) }}</p>
                            @else
                                <p class="text-[--brand-orange] font-bold text-lg">৳{{ number_format($product->price, 2) }}</p>
                            @endif
                        </div>
                        <button class="add-to-cart-btn bg-[--brand-orange] text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition-colors font-medium" data-product-id="{{ $product->id }}">
                            Add to Cart
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Best Sellers -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Best Sellers</h2>
            <p class="text-gray-600">Customer favorites and top-rated products</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($bestSellers as $product)
            <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group">
                <div class="relative">
                <a href="{{ route('product.details', $product->slug) }}">
                        <img src="{{ asset('admin-assets/products/'.$product->thumbnail) }}" alt="{{ $product->name }}" class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-300">
                    </a>
                    @if($product->discount_price)
                        <div class="absolute top-2 left-2 bg-red-500 text-white px-2 py-1 rounded-full text-xs font-semibold">
                            SALE
                        </div>
                    @endif
                    @if($product->is_new)
                        <div class="absolute top-2 right-2 bg-green-500 text-white px-2 py-1 rounded-full text-xs font-semibold">
                            NEW
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-all duration-300 flex items-center justify-center">
                        <button class="quick-view-btn bg-[--brand-orange] text-white px-6 py-2 rounded-lg opacity-0 group-hover:opacity-100 transition-all duration-300 hover:bg-orange-600" data-product-id="{{ $product->id }}">
                            Quick View
                        </button>
                    </div>
                </div>
                <div class="p-6">
                    <a href="{{ route('product.details', $product->slug) }}">
                        <h3 class="text-lg font-semibold text-gray-800 hover:text-[--brand-orange] transition-colors line-clamp-2 mb-2">
                            {{ $product->name }}
                        </h3>
                    </a>
                    <div class="flex items-center mb-3">
                        <div class="flex text-yellow-400">
                            @for($i = 0; $i < 5; $i++)
                                <span>★</span>
                            @endfor
                        </div>
                        <span class="text-gray-500 text-sm ml-2">({{ rand(10, 100) }})</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            @if($product->discount_price)
                                <p class="text-[--brand-orange] font-bold text-lg">৳{{ number_format($product->discount_price, 2) }}</p>
                                <p class="text-gray-400 line-through text-sm">৳{{ number_format($product->price, 2) }}</p>
                            @else
                                <p class="text-[--brand-orange] font-bold text-lg">৳{{ number_format($product->price, 2) }}</p>
                            @endif
                        </div>
                        <button class="add-to-cart-btn bg-[--brand-orange] text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition-colors font-medium" data-product-id="{{ $product->id }}">
                            Add to Cart
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Newsletter Section
<section class="py-16 bg-[--brand-orange] text-white">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold mb-4">Stay Updated</h2>
        <p class="text-xl mb-8">Get the latest beauty tips and exclusive offers delivered to your inbox</p>
        <form class="max-w-md mx-auto flex gap-4">
            <input type="email" placeholder="Enter your email" class="flex-1 px-4 py-3 rounded-lg text-gray-800">
            <button type="submit" class="bg-white text-[--brand-orange] px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                Subscribe
            </button>
        </form>
    </div>
</section> -->

 <!-- Top Banner Section -->
 <div
        class="bg-[var(--brand-orange)] text-white flex flex-col md:flex-row justify-between text-center py-8 px-4 md:px-16 space-y-6 md:space-y-0"
      >
        <div class="flex-1">
          <div class="text-3xl mb-2">🚚</div>
          <h3 class="font-bold text-lg">Fastest Delivery</h3>
          <p class="text-sm">
            We offer fastest delivery in all over Bangladesh.
          </p>
        </div>
        <div
          class="flex-1 border-t md:border-t-0 md:border-l md:border-r border-white px-4"
        >
          <div class="text-3xl mb-2">⏰</div>
          <h3 class="font-bold text-lg">24/7 Support</h3>
          <p class="text-sm">Get help anytime you need it — day or night.</p>
        </div>
        <div
          class="flex-1 border-t md:border-t-0 md:border-l md:border-r border-white px-4"
        >
          <div class="text-3xl mb-2">✅</div>
          <h3 class="font-bold text-lg">Authenticity</h3>
          <p class="text-sm">100% genuine and authentic products only.</p>
        </div>
        <div class="flex-1">
          <div class="text-3xl mb-2">🎁</div>
          <h3 class="font-bold text-lg">Free Gifts</h3>
          <p class="text-sm">Loyal customers get special gifts from us.</p>
        </div>
      </div>


<!-- Product Details Modal -->
<div id="productModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold">Product Details</h2>
                    <button id="closeProductModal" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
                </div>
                <div id="modalContent" class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Product image and details will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Shopping Cart Sidebar -->
<div id="cartSidebar" class="fixed right-0 top-0 h-full w-96 bg-white shadow-xl transform translate-x-full transition-transform duration-300 z-50">
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold">Shopping Cart</h2>
            <button id="closeCartSidebar" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
                </div>
        <div id="cartItems" class="space-y-4 mb-6">
            <!-- Cart items will be loaded here -->
        </div>
        <div class="border-t pt-4">
            <div class="flex justify-between items-center mb-4">
                <span class="text-lg font-semibold">Total:</span>
                <span id="cartTotal" class="text-xl font-bold text-[--brand-orange]">৳0.00</span>
            </div>
            <div class="space-y-2">
                <a href="{{ route('cart') }}" class="block w-full bg-[--brand-orange] text-white text-center py-3 rounded-lg hover:bg-orange-600 transition-colors">
                    View Cart
                </a>
                <a href="{{ route('checkout') }}" class="block w-full bg-gray-800 text-white text-center py-3 rounded-lg hover:bg-gray-700 transition-colors">
                    Checkout
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Cart Overlay -->
<div id="cartOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden"></div>

  

@endsection

@push('styles')
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush

@push('scripts')
<script>
// ---------- Slider ----------
const slider = document.getElementById("slider");
const prev = document.getElementById("prev");
const next = document.getElementById("next");
const dots = document.querySelectorAll(".hero-dot");
let current = 0;
const slides = slider.children.length;

function updateSlider() {
    slider.style.transform = `translateX(-${current * 100}%)`;
    dots.forEach((d, i) =>
        d.classList.toggle("bg-white/60", i === current)
    );
}
prev.addEventListener("click", () => {
    current = (current - 1 + slides) % slides;
    updateSlider();
});
next.addEventListener("click", () => {
    current = (current + 1) % slides;
    updateSlider();
});
dots.forEach((d) =>
    d.addEventListener("click", (e) => {
        current = +e.target.dataset.slide;
        updateSlider();
    })
);
setInterval(() => {
    current = (current + 1) % slides;
    updateSlider();
}, 5000);

// ---------- Slogan ----------
const slogans = @json($slogans->pluck('text')->toArray());
let sloganIndex = 0;
const sloganEl = document.getElementById("slogan");

if (slogans.length > 0) {
function updateSlogan() {
    sloganEl.classList.add("opacity-0", "scale-95");
    setTimeout(() => {
        sloganEl.textContent = slogans[sloganIndex];
        sloganIndex = (sloganIndex + 1) % slogans.length;
        sloganEl.classList.remove("opacity-0", "scale-95");
    }, 350);
}

updateSlogan();
setInterval(updateSlogan, 4000);

// Apply gradient text
    sloganEl.style.background = "linear-gradient(90deg, #f36c21, #ff8c42, #ffb67a)";
sloganEl.style.webkitBackgroundClip = "text";
sloganEl.style.webkitTextFillColor = "transparent";
sloganEl.style.backgroundClip = "text"; // For non-webkit browsers
}

</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Home page JavaScript loaded');
    console.log('Add to cart buttons found:', document.querySelectorAll('.add-to-cart-btn').length);
    console.log('Quick view buttons found:', document.querySelectorAll('.quick-view-btn').length);
    
    // Initialize cart
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    
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
    
    // Add to cart function
    function addToCart(productId, quantity = 1) {
        console.log('Adding to cart:', productId);
        
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
                // Add to local cart for sidebar
                addToLocalCart(productId, quantity);
            } else {
                showNotification(data.message || 'Failed to add product to cart', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error adding product to cart', 'error');
        });
    }
    
    // Add to local cart
    function addToLocalCart(productId, quantity) {
        const existingItem = cart.find(item => item.id === productId);
        if (existingItem) {
            existingItem.quantity += quantity;
        } else {
            cart.push({ id: productId, quantity: quantity });
        }
        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartUI();
    }
    
    // Update cart UI
    function updateCartUI() {
        const cartItems = document.getElementById('cartItems');
        const cartTotal = document.getElementById('cartTotal');
        
    if (cart.length === 0) {
            cartItems.innerHTML = '<p class="text-gray-500 text-center">Your cart is empty</p>';
            cartTotal.textContent = '৳0.00';
        return;
    }
        
        // Fetch cart items from server
        fetch('{{ route("api.cart.items") }}')
            .then(response => response.json())
            .then(items => {
    let total = 0;
                cartItems.innerHTML = items.map(item => {
                    total += item.total;
                    return `
                        <div class="flex items-center space-x-4">
                            <img src="/admin-assets/products/${item.thumbnail}" alt="${item.name}" class="w-16 h-16 object-cover rounded">
            <div class="flex-1">
                                <h3 class="font-semibold">${item.name}</h3>
                                <p class="text-gray-600">$${item.price.toFixed(2)} x ${item.quantity}</p>
            </div>
                            <button onclick="removeFromCart(${item.id})" class="text-red-500 hover:text-red-700">
                                Remove
                            </button>
            </div>
          `;
                }).join('');
                
                cartTotal.textContent = `$${total.toFixed(2)}`;
            })
            .catch(error => {
                console.error('Error fetching cart items:', error);
                cartItems.innerHTML = '<p class="text-red-500 text-center">Error loading cart items</p>';
            });
    }

    // Remove from cart
    function removeFromCart(productId) {
        fetch('{{ route("cart.remove") }}', {
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
                showNotification('Product removed from cart', 'success');
                updateCartUI();
                updateCartCount();
            } else {
                showNotification('Failed to remove product', 'error');
            }
        })
        .catch(error => {
            console.error('Error removing from cart:', error);
            showNotification('Error removing product', 'error');
        });
    }
    
    // Update cart count in header - use layout's function if available
    function updateCartCount() {
        if (typeof window.updateCartCount === 'function' && window.updateCartCount !== updateCartCount) {
            // Use the layout's updateCartCount function
            window.updateCartCount();
        } else {
            // Fallback to our own implementation
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
    }
    
    // Open product modal
    function openProductModal(productId) {
        console.log('Opening product modal for:', productId);
        console.log('Product ID type:', typeof productId);
        console.log('Product ID value:', productId);
        
        // Add timeout to fetch request
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), 10000); // 10 second timeout
        
        const url = `/api/products/${productId}`;
        console.log('Fetching URL:', url);
        
        // Test with a simple fetch first
        fetch(url)
            .then(response => {
                console.log('Simple fetch response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(product => {
                console.log('Simple fetch product data:', product);
                // Show the modal with the product data
                showProductModal(product);
            })
            .catch(error => {
                console.error('Simple fetch error:', error);
                showNotification(`Error loading product details: ${error.message}`, 'error');
            });
    }
    
    // Show product modal with data
    function showProductModal(product) {
        console.log('Showing product modal with data:', product);
        
        document.getElementById('modalContent').innerHTML = `
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
                    <span class="text-3xl font-bold text-[--brand-orange]">$${product.price}</span>
                    ${product.discount_price ? `<span class="text-gray-400 line-through ml-2">$${product.discount_price}</span>` : ''}
                </div>
                <div class="flex items-center space-x-4 mb-6">
                    <label class="font-semibold">Quantity:</label>
                    <div class="flex items-center border rounded-lg">
                        <button onclick="decreaseQuantity()" class="px-3 py-1 hover:bg-gray-100">-</button>
                        <input type="number" id="productQuantity" value="1" min="1" max="10" class="w-16 text-center border-0">
                        <button onclick="increaseQuantity()" class="px-3 py-1 hover:bg-gray-100">+</button>
                    </div>
                </div>
                <button onclick="addToCart(${product.id}, parseInt(document.getElementById('productQuantity').value))" class="w-full bg-[--brand-orange] text-white py-3 rounded-lg hover:bg-orange-600 transition-colors">
                    Add to Cart
                </button>
            </div>
        `;
        
        // Add close button event listener
        document.getElementById('closeProductModal').addEventListener('click', function() {
            closeProductModal();
        });
        
        document.getElementById('productModal').classList.remove('hidden');
    }
    
    // Close product modal
    function closeProductModal() {
        document.getElementById('productModal').classList.add('hidden');
    }
    
    // Open cart sidebar - use layout's cart modal
    function openCartSidebar() {
        if (typeof window.openCartModal === 'function') {
            window.openCartModal();
    } else {
            console.error('Cart modal function not found');
        }
    }
    
    // Close cart sidebar - use layout's cart modal
    function closeCartSidebar() {
        if (typeof window.closeCartModal === 'function') {
            window.closeCartModal();
    } else {
            console.error('Cart modal close function not found');
        }
    }
    
    // Event listeners
    document.addEventListener('click', function(e) {
        console.log('Click detected on:', e.target);
        
        // Add to cart buttons
        if (e.target.classList.contains('add-to-cart-btn')) {
            console.log('Add to cart button clicked');
            const productId = parseInt(e.target.getAttribute('data-product-id'));
            console.log('Product ID:', productId);
            addToCart(productId);
        }
        
        // Quick view buttons
        if (e.target.classList.contains('quick-view-btn')) {
            console.log('Quick view button clicked');
            const productId = parseInt(e.target.getAttribute('data-product-id'));
            console.log('Product ID:', productId);
            openProductModal(productId);
        }
    });
    
    // Modal close buttons
    const closeProductModalBtn = document.getElementById('closeProductModal');
    if (closeProductModalBtn) {
        closeProductModalBtn.addEventListener('click', closeProductModal);
    }
    
    // Cart sidebar elements don't exist on home page, so we don't add listeners for them
    
    // Make functions globally accessible (but don't override layout's cart functions)
    window.addToCart = addToCart;
    window.openProductModal = openProductModal;
    window.closeProductModal = closeProductModal;
    window.openCartSidebar = openCartSidebar;
    window.closeCartSidebar = closeCartSidebar;
    window.removeFromCart = removeFromCart;
    window.updateCartUI = updateCartUI;
    window.showNotification = showNotification;
    
    // Don't override the layout's updateCartCount function
    // window.updateCartCount = updateCartCount; // Commented out to avoid conflicts
    
    // Initialize
    updateCartUI();
    updateCartCount();
});
</script>
@endpush
