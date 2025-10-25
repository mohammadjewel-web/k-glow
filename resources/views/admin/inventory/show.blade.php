@extends('layouts.admin')
@section('title', 'Inventory Details')
@section('page-title', 'Inventory Details')

@section('content')
<!-- Header -->
<div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl shadow-lg mb-8 p-6 text-white">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold mb-2">Inventory Details</h1>
            <p class="text-orange-100">View inventory information and stock movements</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.inventory.index') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg font-medium transition-all duration-300 hover:scale-105">
                <i class="fas fa-arrow-left mr-2"></i> Back to Inventory
            </a>
            <button onclick="updateStock({{ $inventory->id }})" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg font-medium transition-all duration-300 hover:scale-105">
                <i class="fas fa-edit mr-2"></i> Update Stock
            </button>
        </div>
    </div>
</div>

<!-- Product Information -->
<div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-8">
    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
        <div class="flex items-center">
            <div class="bg-orange-100 p-2 rounded-lg mr-3">
                <i class="fas fa-box text-orange-600"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-0">Product Information</h3>
                <p class="text-sm text-gray-600 mb-0">Basic product and inventory details</p>
            </div>
        </div>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Product Image -->
            <div class="lg:col-span-1">
                <div class="bg-gray-50 rounded-lg p-4">
                    @if($inventory->product->thumbnail)
                        <img src="{{ asset('admin-assets/products/' . $inventory->product->thumbnail) }}" 
                             class="w-full h-64 object-cover rounded-lg shadow-md" alt="Product Thumbnail"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="w-full h-64 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center" style="display: none;">
                            <i class="fas fa-image text-gray-400 text-4xl"></i>
                        </div>
                    @elseif($inventory->product->images && $inventory->product->images->count() > 0)
                        <img src="{{ asset('admin-assets/products/' . $inventory->product->images->first()->image) }}" 
                             class="w-full h-64 object-cover rounded-lg shadow-md" alt="Product Image"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="w-full h-64 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center" style="display: none;">
                            <i class="fas fa-image text-gray-400 text-4xl"></i>
                        </div>
                    @else
                        <div class="w-full h-64 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center">
                            <i class="fas fa-image text-gray-400 text-4xl"></i>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Product Details -->
            <div class="lg:col-span-2">
                <div class="space-y-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $inventory->product->name }}</h2>
                        <p class="text-gray-600">{{ $inventory->product->short_description }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="bg-blue-100 p-2 rounded-lg mr-3">
                                    <i class="fas fa-tag text-blue-600"></i>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500">Category</div>
                                    <div class="font-semibold text-gray-900">{{ $inventory->product->category->name ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="bg-green-100 p-2 rounded-lg mr-3">
                                    <i class="fas fa-award text-green-600"></i>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500">Brand</div>
                                    <div class="font-semibold text-gray-900">{{ $inventory->product->brand->name ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4">
                        <div class="bg-orange-50 px-4 py-2 rounded-lg">
                            <div class="text-sm text-gray-500">SKU</div>
                            <div class="text-lg font-bold text-orange-600">{{ $inventory->sku }}</div>
                        </div>
                        <div class="bg-gray-50 px-4 py-2 rounded-lg">
                            <div class="text-sm text-gray-500">Barcode</div>
                            <div class="text-lg font-bold text-gray-900">{{ $inventory->product->barcode ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stock Information -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- Current Stock Status -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200">
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 rounded-t-xl">
            <div class="flex items-center">
                <div class="bg-green-100 p-2 rounded-lg mr-3">
                    <i class="fas fa-warehouse text-green-600"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-0">Current Stock</h3>
                    <p class="text-sm text-gray-600 mb-0">Real-time inventory levels</p>
                </div>
            </div>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div class="flex justify-between py-3 border-b border-gray-100">
                    <span class="text-gray-600">Current Stock</span>
                    <span class="text-2xl font-bold text-gray-900">{{ $inventory->current_stock }}</span>
                </div>
                <div class="flex justify-between py-3 border-b border-gray-100">
                    <span class="text-gray-600">Reserved Stock</span>
                    <span class="text-lg font-semibold text-yellow-600">{{ $inventory->reserved_stock }}</span>
                </div>
                <div class="flex justify-between py-3 border-b border-gray-100">
                    <span class="text-gray-600">Available Stock</span>
                    <span class="text-lg font-semibold text-green-600">{{ $inventory->current_stock - $inventory->reserved_stock }}</span>
                </div>
                <div class="flex justify-between py-3 border-b border-gray-100">
                    <span class="text-gray-600">Minimum Stock</span>
                    <span class="text-lg font-semibold text-red-600">{{ $inventory->minimum_stock }}</span>
                </div>
                <div class="flex justify-between py-3">
                    <span class="text-gray-600">Stock Status</span>
                    @php
                        $statusConfig = match($inventory->stock_status) {
                            'out_of_stock' => ['class' => 'bg-red-100 text-red-800', 'icon' => 'fas fa-times-circle'],
                            'low_stock' => ['class' => 'bg-yellow-100 text-yellow-800', 'icon' => 'fas fa-exclamation-triangle'],
                            'in_stock' => ['class' => 'bg-green-100 text-green-800', 'icon' => 'fas fa-check-circle'],
                            default => ['class' => 'bg-gray-100 text-gray-800', 'icon' => 'fas fa-question-circle']
                        };
                    @endphp
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $statusConfig['class'] }}">
                        <i class="{{ $statusConfig['icon'] }} mr-1"></i>
                        {{ ucfirst(str_replace('_', ' ', $inventory->stock_status)) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Pricing Information -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200">
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 rounded-t-xl">
            <div class="flex items-center">
                <div class="bg-blue-100 p-2 rounded-lg mr-3">
                    <i class="fas fa-dollar-sign text-blue-600"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-0">Pricing & Value</h3>
                    <p class="text-sm text-gray-600 mb-0">Cost and selling information</p>
                </div>
            </div>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div class="flex justify-between py-3 border-b border-gray-100">
                    <span class="text-gray-600">Cost Price</span>
                    <span class="text-lg font-semibold text-gray-900">৳{{ number_format($inventory->cost_price, 2) }}</span>
                </div>
                <div class="flex justify-between py-3 border-b border-gray-100">
                    <span class="text-gray-600">Selling Price</span>
                    <span class="text-lg font-semibold text-gray-900">৳{{ number_format($inventory->selling_price, 2) }}</span>
                </div>
                <div class="flex justify-between py-3 border-b border-gray-100">
                    <span class="text-gray-600">Stock Value (Cost)</span>
                    <span class="text-lg font-semibold text-blue-600">৳{{ number_format($inventory->stock_value, 2) }}</span>
                </div>
                <div class="flex justify-between py-3 border-b border-gray-100">
                    <span class="text-gray-600">Potential Value</span>
                    <span class="text-lg font-semibold text-green-600">৳{{ number_format(($inventory->current_stock - $inventory->reserved_stock) * $inventory->selling_price, 2) }}</span>
                </div>
                <div class="flex justify-between py-3">
                    <span class="text-gray-600">Profit Margin</span>
                    <span class="text-lg font-semibold text-orange-600">
                        {{ $inventory->cost_price > 0 ? number_format((($inventory->selling_price - $inventory->cost_price) / $inventory->cost_price) * 100, 1) : 0 }}%
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stock Movement History -->
<div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-8">
    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="bg-purple-100 p-2 rounded-lg mr-3">
                    <i class="fas fa-history text-purple-600"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-0">Stock Movement History</h3>
                    <p class="text-sm text-gray-600 mb-0">Recent stock movements and transactions</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm font-medium">
                    {{ $movementStats['total_movements'] ?? 0 }} Total Movements
                </span>
            </div>
        </div>
    </div>
    <div class="p-6">
        @if(isset($movementStats['recent_movements']) && $movementStats['recent_movements']->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($movementStats['recent_movements'] as $movement)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $movement->created_at->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $movement->created_at->format('H:i:s') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $typeConfig = match($movement->type) {
                                        'in' => ['class' => 'bg-green-100 text-green-800', 'icon' => 'fas fa-arrow-down', 'label' => 'Stock In'],
                                        'out' => ['class' => 'bg-red-100 text-red-800', 'icon' => 'fas fa-arrow-up', 'label' => 'Stock Out'],
                                        'adjustment' => ['class' => 'bg-blue-100 text-blue-800', 'icon' => 'fas fa-edit', 'label' => 'Adjustment'],
                                        default => ['class' => 'bg-gray-100 text-gray-800', 'icon' => 'fas fa-question', 'label' => 'Unknown']
                                    };
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $typeConfig['class'] }}">
                                    <i class="{{ $typeConfig['icon'] }} mr-1"></i>
                                    {{ $typeConfig['label'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold {{ $movement->quantity > 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $movement->quantity > 0 ? '+' : '' }}{{ $movement->quantity }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $movement->reason ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $movement->user->name ?? 'System' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ Str::limit($movement->notes, 50) }}</div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8">
                <div class="bg-gray-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-history text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Stock Movements</h3>
                <p class="text-gray-500">No stock movements have been recorded for this product yet.</p>
            </div>
        @endif
    </div>
</div>

<!-- Movement Statistics -->
@if(isset($movementStats))
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Total In</p>
                <p class="text-2xl font-bold text-green-600">{{ $movementStats['total_in'] ?? 0 }}</p>
            </div>
            <div class="bg-green-100 p-3 rounded-lg">
                <i class="fas fa-arrow-down text-green-600"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Total Out</p>
                <p class="text-2xl font-bold text-red-600">{{ $movementStats['total_out'] ?? 0 }}</p>
            </div>
            <div class="bg-red-100 p-3 rounded-lg">
                <i class="fas fa-arrow-up text-red-600"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Adjustments</p>
                <p class="text-2xl font-bold text-blue-600">{{ $movementStats['total_adjustments'] ?? 0 }}</p>
            </div>
            <div class="bg-blue-100 p-3 rounded-lg">
                <i class="fas fa-edit text-blue-600"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Net Movement</p>
                <p class="text-2xl font-bold text-gray-900">{{ ($movementStats['total_in'] ?? 0) + ($movementStats['total_out'] ?? 0) + ($movementStats['total_adjustments'] ?? 0) }}</p>
            </div>
            <div class="bg-gray-100 p-3 rounded-lg">
                <i class="fas fa-balance-scale text-gray-600"></i>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Update Stock Modal -->
<div id="updateStockModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4">
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4 rounded-t-xl">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="bg-white bg-opacity-20 p-2 rounded-lg mr-3">
                        <i class="fas fa-edit text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-white">Update Stock</h3>
                        <p class="text-orange-100 text-sm">Adjust inventory levels</p>
                    </div>
                </div>
                <button onclick="closeUpdateModal()" class="text-white hover:text-orange-200 transition-colors duration-300">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>

        <form id="updateStockForm" class="p-6">
            <input type="hidden" id="inventoryId" name="inventory_id" value="{{ $inventory->id }}">
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Movement Type</label>
                    <select id="movementType" name="type" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300">
                        <option value="in">📥 Stock In</option>
                        <option value="out">📤 Stock Out</option>
                        <option value="adjustment">⚖️ Adjustment</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                    <input type="number" id="quantity" name="quantity" required min="1" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reason</label>
                    <input type="text" id="reason" name="reason" placeholder="Optional reason for this movement" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea id="notes" name="notes" rows="3" placeholder="Additional notes (optional)" 
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300"></textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Unit Cost (Optional)</label>
                    <input type="number" id="unitCost" name="unit_cost" step="0.01" min="0" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300">
                </div>
            </div>

            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="closeUpdateModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-medium transition-all duration-300 hover:scale-105">
                    <i class="fas fa-times mr-2"></i> Cancel
                </button>
                <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-3 rounded-lg font-medium transition-all duration-300 hover:scale-105 shadow-lg">
                    <i class="fas fa-save mr-2"></i> Update Stock
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Update stock function
function updateStock(inventoryId) {
    document.getElementById('inventoryId').value = inventoryId;
    document.getElementById('updateStockModal').classList.remove('hidden');
    document.getElementById('updateStockModal').classList.add('flex');
}

// Close update modal
function closeUpdateModal() {
    document.getElementById('updateStockModal').classList.add('hidden');
    document.getElementById('updateStockModal').classList.remove('flex');
}

// Update stock form submission
document.getElementById('updateStockForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const inventoryId = formData.get('inventory_id');
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Updating...';
    submitBtn.disabled = true;
    
    fetch(`/admin/inventory/${inventoryId}/update-stock`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            quantity: parseInt(formData.get('quantity')),
            type: formData.get('type'),
            reason: formData.get('reason'),
            notes: formData.get('notes'),
            unit_cost: parseFloat(formData.get('unit_cost')) || null
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Stock updated successfully!', 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            showNotification('Error: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error updating stock', 'error');
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

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

