@extends('layouts.admin')

@section('title', 'Edit Slider')
@section('page-title', 'Edit Slider')

@section('content')
<!-- Header Section -->
<div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl shadow-lg mb-8 p-6 text-white">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold mb-2">Edit Slider</h1>
            <p class="text-orange-100">Update slider details and settings</p>
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
            
            <form action="{{ route('admin.sliders.update', $slider) }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                @method('PUT')
                
                <!-- Title -->
                <div class="mb-6">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Slider Title
                    </label>
                    <input type="text" 
                           id="title" 
                           name="title" 
                           value="{{ old('title', $slider->title) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('title') border-red-500 @enderror">
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
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('description') border-red-500 @enderror">{{ old('description', $slider->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Current Image -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Image</label>
                    <div class="relative inline-block">
                        <img src="{{ asset($slider->image) }}" 
                             alt="{{ $slider->title }}" 
                             class="rounded-lg border-2 border-gray-200 shadow-sm"
                             style="max-height: 200px; object-fit: cover;">
                        <div class="absolute top-2 right-2 bg-green-500 text-white text-xs px-2 py-1 rounded-full">
                            <i class="fas fa-check mr-1"></i>Current
                        </div>
                    </div>
                </div>

                <!-- New Image Upload -->
                <div class="mb-6">
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                        Change Image (Optional)
                    </label>
                    <input type="file" 
                           id="image" 
                           name="image" 
                           accept="image/jpeg,image/jpg,image/png,image/gif,image/webp" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('image') border-red-500 @enderror"
                           onchange="previewImage(event)">
                    @error('image')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">Leave empty to keep current image. Accepts: JPG, PNG, GIF, WebP (Max 2MB)</p>
                    
                    <!-- New Image Preview -->
                    <div id="image-preview" class="mt-4 hidden">
                        <p class="text-sm font-medium text-gray-700 mb-2">New Image Preview:</p>
                        <div class="relative inline-block">
                            <img id="preview-img" src="" alt="Preview" class="rounded-lg border-2 border-orange-300 shadow-sm" style="max-height: 200px; object-fit: cover;">
                            <div class="absolute top-2 right-2 bg-orange-500 text-white text-xs px-2 py-1 rounded-full">
                                <i class="fas fa-sparkles mr-1"></i>New
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Button Settings -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="button_text" class="block text-sm font-medium text-gray-700 mb-2">Button Text</label>
                        <input type="text" 
                               id="button_text" 
                               name="button_text" 
                               value="{{ old('button_text', $slider->button_text) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('button_text') border-red-500 @enderror">
                        @error('button_text')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="button_link" class="block text-sm font-medium text-gray-700 mb-2">Button Link</label>
                        <input type="url" 
                               id="button_link" 
                               name="button_link" 
                               value="{{ old('button_link', $slider->button_link) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('button_link') border-red-500 @enderror">
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
                           value="{{ old('order', $slider->order) }}" 
                           min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('order') border-red-500 @enderror">
                    @error('order')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Active Status -->
                <div class="mb-6">
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="is_active" 
                               name="is_active" 
                               value="1"
                               {{ old('is_active', $slider->is_active) ? 'checked' : '' }}
                               class="w-4 h-4 text-orange-600 bg-gray-100 border-gray-300 rounded focus:ring-orange-500">
                        <label for="is_active" class="ml-2 text-sm font-medium text-gray-700">
                            Active (Show on homepage)
                        </label>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <div class="flex space-x-3">
                        <a href="{{ route('admin.sliders.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            <i class="fas fa-times mr-2"></i> Cancel
                        </a>
                        <button type="button" 
                                onclick="if(confirm('Are you sure you want to delete this slider?')) document.getElementById('delete-form').submit();" 
                                class="px-6 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors">
                            <i class="fas fa-trash mr-2"></i> Delete
                        </button>
                    </div>
                    <button type="submit" class="px-6 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors">
                        <i class="fas fa-save mr-2"></i> Update Slider
                    </button>
                </div>
            </form>

            <!-- Delete Form (Hidden) -->
            <form id="delete-form" action="{{ route('admin.sliders.destroy', $slider) }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1">
        <!-- Slider Info Card -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-info-circle mr-2"></i>
                    Slider Info
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Status</p>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $slider->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            <i class="fas fa-{{ $slider->is_active ? 'check-circle' : 'times-circle' }} mr-2"></i>
                            {{ $slider->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Display Order</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $slider->order }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Created</p>
                        <p class="text-sm text-gray-900">{{ $slider->created_at->format('M d, Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $slider->created_at->diffForHumans() }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Last Updated</p>
                        <p class="text-sm text-gray-900">{{ $slider->updated_at->format('M d, Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $slider->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Guidelines Card -->
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl shadow-lg border border-blue-200 p-6">
            <div class="flex items-start">
                <i class="fas fa-lightbulb text-blue-600 text-2xl mr-3 mt-1"></i>
                <div>
                    <h4 class="font-semibold text-blue-900 mb-2">Update Tips</h4>
                    <ul class="space-y-2 text-sm text-blue-800">
                        <li>• Upload new image to replace current one</li>
                        <li>• Keep button text short and clear</li>
                        <li>• Test button link before saving</li>
                        <li>• Lower order numbers = higher priority</li>
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
