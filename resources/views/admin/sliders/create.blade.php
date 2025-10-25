@extends('layouts.admin')

@section('title', 'Add Slider')
@section('page-title', 'Add New Slider')

@section('content')
<!-- Header Section -->
<div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl shadow-lg mb-8 p-6 text-white">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold mb-2">Add New Slider</h1>
            <p class="text-orange-100">Create a new homepage slider</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.sliders.index') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg font-medium transition-all duration-300 hover:scale-105">
                <i class="fas fa-arrow-left mr-2"></i> Back to Sliders
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Main Form -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Slider Information</h3>
            </div>
            
            <form action="{{ route('admin.sliders.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                
                <!-- Title -->
                <div class="mb-6">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Slider Title
                    </label>
                    <input type="text" 
                           id="title" 
                           name="title" 
                           value="{{ old('title') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('title') border-red-500 @enderror" 
                           placeholder="e.g., Summer Sale 2025!">
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">Optional: Main heading displayed on the slider</p>
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea id="description" 
                              name="description" 
                              rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('description') border-red-500 @enderror"
                              placeholder="Short description or tagline">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">Optional subtitle displayed below the title</p>
                </div>

                <!-- Image Upload -->
                <div class="mb-6">
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                        Slider Image <span class="text-red-500">*</span>
                    </label>
                    <input type="file" 
                           id="image" 
                           name="image" 
                           accept="image/jpeg,image/jpg,image/png,image/gif,image/webp" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('image') border-red-500 @enderror"
                           onchange="previewImage(event)"
                           required>
                    @error('image')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">Recommended: 1920x600px (JPG, PNG, GIF, WebP - Max 2MB)</p>
                    
                    <!-- Image Preview -->
                    <div id="image-preview" class="mt-4 hidden">
                        <p class="text-sm font-medium text-gray-700 mb-2">Preview:</p>
                        <img id="preview-img" src="" alt="Preview" class="w-full rounded-lg border border-gray-200 shadow-sm" style="max-height: 200px; object-fit: cover;">
                    </div>
                </div>

                <!-- Button Settings -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="button_text" class="block text-sm font-medium text-gray-700 mb-2">Button Text</label>
                        <input type="text" 
                               id="button_text" 
                               name="button_text" 
                               value="{{ old('button_text') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('button_text') border-red-500 @enderror"
                               placeholder="e.g., Shop Now">
                        @error('button_text')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="button_link" class="block text-sm font-medium text-gray-700 mb-2">Button Link</label>
                        <input type="url" 
                               id="button_link" 
                               name="button_link" 
                               value="{{ old('button_link') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('button_link') border-red-500 @enderror"
                               placeholder="https://example.com/shop">
                        @error('button_link')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Display Order -->
                <div class="mb-6">
                    <label for="order" class="block text-sm font-medium text-gray-700 mb-2">Display Order</label>
                    <input type="number" 
                           id="order" 
                           name="order" 
                           value="{{ old('order', 0) }}" 
                           min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('order') border-red-500 @enderror">
                    @error('order')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">Lower numbers appear first (0 = first position)</p>
                </div>

                <!-- Active Status -->
                <div class="mb-6">
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="is_active" 
                               name="is_active" 
                               value="1"
                               {{ old('is_active', true) ? 'checked' : '' }}
                               class="w-4 h-4 text-orange-600 bg-gray-100 border-gray-300 rounded focus:ring-orange-500">
                        <label for="is_active" class="ml-2 text-sm font-medium text-gray-700">
                            Active (Show on homepage)
                        </label>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Enable to display this slider on the homepage</p>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.sliders.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="fas fa-times mr-2"></i> Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors">
                        <i class="fas fa-save mr-2"></i> Create Slider
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1">
        <!-- Guidelines Card -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200 bg-blue-50">
                <h3 class="text-lg font-semibold text-blue-900 flex items-center">
                    <i class="fas fa-info-circle mr-2"></i>
                    Slider Guidelines
                </h3>
            </div>
            <div class="p-6">
                <div class="mb-4">
                    <h4 class="font-semibold text-gray-900 mb-2">Image Requirements:</h4>
                    <ul class="space-y-1 text-sm text-gray-600">
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                            <span>Size: 1920x600px recommended</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                            <span>Format: JPG, PNG, GIF, or WebP</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                            <span>Max size: 2MB</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                            <span>Use high-quality images</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-star text-orange-500 mr-2 mt-1"></i>
                            <span><strong>WebP recommended</strong> for smaller file size</span>
                        </li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">Best Practices:</h4>
                    <ul class="space-y-1 text-sm text-gray-600">
                        <li class="flex items-start">
                            <i class="fas fa-lightbulb text-yellow-500 mr-2 mt-1"></i>
                            <span>Keep title short and compelling</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-lightbulb text-yellow-500 mr-2 mt-1"></i>
                            <span>Add clear call-to-action button</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-lightbulb text-yellow-500 mr-2 mt-1"></i>
                            <span>Optimize images before upload</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-lightbulb text-yellow-500 mr-2 mt-1"></i>
                            <span>Test on mobile devices</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Tips Card -->
        <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl shadow-lg border border-orange-200 p-6">
            <div class="flex items-start">
                <i class="fas fa-star text-orange-500 text-2xl mr-3 mt-1"></i>
                <div>
                    <h4 class="font-semibold text-orange-900 mb-2">Quick Tips</h4>
                    <ul class="space-y-2 text-sm text-orange-800">
                        <li>• Place important text in the center</li>
                        <li>• Use consistent image dimensions</li>
                        <li>• Consider mobile responsiveness</li>
                        <li>• Compress images for faster loading</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-img').src = e.target.result;
            document.getElementById('image-preview').classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
}
</script>
@endsection
