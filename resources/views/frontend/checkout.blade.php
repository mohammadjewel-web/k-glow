@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto p-6">
        <h1 class="text-3xl font-bold mb-8">Checkout</h1>
        
        <div class="grid lg:grid-cols-2 gap-8">
            <!-- Left Side - Checkout Form -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-6">Billing Information</h2>
                
                @auth
                    <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
                        @csrf
                        
                        <!-- Shipping Address -->
                        <div class="mb-6">
                            <label for="shipping_address" class="block text-sm font-medium text-gray-700 mb-2">
                                Shipping Address *
                            </label>
                            <textarea name="shipping_address" id="shipping_address" rows="3" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[--brand-orange] focus:border-[--brand-orange]"
                                placeholder="Enter your complete shipping address">{{ old('shipping_address', Auth::user()->name . "\n" . Auth::user()->email) }}</textarea>
                            @error('shipping_address')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Billing Address -->
                        <div class="mb-6">
                            <label for="billing_address" class="block text-sm font-medium text-gray-700 mb-2">
                                Billing Address *
                            </label>
                            <textarea name="billing_address" id="billing_address" rows="3" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[--brand-orange] focus:border-[--brand-orange]"
                                placeholder="Enter your billing address">{{ old('billing_address', Auth::user()->name . "\n" . Auth::user()->email) }}</textarea>
                            @error('billing_address')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div class="mb-6">
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                Phone Number *
                            </label>
                            <input type="text" name="phone" id="phone" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[--brand-orange] focus:border-[--brand-orange]"
                                placeholder="Enter your phone number" value="{{ old('phone', Auth::user()->phone) }}">
                            @error('phone')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- City -->
                        <div class="mb-6">
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                                City *
                            </label>
                            <input type="text" name="city" id="city" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[--brand-orange] focus:border-[--brand-orange]"
                                placeholder="Enter your city" value="{{ old('city', Auth::user()->location ?? 'Dhaka') }}">
                            @error('city')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- State -->
                        <div class="mb-6">
                            <label for="state" class="block text-sm font-medium text-gray-700 mb-2">
                                State/Division *
                            </label>
                            <input type="text" name="state" id="state" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[--brand-orange] focus:border-[--brand-orange]"
                                placeholder="Enter your state or division" value="{{ old('state', 'Dhaka') }}">
                            @error('state')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Postal Code -->
                        <div class="mb-6">
                            <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-2">
                                Postal Code *
                            </label>
                            <input type="text" name="postal_code" id="postal_code" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[--brand-orange] focus:border-[--brand-orange]"
                                placeholder="Enter your postal code" value="{{ old('postal_code', '1000') }}">
                            @error('postal_code')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Payment Method -->
                        <!-- <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Payment Method *</label>
                            <div class="space-y-3">
                                <label class="flex items-center">
                                    <input type="radio" name="payment_method" value="cash_on_delivery" 
                                           class="mr-3" {{ old('payment_method') == 'cash_on_delivery' ? 'checked' : '' }}>
                                    <span>Cash on Delivery</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="payment_method" value="bkash" 
                                           class="mr-3" {{ old('payment_method') == 'bkash' ? 'checked' : '' }}>
                                    <span>Bkash</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="payment_method" value="nagad" 
                                           class="mr-3" {{ old('payment_method') == 'nagad' ? 'checked' : '' }}>
                                    <span>Nagad</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="payment_method" value="card" 
                                           class="mr-3" {{ old('payment_method') == 'card' ? 'checked' : '' }}>
                                    <span>Credit/Debit Card</span>
                                </label>
                            </div>
                            @error('payment_method')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div> -->

                        <!-- Notes -->
                        <div class="mb-6">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                Order Notes (Optional)
                            </label>
                            <textarea name="notes" id="notes" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[--brand-orange] focus:border-[--brand-orange]"
                                placeholder="Any special instructions for your order">{{ old('notes') }}</textarea>
                            @error('notes')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Payment Method -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-4">Payment Method *</label>
                            <div class="space-y-3">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                    <input type="radio" name="payment_method" value="sslcommerz" class="text-orange-600 focus:ring-orange-500" checked>
                                    <div class="ml-3">
                                        <div class="flex items-center">
                                            <i class="fas fa-credit-card text-blue-500 mr-2"></i>
                                            <span class="font-medium">Card Payment (SSLCommerz)</span>
                                        </div>
                                        <p class="text-sm text-gray-500">Pay with Credit/Debit Card or Mobile Banking</p>
                                    </div>
                                </label>
                                
                                <!-- <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                    <input type="radio" name="payment_method" value="cash_on_delivery" class="text-orange-600 focus:ring-orange-500">
                                    <div class="ml-3">
                                        <div class="flex items-center">
                                            <i class="fas fa-money-bill-wave text-green-500 mr-2"></i>
                                            <span class="font-medium">Cash on Delivery</span>
                                        </div>
                                        <p class="text-sm text-gray-500">Pay when you receive your order</p>
                                    </div>
                                </label>
                                
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                    <input type="radio" name="payment_method" value="bkash" class="text-orange-600 focus:ring-orange-500">
                                    <div class="ml-3">
                                        <div class="flex items-center">
                                            <i class="fas fa-mobile-alt text-pink-500 mr-2"></i>
                                            <span class="font-medium">bKash</span>
                                        </div>
                                        <p class="text-sm text-gray-500">Pay with bKash mobile banking</p>
                                    </div>
                                </label> -->
                            </div>
                            @error('payment_method')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" id="checkoutBtn"
                                class="w-full py-3 bg-[--brand-orange] text-white rounded-lg font-semibold hover:bg-orange-600 transition-colors">
                            <span id="btnText">Place Order - ৳{{ number_format($total, 2) }}</span>
                        </button>
                    </form>
                @else
                    <div class="text-center py-8">
                        <h3 class="text-lg font-semibold mb-4">Please Login to Continue</h3>
                        <p class="text-gray-600 mb-6">You need to be logged in to place an order.</p>
                        <div class="space-x-4">
                            <a href="{{ route('login') }}" class="btn-primary px-6 py-2 rounded">Login</a>
                            <a href="{{ route('register') }}" class="btn-outline px-6 py-2 rounded">Register</a>
                        </div>
                    </div>
                @endauth
            </div>

            <!-- Right Side - Order Summary -->
            <div class="bg-white rounded-lg shadow-md p-6 h-fit">
                <h2 class="text-xl font-semibold mb-6">Order Summary</h2>
                
                <!-- Cart Items -->
                <div class="space-y-4 mb-6">
                    @foreach($cartItems as $item)
                        <div class="flex items-center space-x-4">
                            <img src="{{ asset('admin-assets/products/'.$item['product']->thumbnail) }}" 
                                 class="w-16 h-16 rounded object-cover" 
                                 alt="{{ $item['product']->name }}" />
                            <div class="flex-1">
                                <h4 class="font-medium">{{ $item['product']->name }}</h4>
                                <p class="text-sm text-gray-500">Qty: {{ $item['quantity'] }}</p>
                                @if(!empty($item['options']))
                                    <p class="text-xs text-gray-500">
                                        @foreach($item['options'] as $key => $value)
                                            {{ ucfirst($key) }}: {{ $value }}@if(!$loop->last), @endif
                                        @endforeach
                                    </p>
                                @endif
                            </div>
                            <div class="text-right">
                                <p class="font-semibold">৳{{ number_format($item['total'], 2) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Order Totals -->
                <div class="border-t pt-4 space-y-2">
                    <div class="flex justify-between">
                        <span>Subtotal</span>
                        <span>৳{{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Shipping</span>
                        <span>
                            @if($shipping == 0)
                                <span class="text-green-600">Free</span>
                            @else
                                ৳{{ number_format($shipping, 2) }}
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span>Tax</span>
                        <span>৳{{ number_format($tax, 2) }}</span>
                    </div>
                    <div class="flex justify-between font-bold text-lg border-t pt-2">
                        <span>Total</span>
                        <span>৳{{ number_format($total, 2) }}</span>
                    </div>
                </div>

                <!-- Security Notice -->
                <div class="mt-6 p-4 bg-green-50 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-sm text-green-700">Secure checkout with SSL encryption</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Checkout JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('checkoutForm');
    const checkoutBtn = document.getElementById('checkoutBtn');
    const btnText = document.getElementById('btnText');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
            const formData = new FormData(form);
            
            // Show loading state
            checkoutBtn.disabled = true;
            btnText.textContent = 'Processing...';
            
            if (paymentMethod === 'sslcommerz') {
                // Create order first, then redirect to payment
                createOrderAndRedirect(formData);
            } else {
                // For other payment methods, submit directly
                form.submit();
            }
        });
        
        // Update button text based on payment method
        const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
        paymentRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                updateButtonText(this.value);
            });
        });
    }
    
    function updateButtonText(paymentMethod) {
        const total = {{ $total }};
        switch(paymentMethod) {
            case 'sslcommerz':
                btnText.textContent = `Pay with Card - ৳${total.toFixed(2)}`;
                break;
            case 'cash_on_delivery':
                btnText.textContent = `Place Order (COD) - ৳${total.toFixed(2)}`;
                break;
            case 'bkash':
                btnText.textContent = `Pay with bKash - ৳${total.toFixed(2)}`;
                break;
            default:
                btnText.textContent = `Place Order - ৳${total.toFixed(2)}`;
        }
    }
    
    function createOrderAndRedirect(formData) {
        // First create the order
        fetch('{{ route("checkout.process") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.order_id) {
                // Now initiate payment
                initiatePayment(data.order_id);
            } else {
                showError(data.message || 'Order creation failed');
                resetButton();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('An error occurred while creating the order');
            resetButton();
        });
    }
    
    function initiatePayment(orderId) {
        const paymentData = new FormData();
        paymentData.append('order_id', orderId);
        paymentData.append('payment_method', 'sslcommerz');
        
        fetch('{{ route("payment.initiate") }}', {
            method: 'POST',
            body: paymentData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.payment_url) {
                // Redirect to SSLCommerz payment page
                window.location.href = data.payment_url;
            } else {
                showError(data.message || 'Payment initiation failed');
                resetButton();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('An error occurred while initiating payment');
            resetButton();
        });
    }
    
    function showError(message) {
        alert('Error: ' + message);
    }
    
    function resetButton() {
        checkoutBtn.disabled = false;
        btnText.textContent = 'Place Order - ৳{{ number_format($total, 2) }}';
    }
});
</script>
@endsection
