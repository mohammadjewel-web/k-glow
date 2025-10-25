@extends('layouts.admin')

@section('title', 'Create Subcategory')
@section('page-title', 'Create New Subcategory')

@section('content')
<!-- Header Section -->
<div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl shadow-lg mb-8 p-6 text-white">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold mb-2">Create New Subcategory</h1>
            <p class="text-orange-100">Add a new subcategory to organize your products</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.subcategories.index') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg font-medium transition-all duration-300 hover:scale-105">
                <i class="fas fa-arrow-left mr-2"></i> Back to Subcategories
            </a>
        </div>
    </div>
</div>

<!-- Create Form -->
<div class="bg-white rounded-xl shadow-lg p-8">
    <form action="{{ route('admin.subcategories.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Left Column -->
            <div class="space-y-6">
                <!-- Basic Information -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
                    
                    <div class="space-y-4">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Subcategory Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('name') border-red-500 @enderror"
                                   placeholder="Enter subcategory name" required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Slug -->
                        <div>
                            <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                                Slug
                            </label>
                            <input type="text" id="slug" name="slug" value="{{ old('slug') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('slug') border-red-500 @enderror"
                                   placeholder="subcategory-slug">
                            <p class="mt-1 text-sm text-gray-500">Leave empty to auto-generate from name</p>
                            @error('slug')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Category -->
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Parent Category <span class="text-red-500">*</span>
                            </label>
                            <select id="category_id" name="category_id" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('category_id') border-red-500 @enderror" required>
                                <option value="">Select a category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Description
                            </label>
                            <textarea id="description" name="description" rows="4" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('description') border-red-500 @enderror"
                                      placeholder="Enter subcategory description">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Settings -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Settings</h3>
                    
                    <div class="space-y-4">
                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <div class="flex items-center space-x-4">
                                <label class="flex items-center">
                                    <input type="radio" name="status" value="1" {{ old('status', '1') == '1' ? 'checked' : '' }} 
                                           class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300">
                                    <span class="ml-2 text-sm text-gray-700">Active</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="status" value="0" {{ old('status') == '0' ? 'checked' : '' }} 
                                           class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300">
                                    <span class="ml-2 text-sm text-gray-700">Inactive</span>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Featured -->
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="featured" value="1" {{ old('featured') ? 'checked' : '' }} 
                                       class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">Featured Subcategory</span>
                            </label>
                            <p class="mt-1 text-sm text-gray-500">Show this subcategory in featured sections</p>
                        </div>
                        
                        <!-- Navigation -->
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="nav" value="1" {{ old('nav') ? 'checked' : '' }} 
                                       class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">Show in Navigation</span>
                            </label>
                            <p class="mt-1 text-sm text-gray-500">Display this subcategory in the main navigation menu</p>
                        </div>
                        
                        <!-- Sort Order -->
                        <div>
                            <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                                Sort Order
                            </label>
                            <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                   placeholder="0">
                            <p class="mt-1 text-sm text-gray-500">Lower numbers appear first</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Image Upload -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Subcategory Image</h3>
                    
                    <div class="space-y-4">
                        <!-- Image Preview -->
                        <div id="imagePreview" class="hidden">
                            <img id="previewImg" src="" alt="Preview" class="w-full h-48 object-cover rounded-lg border">
                        </div>
                        
                        <!-- Image Upload -->
                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                                Upload Image
                            </label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-orange-400 transition-colors duration-200">
                                <div class="space-y-1 text-center">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400"></i>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-orange-600 hover:text-orange-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-orange-500">
                                            <span>Upload a file</span>
                                            <input id="image" name="image" type="file" accept="image/*" class="sr-only" onchange="previewImage(this)">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                                </div>
                            </div>
                            @error('image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- SEO Information -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">SEO Information</h3>
                    
                    <div class="space-y-4">
                        <!-- Meta Title -->
                        <div>
                            <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">
                                Meta Title
                            </label>
                            <input type="text" id="meta_title" name="meta_title" value="{{ old('meta_title') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                   placeholder="SEO title for search engines">
                        </div>
                        
                        <!-- Meta Description -->
                        <div>
                            <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">
                                Meta Description
                            </label>
                            <textarea id="meta_description" name="meta_description" rows="3" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                      placeholder="SEO description for search engines">{{ old('meta_description') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Form Actions -->
        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
            <a href="{{ route('admin.subcategories.index') }}" 
               class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                Cancel
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg font-medium transition-colors duration-200">
                <i class="fas fa-save mr-2"></i> Create Subcategory
            </button>
        </div>
    </form>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('imagePreview').classList.remove('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Auto-generate slug from name
document.getElementById('name').addEventListener('input', function() {
    const name = this.value;
    const slug = name.toLowerCase()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .trim('-');
    document.getElementById('slug').value = slug;
});
</script>
@endsection