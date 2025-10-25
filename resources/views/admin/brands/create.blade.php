@extends('layouts.admin')

@section('title', 'Create Brand')
@section('page-title', 'Create Brand')

@section('content')
<!-- Header Section -->
<div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl shadow-lg mb-8 p-6 text-white">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold mb-2">Create Brand</h1>
            <p class="text-orange-100">Add a new brand to your store</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.brands.index') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg font-medium transition-all duration-300 hover:scale-105">
                <i class="fas fa-list mr-2"></i> Back to Brands
            </a>
        </div>
    </div>
</div>

<!-- Create Brand Form -->
<div class="bg-white rounded-xl shadow-lg p-6">
    <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <!-- Basic Information -->
        <div class="mb-8">
            <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-info-circle text-orange-600 mr-2"></i>
                Basic Information
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-tag mr-1 text-orange-600"></i>Brand Name *
                    </label>
                    <input type="text" id="name" name="name" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300 @error('name') border-red-500 @enderror"
                           value="{{ old('name') }}"
                           placeholder="Enter brand name">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-link mr-1 text-orange-600"></i>Slug
                    </label>
                    <input type="text" id="slug" name="slug"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300 @error('slug') border-red-500 @enderror"
                           value="{{ old('slug') }}"
                           placeholder="auto-generated">
                    @error('slug')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="mt-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-align-left mr-1 text-orange-600"></i>Description
                </label>
                <textarea id="description" name="description" rows="4"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300 @error('description') border-red-500 @enderror"
                          placeholder="Enter brand description">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Brand Assets -->
        <div class="mb-8">
            <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-image text-orange-600 mr-2"></i>
                Brand Assets
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-upload mr-1 text-orange-600"></i>Brand Logo
                    </label>
                    <input type="file" id="logo" name="logo" accept="image/*"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300 @error('logo') border-red-500 @enderror">
                    <p class="text-xs text-gray-500 mt-1">Recommended size: 200x100px. Max size: 2MB</p>
                    @error('logo')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="website" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-globe mr-1 text-orange-600"></i>Website
                    </label>
                    <input type="url" id="website" name="website"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300 @error('website') border-red-500 @enderror"
                           value="{{ old('website') }}"
                           placeholder="https://brand-website.com">
                    @error('website')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Brand Settings -->
        <div class="mb-8">
            <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-cog text-orange-600 mr-2"></i>
                Brand Settings
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="is_active" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300">
                        <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Featured</label>
                    <select name="featured" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300">
                        <option value="0" {{ old('featured', '0') == '0' ? 'selected' : '' }}>Regular</option>
                        <option value="1" {{ old('featured') == '1' ? 'selected' : '' }}>Featured</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Navigation</label>
                    <select name="nav" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300">
                        <option value="0" {{ old('nav', '0') == '0' ? 'selected' : '' }}>Hidden</option>
                        <option value="1" {{ old('nav') == '1' ? 'selected' : '' }}>Show in Nav</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- SEO Information -->
        <div class="mb-8">
            <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-search text-orange-600 mr-2"></i>
                SEO Information
            </h3>
            
            <div class="space-y-6">
                <div>
                    <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-heading mr-1 text-orange-600"></i>Meta Title
                    </label>
                    <input type="text" id="meta_title" name="meta_title"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300 @error('meta_title') border-red-500 @enderror"
                           value="{{ old('meta_title') }}"
                           placeholder="Enter meta title">
                    @error('meta_title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-align-left mr-1 text-orange-600"></i>Meta Description
                    </label>
                    <textarea id="meta_description" name="meta_description" rows="3"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300 @error('meta_description') border-red-500 @enderror"
                              placeholder="Enter meta description">{{ old('meta_description') }}</textarea>
                    @error('meta_description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
            <a href="{{ route('admin.brands.index') }}" 
               class="px-6 py-3 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors duration-200">
                <i class="fas fa-times mr-2"></i>Cancel
            </a>
            <button type="submit" 
                    class="px-6 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors duration-200">
                <i class="fas fa-save mr-2"></i>Create Brand
            </button>
        </div>
    </form>
</div>

<script>
// Auto-generate slug from name
document.getElementById('name').addEventListener('input', function() {
    const name = this.value;
    const slug = name.toLowerCase()
        .replace(/[^a-z0-9 -]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .trim('-');
    document.getElementById('slug').value = slug;
});
</script>
@endsection
