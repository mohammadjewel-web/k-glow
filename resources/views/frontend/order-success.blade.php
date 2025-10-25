@extends('layouts.app')

@section('title', 'Order Success')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto p-6">
        <!-- Success Header -->
        <div class="bg-white rounded-xl shadow-lg p-8 text-center mb-8">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-check text-green-600 text-3xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Order Placed Successfully!</h1>
            <p class="text-lg text-gray-600 mb-4">Thank you for your purchase</p>
            <div class="bg-orange-100 rounded-lg p-4 inline-block">
                <p class="text-orange-800 font-semibold">Order Number: #{{ $order->order_number }}</p>
            </div>
        </div>

        <!-- Order Details -->
        <div class="grid lg:grid-cols-2 gap-8">
            <!-- Order Summary -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-semibold mb-6">Order Summary</h2>
                
                <!-- Order Items -->
                <div class="space-y-4 mb-6">
                    @foreach($order->orderItems as $item)
                        <div class="flex items-center space-x-4">
                            <img src="{{ asset('admin-assets/products/'.$item->product->thumbnail) }}" 
                                 class="w-16 h-16 rounded object-cover" 
                                 alt="{{ $item->product_name }}" />
                            <div class="flex-1">
                                <h4 class="font-medium">{{ $item->product_name }}</h4>
                                <p class="text-sm text-gray-500">Qty: {{ $item->quantity }}</p>
                                <p class="text-sm text-gray-500">SKU: {{ $item->product_sku }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold">৳{{ number_format($item->total, 2) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Order Totals -->
                <div class="border-t pt-4 space-y-2">
                    <div class="flex justify-between">
                        <span>Subtotal</span>
                        <span>৳{{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Shipping</span>
                        <span>
                            @if($order->shipping_amount == 0)
                                <span class="text-green-600">Free</span>
                            @else
                                ৳{{ number_format($order->shipping_amount, 2) }}
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span>Tax</span>
                        <span>৳{{ number_format($order->tax_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between font-bold text-lg border-t pt-2">
                        <span>Total</span>
                        <span>৳{{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Order Information -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-semibold mb-6">Order Information</h2>
                
                <div class="space-y-4">
                    <div>
                        <h3 class="font-medium text-gray-900 mb-2">Payment Status</h3>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            {{ $order->payment_status === 'completed' ? 'bg-green-100 text-green-800' : 
                               ($order->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            <i class="fas {{ $order->payment_status === 'completed' ? 'fa-check' : 
                                          ($order->payment_status === 'pending' ? 'fa-clock' : 'fa-times') }} mr-1"></i>
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>

                    <div>
                        <h3 class="font-medium text-gray-900 mb-2">Payment Method</h3>
                        <p class="text-gray-600">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
                    </div>

                    <div>
                        <h3 class="font-medium text-gray-900 mb-2">Order Status</h3>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            {{ $order->order_status === 'confirmed' ? 'bg-blue-100 text-blue-800' : 
                               ($order->order_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                            {{ ucfirst($order->order_status) }}
                        </span>
                    </div>

                    <div>
                        <h3 class="font-medium text-gray-900 mb-2">Order Date</h3>
                        <p class="text-gray-600">{{ $order->created_at->format('F d, Y \a\t h:i A') }}</p>
                    </div>

                    @if($order->transaction_id)
                    <div>
                        <h3 class="font-medium text-gray-900 mb-2">Transaction ID</h3>
                        <p class="text-gray-600 font-mono text-sm">{{ $order->transaction_id }}</p>
                    </div>
                    @endif

                    <div>
                        <h3 class="font-medium text-gray-900 mb-2">Shipping Address</h3>
                        <p class="text-gray-600 whitespace-pre-line">{{ $order->shipping_address }}</p>
                    </div>

                    <div>
                        <h3 class="font-medium text-gray-900 mb-2">Phone Number</h3>
                        <p class="text-gray-600">{{ $order->phone }}</p>
                    </div>

                    @if($order->notes)
                    <div>
                        <h3 class="font-medium text-gray-900 mb-2">Order Notes</h3>
                        <p class="text-gray-600">{{ $order->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Next Steps -->
        <div class="bg-white rounded-xl shadow-lg p-6 mt-8">
            <h2 class="text-xl font-semibold mb-4">What's Next?</h2>
            <div class="grid md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-envelope text-blue-600"></i>
                    </div>
                    <h3 class="font-medium mb-2">Order Confirmation</h3>
                    <p class="text-sm text-gray-600">You'll receive an email confirmation shortly</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-truck text-green-600"></i>
                    </div>
                    <h3 class="font-medium mb-2">Order Processing</h3>
                    <p class="text-sm text-gray-600">We'll prepare your order for shipping</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-phone text-purple-600"></i>
                    </div>
                    <h3 class="font-medium mb-2">Delivery Updates</h3>
                    <p class="text-sm text-gray-600">We'll keep you updated on delivery status</p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 mt-8 justify-center">
            <a href="{{ route('shop') }}" class="px-6 py-3 bg-orange-600 text-white rounded-lg font-semibold hover:bg-orange-700 transition-colors text-center">
                <i class="fas fa-shopping-bag mr-2"></i> Continue Shopping
            </a>
            <a href="{{ route('customer.order.details', $order->id) }}" class="px-6 py-3 bg-gray-600 text-white rounded-lg font-semibold hover:bg-gray-700 transition-colors text-center">
                <i class="fas fa-eye mr-2"></i> View Order Details
            </a>
            <a href="{{ route('customer.orders') }}" class="px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition-colors text-center">
                <i class="fas fa-list mr-2"></i> My Orders
            </a>
        </div>
    </div>
</div>
@endsection


