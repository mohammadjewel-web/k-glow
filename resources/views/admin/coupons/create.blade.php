@extends('layouts.admin')

@section('title', 'Create New Coupon')
@section('page-title', 'Add New Coupon')

@section('content')
<div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl shadow-lg mb-8 p-6 text-white">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold mb-2">Create New Coupon</h1>
            <p class="text-orange-100">Add a new discount coupon to your store</p>
        </div>
        <a href="{{ route('admin.coupons.index') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg font-medium transition-all duration-300 hover:scale-105">
            <i class="fas fa-list mr-2"></i> Back to Coupons
        </a>
    </div>
</div>

<!-- Coupon Creation Form -->
<div class="bg-white rounded-xl shadow-lg p-8">
    <form method="POST" action="{{ route('admin.coupons.store') }}" enctype="multipart/form-data" id="couponForm">
        @csrf
        
        <!-- Basic Information -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-info-circle mr-2 text-orange-600"></i>
                Basic Information
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-tag mr-1 text-orange-600"></i>Coupon Code *
                    </label>
                    <input type="text" name="code" id="code" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300"
                           placeholder="e.g., SAVE20" value="{{ old('code') }}">
                    @error('code')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-ticket-alt mr-1 text-orange-600"></i>Coupon Name *
                    </label>
                    <input type="text" name="name" id="name" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300"
                           placeholder="e.g., 20% Off Sale" value="{{ old('name') }}">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-align-left mr-1 text-orange-600"></i>Description
                </label>
                <textarea name="description" id="description" rows="3"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300"
                          placeholder="Describe this coupon...">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Discount Configuration -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-percentage mr-2 text-orange-600"></i>
                Discount Configuration
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-tag mr-1 text-orange-600"></i>Discount Type *
                    </label>
                    <select name="type" id="type" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300">
                        <option value="">Select Type</option>
                        <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>Percentage</option>
                        <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                    </select>
                    @error('type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-dollar-sign mr-1 text-orange-600"></i>Discount Value *
                    </label>
                    <div class="relative">
                        <input type="number" name="value" id="value" required step="0.01" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300"
                               placeholder="0.00" value="{{ old('value') }}">
                        <span class="absolute right-3 top-3 text-gray-500" id="valueUnit">৳</span>
                    </div>
                    @error('value')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-shopping-cart mr-1 text-orange-600"></i>Minimum Amount
                    </label>
                    <input type="number" name="minimum_amount" id="minimum_amount" step="0.01" min="0"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300"
                           placeholder="0.00" value="{{ old('minimum_amount') }}">
                    @error('minimum_amount')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-chart-line mr-1 text-orange-600"></i>Maximum Discount (for percentage coupons)
                </label>
                <input type="number" name="maximum_discount" id="maximum_discount" step="0.01" min="0"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300"
                       placeholder="0.00" value="{{ old('maximum_discount') }}">
                @error('maximum_discount')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Usage Limits -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-users mr-2 text-orange-600"></i>
                Usage Limits
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-chart-bar mr-1 text-orange-600"></i>Total Usage Limit
                    </label>
                    <input type="number" name="usage_limit" id="usage_limit" min="1"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300"
                           placeholder="Leave empty for unlimited" value="{{ old('usage_limit') }}">
                    @error('usage_limit')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user mr-1 text-orange-600"></i>Usage Limit Per User
                    </label>
                    <input type="number" name="usage_limit_per_user" id="usage_limit_per_user" min="1"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300"
                           placeholder="Leave empty for unlimited" value="{{ old('usage_limit_per_user') }}">
                    @error('usage_limit_per_user')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Validity Period -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-calendar mr-2 text-orange-600"></i>
                Validity Period
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-play mr-1 text-orange-600"></i>Start Date
                    </label>
                    <input type="datetime-local" name="starts_at" id="starts_at"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300"
                           value="{{ old('starts_at') }}">
                    @error('starts_at')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-stop mr-1 text-orange-600"></i>Expiry Date
                    </label>
                    <input type="datetime-local" name="expires_at" id="expires_at"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300"
                           value="{{ old('expires_at') }}">
                    @error('expires_at')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Status & Visibility -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-toggle-on mr-2 text-orange-600"></i>
                Status & Visibility
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" 
                           class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded" 
                           {{ old('is_active', true) ? 'checked' : '' }}>
                    <label for="is_active" class="ml-2 block text-sm text-gray-900">
                        <i class="fas fa-check-circle mr-1"></i>Active Coupon
                    </label>
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" name="is_public" id="is_public" value="1" 
                           class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded" 
                           {{ old('is_public', true) ? 'checked' : '' }}>
                    <label for="is_public" class="ml-2 block text-sm text-gray-900">
                        <i class="fas fa-globe mr-1"></i>Public Coupon
                    </label>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
            <a href="{{ route('admin.coupons.index') }}" 
               class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                <i class="fas fa-times mr-2"></i>Cancel
            </a>
            <button type="submit" 
                    class="px-6 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors duration-200">
                <i class="fas fa-save mr-2"></i>Create Coupon
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const valueUnit = document.getElementById('valueUnit');
    const valueInput = document.getElementById('value');
    
    // Update value unit based on discount type
    typeSelect.addEventListener('change', function() {
        if (this.value === 'percentage') {
            valueUnit.textContent = '%';
            valueInput.placeholder = '0';
        } else if (this.value === 'fixed') {
            valueUnit.textContent = '৳';
            valueInput.placeholder = '0.00';
        }
    });
    
    // Set initial value unit
    if (typeSelect.value === 'percentage') {
        valueUnit.textContent = '%';
        valueInput.placeholder = '0';
    } else if (typeSelect.value === 'fixed') {
        valueUnit.textContent = '৳';
        valueInput.placeholder = '0.00';
    }
    
    // Form validation
    const form = document.getElementById('couponForm');
    form.addEventListener('submit', function(e) {
        const code = document.getElementById('code').value.trim();
        const name = document.getElementById('name').value.trim();
        const type = document.getElementById('type').value;
        const value = document.getElementById('value').value;
        
        if (!code || !name || !type || !value) {
            e.preventDefault();
            alert('Please fill in all required fields.');
            return false;
        }
        
        if (type === 'percentage' && (parseFloat(value) < 0 || parseFloat(value) > 100)) {
            e.preventDefault();
            alert('Percentage value must be between 0 and 100.');
            return false;
        }
        
        if (type === 'fixed' && parseFloat(value) < 0) {
            e.preventDefault();
            alert('Fixed amount must be greater than 0.');
            return false;
        }
    });
});
</script>
@endsection
