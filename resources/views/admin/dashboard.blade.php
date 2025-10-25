@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Statistics Cards -->
    <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-orange-500 hover:shadow-xl transition-shadow duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-orange-600 uppercase tracking-wide">Total Revenue</p>
                <p class="text-2xl font-bold text-gray-900">৳{{ number_format($stats['total_revenue'], 2) }}</p>
            </div>
            <div class="bg-orange-100 p-3 rounded-full">
                <i class="fas fa-dollar-sign text-2xl text-orange-600"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-green-500 hover:shadow-xl transition-shadow duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-green-600 uppercase tracking-wide">Total Orders</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_orders'] }}</p>
            </div>
            <div class="bg-green-100 p-3 rounded-full">
                <i class="fas fa-shopping-cart text-2xl text-green-600"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-cyan-500 hover:shadow-xl transition-shadow duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-cyan-600 uppercase tracking-wide">Total Products</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_products'] }}</p>
            </div>
            <div class="bg-cyan-100 p-3 rounded-full">
                <i class="fas fa-box text-2xl text-cyan-600"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-yellow-500 hover:shadow-xl transition-shadow duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-yellow-600 uppercase tracking-wide">Total Customers</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_customers'] }}</p>
            </div>
            <div class="bg-yellow-100 p-3 rounded-full">
                <i class="fas fa-users text-2xl text-yellow-600"></i>
            </div>
        </div>
    </div>
</div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Revenue Overview -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h6 class="text-lg font-semibold text-gray-900">Revenue Overview</h6>
                <div class="flex space-x-2">
                    <button type="button" class="px-3 py-1 text-sm bg-orange-100 text-orange-700 rounded-md hover:bg-orange-200 active">7 Days</button>
                    <button type="button" class="px-3 py-1 text-sm bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">30 Days</button>
                    <button type="button" class="px-3 py-1 text-sm bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">90 Days</button>
                </div>
            </div>
            
            <!-- Revenue Stats -->
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">৳{{ number_format($stats['total_revenue'] * 0.3, 0) }}</div>
                    <div class="text-sm text-gray-500">This Week</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-orange-600">৳{{ number_format($stats['total_revenue'] * 0.6, 0) }}</div>
                    <div class="text-sm text-gray-500">This Month</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-purple-600">৳{{ number_format($stats['total_revenue'], 0) }}</div>
                    <div class="text-sm text-gray-500">Total</div>
                </div>
            </div>
            
            <!-- Simple Progress Bars -->
            <div class="space-y-3">
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600">Weekly Revenue</span>
                        <span class="text-gray-900 font-medium">30%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: 30%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600">Monthly Revenue</span>
                        <span class="text-gray-900 font-medium">60%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-orange-500 h-2 rounded-full" style="width: 60%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600">Total Revenue</span>
                        <span class="text-gray-900 font-medium">100%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-purple-500 h-2 rounded-full" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Status -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h6 class="text-lg font-semibold text-gray-900 mb-4">Order Status</h6>
            
            <!-- Order Status Stats -->
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-yellow-600">{{ $stats['total_orders'] * 0.3 }}</div>
                    <div class="text-sm text-gray-500">Pending</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">{{ $stats['total_orders'] * 0.5 }}</div>
                    <div class="text-sm text-gray-500">Completed</div>
                </div>
</div>

            <!-- Order Status Bars -->
            <div class="space-y-3">
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600">Pending Orders</span>
                        <span class="text-gray-900 font-medium">30%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-yellow-500 h-2 rounded-full" style="width: 30%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600">Processing</span>
                        <span class="text-gray-900 font-medium">20%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: 20%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600">Completed</span>
                        <span class="text-gray-900 font-medium">50%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: 50%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Recent Orders -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="flex justify-between items-center p-6 border-b border-gray-200">
            <h6 class="text-lg font-semibold text-gray-900">Recent Orders</h6>
            <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 bg-orange-600 text-white text-sm rounded-md hover:bg-orange-700 transition-colors">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($recent_orders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $order->order_number }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $order->user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-{{ $order->status_color }}-100 text-{{ $order->status_color }}-800">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">৳{{ number_format($order->total_amount, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="text-orange-600 hover:text-orange-900">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                </tr>
                    @endforeach
            </tbody>
        </table>
    </div>
</div>

    <!-- Low Stock Products -->
    <div class="col-xl-4 col-lg-5">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-orange-200">
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4">
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <div class="bg-white bg-opacity-20 p-2 rounded-lg mr-3">
                            <i class="fas fa-exclamation-triangle text-white text-lg"></i>
                        </div>
                        <div>
                            <h6 class="text-white font-bold text-lg mb-0">Low Stock Alert</h6>
                            <p class="text-orange-100 text-sm mb-0">Products running low on stock</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.inventory.index') }}?status=low_stock" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-3 py-1 rounded-lg text-sm font-medium transition-all duration-300">
                        View All
                    </a>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($low_stock_products as $product)
                    <div class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-lg p-4 border border-yellow-200 hover:shadow-md transition-all duration-300">
                        <div class="flex justify-between items-center">
                            <div class="flex-1">
                                <div class="font-semibold text-gray-800 text-sm mb-1">{{ $product->product ? $product->product->name : 'Unknown Product' }}</div>
                                <div class="text-xs text-gray-500 mb-2">SKU: {{ $product->sku ?? 'N/A' }}</div>
                                <div class="flex items-center">
                                    <div class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs font-medium mr-2">
                                        {{ $product->current_stock }} left
                                    </div>
                                    <div class="text-xs text-gray-500">Min: {{ $product->minimum_stock }}</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-exclamation text-white text-sm"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Top Products -->
    <div class="col-xl-6 col-lg-6">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-green-200">
            <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <div class="bg-white bg-opacity-20 p-2 rounded-lg mr-3">
                            <i class="fas fa-chart-line text-white text-lg"></i>
                        </div>
                        <div>
                            <h6 class="text-white font-bold text-lg mb-0">Top Selling Products</h6>
                            <p class="text-green-100 text-sm mb-0">Best performing products</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.products.index') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-3 py-1 rounded-lg text-sm font-medium transition-all duration-300">
                        View All
                    </a>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($top_products as $product)
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg p-4 border border-green-200 hover:shadow-md transition-all duration-300">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center flex-1">
                                @if($product->images && $product->images->count() > 0)
                                    <img src="{{ asset('admin-assets/products/' . $product->images->first()->image) }}" 
                                         class="w-12 h-12 rounded-lg object-cover mr-4 shadow-sm" alt="Product">
                                @else
                                    <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-emerald-500 rounded-lg flex items-center justify-center mr-4 shadow-sm">
                                        <i class="fas fa-image text-white text-sm"></i>
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <div class="font-semibold text-gray-800 text-sm mb-1">{{ $product->name }}</div>
                                    <div class="text-xs text-gray-500 mb-2">{{ $product->category ? $product->category->name : 'N/A' }}</div>
                                    <div class="flex items-center">
                                        <div class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium mr-2">
                                            {{ $product->order_items_sum_quantity ?? 0 }} sold
                                        </div>
                                        <div class="text-xs text-gray-500">৳{{ number_format($product->price, 2) }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-emerald-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-trophy text-white text-sm"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Customers -->
    <div class="col-xl-6 col-lg-6">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-blue-200">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <div class="bg-white bg-opacity-20 p-2 rounded-lg mr-3">
                            <i class="fas fa-users text-white text-lg"></i>
                        </div>
                        <div>
                            <h6 class="text-white font-bold text-lg mb-0">Recent Customers</h6>
                            <p class="text-blue-100 text-sm mb-0">Latest registered users</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.users.index') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-3 py-1 rounded-lg text-sm font-medium transition-all duration-300">
                        View All
                    </a>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($recent_customers as $customer)
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-4 border border-blue-200 hover:shadow-md transition-all duration-300">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center flex-1">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-full flex items-center justify-center mr-4 shadow-sm">
                                    <span class="text-white font-bold text-sm">{{ strtoupper(substr($customer->name, 0, 1)) }}</span>
                                </div>
                                <div class="flex-1">
                                    <div class="font-semibold text-gray-800 text-sm mb-1">{{ $customer->name }}</div>
                                    <div class="text-xs text-gray-500 mb-2">{{ $customer->email }}</div>
                                    <div class="flex items-center">
                                        <div class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-medium mr-2">
                                            {{ $customer->orders_count ?? 0 }} orders
                                        </div>
                                        <div class="text-xs text-gray-500">{{ $customer->created_at->format('M d, Y') }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user-plus text-white text-sm"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Simple button toggle functionality
function updateChart(period) {
    // Update active button
    document.querySelectorAll('.flex.space-x-2 button').forEach(btn => {
        btn.classList.remove('bg-orange-100', 'text-orange-700');
        btn.classList.add('bg-gray-100', 'text-gray-700');
    });
    event.target.classList.remove('bg-gray-100', 'text-gray-700');
    event.target.classList.add('bg-orange-100', 'text-orange-700');
    
    // Here you could fetch new data based on the period
    console.log('Updating data for period:', period);
}

// Helper function for order status colors
function getOrderStatusColor(status) {
    const colors = {
        'pending': 'warning',
        'confirmed': 'info',
        'processing': 'primary',
        'shipped': 'info',
        'delivered': 'success',
        'cancelled': 'danger',
        'refunded': 'secondary'
    };
    return colors[status] || 'secondary';
}
</script>
@endsection