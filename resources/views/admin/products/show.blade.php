@extends('layouts.admin')

@section('title', 'View Product')
@section('page-title', 'View Product')

@section('content')
<!-- Header Section -->
<div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl shadow-lg mb-8 p-6 text-white">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold mb-2">Product Details</h1>
            <p class="text-orange-100">View complete product information</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.products.edit', $product->id) }}" 
               class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg font-medium transition-all duration-300 hover:scale-105">
                <i class="fas fa-edit mr-2"></i> Edit Product
            </a>
            <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                <i class="fas fa-eye text-white text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Product Overview -->
<div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-8">
    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 rounded-t-xl">
        <div class="flex items-center">
            <div class="bg-orange-100 p-2 rounded-lg mr-3">
                <i class="fas fa-info-circle text-orange-600"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-0">Product Overview</h3>
                <p class="text-sm text-gray-600 mb-0">Basic product information and status</p>
            </div>
        </div>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Product Image -->
            <div class="lg:col-span-1">
                <div class="bg-gray-50 rounded-lg p-4">
                    @if($product->thumbnail)
                        <img src="{{ asset('admin-assets/products/'.$product->thumbnail) }}" 
                             class="w-full h-64 object-cover rounded-lg shadow-md" alt="Product Thumbnail"
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

            <!-- Product Info -->
            <div class="lg:col-span-2">
                <div class="space-y-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $product->name }}</h2>
                        <p class="text-gray-600">{{ $product->short_description }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="bg-blue-100 p-2 rounded-lg mr-3">
                                    <i class="fas fa-tag text-blue-600"></i>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500">Category</div>
                                    <div class="font-semibold text-gray-900">{{ $product->category->name ?? 'N/A' }}</div>
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
                                    <div class="font-semibold text-gray-900">{{ $product->brand->name ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4">
                        <div class="bg-orange-50 px-4 py-2 rounded-lg">
                            <div class="text-sm text-gray-500">Price</div>
                            <div class="text-xl font-bold text-orange-600">৳{{ number_format($product->price, 2) }}</div>
                        </div>
                        @if($product->discount_price)
                        <div class="bg-red-50 px-4 py-2 rounded-lg">
                            <div class="text-sm text-gray-500">Discount Price</div>
                            <div class="text-xl font-bold text-red-600">৳{{ number_format($product->discount_price, 2) }}</div>
                        </div>
                        @endif
                        <div class="bg-gray-50 px-4 py-2 rounded-lg">
                            <div class="text-sm text-gray-500">Stock</div>
                            <div class="text-xl font-bold text-gray-900">{{ $product->stock ?? 0 }}</div>
                        </div>
                    </div>

                    <!-- Status Badges -->
                    <div class="flex flex-wrap gap-2">
                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full {{ $product->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $product->status ? 'Active' : 'Inactive' }}
                        </span>
                        @if($product->is_featured)
                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Featured</span>
                        @endif
                        @if($product->is_new)
                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">New</span>
                        @endif
                        @if($product->is_best_seller)
                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">Best Seller</span>
                        @endif
                        @if($product->is_flash_sale)
                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Flash Sale</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Product Details -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- Basic Information -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200">
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 rounded-t-xl">
            <div class="flex items-center">
                <div class="bg-blue-100 p-2 rounded-lg mr-3">
                    <i class="fas fa-info text-blue-600"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-0">Basic Information</h3>
                    <p class="text-sm text-gray-600 mb-0">Product details and specifications</p>
                </div>
            </div>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-600">SKU</span>
                    <span class="font-medium text-gray-900">{{ $product->sku ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-600">Barcode</span>
                    <span class="font-medium text-gray-900">{{ $product->barcode ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-600">Weight</span>
                    <span class="font-medium text-gray-900">{{ $product->weight ?? 'N/A' }} kg</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-600">Dimensions</span>
                    <span class="font-medium text-gray-900">{{ $product->dimensions ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-600">Material</span>
                    <span class="font-medium text-gray-900">{{ $product->material ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-600">Colors</span>
                    <span class="font-medium text-gray-900">{{ $product->colors ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-600">Sizes</span>
                    <span class="font-medium text-gray-900">{{ $product->sizes ? implode(', ', $product->sizes) : 'N/A' }}</span>
                </div>
                <div class="flex justify-between py-2">
                    <span class="text-gray-600">Tags</span>
                    <span class="font-medium text-gray-900">{{ $product->tags ?? 'N/A' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- SEO Information -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200">
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 rounded-t-xl">
            <div class="flex items-center">
                <div class="bg-green-100 p-2 rounded-lg mr-3">
                    <i class="fas fa-search text-green-600"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-0">SEO Information</h3>
                    <p class="text-sm text-gray-600 mb-0">Search engine optimization details</p>
                </div>
            </div>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div>
                    <div class="text-sm text-gray-500 mb-1">Meta Title</div>
                    <div class="text-gray-900">{{ $product->meta_title ?? 'Not set' }}</div>
                </div>
                <div>
                    <div class="text-sm text-gray-500 mb-1">Meta Description</div>
                    <div class="text-gray-900">{{ $product->meta_description ?? 'Not set' }}</div>
                </div>
                <div>
                    <div class="text-sm text-gray-500 mb-1">Slug</div>
                    <div class="text-gray-900 font-mono text-sm">{{ $product->slug }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Product Description -->
<div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-8">
    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 rounded-t-xl">
        <div class="flex items-center">
            <div class="bg-purple-100 p-2 rounded-lg mr-3">
                <i class="fas fa-file-text text-purple-600"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-0">Product Description</h3>
                <p class="text-sm text-gray-600 mb-0">Detailed product information</p>
            </div>
        </div>
    </div>
    <div class="p-6">
        <div class="prose max-w-none">
            <div class="text-gray-700 leading-relaxed">
                {{ $product->description ?: 'No description available.' }}
            </div>
        </div>
    </div>
</div>

<!-- Product Images Gallery -->
@if($productImages && $productImages->count() > 0)
<div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-8">
    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 rounded-t-xl">
        <div class="flex items-center">
            <div class="bg-pink-100 p-2 rounded-lg mr-3">
                <i class="fas fa-images text-pink-600"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-0">Product Gallery</h3>
                <p class="text-sm text-gray-600 mb-0">Product images and media</p>
            </div>
        </div>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach($productImages as $img)
            @if($img->image && file_exists(public_path('admin-assets/products/'.$img->image)))
            <div class="relative group">
                <img src="{{ asset('admin-assets/products/'.$img->image) }}" 
                     class="w-full h-24 object-cover rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300" 
                     alt="Product Image">
                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 rounded-lg transition-all duration-300 flex items-center justify-center">
                    <i class="fas fa-search-plus text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300"></i>
                </div>
            </div>
            @endif
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Flash Sale Information -->
@if($product->is_flash_sale)
<div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-8">
    <div class="bg-gradient-to-r from-red-50 to-red-100 px-6 py-4 rounded-t-xl">
        <div class="flex items-center">
            <div class="bg-red-100 p-2 rounded-lg mr-3">
                <i class="fas fa-bolt text-red-600"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-0">Flash Sale Information</h3>
                <p class="text-sm text-gray-600 mb-0">Limited time offer details</p>
            </div>
        </div>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-red-50 p-4 rounded-lg">
                <div class="text-sm text-gray-500 mb-1">Flash Sale Price</div>
                <div class="text-2xl font-bold text-red-600">৳{{ number_format($product->flash_sale_price, 2) }}</div>
            </div>
            <div class="bg-orange-50 p-4 rounded-lg">
                <div class="text-sm text-gray-500 mb-1">Start Date</div>
                <div class="text-lg font-semibold text-orange-600">{{ $product->flash_sale_start ? $product->flash_sale_start->format('M d, Y H:i') : 'N/A' }}</div>
            </div>
            <div class="bg-yellow-50 p-4 rounded-lg">
                <div class="text-sm text-gray-500 mb-1">End Date</div>
                <div class="text-lg font-semibold text-yellow-600">{{ $product->flash_sale_end ? $product->flash_sale_end->format('M d, Y H:i') : 'N/A' }}</div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Action Buttons -->
<div class="flex justify-between items-center pt-6 border-t border-gray-200">
    <a href="{{ route('admin.products.index') }}" 
       class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white rounded-lg font-medium transition-all duration-300 hover:scale-105">
        <i class="fas fa-arrow-left mr-2"></i>Back to Products
    </a>
    <div class="flex space-x-3">
        <a href="{{ route('admin.products.edit', $product->id) }}" 
           class="px-6 py-3 bg-orange-600 hover:bg-orange-700 text-white rounded-lg font-medium transition-all duration-300 hover:scale-105">
            <i class="fas fa-edit mr-2"></i>Edit Product
        </a>
    </div>
</div>
@endsection