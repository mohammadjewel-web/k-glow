@extends('layouts.admin')

@section('title', 'Subcategory Details')
@section('page-title', 'Subcategory: ' . $subcategory->name)

@section('content')
<!-- Header Section -->
<div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl shadow-lg mb-8 p-6 text-white">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold mb-2">Subcategory Details</h1>
            <p class="text-orange-100">View and manage subcategory information</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.subcategories.edit', $subcategory->id) }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg font-medium transition-all duration-300 hover:scale-105">
                <i class="fas fa-edit mr-2"></i> Edit Subcategory
            </a>
            <a href="{{ route('admin.subcategories.index') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg font-medium transition-all duration-300 hover:scale-105">
                <i class="fas fa-arrow-left mr-2"></i> Back to Subcategories
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Main Information -->
    <div class="lg:col-span-2 space-y-8">
        <!-- Basic Information -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-6">Basic Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">Subcategory Name</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $subcategory->name }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">Slug</label>
                    <p class="text-lg text-gray-900 font-mono">{{ $subcategory->slug }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">Parent Category</label>
                    <div class="flex items-center">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            <i class="fas fa-tag mr-2"></i>
                            {{ $subcategory->category->name }}
                        </span>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">Status</label>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $subcategory->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        <i class="fas fa-{{ $subcategory->status ? 'check' : 'times' }}-circle mr-2"></i>
                        {{ $subcategory->status ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">Featured</label>
                    @if($subcategory->featured)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                            <i class="fas fa-star mr-2"></i>
                            Featured
                        </span>
                    @else
                        <span class="text-gray-400">Not featured</span>
                    @endif
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">Navigation</label>
                    @if($subcategory->nav)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            <i class="fas fa-bars mr-2"></i>
                            In Navigation
                        </span>
                    @else
                        <span class="text-gray-400">Not in navigation</span>
                    @endif
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">Sort Order</label>
                    <p class="text-lg text-gray-900">{{ $subcategory->sort_order }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">Created</label>
                    <p class="text-lg text-gray-900">{{ $subcategory->created_at->format('M d, Y') }}</p>
                </div>
            </div>
            
            @if($subcategory->description)
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-500 mb-2">Description</label>
                    <p class="text-gray-900 leading-relaxed">{{ $subcategory->description }}</p>
                </div>
            @endif
        </div>
        
        <!-- SEO Information -->
        @if($subcategory->meta_title || $subcategory->meta_description)
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-6">SEO Information</h3>
                
                <div class="space-y-4">
                    @if($subcategory->meta_title)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-2">Meta Title</label>
                            <p class="text-gray-900">{{ $subcategory->meta_title }}</p>
                        </div>
                    @endif
                    
                    @if($subcategory->meta_description)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-2">Meta Description</label>
                            <p class="text-gray-900">{{ $subcategory->meta_description }}</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif
        
        <!-- Products in this Subcategory -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-6">Products in this Subcategory</h3>
            
            @if($subcategory->products && $subcategory->products->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($subcategory->products->take(6) as $product)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow duration-200">
                            <div class="flex items-center space-x-3">
                                @if($product->thumbnail)
                                    <img src="{{ asset('admin-assets/products/' . $product->thumbnail) }}" 
                                         class="w-12 h-12 rounded-lg object-cover" alt="{{ $product->name }}">
                                @else
                                    <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-image text-gray-400"></i>
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $product->name }}</p>
                                    <p class="text-sm text-gray-500">৳{{ number_format($product->price, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                @if($subcategory->products->count() > 6)
                    <div class="mt-4 text-center">
                        <p class="text-sm text-gray-500">And {{ $subcategory->products->count() - 6 }} more products...</p>
                    </div>
                @endif
            @else
                <div class="text-center py-8">
                    <i class="fas fa-box-open text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500">No products in this subcategory yet</p>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Sidebar -->
    <div class="space-y-8">
        <!-- Subcategory Image -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Subcategory Image</h3>
            
            @if($subcategory->image)
                <img src="{{ asset('admin-assets/subcategories/' . $subcategory->image) }}" 
                     alt="{{ $subcategory->name }}" class="w-full h-48 object-cover rounded-lg border">
            @else
                <div class="w-full h-48 bg-gray-200 rounded-lg flex items-center justify-center">
                    <div class="text-center">
                        <i class="fas fa-image text-4xl text-gray-400 mb-2"></i>
                        <p class="text-gray-500">No image uploaded</p>
                    </div>
                </div>
            @endif
        </div>
        
        <!-- Quick Stats -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Quick Stats</h3>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-500">Total Products</span>
                    <span class="text-lg font-semibold text-gray-900">{{ $subcategory->products->count() }}</span>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-500">Active Products</span>
                    <span class="text-lg font-semibold text-gray-900">{{ $subcategory->products->where('status', true)->count() }}</span>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-500">Created</span>
                    <span class="text-lg font-semibold text-gray-900">{{ $subcategory->created_at->diffForHumans() }}</span>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-500">Last Updated</span>
                    <span class="text-lg font-semibold text-gray-900">{{ $subcategory->updated_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Quick Actions</h3>
            
            <div class="space-y-3">
                <a href="{{ route('admin.subcategories.edit', $subcategory->id) }}" 
                   class="w-full bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center">
                    <i class="fas fa-edit mr-2"></i> Edit Subcategory
                </a>
                
                <a href="{{ route('admin.products.index', ['subcategory' => $subcategory->id]) }}" 
                   class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center">
                    <i class="fas fa-box mr-2"></i> View Products
                </a>
                
                <button onclick="deleteSubcategory({{ $subcategory->id }})" 
                        class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center">
                    <i class="fas fa-trash mr-2"></i> Delete Subcategory
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <i class="fas fa-exclamation-triangle text-red-600"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-4">Delete Subcategory</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">Are you sure you want to delete this subcategory? This action cannot be undone.</p>
            </div>
            <div class="items-center px-4 py-3">
                <button id="confirmDelete" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg mr-2">
                    Delete
                </button>
                <button onclick="closeDeleteModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function deleteSubcategory(id) {
    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('confirmDelete').onclick = function() {
        fetch(`/admin/subcategories/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '{{ route("admin.subcategories.index") }}';
            } else {
                alert('Error deleting subcategory');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting subcategory');
        });
    };
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}
</script>
@endsection