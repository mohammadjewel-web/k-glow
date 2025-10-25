@extends('layouts.admin')

@section('title', 'Order Management')
@section('page-title', 'Orders')

@section('content')
<!-- Header Section -->
<div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl shadow-lg mb-8 p-6 text-white">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold mb-2">Order Management</h1>
            <p class="text-orange-100">Manage and track all customer orders</p>
        </div>
        <div class="flex space-x-3">
            <button class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg font-medium transition-all duration-300 hover:scale-105" onclick="exportOrders()">
                <i class="fas fa-download mr-2"></i> Export Orders
            </button>
            <button class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg font-medium transition-all duration-300 hover:scale-105">
                <i class="fas fa-plus mr-2"></i> Add Order
            </button>
        </div>
    </div>
</div>

<!-- Filters Section -->
<div class="bg-white rounded-xl shadow-lg mb-8 border border-gray-200">
    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="bg-orange-100 p-2 rounded-lg mr-3">
                    <i class="fas fa-filter text-orange-600"></i>
                </div>
                <div>
                    <h6 class="text-lg font-semibold text-gray-800 mb-0">Advanced Filters</h6>
                    <p class="text-sm text-gray-600 mb-0">Filter orders by various criteria</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <span class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm font-medium">
                    {{ $orders->total() }} Total Orders
                </span>
                <button onclick="toggleFilters()" class="bg-orange-100 hover:bg-orange-200 text-orange-700 px-3 py-1 rounded-lg text-sm font-medium transition-all duration-300">
                    <i class="fas fa-chevron-down mr-1" id="filterToggleIcon"></i> Toggle Filters
                </button>
            </div>
        </div>
    </div>
    
    <div id="filtersContent" class="p-6">
        <form method="GET" action="{{ route('admin.orders.index') }}" id="filterForm">
            <!-- Basic Filters Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-search mr-1 text-orange-600"></i>Search
                    </label>
                    <div class="relative">
                        <input type="text" class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300" 
                               id="search" name="search" value="{{ request('search') }}" placeholder="Order #, Customer name or email...">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                </div>
                
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-truck mr-1 text-orange-600"></i>Order Status
                    </label>
                    <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300" id="status" name="status">
                        <option value="">All Status</option>
                        @foreach($statusOptions as $value => $label)
                            <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="payment_status" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-credit-card mr-1 text-orange-600"></i>Payment Status
                    </label>
                    <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300" id="payment_status" name="payment_status">
                        <option value="">All Payment</option>
                        @foreach($paymentStatusOptions as $value => $label)
                            <option value="{{ $value }}" {{ request('payment_status') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="sort_by" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-sort mr-1 text-orange-600"></i>Sort By
                    </label>
                    <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300" id="sort_by" name="sort_by">
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date Created</option>
                        <option value="order_number" {{ request('sort_by') == 'order_number' ? 'selected' : '' }}>Order Number</option>
                        <option value="total_amount" {{ request('sort_by') == 'total_amount' ? 'selected' : '' }}>Total Amount</option>
                        <option value="status" {{ request('sort_by') == 'status' ? 'selected' : '' }}>Status</option>
                    </select>
                </div>
            </div>
            
            <!-- Advanced Filters Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div>
                    <label for="date_from" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar-alt mr-1 text-orange-600"></i>From Date
                    </label>
                    <input type="date" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300" 
                           id="date_from" name="date_from" value="{{ request('date_from') }}">
                </div>
                
                <div>
                    <label for="date_to" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar-alt mr-1 text-orange-600"></i>To Date
                    </label>
                    <input type="date" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300" 
                           id="date_to" name="date_to" value="{{ request('date_to') }}">
                </div>
                
                <div>
                    <label for="amount_from" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-dollar-sign mr-1 text-orange-600"></i>Min Amount
                    </label>
                    <input type="number" step="0.01" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300" 
                           id="amount_from" name="amount_from" value="{{ request('amount_from') }}" placeholder="0.00">
                </div>
                
                <div>
                    <label for="amount_to" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-dollar-sign mr-1 text-orange-600"></i>Max Amount
                    </label>
                    <input type="number" step="0.01" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300" 
                           id="amount_to" name="amount_to" value="{{ request('amount_to') }}" placeholder="999999.99">
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center space-x-3">
                    <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-3 rounded-lg font-medium transition-all duration-300 hover:scale-105 shadow-lg">
                        <i class="fas fa-search mr-2"></i> Apply Filters
                    </button>
                    
                    <a href="{{ route('admin.orders.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-medium transition-all duration-300 hover:scale-105">
                        <i class="fas fa-times mr-2"></i> Clear All
                    </a>
                    
                    <a href="{{ route('admin.orders.export', request()->query()) }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition-all duration-300 hover:scale-105">
                        <i class="fas fa-download mr-2"></i> Export CSV
                    </a>
                </div>
                
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-500">Sort Order:</span>
                    <select name="sort_order" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Descending</option>
                        <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                    </select>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Orders Table -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <div class="bg-orange-100 p-2 rounded-lg mr-3">
                    <i class="fas fa-shopping-cart text-orange-600"></i>
                </div>
                <div>
                    <h6 class="text-lg font-semibold text-gray-800 mb-0">Orders List</h6>
                    <p class="text-sm text-gray-600 mb-0">Manage and track customer orders</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <span class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm font-medium">
                    {{ $orders->count() }} Orders
                </span>
            </div>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order #</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($orders as $order)
                <tr class="hover:bg-gray-50 transition-colors duration-200">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="bg-orange-100 p-2 rounded-lg mr-3">
                                <i class="fas fa-receipt text-orange-600 text-sm"></i>
                            </div>
                            <div>
                                <div class="text-sm font-bold text-gray-900">#{{ $order->order_number }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-full flex items-center justify-center mr-3">
                                <span class="text-white font-bold text-sm">{{ strtoupper(substr($order->user->name, 0, 1)) }}</span>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $order->user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $order->user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-{{ $order->status_color }}-100 text-{{ $order->status_color }}-800">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-{{ $order->payment_status_color }}-100 text-{{ $order->payment_status_color }}-800">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                        ৳{{ number_format($order->total_amount, 2) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $order->created_at->format('M d, Y H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.orders.show', $order->id) }}" 
                               class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-1 rounded-lg transition-colors duration-200" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button class="bg-yellow-100 hover:bg-yellow-200 text-yellow-700 px-3 py-1 rounded-lg transition-colors duration-200" 
                                    onclick="updateOrderStatus({{ $order->id }})" title="Update Status">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="bg-green-100 hover:bg-green-200 text-green-700 px-3 py-1 rounded-lg transition-colors duration-200" 
                                    onclick="printOrder({{ $order->id }})" title="Print">
                                <i class="fas fa-print"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-gray-200 flex justify-center">
        {{ $orders->links() }}
    </div>
</div>

<!-- Update Order Status Modal -->
<div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden" id="updateStatusModal">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-xl bg-white">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-t-xl -m-5 mb-5 p-6 text-white">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <div class="bg-white bg-opacity-20 p-2 rounded-lg mr-3">
                        <i class="fas fa-edit text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold">Update Order Status</h3>
                        <p class="text-orange-100 text-sm">Change order and payment status</p>
                    </div>
                </div>
                <button onclick="closeStatusModal()" class="text-white hover:text-orange-200 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>

        <form id="updateStatusForm">
            <input type="hidden" id="orderId" name="order_id">
            
            <!-- Order Status -->
            <div class="mb-6">
                <label for="newStatus" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-truck mr-2 text-orange-600"></i>Order Status
                </label>
                <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300" 
                        id="newStatus" name="status" required>
                    <option value="pending">🟡 Pending</option>
                    <option value="confirmed">🔵 Confirmed</option>
                    <option value="processing">🟠 Processing</option>
                    <option value="shipped">🚚 Shipped</option>
                    <option value="delivered">✅ Delivered</option>
                    <option value="cancelled">❌ Cancelled</option>
                </select>
            </div>
            
            <!-- Payment Status -->
            <div class="mb-6">
                <label for="paymentStatus" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-credit-card mr-2 text-orange-600"></i>Payment Status
                </label>
                <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300" 
                        id="paymentStatus" name="payment_status">
                    <option value="pending">🟡 Pending</option>
                    <option value="paid">✅ Paid</option>
                    <option value="failed">❌ Failed</option>
                    <option value="refunded">🔄 Refunded</option>
                </select>
            </div>
            
            <!-- Notes -->
            <div class="mb-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-sticky-note mr-2 text-orange-600"></i>Notes
                </label>
                <textarea class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300" 
                          id="notes" name="notes" rows="3" 
                          placeholder="Add notes about the status change (optional)"></textarea>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex space-x-3">
                <button type="button" onclick="closeStatusModal()" 
                        class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-4 py-3 rounded-lg font-medium transition-all duration-300 hover:scale-105">
                    <i class="fas fa-times mr-2"></i>Cancel
                </button>
                <button type="submit" 
                        class="flex-1 bg-orange-600 hover:bg-orange-700 text-white px-4 py-3 rounded-lg font-medium transition-all duration-300 hover:scale-105">
                    <i class="fas fa-save mr-2"></i>Update Status
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

// Close status modal function
function closeStatusModal() {
    document.getElementById('updateStatusModal').classList.add('hidden');
    document.getElementById('updateStatusModal').classList.remove('flex');
}

// Update status form submission
document.getElementById('updateStatusForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const orderId = formData.get('order_id');
    const submitButton = this.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;
    
    // Show loading state
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Updating...';
    submitButton.disabled = true;
    
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
            // Show success message
            showNotification('Order status updated successfully!', 'success');
            closeStatusModal();
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification('Error: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error updating order status', 'error');
    })
    .finally(() => {
        // Reset button state
        submitButton.innerHTML = originalText;
        submitButton.disabled = false;
    });
});

// Show notification function
function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg text-white font-medium transition-all duration-300 ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    }`;
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} mr-2"></i>
            ${message}
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Print order function
function printOrder(orderId) {
    window.open(`/admin/orders/${orderId}/print`, '_blank');
}

// Export orders function
function exportOrders() {
    const params = new URLSearchParams(window.location.search);
    window.open(`/admin/orders/export?${params.toString()}`, '_blank');
}

// Toggle filters function
function toggleFilters() {
    const filtersContent = document.getElementById('filtersContent');
    const toggleIcon = document.getElementById('filterToggleIcon');
    
    if (filtersContent.style.display === 'none') {
        filtersContent.style.display = 'block';
        toggleIcon.className = 'fas fa-chevron-up mr-1';
    } else {
        filtersContent.style.display = 'none';
        toggleIcon.className = 'fas fa-chevron-down mr-1';
    }
}

// Auto-submit form on filter change
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterForm');
    const filterInputs = filterForm.querySelectorAll('select, input[type="date"], input[type="number"]');
    
    // Auto-submit on change (with debounce)
    let timeoutId;
    filterInputs.forEach(input => {
        input.addEventListener('change', function() {
            clearTimeout(timeoutId);
            timeoutId = setTimeout(() => {
                filterForm.submit();
            }, 500);
        });
    });
    
    // Real-time search
    const searchInput = document.getElementById('search');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                filterForm.submit();
            }, 1000);
        });
    }
    
    // Initialize filters state
    const urlParams = new URLSearchParams(window.location.search);
    const hasFilters = Array.from(urlParams.keys()).some(key => 
        key !== 'page' && urlParams.get(key) !== ''
    );
    
    if (!hasFilters) {
        document.getElementById('filtersContent').style.display = 'none';
        document.getElementById('filterToggleIcon').className = 'fas fa-chevron-down mr-1';
    }
});

</script>
@endsection
