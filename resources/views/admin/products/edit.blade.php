@extends('layouts.admin')

@section('title', 'Edit Product')
@section('page-title', 'Edit Product')

@section('content')
<!-- Header Section -->
<div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl shadow-lg mb-8 p-6 text-white">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold mb-2">Edit Product</h1>
            <p class="text-orange-100">Update product information and settings</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.products.show', $product->id) }}" 
               class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg font-medium transition-all duration-300 hover:scale-105">
                <i class="fas fa-eye mr-2"></i> View Product
            </a>
            <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                <i class="fas fa-edit text-white text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Success Message -->
@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
    <i class="fas fa-check-circle mr-2"></i>
    {{ session('success') }}
</div>
@endif

<!-- Validation Errors -->
@if($errors->any())
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
    <div class="flex items-center mb-2">
        <i class="fas fa-exclamation-circle mr-2"></i>
        <strong>Please fix the following errors:</strong>
    </div>
    <ul class="list-disc pl-5">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<!-- Main Form -->
<div class="bg-white rounded-xl shadow-lg border border-gray-200">
    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="p-6">
        @csrf
        @method('PUT')

        <!-- Basic Information Section -->
        <div class="mb-8">
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 rounded-lg mb-6">
                <div class="flex items-center">
                    <div class="bg-orange-100 p-2 rounded-lg mr-3">
                        <i class="fas fa-info-circle text-orange-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-0">Basic Information</h3>
                        <p class="text-sm text-gray-600 mb-0">Update the basic details of your product</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-tag mr-1 text-orange-600"></i>Product Name *
                    </label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300"
                        placeholder="Enter product name">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-link mr-1 text-orange-600"></i>Slug
                    </label>
                    <input type="text" name="slug" value="{{ old('slug', $product->slug) }}" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300"
                        placeholder="Auto-generated if empty">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                <div x-data="categorySelect()">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-folder mr-1 text-orange-600"></i>Category *
                    </label>
                    <select name="category_id" x-model="categoryId" @change="fetchSubcategories()" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300">
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $cat->id == $product->category_id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div x-data="categorySelect()">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-folder-open mr-1 text-orange-600"></i>Subcategory
                    </label>
                    <select name="subcategory_id" x-model="subcategoryId"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300">
                        <option value="">Select Subcategory</option>
                        <template x-for="sub in subcategories" :key="sub.id">
                            <option :value="sub.id" x-text="sub.name"></option>
                        </template>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-award mr-1 text-orange-600"></i>Brand
                    </label>
                    <select name="brand_id"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300">
                        <option value="">Select Brand</option>
                        @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ $brand->id == $product->brand_id ? 'selected' : '' }}>{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-align-left mr-1 text-orange-600"></i>Short Description
                </label>
                <textarea name="short_description" rows="3"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300"
                    placeholder="Brief description of the product">{{ old('short_description', $product->short_description) }}</textarea>
            </div>

            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-file-text mr-1 text-orange-600"></i>Full Description
                </label>
                <textarea name="description" rows="4"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300"
                    placeholder="Detailed description of the product">{{ old('description', $product->description) }}</textarea>
            </div>
        </div>

        <!-- Pricing & Inventory Section -->
        <div class="mb-8">
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 rounded-lg mb-6">
                <div class="flex items-center">
                    <div class="bg-green-100 p-2 rounded-lg mr-3">
                        <i class="fas fa-dollar-sign text-green-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-0">Pricing & Inventory</h3>
                        <p class="text-sm text-gray-600 mb-0">Update product pricing and stock information</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-money-bill-wave mr-1 text-orange-600"></i>Price *
                    </label>
                    <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300"
                        placeholder="0.00">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-percentage mr-1 text-orange-600"></i>Discount Price
                    </label>
                    <input type="number" step="0.01" name="discount_price" value="{{ old('discount_price', $product->discount_price) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300"
                        placeholder="0.00">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-barcode mr-1 text-orange-600"></i>SKU
                    </label>
                    <input type="text" name="sku" value="{{ old('sku', $product->sku) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300"
                        placeholder="Product SKU">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-qrcode mr-1 text-orange-600"></i>Barcode
                    </label>
                    <input type="text" name="barcode" value="{{ old('barcode', $product->barcode) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300"
                        placeholder="Product barcode">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-warehouse mr-1 text-orange-600"></i>Stock
                    </label>
                    <input type="number" name="stock" value="{{ old('stock', $product->stock) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300"
                        placeholder="0">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-weight mr-1 text-orange-600"></i>Weight (kg)
                    </label>
                    <input type="number" step="0.01" name="weight" value="{{ old('weight', $product->weight) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300"
                        placeholder="0.00">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-ruler mr-1 text-orange-600"></i>Dimensions
                    </label>
                    <input type="text" name="dimensions" value="{{ old('dimensions', $product->dimensions) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300"
                        placeholder="L x W x H">
                </div>
            </div>

            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-cube mr-1 text-orange-600"></i>Material
                </label>
                <input type="text" name="material" value="{{ old('material', $product->material) }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300"
                    placeholder="Product material">
            </div>
        </div>

        <!-- Product Attributes Section -->
        <div class="mb-8">
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 rounded-lg mb-6">
                <div class="flex items-center">
                    <div class="bg-purple-100 p-2 rounded-lg mr-3">
                        <i class="fas fa-palette text-purple-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-0">Product Attributes</h3>
                        <p class="text-sm text-gray-600 mb-0">Update colors, sizes, and tags</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-palette mr-1 text-orange-600"></i>Colors
                    </label>
                    <input type="text" name="colors" value="{{ old('colors', $product->colors) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300"
                        placeholder="Red, Blue, Green (comma separated)">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-tags mr-1 text-orange-600"></i>Tags
                    </label>
                    <input type="text" name="tags" value="{{ old('tags', $product->tags) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300"
                        placeholder="tag1, tag2, tag3 (comma separated)">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-ruler-combined mr-1 text-orange-600"></i>Sizes
                    </label>
                    <div class="flex flex-wrap gap-3 mt-2">
                        @php
                        $allSizes = ['XS','S','M','L','XL','XXL'];
                        $selectedSizes = is_array(old('sizes', json_decode($product->sizes, true))) ? old('sizes', json_decode($product->sizes, true)) : [];
                        @endphp
                        @foreach($allSizes as $size)
                        <label class="inline-flex items-center bg-gray-100 hover:bg-gray-200 px-3 py-2 rounded-lg cursor-pointer transition-colors duration-200">
                            <input type="checkbox" name="sizes[]" value="{{ $size }}"
                                class="form-checkbox h-4 w-4 text-orange-500 rounded mr-2" 
                                {{ in_array($size, $selectedSizes) ? 'checked' : '' }}>
                            <span class="text-sm font-medium">{{ $size }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- SEO Section -->
        <div class="mb-8">
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 rounded-lg mb-6">
                <div class="flex items-center">
                    <div class="bg-blue-100 p-2 rounded-lg mr-3">
                        <i class="fas fa-search text-blue-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-0">SEO Settings</h3>
                        <p class="text-sm text-gray-600 mb-0">Optimize for search engines</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-heading mr-1 text-orange-600"></i>Meta Title
                    </label>
                    <input type="text" name="meta_title" value="{{ old('meta_title', $product->meta_title) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300"
                        placeholder="SEO title">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-paragraph mr-1 text-orange-600"></i>Meta Description
                    </label>
                    <input type="text" name="meta_description" value="{{ old('meta_description', $product->meta_description) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300"
                        placeholder="SEO description">
                </div>
            </div>
        </div>

        <!-- Media Section -->
        <div class="mb-8">
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 rounded-lg mb-6">
                <div class="flex items-center">
                    <div class="bg-pink-100 p-2 rounded-lg mr-3">
                        <i class="fas fa-images text-pink-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-0">Product Media</h3>
                        <p class="text-sm text-gray-600 mb-0">Update product images</p>
                    </div>
                </div>
            </div>

            <!-- Current Thumbnail -->
            @if($product->thumbnail && file_exists(public_path('admin-assets/products/'.$product->thumbnail)))
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Current Thumbnail</label>
                <div class="flex items-center space-x-4">
                    <img src="{{ asset('admin-assets/products/'.$product->thumbnail) }}" 
                         class="w-20 h-20 rounded-lg object-cover shadow-md" alt="Current Thumbnail">
                    <div>
                        <p class="text-sm text-gray-600">Current thumbnail image</p>
                        <p class="text-xs text-gray-500">Upload a new image to replace</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Current Images -->
            @if($productImages && $productImages->count() > 0)
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Current Product Images</label>
                <div class="flex flex-wrap gap-3">
                    @foreach($productImages as $img)
                    <div class="relative group">
                        <img src="{{ asset('admin-assets/products/'.$img->image) }}" 
                             class="w-16 h-16 rounded-lg object-cover shadow-md" alt="Product Image">
                        <div class="absolute inset-0 bg-black bg-opacity-50 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center justify-center">
                            <span class="text-white text-xs">Current</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                <p class="text-xs text-gray-500 mt-2">Upload new images to add to the gallery</p>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-image mr-1 text-orange-600"></i>New Thumbnail Image
                    </label>
                    <input type="file" name="thumbnail" accept="image/*"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300">
                    <input type="hidden" name="old_thumbnail" value="{{ $product->thumbnail }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-images mr-1 text-orange-600"></i>Additional Product Images
                    </label>
                    <input type="file" name="images[]" multiple accept="image/*"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300">
                </div>
            </div>
        </div>

        <!-- Status & Flags Section -->
        <div class="mb-8">
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 rounded-lg mb-6">
                <div class="flex items-center">
                    <div class="bg-yellow-100 p-2 rounded-lg mr-3">
                        <i class="fas fa-flag text-yellow-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-0">Product Status & Flags</h3>
                        <p class="text-sm text-gray-600 mb-0">Update product visibility and special flags</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <label class="flex items-center bg-green-50 hover:bg-green-100 p-4 rounded-lg cursor-pointer transition-colors duration-200">
                    <input type="checkbox" name="status" value="1" {{ $product->status ? 'checked' : '' }} class="form-checkbox h-5 w-5 text-green-500 rounded mr-3">
                    <div>
                        <div class="font-medium text-gray-900">Active</div>
                        <div class="text-sm text-gray-500">Product is visible</div>
                    </div>
                </label>
                <label class="flex items-center bg-blue-50 hover:bg-blue-100 p-4 rounded-lg cursor-pointer transition-colors duration-200">
                    <input type="checkbox" name="is_featured" value="1" {{ $product->is_featured ? 'checked' : '' }} class="form-checkbox h-5 w-5 text-blue-500 rounded mr-3">
                    <div>
                        <div class="font-medium text-gray-900">Featured</div>
                        <div class="text-sm text-gray-500">Show on homepage</div>
                    </div>
                </label>
                <label class="flex items-center bg-purple-50 hover:bg-purple-100 p-4 rounded-lg cursor-pointer transition-colors duration-200">
                    <input type="checkbox" name="is_new" value="1" {{ $product->is_new ? 'checked' : '' }} class="form-checkbox h-5 w-5 text-purple-500 rounded mr-3">
                    <div>
                        <div class="font-medium text-gray-900">New</div>
                        <div class="text-sm text-gray-500">New arrival</div>
                    </div>
                </label>
                <label class="flex items-center bg-orange-50 hover:bg-orange-100 p-4 rounded-lg cursor-pointer transition-colors duration-200">
                    <input type="checkbox" name="is_best_seller" value="1" {{ $product->is_best_seller ? 'checked' : '' }} class="form-checkbox h-5 w-5 text-orange-500 rounded mr-3">
                    <div>
                        <div class="font-medium text-gray-900">Best Seller</div>
                        <div class="text-sm text-gray-500">Top selling</div>
                    </div>
                </label>
                <label class="flex items-center bg-red-50 hover:bg-red-100 p-4 rounded-lg cursor-pointer transition-colors duration-200">
                    <input type="checkbox" name="is_flash_sale" value="1" {{ $product->is_flash_sale ? 'checked' : '' }} class="form-checkbox h-5 w-5 text-red-500 rounded mr-3">
                    <div>
                        <div class="font-medium text-gray-900">Flash Sale</div>
                        <div class="text-sm text-gray-500">Limited time offer</div>
                    </div>
                </label>
            </div>
        </div>

        <!-- Flash Sale Section -->
        <div class="mb-8">
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 rounded-lg mb-6">
                <div class="flex items-center">
                    <div class="bg-red-100 p-2 rounded-lg mr-3">
                        <i class="fas fa-bolt text-red-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-0">Flash Sale Settings</h3>
                        <p class="text-sm text-gray-600 mb-0">Configure flash sale details</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-fire mr-1 text-orange-600"></i>Flash Sale Price
                    </label>
                    <input type="number" step="0.01" name="flash_sale_price" value="{{ old('flash_sale_price', $product->flash_sale_price) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300"
                        placeholder="0.00">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-play mr-1 text-orange-600"></i>Start Date
                    </label>
                    <input type="datetime-local" name="flash_sale_start" 
                        value="{{ old('flash_sale_start', optional($product->flash_sale_start)->format('Y-m-d\TH:i')) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-stop mr-1 text-orange-600"></i>End Date
                    </label>
                    <input type="datetime-local" name="flash_sale_end" 
                        value="{{ old('flash_sale_end', optional($product->flash_sale_end)->format('Y-m-d\TH:i')) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300">
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
            <a href="{{ route('admin.products.index') }}" 
               class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white rounded-lg font-medium transition-all duration-300 hover:scale-105">
                <i class="fas fa-arrow-left mr-2"></i>Cancel
            </a>
            <button type="submit" 
                    class="px-6 py-3 bg-orange-600 hover:bg-orange-700 text-white rounded-lg font-medium transition-all duration-300 hover:scale-105 shadow-lg">
                <i class="fas fa-save mr-2"></i>Update Product
            </button>
        </div>
    </form>
</div>

<script>
function categorySelect() {
    return {
        categoryId: '{{ $product->category_id }}',
        subcategoryId: '{{ $product->subcategory_id }}',
        subcategories: [],

        init() {
            if (this.categoryId) {
                this.fetchSubcategories();
            }
        },

        fetchSubcategories() {
            if (!this.categoryId) {
                this.subcategories = [];
                this.subcategoryId = '';
                return;
            }

            fetch(`/admin/get-subcategories/${this.categoryId}`)
                .then(res => res.json())
                .then(data => {
                    this.subcategories = data;
                })
                .catch(err => console.error(err));
        }
    }
}
</script>
@endsection