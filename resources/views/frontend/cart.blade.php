@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<style>
.btn-primary {
    background-color: #f36c21 !important;
    color: white !important;
    border: none !important;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-primary:hover {
    background-color: #d95a18 !important;
}

.update-qty {
    background-color: #f8f9fa !important;
    color: #6b7280 !important;
    cursor: pointer;
    transition: all 0.2s;
    border: none !important;
    font-weight: 500;
}

.update-qty:hover {
    background-color: #e9ecef !important;
    color: #495057 !important;
}

.quantity-input {
    background-color: white !important;
    border: none !important;
    outline: none !important;
    font-weight: 500;
    color: #374151;
    text-align: center;
    font-size: 14px;
}

/* Quantity container styling */
.quantity-container {
    border: 1px solid #d1d5db;
    border-radius: 6px;
    overflow: hidden;
    background-color: white;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}

.quantity-container:hover {
    border-color: #9ca3af;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.remove-item {
    background-color: transparent !important;
    border: none !important;
    color: #dc2626 !important;
    cursor: pointer;
    text-decoration: underline;
    transition: all 0.2s;
}

.remove-item:hover {
    color: #b91c1c !important;
    text-decoration: none !important;
}
</style>

<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto p-6 grid lg:grid-cols-3 gap-8">
        <!-- Left Side - Cart Items -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Free Shipping Progress -->
            <div class="border border-dashed border-gray-300 rounded-md p-4">
                @if($subtotal >= 50)
                    <p class="text-sm mb-2 text-green-600">🎉 Your order qualifies for free shipping!</p>
                @else
                    <p class="text-sm mb-2">Add ${{ number_format(50 - $subtotal, 2) }} more for free shipping!</p>
                @endif
                <div class="w-full bg-gray-200 h-3 rounded">
                    <div class="h-3 rounded bg-[--brand-orange]" style="width: {{ min(($subtotal / 50) * 100, 100) }}%"></div>
                </div>
            </div>

            <!-- Cart Items -->
            <div class="space-y-6">
                @if(count($cartItems) > 0)
                    @foreach($cartItems as $item)
                        <div class="flex items-center justify-between border-b pb-4" data-product-id="{{ $item['id'] }}">
                            <div class="flex items-center space-x-4">
                                <img src="{{ asset('admin-assets/products/'.$item['product']->thumbnail) }}" 
                                     class="w-16 h-16 rounded object-cover" 
                                     alt="{{ $item['product']->name }}" />
                                <div>
                                    <h3 class="font-medium">{{ $item['product']->name }}</h3>
                                    <p class="text-xs text-gray-500">SKU: {{ $item['product']->sku ?? 'N/A' }}</p>
                                    @if(!empty($item['options']))
                                        <div class="text-xs text-gray-500">
                                            @foreach($item['options'] as $key => $value)
                                                {{ ucfirst($key) }}: {{ $value }}@if(!$loop->last), @endif
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold">৳{{ number_format($item['price'], 2) }}</p>
                                <div class="flex items-center mt-2 quantity-container w-20">
                                    <button class="px-2 py-1 update-qty border-r border-gray-300" data-action="decrease" data-product-id="{{ $item['id'] }}">-</button>
                                    <input type="text" value="{{ $item['quantity'] }}" 
                                           class="w-8 text-center quantity-input" 
                                           data-product-id="{{ $item['id'] }}" readonly />
                                    <button class="px-2 py-1 update-qty border-l border-gray-300" data-action="increase" data-product-id="{{ $item['id'] }}">+</button>
                                </div>
                                <p class="mt-2 text-sm text-gray-700">৳{{ number_format($item['total'], 2) }}</p>
                                <button class="mt-2 text-sm remove-item" 
                                        data-product-id="{{ $item['id'] }}">Remove</button>
                            </div>
                        </div>
                    @endforeach
                @else
                    <!-- Empty Cart -->
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 7h13l-2-7M16 21a1 1 0 11-2 0 1 1 0 012 0zm-8 0a1 1 0 11-2 0 1 1 0 012 0z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Your cart is empty</h3>
                        <p class="mt-1 text-sm text-gray-500">Start shopping to add items to your cart.</p>
                        <div class="mt-6">
                            <a href="{{ route('shop') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-[--brand-orange] hover:bg-orange-600">
                                Continue Shopping
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            @if(count($cartItems) > 0)
                <!-- Coupon -->
                <div class="flex items-center space-x-3 mt-6">
                    <input type="text" placeholder="Coupon code" 
                           class="flex-1 border rounded px-3 py-2 focus:ring-[--brand-orange] focus:border-[--brand-orange]" 
                           id="coupon-code" />
                    <button class="btn-primary px-5 py-2 rounded" id="apply-coupon" style="background-color: #f36c21; color: white; border: none; cursor: pointer;">APPLY COUPON</button>
                </div>
            @endif
        </div>

        <!-- Right Side - Cart Totals -->
        @if(count($cartItems) > 0)
            <div class="bg-white rounded-lg shadow-md p-6 h-fit">
                <h2 class="font-bold text-lg mb-4">CART TOTALS</h2>
                <div class="flex justify-between py-2 border-b">
                    <span>Subtotal</span>
                    <span>৳{{ number_format($subtotal, 2) }}</span>
                </div>
                <div class="py-2 border-b">
                    <p>Shipping</p>
                    @if($shipping == 0)
                        <p class="text-sm text-green-600">Free shipping</p>
                    @else
                        <p class="text-sm text-gray-600">৳{{ number_format($shipping, 2) }}</p>
                    @endif
                    <p class="text-sm text-gray-600">Shipping to Bangladesh.</p>
                    <a href="#" class="text-sm text-[--brand-orange]">Change address</a>
                </div>
                <div class="flex justify-between py-2 border-b">
                    <span>Tax</span>
                    <span>৳{{ number_format($tax, 2) }}</span>
                </div>
                @if(isset($discount) && $discount > 0)
                <div class="flex justify-between py-2 border-b text-green-600">
                    <span>Discount ({{ session('applied_coupon.code') }})</span>
                    <span>-৳{{ number_format($discount, 2) }}</span>
                </div>
                @endif
                <div class="flex justify-between py-4 font-bold text-lg">
                    <span>Total</span>
                    <span>৳{{ number_format($total, 2) }}</span>
                </div>
                <a href="{{ route('checkout') }}" class="btn-primary w-full py-3 rounded font-semibold text-center block" style="background-color: #f36c21; color: white; border: none; cursor: pointer; display: block;">
                    PROCEED TO CHECKOUT
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Cart JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update quantity
    document.querySelectorAll('.update-qty').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const action = this.dataset.action;
            const quantityInput = document.querySelector(`input[data-product-id="${productId}"]`);
            let currentQty = parseInt(quantityInput.value);
            
            if (action === 'increase') {
                currentQty++;
            } else if (action === 'decrease' && currentQty > 1) {
                currentQty--;
            }
            
            updateCartItem(productId, currentQty);
        });
    });

    // Remove item
    document.querySelectorAll('.remove-item').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            removeCartItem(productId);
        });
    });

    // Update cart item
    function updateCartItem(productId, quantity) {
        fetch('{{ route("cart.update") }}', {
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
                location.reload(); // Reload to update totals
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // Remove cart item
    function removeCartItem(productId) {
        if (confirm('Are you sure you want to remove this item from your cart?')) {
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
                    location.reload(); // Reload to update cart
                }
            })
            .catch(error => console.error('Error:', error));
        }
    }

    // Apply coupon
    document.getElementById('apply-coupon')?.addEventListener('click', function() {
        const couponCode = document.getElementById('coupon-code').value;
        if (couponCode) {
            applyCoupon(couponCode);
        } else {
            alert('Please enter a coupon code');
        }
    });

    // Apply coupon function
    function applyCoupon(couponCode) {
        fetch('{{ route("cart.apply-coupon") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                code: couponCode
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload(); // Reload to update totals
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error applying coupon');
        });
    }

    // Remove coupon function
    function removeCoupon() {
        fetch('{{ route("cart.remove-coupon") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload(); // Reload to update totals
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error removing coupon');
        });
    }
});
</script>
@endsection
