@extends('layouts.admin')

@section('title', 'Edit Slogan')
@section('page-title', 'Edit Slogan')

@section('content')
<!-- Header Section -->
<div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl shadow-lg mb-8 p-6 text-white">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold mb-2">Edit Slogan</h1>
            <p class="text-orange-100">Update slogan text and settings</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.slogans.index') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg font-medium transition-all duration-300 hover:scale-105">
                <i class="fas fa-arrow-left mr-2"></i> Back to Slogans
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Main Form -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Slogan Information</h3>
            </div>
            
            <form action="{{ route('admin.slogans.update', $slogan) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')
                
                <!-- Slogan Text -->
                <div class="mb-6">
                    <label for="text" class="block text-sm font-medium text-gray-700 mb-2">
                        Slogan Text <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="text" 
                           name="text" 
                           value="{{ old('text', $slogan->text) }}" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-lg @error('text') border-red-500 @enderror" 
                           maxlength="255"
                           required>
                    @error('text')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <div class="flex justify-between items-center mt-1">
                        <p class="text-xs text-gray-500">Short, memorable phrase for your brand</p>
                        <p class="text-xs text-gray-500"><span id="char-count">{{ strlen($slogan->text) }}</span>/255</p>
                    </div>
                </div>

                <!-- Preview -->
                <div class="mb-6 p-6 bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg border border-orange-200">
                    <p class="text-sm font-medium text-gray-700 mb-3">
                        <i class="fas fa-eye mr-2 text-orange-500"></i>Preview:
                    </p>
                    <div class="text-center bg-white p-6 rounded-lg shadow-sm">
                        <p id="preview-text" class="text-2xl font-semibold" style="background: linear-gradient(90deg, #f36c21, #ff8c42, #ffb67a); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                            {{ $slogan->text }}
                        </p>
                    </div>
                </div>

                <!-- Display Order -->
                <div class="mb-6">
                    <label for="order" class="block text-sm font-medium text-gray-700 mb-2">Display Order</label>
                    <input type="number" 
                           id="order" 
                           name="order" 
                           value="{{ old('order', $slogan->order) }}" 
                           min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('order') border-red-500 @enderror">
                    @error('order')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">Lower numbers appear first in rotation</p>
                </div>

                <!-- Active Status -->
                <div class="mb-6">
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="is_active" 
                               name="is_active" 
                               value="1"
                               {{ old('is_active', $slogan->is_active) ? 'checked' : '' }}
                               class="w-4 h-4 text-orange-600 bg-gray-100 border-gray-300 rounded focus:ring-orange-500">
                        <label for="is_active" class="ml-2 text-sm font-medium text-gray-700">
                            Active (Include in rotation)
                        </label>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <div class="flex space-x-3">
                        <a href="{{ route('admin.slogans.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            <i class="fas fa-times mr-2"></i> Cancel
                        </a>
                        <button type="button" 
                                onclick="if(confirm('Are you sure you want to delete this slogan?')) document.getElementById('delete-form').submit();" 
                                class="px-6 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors">
                            <i class="fas fa-trash mr-2"></i> Delete
                        </button>
                    </div>
                    <button type="submit" class="px-6 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors">
                        <i class="fas fa-save mr-2"></i> Update Slogan
                    </button>
                </div>
            </form>

            <!-- Delete Form (Hidden) -->
            <form id="delete-form" action="{{ route('admin.slogans.destroy', $slogan) }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1">
        <!-- Slogan Info Card -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-info-circle mr-2"></i>
                    Slogan Info
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Status</p>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $slogan->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            <i class="fas fa-{{ $slogan->is_active ? 'check-circle' : 'times-circle' }} mr-2"></i>
                            {{ $slogan->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Display Order</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $slogan->order }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Created</p>
                        <p class="text-sm text-gray-900">{{ $slogan->created_at->format('M d, Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $slogan->created_at->diffForHumans() }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Last Updated</p>
                        <p class="text-sm text-gray-900">{{ $slogan->updated_at->format('M d, Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $slogan->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tips Card -->
        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl shadow-lg border border-green-200 p-6">
            <div class="flex items-start">
                <i class="fas fa-star text-green-600 text-2xl mr-3 mt-1"></i>
                <div>
                    <h4 class="font-semibold text-green-900 mb-2">Quick Tips</h4>
                    <ul class="space-y-2 text-sm text-green-800">
                        <li>• Test different variations</li>
                        <li>• Use action-oriented words</li>
                        <li>• Emphasize benefits</li>
                        <li>• Keep it customer-focused</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Character counter and preview
const textInput = document.getElementById('text');
const charCount = document.getElementById('char-count');
const previewText = document.getElementById('preview-text');

textInput.addEventListener('input', function(e) {
    const length = e.target.value.length;
    charCount.textContent = length;
    
    // Update preview
    previewText.textContent = e.target.value || 'Your slogan will appear here';
});
</script>
@endsection


