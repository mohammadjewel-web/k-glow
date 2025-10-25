@extends('layouts.admin')

@section('title', 'Reports & Analytics')
@section('page-title', 'Reports')

@section('content')
<style>
    .btn-export-report {
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        text-decoration: none;
    }
    
    .btn-export-report:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.05);
        color: white;
        text-decoration: none;
    }
    
    .btn-generate-report {
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        text-decoration: none;
    }
    
    .btn-generate-report:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.05);
        color: white;
        text-decoration: none;
    }
</style>

<!-- Header Section -->
<div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl shadow-lg mb-8 p-6 text-white">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold mb-2">Reports & Analytics</h1>
            <p class="text-orange-100">Comprehensive business insights and analytics</p>
        </div>
        <div class="flex space-x-3">
            <button class="btn-export-report" onclick="exportReport('sales')">
                <i class="fas fa-download mr-2"></i> Export Sales
            </button>
            <button class="btn-export-report" onclick="exportReport('orders')">
                <i class="fas fa-download mr-2"></i> Export Orders
            </button>
            <button class="btn-generate-report" onclick="generateReport()">
                <i class="fas fa-chart-line mr-2"></i> Generate Report
            </button>
        </div>
    </div>
</div>

<!-- Date Range Filter -->
<div class="bg-white rounded-xl shadow-lg mb-8 p-6">
    <div class="flex flex-wrap items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Date Range Filter</h3>
        <button onclick="clearFilters()" class="text-orange-600 hover:text-orange-800 text-sm font-medium">
            <i class="fas fa-times mr-1"></i> Clear Filters
        </button>
    </div>

    <form id="dateFilterForm" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">From Date</label>
            <input type="date" id="date_from" name="date_from" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                   value="{{ request('date_from', now()->subDays(30)->format('Y-m-d')) }}">
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">To Date</label>
            <input type="date" id="date_to" name="date_to" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                   value="{{ request('date_to', now()->format('Y-m-d')) }}">
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Quick Select</label>
            <select id="period" name="period" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                <option value="">Custom Range</option>
                <option value="today">Today</option>
                <option value="yesterday">Yesterday</option>
                <option value="last_7_days">Last 7 Days</option>
                <option value="last_30_days">Last 30 Days</option>
                <option value="last_90_days">Last 90 Days</option>
                <option value="this_month">This Month</option>
                <option value="last_month">Last Month</option>
                <option value="this_year">This Year</option>
            </select>
        </div>
        
        <div class="flex items-end">
            <button type="submit" class="w-full px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors duration-200">
                <i class="fas fa-filter mr-2"></i> Apply Filter
            </button>
        </div>
    </form>
</div>

<!-- Key Metrics -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <div class="flex items-center">
            <div class="bg-blue-100 p-3 rounded-lg">
                <i class="fas fa-dollar-sign text-blue-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                <p class="text-2xl font-bold text-gray-900">৳0</p>
                <p class="text-xs text-green-600 mt-1">
                    <i class="fas fa-arrow-up mr-1"></i>0% from last period
                </p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <div class="flex items-center">
            <div class="bg-green-100 p-3 rounded-lg">
                <i class="fas fa-shopping-cart text-green-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Orders</p>
                <p class="text-2xl font-bold text-gray-900">0</p>
                <p class="text-xs text-green-600 mt-1">
                    <i class="fas fa-arrow-up mr-1"></i>0% from last period
                </p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <div class="flex items-center">
            <div class="bg-yellow-100 p-3 rounded-lg">
                <i class="fas fa-users text-yellow-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">New Customers</p>
                <p class="text-2xl font-bold text-gray-900">0</p>
                <p class="text-xs text-green-600 mt-1">
                    <i class="fas fa-arrow-up mr-1"></i>0% from last period
                </p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <div class="flex items-center">
            <div class="bg-purple-100 p-3 rounded-lg">
                <i class="fas fa-chart-line text-purple-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Average Order Value</p>
                <p class="text-2xl font-bold text-gray-900">৳0</p>
                <p class="text-xs text-green-600 mt-1">
                    <i class="fas fa-arrow-up mr-1"></i>0% from last period
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- Revenue Chart -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Revenue Trend</h3>
            <div class="flex space-x-2">
                <button class="px-3 py-1 text-xs bg-orange-100 text-orange-800 rounded-full">Daily</button>
                <button class="px-3 py-1 text-xs bg-gray-100 text-gray-600 rounded-full">Weekly</button>
                <button class="px-3 py-1 text-xs bg-gray-100 text-gray-600 rounded-full">Monthly</button>
            </div>
        </div>
        <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
            <div class="text-center">
                <i class="fas fa-chart-area text-gray-400 text-4xl mb-2"></i>
                <p class="text-gray-500">Revenue chart will be displayed here</p>
            </div>
        </div>
    </div>

    <!-- Orders Chart -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Orders Trend</h3>
            <div class="flex space-x-2">
                <button class="px-3 py-1 text-xs bg-orange-100 text-orange-800 rounded-full">Daily</button>
                <button class="px-3 py-1 text-xs bg-gray-100 text-gray-600 rounded-full">Weekly</button>
                <button class="px-3 py-1 text-xs bg-gray-100 text-gray-600 rounded-full">Monthly</button>
            </div>
        </div>
        <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
            <div class="text-center">
                <i class="fas fa-chart-bar text-gray-400 text-4xl mb-2"></i>
                <p class="text-gray-500">Orders chart will be displayed here</p>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Reports -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    <!-- Top Products -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Top Products</h3>
            <button class="text-orange-600 hover:text-orange-800 text-sm font-medium">
                View All
            </button>
        </div>
        <div class="space-y-3">
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-box text-gray-400"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Product Name</p>
                        <p class="text-xs text-gray-500">0 sales</p>
                    </div>
                </div>
                <span class="text-sm font-semibold text-gray-900">৳0</span>
            </div>
            <div class="text-center py-4">
                <i class="fas fa-chart-pie text-gray-400 text-2xl mb-2"></i>
                <p class="text-gray-500 text-sm">No data available</p>
            </div>
        </div>
    </div>

    <!-- Top Categories -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Top Categories</h3>
            <button class="text-orange-600 hover:text-orange-800 text-sm font-medium">
                View All
            </button>
        </div>
        <div class="space-y-3">
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-tags text-gray-400"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Category Name</p>
                        <p class="text-xs text-gray-500">0 orders</p>
                    </div>
                </div>
                <span class="text-sm font-semibold text-gray-900">৳0</span>
            </div>
            <div class="text-center py-4">
                <i class="fas fa-chart-pie text-gray-400 text-2xl mb-2"></i>
                <p class="text-gray-500 text-sm">No data available</p>
            </div>
        </div>
    </div>

    <!-- Customer Analytics -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Customer Analytics</h3>
            <button class="text-orange-600 hover:text-orange-800 text-sm font-medium">
                View All
            </button>
        </div>
        <div class="space-y-4">
            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                <span class="text-sm text-gray-600">New Customers</span>
                <span class="text-sm font-semibold text-gray-900">0</span>
            </div>
            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                <span class="text-sm text-gray-600">Returning Customers</span>
                <span class="text-sm font-semibold text-gray-900">0</span>
            </div>
            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                <span class="text-sm text-gray-600">Customer Retention</span>
                <span class="text-sm font-semibold text-gray-900">0%</span>
            </div>
        </div>
    </div>
</div>

<!-- Export Options -->
<div class="bg-white rounded-xl shadow-lg p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Export Reports</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <button onclick="exportReport('sales')" class="flex items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-orange-500 hover:bg-orange-50 transition-colors duration-200">
            <div class="text-center">
                <i class="fas fa-dollar-sign text-2xl text-gray-400 mb-2"></i>
                <p class="text-sm font-medium text-gray-900">Sales Report</p>
                <p class="text-xs text-gray-500">Revenue and sales data</p>
            </div>
        </button>
        
        <button onclick="exportReport('orders')" class="flex items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-orange-500 hover:bg-orange-50 transition-colors duration-200">
            <div class="text-center">
                <i class="fas fa-shopping-cart text-2xl text-gray-400 mb-2"></i>
                <p class="text-sm font-medium text-gray-900">Orders Report</p>
                <p class="text-xs text-gray-500">Order details and status</p>
            </div>
        </button>
        
        <button onclick="exportReport('customers')" class="flex items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-orange-500 hover:bg-orange-50 transition-colors duration-200">
            <div class="text-center">
                <i class="fas fa-users text-2xl text-gray-400 mb-2"></i>
                <p class="text-sm font-medium text-gray-900">Customers Report</p>
                <p class="text-xs text-gray-500">Customer analytics and data</p>
            </div>
        </button>
    </div>
</div>

<script>
// Export report function
function exportReport(type) {
    const dateFrom = document.getElementById('date_from').value;
    const dateTo = document.getElementById('date_to').value;
    
    const url = `/admin/reports/export/${type}?date_from=${dateFrom}&date_to=${dateTo}`;
    window.open(url, '_blank');
}

// Generate report function
function generateReport() {
    const dateFrom = document.getElementById('date_from').value;
    const dateTo = document.getElementById('date_to').value;
    
    if (!dateFrom || !dateTo) {
        alert('Please select date range');
        return;
    }
    
    // Show loading state
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Generating...';
    button.disabled = true;
    
    // Simulate report generation
    setTimeout(() => {
        button.innerHTML = originalText;
        button.disabled = false;
        alert('Report generated successfully!');
    }, 2000);
}

// Clear filters function
function clearFilters() {
    document.getElementById('date_from').value = '';
    document.getElementById('date_to').value = '';
    document.getElementById('period').value = '';
    
    // Reload page to show all data
    window.location.href = window.location.pathname;
}

// Period quick select
document.getElementById('period').addEventListener('change', function() {
    const today = new Date();
    const dateTo = document.getElementById('date_to');
    const dateFrom = document.getElementById('date_from');
    
    switch(this.value) {
        case 'today':
            dateFrom.value = today.toISOString().split('T')[0];
            dateTo.value = today.toISOString().split('T')[0];
            break;
        case 'yesterday':
            const yesterday = new Date(today);
            yesterday.setDate(yesterday.getDate() - 1);
            dateFrom.value = yesterday.toISOString().split('T')[0];
            dateTo.value = yesterday.toISOString().split('T')[0];
            break;
        case 'last_7_days':
            const last7Days = new Date(today);
            last7Days.setDate(last7Days.getDate() - 7);
            dateFrom.value = last7Days.toISOString().split('T')[0];
            dateTo.value = today.toISOString().split('T')[0];
            break;
        case 'last_30_days':
            const last30Days = new Date(today);
            last30Days.setDate(last30Days.getDate() - 30);
            dateFrom.value = last30Days.toISOString().split('T')[0];
            dateTo.value = today.toISOString().split('T')[0];
            break;
        case 'last_90_days':
            const last90Days = new Date(today);
            last90Days.setDate(last90Days.getDate() - 90);
            dateFrom.value = last90Days.toISOString().split('T')[0];
            dateTo.value = today.toISOString().split('T')[0];
            break;
        case 'this_month':
            const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
            dateFrom.value = firstDay.toISOString().split('T')[0];
            dateTo.value = today.toISOString().split('T')[0];
            break;
        case 'last_month':
            const lastMonth = new Date(today.getFullYear(), today.getMonth() - 1, 1);
            const lastDayOfLastMonth = new Date(today.getFullYear(), today.getMonth(), 0);
            dateFrom.value = lastMonth.toISOString().split('T')[0];
            dateTo.value = lastDayOfLastMonth.toISOString().split('T')[0];
            break;
        case 'this_year':
            const firstDayOfYear = new Date(today.getFullYear(), 0, 1);
            dateFrom.value = firstDayOfYear.toISOString().split('T')[0];
            dateTo.value = today.toISOString().split('T')[0];
            break;
    }
});

// Form submission
document.getElementById('dateFilterForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const dateFrom = document.getElementById('date_from').value;
    const dateTo = document.getElementById('date_to').value;
    
    if (!dateFrom || !dateTo) {
        alert('Please select both start and end dates');
        return;
    }
    
    if (new Date(dateFrom) > new Date(dateTo)) {
        alert('Start date cannot be after end date');
        return;
    }
    
    // Reload page with new date range
    const url = new URL(window.location);
    url.searchParams.set('date_from', dateFrom);
    url.searchParams.set('date_to', dateTo);
    window.location.href = url.toString();
});
</script>
@endsection