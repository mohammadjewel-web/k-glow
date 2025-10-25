@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<h1 class="text-3xl font-bold mb-6 text-gray-800">Dashboard</h1>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <x-dashboard-card title="Total Orders" value="120" icon="📦" color="text-[--brand-orange]" />
    <x-dashboard-card title="Products" value="80" icon="🛍️" color="text-[--brand-orange]" />
    <x-dashboard-card title="Users" value="45" icon="👥" color="text-[--brand-orange]" />
    <x-dashboard-card title="Revenue" value="৳12,450" icon="💰" color="text-[--brand-orange]" />
</div>

<!-- Sales Chart & Recent Orders -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white p-6 rounded-xl shadow">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">Monthly Sales</h2>
        <canvas id="salesChart" class="w-full h-64"></canvas>
    </div>

    <div class="bg-white p-6 rounded-xl shadow">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">Recent Orders</h2>
        <table class="min-w-full table-auto">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Order ID</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Customer</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Amount</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Status</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b hover:bg-gray-50 transition">
                    <td class="px-4 py-2">#001</td>
                    <td class="px-4 py-2">John Doe</td>
                    <td class="px-4 py-2">৳120</td>
                    <td class="px-4 py-2 text-green-600 font-semibold">Completed</td>
                </tr>
                <tr class="border-b hover:bg-gray-50 transition">
                    <td class="px-4 py-2">#002</td>
                    <td class="px-4 py-2">Jane Smith</td>
                    <td class="px-4 py-2">৳85</td>
                    <td class="px-4 py-2 text-yellow-500 font-semibold">Pending</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('salesChart').getContext('2d');
const salesChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug'],
        datasets: [{
            label: 'Revenue ($)',
            data: [1200, 1900, 3000, 5000, 4200, 6100, 7000, 8000],
            backgroundColor: 'rgba(243,108,33,0.2)',
            borderColor: '#f36c21',
            borderWidth: 2,
            tension: 0.4,
            fill: true,
            pointBackgroundColor: '#f36c21'
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
@endsection