@extends('layouts.admin')

@section('title', 'Add Slogan')
@section('page-title', 'Add New Slogan')

@section('content')
<!-- Header Section -->
<div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl shadow-lg mb-8 p-6 text-white">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold mb-2">Add New Slogan</h1>
            <p class="text-orange-100">Create a new rotating slogan for homepage</p>
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
            
            <form action="{{ route('admin.slogans.store') }}" method="POST" class="p-6">
                @csrf
                
                <!-- Slogan Text -->
                <div class="mb-6">
                    <label for="text" class="block text-sm font-medium text-gray-700 mb-2">
                        Slogan Text <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="text" 
                           name="text" 
                           value="{{ old('text') }}" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-lg @error('text') border-red-500 @enderror" 
                           placeholder="e.g., Natural Beauty, Touch Of Science"
                           maxlength="255"
                           required>
                    @error('text')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <div class="flex justify-between items-center mt-1">
                        <p class="text-xs text-gray-500">Short, memorable phrase that represents your brand</p>
                        <p class="text-xs text-gray-500"><span id="char-count">0</span>/255</p>
                    </div>
                </div>

                <!-- Preview -->
                <div class="mb-6 p-6 bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg border border-orange-200">
                    <p class="text-sm font-medium text-gray-700 mb-3">
                        <i class="fas fa-eye mr-2 text-orange-500"></i>Preview:
                    </p>
                    <div class="text-center bg-white p-6 rounded-lg shadow-sm">
                        <p id="preview-text" class="text-2xl font-semibold" style="background: linear-gradient(90deg, #f36c21, #ff8c42, #ffb67a); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                            Your slogan will appear here
                        </p>
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
                            Active (Include in rotation)
                        </label>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Enable to include this slogan in the homepage rotation</p>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.slogans.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="fas fa-times mr-2"></i> Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors">
                        <i class="fas fa-save mr-2"></i> Create Slogan
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
                    Guidelines
                </h3>
            </div>
            <div class="p-6">
                <h4 class="font-semibold text-gray-900 mb-2">Best Practices:</h4>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li class="flex items-start">
                        <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                        <span>Keep it short (under 100 characters)</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                        <span>Use clear, compelling language</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                        <span>Highlight unique value proposition</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                        <span>Focus on customer benefits</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                        <span>Make it memorable and catchy</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Examples Card -->
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl shadow-lg border border-purple-200 p-6">
            <div class="flex items-start">
                <i class="fas fa-lightbulb text-purple-600 text-2xl mr-3 mt-1"></i>
                <div>
                    <h4 class="font-semibold text-purple-900 mb-3">Example Slogans</h4>
                    <div class="space-y-2 text-sm text-purple-800">
                        <div class="bg-white bg-opacity-60 p-2 rounded">• Natural Beauty, Touch Of Science</div>
                        <div class="bg-white bg-opacity-60 p-2 rounded">• Discover the best K-Beauty products</div>
                        <div class="bg-white bg-opacity-60 p-2 rounded">• Your favorite brands, all in one place</div>
                        <div class="bg-white bg-opacity-60 p-2 rounded">• Glow Like Never Before</div>
                        <div class="bg-white bg-opacity-60 p-2 rounded">• Premium Korean Skincare Delivered</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Character counter
const textInput = document.getElementById('text');
const charCount = document.getElementById('char-count');
const previewText = document.getElementById('preview-text');

textInput.addEventListener('input', function(e) {
    const length = e.target.value.length;
    charCount.textContent = length;
    
    // Update preview
    previewText.textContent = e.target.value || 'Your slogan will appear here';
});

// Initialize
charCount.textContent = textInput.value.length;
</script>
@endsection


