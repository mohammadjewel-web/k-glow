@extends('layouts.admin')

@section('title', 'Category Details')
@section('page-title', 'Category Details')

@section('content')
<!-- Header Section -->
<div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl shadow-lg mb-8 p-6 text-white">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold mb-2">{{ $category->name }}</h1>
            <p class="text-orange-100">Category details and information</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.categories.index') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg font-medium transition-all duration-300 hover:scale-105">
                <i class="fas fa-arrow-left mr-2"></i> Back to Categories
            </a>
            <a href="{{ route('admin.categories.edit', $category->id) }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg font-medium transition-all duration-300 hover:scale-105">
                <i class="fas fa-edit mr-2"></i> Edit Category
            </a>
        </div>
    </div>
</div>

<!-- Category Information -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    <!-- Main Information -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Category Information</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category Name</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $category->name }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Slug</label>
                        <p class="text-gray-900">{{ $category->slug }}</p>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <p class="text-gray-900">{{ $category->description ?: 'No description provided' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $category->status ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $category->status ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Featured</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $category->featured ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $category->featured ? 'Featured' : 'Regular' }}
                        </span>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Navigation</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $category->nav ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $category->nav ? 'In Navigation' : 'Hidden' }}
                        </span>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Created</label>
                        <p class="text-gray-900">{{ $category->created_at->format('M d, Y \a\t h:i A') }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Last Updated</label>
                        <p class="text-gray-900">{{ $category->updated_at->format('M d, Y \a\t h:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Category Image -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Category Image</h3>
            </div>
            <div class="p-6 text-center">
                @if($category->image)
                    <img src="{{ asset('admin-assets/categories/' . $category->image) }}" 
                         class="w-32 h-32 object-cover rounded-lg mx-auto border border-gray-300" alt="Category Image">
                @else
                    <div class="w-32 h-32 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center mx-auto">
                        <i class="fas fa-image text-gray-400 text-3xl"></i>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Statistics -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Statistics</h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-600">Subcategories</span>
                    <span class="text-lg font-bold text-blue-600">{{ $category->subcategories->count() }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-600">Products</span>
                    <span class="text-lg font-bold text-green-600">{{ $category->products->count() }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Subcategories Section -->
@if($category->subcategories->count() > 0)
<div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-8">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Subcategories</h3>
            <a href="{{ route('admin.subcategories.index', ['category' => $category->id]) }}" class="text-orange-600 hover:text-orange-800 text-sm font-medium">
                View All
            </a>
        </div>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($category->subcategories->take(6) as $subcategory)
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="font-medium text-gray-900">{{ $subcategory->name }}</h4>
                    <span class="text-xs text-gray-500">{{ $subcategory->products->count() }} products</span>
                </div>
                <p class="text-sm text-gray-600 mb-3">{{ Str::limit($subcategory->description, 50) }}</p>
                <div class="flex items-center justify-between">
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $subcategory->status ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $subcategory->status ? 'Active' : 'Inactive' }}
                    </span>
                    <a href="{{ route('admin.subcategories.show', $subcategory->id) }}" class="text-orange-600 hover:text-orange-800 text-sm">
                        View
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Products Section -->
@if($category->products->count() > 0)
<div class="bg-white rounded-xl shadow-lg border border-gray-200">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Recent Products</h3>
            <a href="{{ route('admin.products.index', ['category' => $category->id]) }}" class="text-orange-600 hover:text-orange-800 text-sm font-medium">
                View All Products
            </a>
        </div>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($category->products->take(6) as $product)
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                <div class="flex items-center mb-3">
                    @if($product->thumbnail)
                        <img src="{{ asset('admin-assets/products/' . $product->thumbnail) }}" 
                             class="w-12 h-12 object-cover rounded-lg mr-3" alt="Product">
                    @else
                        <div class="w-12 h-12 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-image text-gray-400"></i>
                        </div>
                    @endif
                    <div class="flex-1">
                        <h4 class="font-medium text-gray-900">{{ $product->name }}</h4>
                        <p class="text-sm text-gray-600">৳{{ number_format($product->price, 2) }}</p>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $product->status ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $product->status ? 'Active' : 'Inactive' }}
                    </span>
                    <a href="{{ route('admin.products.show', $product->id) }}" class="text-orange-600 hover:text-orange-800 text-sm">
                        View
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif
@endsection

