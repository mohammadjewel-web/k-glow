@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">My Orders</h1>
            <p class="mt-2 text-gray-600">Track and manage your orders</p>
        </div>

        <!-- Filter Tabs -->
        <div class="mb-6">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8">
                    <a href="{{ route('customer.orders') }}" 
                       class="py-2 px-1 border-b-2 font-medium text-sm {{ !$status ? 'border-[--brand-orange] text-[--brand-orange]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        All Orders
                    </a>
                    <a href="{{ route('customer.orders', ['status' => 'pending']) }}" 
                       class="py-2 px-1 border-b-2 font-medium text-sm {{ $status === 'pending' ? 'border-[--brand-orange] text-[--brand-orange]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Pending
                    </a>
                    <a href="{{ route('customer.orders', ['status' => 'processing']) }}" 
                       class="py-2 px-1 border-b-2 font-medium text-sm {{ $status === 'processing' ? 'border-[--brand-orange] text-[--brand-orange]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Processing
                    </a>
                    <a href="{{ route('customer.orders', ['status' => 'shipped']) }}" 
                       class="py-2 px-1 border-b-2 font-medium text-sm {{ $status === 'shipped' ? 'border-[--brand-orange] text-[--brand-orange]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Shipped
                    </a>
                    <a href="{{ route('customer.orders', ['status' => 'delivered']) }}" 
                       class="py-2 px-1 border-b-2 font-medium text-sm {{ $status === 'delivered' ? 'border-[--brand-orange] text-[--brand-orange]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Delivered
                    </a>
                </nav>
            </div>
        </div>

        <!-- Orders List -->
        @if($orders->count() > 0)
            <div class="space-y-6">
                @foreach($orders as $order)
                    <div class="bg-white rounded-lg shadow">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">Order #{{ $order->order_number }}</h3>
                                    <p class="text-sm text-gray-600">Placed on {{ $order->created_at->format('M d, Y \a\t g:i A') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-semibold text-gray-900">${{ number_format($order->total_amount, 2) }}</p>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
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
                        
                        <div class="px-6 py-4">
                            <!-- Order Items -->
                            <div class="space-y-3">
                                @foreach($order->items as $item)
                                    <div class="flex items-center space-x-4">
                                        @if($item->product && $item->product->thumbnail)
                                            <img src="{{ asset('admin-assets/products/'.$item->product->thumbnail) }}" 
                                                 alt="{{ $item->product_name }}" 
                                                 class="w-16 h-16 object-cover rounded-lg">
                                        @else
                                            <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="flex-1">
                                            <h4 class="text-sm font-medium text-gray-900">{{ $item->product_name }}</h4>
                                            <p class="text-sm text-gray-600">Qty: {{ $item->quantity }} × ${{ number_format($item->price, 2) }}</p>
                                            @if($item->product_options)
                                                <p class="text-xs text-gray-500">
                                                    @foreach($item->product_options as $key => $value)
                                                        {{ ucfirst($key) }}: {{ $value }}@if(!$loop->last), @endif
                                                    @endforeach
                                                </p>
                                            @endif
                                        </div>
                                        <div class="text-sm font-medium text-gray-900">
                                            ${{ number_format($item->total, 2) }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <!-- Order Actions -->
                            <div class="mt-6 flex items-center justify-between">
                                <div class="flex space-x-4">
                                    <a href="{{ route('customer.order.details', $order->id) }}" 
                                       class="text-sm text-[--brand-orange] hover:underline">
                                        View Details
                                    </a>
                                    @if($order->canBeCancelled())
                                        <button onclick="cancelOrder({{ $order->id }})" 
                                                class="text-sm text-red-600 hover:underline">
                                            Cancel Order
                                        </button>
                                    @endif
                                </div>
                                <div class="text-sm text-gray-600">
                                    @if($order->status === 'shipped' && $order->shipped_at)
                                        Shipped on {{ $order->shipped_at->format('M d, Y') }}
                                    @elseif($order->status === 'delivered' && $order->delivered_at)
                                        Delivered on {{ $order->delivered_at->format('M d, Y') }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $orders->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No orders found</h3>
                <p class="mt-1 text-sm text-gray-500">
                    @if($status)
                        You don't have any {{ $status }} orders.
                    @else
                        You haven't placed any orders yet.
                    @endif
                </p>
                <div class="mt-6">
                    <a href="{{ route('shop') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-[--brand-orange] hover:bg-orange-600">
                        Start Shopping
                    </a>
                </div>
            </div>
        @endif
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
