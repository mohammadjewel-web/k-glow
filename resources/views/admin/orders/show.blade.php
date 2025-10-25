@extends('layouts.admin')
@section('title', 'Order Details')
@section('page-title', 'Order Details')

@section('content')
<!-- Header -->
<div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl shadow-lg mb-8 p-6 text-white">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold mb-2">Order #{{ $order->order_number }}</h1>
            <p class="text-orange-100">View complete order information and details</p>
        </div>
        <div class="flex space-x-3">
            <button onclick="printOrder({{ $order->id }})" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg font-medium transition-all duration-300 hover:scale-105">
                <i class="fas fa-print mr-2"></i> Print Order
            </button>
            <button onclick="updateOrderStatus({{ $order->id }})" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg font-medium transition-all duration-300 hover:scale-105">
                <i class="fas fa-edit mr-2"></i> Update Status
            </button>
            <a href="{{ route('admin.orders.index') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg font-medium transition-all duration-300 hover:scale-105">
                <i class="fas fa-arrow-left mr-2"></i> Back to Orders
            </a>
        </div>
    </div>
</div>

<!-- Order Overview -->
<div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-8">
    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
        <div class="flex items-center">
            <div class="bg-orange-100 p-2 rounded-lg mr-3">
                <i class="fas fa-shopping-cart text-orange-600"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-0">Order Overview</h3>
                <p class="text-sm text-gray-600 mb-0">Basic order information and status</p>
            </div>
        </div>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Order Status -->
            <div class="lg:col-span-1">
                <div class="bg-gray-50 rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">Order Status</h4>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Order Status:</span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-{{ $order->status_color }}-100 text-{{ $order->status_color }}-800">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Payment Status:</span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-{{ $order->payment_status_color }}-100 text-{{ $order->payment_status_color }}-800">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Payment Method:</span>
                            <span class="font-medium text-gray-900">{{ ucfirst($order->payment_method) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Details -->
            <div class="lg:col-span-2">
                <div class="space-y-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="bg-blue-100 p-2 rounded-lg mr-3">
                                    <i class="fas fa-calendar text-blue-600"></i>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500">Order Date</div>
                                    <div class="font-semibold text-gray-900">{{ $order->created_at->format('M d, Y H:i') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="bg-green-100 p-2 rounded-lg mr-3">
                                    <i class="fas fa-user text-green-600"></i>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500">Customer</div>
                                    <div class="font-semibold text-gray-900">{{ $order->user->name }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4">
                        <div class="bg-orange-50 px-4 py-2 rounded-lg">
                            <div class="text-sm text-gray-500">Subtotal</div>
                            <div class="text-xl font-bold text-orange-600">৳{{ number_format($order->subtotal, 2) }}</div>
                        </div>
                        @if($order->tax_amount > 0)
                        <div class="bg-blue-50 px-4 py-2 rounded-lg">
                            <div class="text-sm text-gray-500">Tax</div>
                            <div class="text-lg font-bold text-blue-600">৳{{ number_format($order->tax_amount, 2) }}</div>
                        </div>
                        @endif
                        @if($order->shipping_amount > 0)
                        <div class="bg-purple-50 px-4 py-2 rounded-lg">
                            <div class="text-sm text-gray-500">Shipping</div>
                            <div class="text-lg font-bold text-purple-600">৳{{ number_format($order->shipping_amount, 2) }}</div>
                        </div>
                        @endif
                        @if($order->discount_amount > 0)
                        <div class="bg-red-50 px-4 py-2 rounded-lg">
                            <div class="text-sm text-gray-500">Discount</div>
                            <div class="text-lg font-bold text-red-600">-৳{{ number_format($order->discount_amount, 2) }}</div>
                        </div>
                        @endif
                        <div class="bg-gray-50 px-4 py-2 rounded-lg">
                            <div class="text-sm text-gray-500">Total</div>
                            <div class="text-2xl font-bold text-gray-900">৳{{ number_format($order->total_amount, 2) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Order Items -->
<div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-8">
    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="bg-blue-100 p-2 rounded-lg mr-3">
                    <i class="fas fa-box text-blue-600"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-0">Order Items</h3>
                    <p class="text-sm text-gray-600 mb-0">Products in this order</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                    {{ $order->items->count() }} Items
                </span>
            </div>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($order->items as $item)
                <tr class="hover:bg-gray-50 transition-colors duration-200">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            @if($item->product && $item->product->thumbnail)
                                <img src="{{ asset('admin-assets/products/' . $item->product->thumbnail) }}" 
                                     class="w-12 h-12 rounded-lg object-cover mr-3 shadow-md" alt="Product"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="w-12 h-12 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center mr-3 shadow-md" style="display: none;">
                                    <i class="fas fa-image text-gray-400"></i>
                                </div>
                            @elseif($item->product && $item->product->images && $item->product->images->count() > 0 && $item->product->images->first())
                                <img src="{{ asset('admin-assets/products/' . $item->product->images->first()->image) }}" 
                                     class="w-12 h-12 rounded-lg object-cover mr-3 shadow-md" alt="Product"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="w-12 h-12 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center mr-3 shadow-md" style="display: none;">
                                    <i class="fas fa-image text-gray-400"></i>
                                </div>
                            @else
                                <div class="w-12 h-12 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center mr-3 shadow-md">
                                    <i class="fas fa-image text-gray-400"></i>
                                </div>
                            @endif
                            <div>
                                <div class="text-sm font-bold text-gray-900">{{ $item->product_name }}</div>
                                @if($item->product_options)
                                    <div class="text-xs text-gray-500">
                                        @foreach($item->product_options as $key => $value)
                                            {{ $key }}: {{ $value }}@if(!$loop->last), @endif
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">৳{{ number_format($item->price, 2) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $item->quantity }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-bold text-gray-900">৳{{ number_format($item->price * $item->quantity, 2) }}</div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Customer Information -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- Shipping Address -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200">
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 rounded-t-xl">
            <div class="flex items-center">
                <div class="bg-green-100 p-2 rounded-lg mr-3">
                    <i class="fas fa-shipping-fast text-green-600"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-0">Shipping Address</h3>
                    <p class="text-sm text-gray-600 mb-0">Delivery information</p>
                </div>
            </div>
        </div>
        <div class="p-6">
            @if($order->shipping_address)
                @php $shipping = is_string($order->shipping_address) ? json_decode($order->shipping_address, true) : $order->shipping_address; @endphp
                <div class="space-y-2">
                    <div class="font-medium text-gray-900">{{ $shipping['name'] ?? 'N/A' }}</div>
                    <div class="text-gray-600">{{ $shipping['address'] ?? 'N/A' }}</div>
                    <div class="text-gray-600">{{ $shipping['city'] ?? '' }}, {{ $shipping['state'] ?? '' }} {{ $shipping['postal_code'] ?? '' }}</div>
                    <div class="text-gray-600">{{ $shipping['country'] ?? 'N/A' }}</div>
                    @if($shipping['phone'] ?? null)
                        <div class="text-gray-600">Phone: {{ $shipping['phone'] }}</div>
                    @endif
                </div>
            @else
                <div class="text-gray-500 text-center py-4">
                    <i class="fas fa-map-marker-alt text-2xl mb-2"></i>
                    <div>No shipping address provided</div>
                </div>
            @endif
        </div>
    </div>

    <!-- Billing Address -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200">
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 rounded-t-xl">
            <div class="flex items-center">
                <div class="bg-blue-100 p-2 rounded-lg mr-3">
                    <i class="fas fa-credit-card text-blue-600"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-0">Billing Address</h3>
                    <p class="text-sm text-gray-600 mb-0">Payment information</p>
                </div>
            </div>
        </div>
        <div class="p-6">
            @if($order->billing_address)
                @php $billing = is_string($order->billing_address) ? json_decode($order->billing_address, true) : $order->billing_address; @endphp
                <div class="space-y-2">
                    <div class="font-medium text-gray-900">{{ $billing['name'] ?? 'N/A' }}</div>
                    <div class="text-gray-600">{{ $billing['address'] ?? 'N/A' }}</div>
                    <div class="text-gray-600">{{ $billing['city'] ?? '' }}, {{ $billing['state'] ?? '' }} {{ $billing['postal_code'] ?? '' }}</div>
                    <div class="text-gray-600">{{ $billing['country'] ?? 'N/A' }}</div>
                    @if($billing['phone'] ?? null)
                        <div class="text-gray-600">Phone: {{ $billing['phone'] }}</div>
                    @endif
                </div>
            @else
                <div class="text-gray-500 text-center py-4">
                    <i class="fas fa-credit-card text-2xl mb-2"></i>
                    <div>No billing address provided</div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Order Notes -->
@if($order->notes)
<div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-8">
    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 rounded-t-xl">
        <div class="flex items-center">
            <div class="bg-yellow-100 p-2 rounded-lg mr-3">
                <i class="fas fa-sticky-note text-yellow-600"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-0">Order Notes</h3>
                <p class="text-sm text-gray-600 mb-0">Special instructions or comments</p>
            </div>
        </div>
    </div>
    <div class="p-6">
        <div class="prose max-w-none">
            <div class="text-gray-700 leading-relaxed">
                {{ $order->notes }}
            </div>
        </div>
    </div>
</div>
@endif

<!-- Timeline -->
<div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-8">
    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 rounded-t-xl">
        <div class="flex items-center">
            <div class="bg-purple-100 p-2 rounded-lg mr-3">
                <i class="fas fa-history text-purple-600"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-0">Order Timeline</h3>
                <p class="text-sm text-gray-600 mb-0">Order status history</p>
            </div>
        </div>
    </div>
    <div class="p-6">
        <div class="flow-root">
            <ul class="-mb-8">
                <li>
                    <div class="relative pb-8">
                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></span>
                        <div class="relative flex space-x-3">
                            <div>
                                <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                    <i class="fas fa-check text-white text-sm"></i>
                                </span>
                            </div>
                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                <div>
                                    <p class="text-sm text-gray-500">Order placed on <time datetime="{{ $order->created_at }}">{{ $order->created_at->format('M d, Y H:i') }}</time></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                
                @if($order->status !== 'pending')
                <li>
                    <div class="relative pb-8">
                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></span>
                        <div class="relative flex space-x-3">
                            <div>
                                <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                    <i class="fas fa-cog text-white text-sm"></i>
                                </span>
                            </div>
                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                <div>
                                    <p class="text-sm text-gray-500">Order {{ $order->status }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                @endif
                
                @if($order->shipped_at)
                <li>
                    <div class="relative pb-8">
                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></span>
                        <div class="relative flex space-x-3">
                            <div>
                                <span class="h-8 w-8 rounded-full bg-purple-500 flex items-center justify-center ring-8 ring-white">
                                    <i class="fas fa-truck text-white text-sm"></i>
                                </span>
                            </div>
                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                <div>
                                    <p class="text-sm text-gray-500">Order shipped on <time datetime="{{ $order->shipped_at }}">{{ $order->shipped_at->format('M d, Y H:i') }}</time></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                @endif
                
                @if($order->delivered_at)
                <li>
                    <div class="relative">
                        <div class="relative flex space-x-3">
                            <div>
                                <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                    <i class="fas fa-home text-white text-sm"></i>
                                </span>
                            </div>
                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                <div>
                                    <p class="text-sm text-gray-500">Order delivered on <time datetime="{{ $order->delivered_at }}">{{ $order->delivered_at->format('M d, Y H:i') }}</time></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                @endif
            </ul>
        </div>
    </div>
</div>

<!-- Update Order Status Modal -->
<div id="updateStatusModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4">
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4 rounded-t-xl">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="bg-white bg-opacity-20 p-2 rounded-lg mr-3">
                        <i class="fas fa-edit text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-white">Update Order Status</h3>
                        <p class="text-orange-100 text-sm">Change order and payment status</p>
                    </div>
                </div>
                <button onclick="closeStatusModal()" class="text-white hover:text-orange-200 transition-colors duration-300">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>

        <form id="updateStatusForm" class="p-6">
            <input type="hidden" id="orderId" name="order_id" value="{{ $order->id }}">
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Order Status</label>
                    <select id="orderStatus" name="status" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300">
                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                        <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>⚙️ Processing</option>
                        <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>🚚 Shipped</option>
                        <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>✅ Delivered</option>
                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>❌ Cancelled</option>
                        <option value="refunded" {{ $order->status == 'refunded' ? 'selected' : '' }}>💰 Refunded</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Status</label>
                    <select id="paymentStatus" name="payment_status" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300">
                        <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                        <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>✅ Paid</option>
                        <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>❌ Failed</option>
                        <option value="refunded" {{ $order->payment_status == 'refunded' ? 'selected' : '' }}>💰 Refunded</option>
                        <option value="cancelled" {{ $order->payment_status == 'cancelled' ? 'selected' : '' }}>❌ Cancelled</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                    <textarea id="statusNotes" name="notes" rows="3" placeholder="Add any notes about this status change" 
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300"></textarea>
                </div>
            </div>

            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="closeStatusModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-medium transition-all duration-300 hover:scale-105">
                    <i class="fas fa-times mr-2"></i> Cancel
                </button>
                <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-3 rounded-lg font-medium transition-all duration-300 hover:scale-105 shadow-lg">
                    <i class="fas fa-save mr-2"></i> Update Status
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Update order status function
function updateOrderStatus(orderId) {
    document.getElementById('orderId').value = orderId;
    document.getElementById('updateStatusModal').classList.remove('hidden');
    document.getElementById('updateStatusModal').classList.add('flex');
}

// Close status modal
function closeStatusModal() {
    document.getElementById('updateStatusModal').classList.add('hidden');
    document.getElementById('updateStatusModal').classList.remove('flex');
}

// Update status form submission
document.getElementById('updateStatusForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const orderId = formData.get('order_id');
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Updating...';
    submitBtn.disabled = true;
    
    fetch(`/admin/orders/${orderId}/update-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            status: formData.get('status'),
            payment_status: formData.get('payment_status'),
            notes: formData.get('notes')
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Order status updated successfully!', 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            showNotification('Error: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error updating order status', 'error');
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

// Print order function
function printOrder(orderId) {
    window.open(`/admin/orders/${orderId}/print`, '_blank');
}

// Show notification
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-4 rounded-lg shadow-lg z-50 transition-all duration-300 ${
        type === 'success' ? 'bg-green-500 text-white' :
        type === 'error' ? 'bg-red-500 text-white' :
        type === 'warning' ? 'bg-yellow-500 text-white' :
        'bg-blue-500 text-white'
    }`;
    
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'times' : type === 'warning' ? 'exclamation' : 'info'}-circle mr-2"></i>
            ${message}
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}
</script>
@endsection