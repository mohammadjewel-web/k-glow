@extends('layouts.admin')

@section('title', 'Add Category')
@section('page-title', 'Add New Category')

@section('content')
<!-- Header Section -->
<div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl shadow-lg mb-8 p-6 text-white">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold mb-2">Add New Category</h1>
            <p class="text-orange-100">Create a new product category</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.categories.index') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg font-medium transition-all duration-300 hover:scale-105">
                <i class="fas fa-arrow-left mr-2"></i> Back to Categories
            </a>
        </div>
    </div>
</div>

<!-- Category Form -->
<div class="bg-white rounded-xl shadow-lg border border-gray-200">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Category Information</h3>
    </div>
    
    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Category Name *</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('name') border-red-500 @enderror" 
                       required>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">Slug</label>
                <input type="text" id="slug" name="slug" value="{{ old('slug') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('slug') border-red-500 @enderror" 
                       placeholder="auto-generated">
                @error('slug')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <div class="mb-6">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
            <textarea id="description" name="description" rows="4" 
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
            @error('description')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Category Image</label>
                <input type="file" id="image" name="image" accept="image/*" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('image') border-red-500 @enderror">
                <p class="text-xs text-gray-500 mt-1">Recommended size: 300x300px</p>
                @error('image')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="icon" class="block text-sm font-medium text-gray-700 mb-2">Icon Class</label>
                <input type="text" id="icon" name="icon" value="{{ old('icon') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('icon') border-red-500 @enderror" 
                       placeholder="fas fa-tag">
                <p class="text-xs text-gray-500 mt-1">Font Awesome icon class</p>
                @error('icon')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select id="status" name="status" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('status') border-red-500 @enderror">
                    <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="featured" class="block text-sm font-medium text-gray-700 mb-2">Featured</label>
                <select id="featured" name="featured" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('featured') border-red-500 @enderror">
                    <option value="0" {{ old('featured', '0') == '0' ? 'selected' : '' }}>Regular</option>
                    <option value="1" {{ old('featured') == '1' ? 'selected' : '' }}>Featured</option>
                </select>
                @error('featured')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="nav" class="block text-sm font-medium text-gray-700 mb-2">Show in Navigation</label>
                <select id="nav" name="nav" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('nav') border-red-500 @enderror">
                    <option value="0" {{ old('nav', '0') == '0' ? 'selected' : '' }}>Hidden</option>
                    <option value="1" {{ old('nav') == '1' ? 'selected' : '' }}>Show in Nav</option>
                </select>
                @error('nav')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <div class="mb-6">
            <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
            <input type="text" id="meta_title" name="meta_title" value="{{ old('meta_title') }}" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('meta_title') border-red-500 @enderror">
            @error('meta_title')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="mb-6">
            <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
            <textarea id="meta_description" name="meta_description" rows="3" 
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('meta_description') border-red-500 @enderror">{{ old('meta_description') }}</textarea>
            @error('meta_description')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
            <a href="{{ route('admin.categories.index') }}" class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors duration-200">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors duration-200">
                <i class="fas fa-save mr-2"></i> Create Category
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

