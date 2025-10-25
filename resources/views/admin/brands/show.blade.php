@extends('layouts.admin')

@section('title', 'Brand Details')
@section('page-title', 'Brand Details')

@section('content')
<!-- Header Section -->
<div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl shadow-lg mb-8 p-6 text-white">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold mb-2">Brand Details</h1>
            <p class="text-orange-100">Comprehensive view of {{ $brand->name }} brand information</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.brands.index') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg font-medium transition-all duration-300 hover:scale-105">
                <i class="fas fa-list mr-2"></i> Back to Brands
            </a>
            <a href="{{ route('admin.brands.edit', $brand->id) }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg font-medium transition-all duration-300 hover:scale-105">
                <i class="fas fa-edit mr-2"></i> Edit Brand
            </a>
        </div>
    </div>
</div>

<!-- Brand Information -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Main Information -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-info-circle text-orange-600 mr-2"></i>
                Brand Information
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Brand Name</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $brand->name }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Slug</label>
                    <p class="text-lg text-gray-900 font-mono">{{ $brand->slug }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Website</label>
                    @if($brand->website)
                        <a href="{{ $brand->website }}" target="_blank" class="text-blue-600 hover:text-blue-800 transition-colors duration-200">
                            <i class="fas fa-external-link-alt mr-1"></i>
                            {{ $brand->website }}
                        </a>
                    @else
                        <span class="text-gray-400">No website</span>
                    @endif
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Created</label>
                    <p class="text-lg text-gray-900">{{ $brand->created_at->format('M d, Y \a\t g:i A') }}</p>
                </div>
            </div>
            
            @if($brand->description)
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-600 mb-2">Description</label>
                <p class="text-gray-900 leading-relaxed">{{ $brand->description }}</p>
            </div>
            @endif
        </div>

        <!-- SEO Information -->
        @if($brand->meta_title || $brand->meta_description)
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-search text-orange-600 mr-2"></i>
                SEO Information
            </h3>
            
            @if($brand->meta_title)
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-600 mb-1">Meta Title</label>
                <p class="text-gray-900">{{ $brand->meta_title }}</p>
            </div>
            @endif
            
            @if($brand->meta_description)
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Meta Description</label>
                <p class="text-gray-900">{{ $brand->meta_description }}</p>
            </div>
            @endif
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Brand Logo -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-image text-orange-600 mr-2"></i>
                Brand Logo
            </h3>
            
            @if($brand->logo)
                <div class="text-center">
                    <img src="{{ asset('admin-assets/brands/' . $brand->logo) }}" 
                         alt="{{ $brand->name }} Logo" 
                         class="w-32 h-32 object-cover rounded-lg mx-auto shadow-md"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="w-32 h-32 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center mx-auto shadow-md" style="display: none;">
                        <i class="fas fa-image text-gray-400 text-2xl"></i>
                    </div>
                </div>
            @else
                <div class="text-center">
                    <div class="w-32 h-32 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center mx-auto shadow-md">
                        <i class="fas fa-image text-gray-400 text-2xl"></i>
                    </div>
                    <p class="text-gray-500 mt-2">No logo uploaded</p>
                </div>
            @endif
        </div>

        <!-- Brand Status -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-toggle-on text-orange-600 mr-2"></i>
                Brand Status
            </h3>
            
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-600">Status</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $brand->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        <i class="fas fa-{{ $brand->is_active ? 'check' : 'times' }} mr-1"></i>
                        {{ $brand->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-600">Featured</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $brand->featured ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800' }}">
                        <i class="fas fa-{{ $brand->featured ? 'star' : 'star-o' }} mr-1"></i>
                        {{ $brand->featured ? 'Featured' : 'Regular' }}
                    </span>
                </div>
                
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-600">Navigation</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $brand->nav ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' }}">
                        <i class="fas fa-{{ $brand->nav ? 'eye' : 'eye-slash' }} mr-1"></i>
                        {{ $brand->nav ? 'In Nav' : 'Hidden' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Products Count -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-box text-orange-600 mr-2"></i>
                Products
            </h3>
            
            <div class="text-center">
                <div class="text-3xl font-bold text-orange-600 mb-2">{{ $brand->products_count ?? 0 }}</div>
                <p class="text-gray-600">Total Products</p>
            </div>
        </div>
    </div>
</div>
@endsection
