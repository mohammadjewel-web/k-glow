@extends('layouts.app')

@section('title', 'Payment Success')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-6">
        <!-- Success Header -->
        <div class="text-center mb-8">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Payment Successful!</h1>
            <p class="text-lg text-gray-600">Your payment has been processed successfully.</p>
        </div>

        @if($orderData)
        <!-- Order Information -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">Order Details</h2>
            <div class="mb-4 p-4 bg-green-50 rounded-lg border border-green-200">
                <p class="text-green-800 font-medium">
                    ✅ Payment has been successfully processed and your order is confirmed!
                </p>
            </div>
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-500">Order Number</p>
                    <p class="font-semibold">#{{ $orderData->order_number }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Amount</p>
                    <p class="font-semibold">৳{{ number_format($orderData->total_amount, 2) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Payment Status</p>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        {{ ucfirst($orderData->payment_status) }}
                    </span>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Order Status</p>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ ucfirst($orderData->order_status) }}
                    </span>
                </div>
            </div>
        </div>
        @else
        <!-- Testing Section for Development -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-8">
            <h2 class="text-lg font-semibold text-yellow-800 mb-4">🧪 Development Testing</h2>
            <p class="text-yellow-700 mb-4">
                SSLCommerz sandbox doesn't call back to localhost URLs. Use the manual completion option below for testing.
            </p>
            
            @php
                $latestOrder = \App\Models\Order::where('payment_status', 'pending')->latest()->first();
            @endphp
            
            @if($latestOrder)
            <div class="bg-white p-4 rounded border">
                <p class="text-sm text-gray-600 mb-2">Latest pending order:</p>
                <p class="font-medium">#{{ $latestOrder->order_number }} - ৳{{ number_format($latestOrder->total_amount, 2) }}</p>
                <a href="{{ route('complete.payment', $latestOrder->id) }}" 
                   class="inline-flex items-center mt-3 px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Complete Payment Manually
                </a>
            </div>
            @else
            <p class="text-yellow-600">No pending orders found.</p>
            @endif
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="text-center space-y-4">
            <div class="space-x-4">
                <a href="{{ route('customer.orders') }}" 
                   class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    View My Orders
                </a>
                
                <a href="{{ route('customer.dashboard') }}" 
                   class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                    </svg>
                    Go to Dashboard
                </a>
            </div>
            
            <div>
                <a href="{{ route('shop') }}" 
                   class="text-primary-600 hover:text-primary-500 font-medium">
                    Continue Shopping
                </a>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="mt-8 bg-blue-50 rounded-lg p-6">
            <h3 class="text-lg font-medium text-blue-900 mb-2">What's Next?</h3>
            <ul class="text-blue-800 space-y-2">
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    You will receive an email confirmation shortly
                </li>
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    Your order will be processed within 24 hours
                </li>
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    Track your order status in your account
                </li>
            </ul>
        </div>
    </div>
</div>

<script>
// Auto redirect to customer orders after 5 seconds
setTimeout(function() {
    window.location.href = '{{ route("customer.orders") }}';
}, 5000);
</script>
@endsection
