@extends('layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Order #{{ $order->order_number }}</h1>
                    <p class="mt-2 text-gray-600">Placed on {{ $order->created_at->format('M d, Y \a\t g:i A') }}</p>
                </div>
                <div class="text-right">
                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                        @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                        @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                        @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                        @elseif($order->status === 'delivered') bg-green-100 text-green-800
                        @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Order Items -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Order Items</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-6">
                            @foreach($order->items as $item)
                                <div class="flex items-start space-x-4">
                                    @if($item->product && $item->product->thumbnail)
                                        <img src="{{ asset('admin-assets/products/'.$item->product->thumbnail) }}" 
                                             alt="{{ $item->product_name }}" 
                                             class="w-20 h-20 object-cover rounded-lg">
                                    @else
                                        <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="flex-1">
                                        <h4 class="text-lg font-medium text-gray-900">{{ $item->product_name }}</h4>
                                        @if($item->product_sku)
                                            <p class="text-sm text-gray-600">SKU: {{ $item->product_sku }}</p>
                                        @endif
                                        <p class="text-sm text-gray-600">Quantity: {{ $item->quantity }}</p>
                                        <p class="text-sm text-gray-600">Price: ${{ number_format($item->price, 2) }} each</p>
                                        @if($item->product_options)
                                            <div class="mt-2">
                                                <p class="text-sm font-medium text-gray-900">Options:</p>
                                                <ul class="text-sm text-gray-600">
                                                    @foreach($item->product_options as $key => $value)
                                                        <li>{{ ucfirst($key) }}: {{ $value }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-semibold text-gray-900">${{ number_format($item->total, 2) }}</p>
                                    </div>
                                </div>
                                @if(!$loop->last)
                                    <hr class="border-gray-200">
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary & Actions -->
            <div class="space-y-6">
                <!-- Order Summary -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Order Summary</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="text-gray-900">${{ number_format($order->subtotal, 2) }}</span>
                            </div>
                            @if($order->discount_amount > 0)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Discount</span>
                                    <span class="text-green-600">-${{ number_format($order->discount_amount, 2) }}</span>
                                </div>
                            @endif
                            @if($order->tax_amount > 0)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Tax</span>
                                    <span class="text-gray-900">${{ number_format($order->tax_amount, 2) }}</span>
                                </div>
                            @endif
                            @if($order->shipping_amount > 0)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Shipping</span>
                                    <span class="text-gray-900">${{ number_format($order->shipping_amount, 2) }}</span>
                                </div>
                            @endif
                            <hr class="border-gray-200">
                            <div class="flex justify-between text-lg font-semibold">
                                <span class="text-gray-900">Total</span>
                                <span class="text-gray-900">${{ number_format($order->total_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shipping Information -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Shipping Information</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-2">
                            <p class="text-sm text-gray-900">{{ $order->shipping_address }}</p>
                            <p class="text-sm text-gray-600">Phone: {{ $order->phone }}</p>
                        </div>
                    </div>
                </div>

                <!-- Order Actions -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Order Actions</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            @if($order->canBeCancelled())
                                <button onclick="cancelOrder({{ $order->id }})" 
                                        class="w-full px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700">
                                    Cancel Order
                                </button>
                            @endif
                            
                            <a href="{{ route('customer.orders') }}" 
                               class="w-full px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 text-center block">
                                Back to Orders
                            </a>
                            
                            <a href="{{ route('shop') }}" 
                               class="w-full px-4 py-2 text-sm font-medium text-white bg-[--brand-orange] border border-transparent rounded-md hover:bg-orange-600 text-center block">
                                Continue Shopping
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Order Timeline -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Order Timeline</h3>
                    </div>
                    <div class="p-6">
                        <div class="flow-root">
                            <ul class="-mb-8">
                                <li>
                                    <div class="relative pb-8">
                                        <div class="relative flex space-x-3">
                                            <div class="flex items-center">
                                                <div class="relative px-1">
                                                    <div class="h-2 w-2 bg-green-500 rounded-full"></div>
                                                </div>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-500">Order placed</p>
                                                </div>
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                    {{ $order->created_at->format('M d, Y') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                
                                @if($order->status === 'processing' || $order->status === 'shipped' || $order->status === 'delivered')
                                    <li>
                                        <div class="relative pb-8">
                                            <div class="relative flex space-x-3">
                                                <div class="flex items-center">
                                                    <div class="relative px-1">
                                                        <div class="h-2 w-2 bg-blue-500 rounded-full"></div>
                                                    </div>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                    <div>
                                                        <p class="text-sm text-gray-500">Order processing</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endif
                                
                                @if($order->status === 'shipped' || $order->status === 'delivered')
                                    <li>
                                        <div class="relative pb-8">
                                            <div class="relative flex space-x-3">
                                                <div class="flex items-center">
                                                    <div class="relative px-1">
                                                        <div class="h-2 w-2 bg-purple-500 rounded-full"></div>
                                                    </div>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                    <div>
                                                        <p class="text-sm text-gray-500">Order shipped</p>
                                                    </div>
                                                    @if($order->shipped_at)
                                                        <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                            {{ $order->shipped_at->format('M d, Y') }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endif
                                
                                @if($order->status === 'delivered')
                                    <li>
                                        <div class="relative">
                                            <div class="relative flex space-x-3">
                                                <div class="flex items-center">
                                                    <div class="relative px-1">
                                                        <div class="h-2 w-2 bg-green-500 rounded-full"></div>
                                                    </div>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                    <div>
                                                        <p class="text-sm text-gray-500">Order delivered</p>
                                                    </div>
                                                    @if($order->delivered_at)
                                                        <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                            {{ $order->delivered_at->format('M d, Y') }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Order Modal -->
<div id="cancelModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Cancel Order</h3>
            </div>
            <form id="cancelForm" method="POST">
                @csrf
                <div class="px-6 py-4">
                    <p class="text-sm text-gray-600 mb-4">Are you sure you want to cancel this order? This action cannot be undone.</p>
                    <div>
                        <label for="cancellation_reason" class="block text-sm font-medium text-gray-700">Reason for cancellation (optional)</label>
                        <textarea id="cancellation_reason" name="cancellation_reason" rows="3" 
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-[--brand-orange] focus:border-[--brand-orange] sm:text-sm"></textarea>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-3">
                    <button type="button" onclick="closeCancelModal()" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700">
                        Cancel Order
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function cancelOrder(orderId) {
    const modal = document.getElementById('cancelModal');
    const form = document.getElementById('cancelForm');
    form.action = '{{ route("customer.order.cancel", ":id") }}'.replace(':id', orderId);
    modal.classList.remove('hidden');
}

function closeCancelModal() {
    document.getElementById('cancelModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('cancelModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeCancelModal();
    }
});
</script>
@endsection
