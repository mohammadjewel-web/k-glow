@extends('layouts.admin')

@section('title', 'Subcategory Management')
@section('page-title', 'Subcategories')

@section('content')
<style>
    .btn-add-subcategory {
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        text-decoration: none;
    }
    
    .btn-add-subcategory:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.05);
        color: white;
        text-decoration: none;
    }
    
    .btn-export-subcategory {
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        text-decoration: none;
    }
    
    .btn-export-subcategory:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.05);
        color: white;
        text-decoration: none;
    }
</style>

<!-- Header Section -->
<div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl shadow-lg mb-8 p-6 text-white">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold mb-2">Subcategory Management</h1>
            <p class="text-orange-100">Organize and manage your product subcategories</p>
        </div>
        <div class="flex space-x-3">
            <button class="btn-export-subcategory" onclick="exportSubcategories()">
                <i class="fas fa-download mr-2"></i> Export Subcategories
            </button>
            <a href="{{ route('admin.subcategories.create') }}" class="btn-add-subcategory">
                <i class="fas fa-plus mr-2"></i> Add Subcategory
            </a>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Subcategories</p>
                <p class="text-3xl font-bold text-gray-900">{{ $subcategories->total() }}</p>
            </div>
            <div class="bg-blue-100 p-3 rounded-full">
                <i class="fas fa-layer-group text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Active Subcategories</p>
                <p class="text-3xl font-bold text-gray-900">{{ $subcategories->where('status', true)->count() }}</p>
            </div>
            <div class="bg-green-100 p-3 rounded-full">
                <i class="fas fa-check-circle text-green-600 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Featured Subcategories</p>
                <p class="text-3xl font-bold text-gray-900">{{ $subcategories->where('featured', true)->count() }}</p>
            </div>
            <div class="bg-yellow-100 p-3 rounded-full">
                <i class="fas fa-star text-yellow-600 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Navigation Items</p>
                <p class="text-3xl font-bold text-gray-900">{{ $subcategories->where('nav', true)->count() }}</p>
            </div>
            <div class="bg-purple-100 p-3 rounded-full">
                <i class="fas fa-bars text-purple-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Advanced Filters -->
<div class="bg-white rounded-xl shadow-lg mb-8 p-6">
    <div class="flex flex-wrap items-center gap-4">
        <div class="flex-1 min-w-64">
            <label class="block text-sm font-medium text-gray-700 mb-2">Search Subcategories</label>
            <div class="relative">
                <input type="text" id="search" name="search" value="{{ request('search') }}" 
                       placeholder="Search by name..." 
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
            </div>
        </div>
        
        <div class="min-w-48">
            <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Category</label>
            <select name="category" id="category" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div class="min-w-32">
            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
            <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                <option value="">All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
    </div>
        
        <div class="flex space-x-2">
            <button onclick="applyFilters()" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                <i class="fas fa-filter mr-2"></i> Apply Filters
            </button>
            <button onclick="clearFilters()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                <i class="fas fa-times mr-2"></i> Clear
        </button>
    </div>
    </div>
    </div>

    <!-- Subcategories Table -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <input type="checkbox" class="rounded border-gray-300 text-orange-600 focus:ring-orange-500">
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subcategory</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Featured</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Navigation</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Products</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($subcategories as $subcategory)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input type="checkbox" class="rounded border-gray-300 text-orange-600 focus:ring-orange-500">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @if($subcategory->image)
                                    <img src="{{ asset('admin-assets/subcategories/' . $subcategory->image) }}" 
                                         class="w-12 h-12 rounded-lg object-cover mr-3 shadow-md" alt="Subcategory">
                                @else
                                    <div class="w-12 h-12 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center mr-3 shadow-md">
                                        <i class="fas fa-layer-group text-gray-400"></i>
                                    </div>
                                @endif
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $subcategory->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $subcategory->slug }}</div>
                                </div>
                                </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $subcategory->category->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $subcategory->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $subcategory->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($subcategory->featured)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-star mr-1"></i> Featured
                                </span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($subcategory->nav)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-bars mr-1"></i> In Nav
                                </span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $subcategory->products_count ?? 0 }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.subcategories.show', $subcategory->id) }}" 
                                   class="text-blue-600 hover:text-blue-900 transition-colors duration-200" 
                                   title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.subcategories.edit', $subcategory->id) }}" 
                                   class="text-yellow-600 hover:text-yellow-900 transition-colors duration-200" 
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="deleteSubcategory({{ $subcategory->id }})" 
                                        class="text-red-600 hover:text-red-900 transition-colors duration-200" 
                                        title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <i class="fas fa-layer-group text-4xl mb-4"></i>
                                <p class="text-lg font-medium">No subcategories found</p>
                                <p class="text-sm">Get started by creating your first subcategory.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($subcategories->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
    {{ $subcategories->links() }}
        </div>
    @endif
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
function applyFilters() {
    const search = document.getElementById('search').value;
    const category = document.getElementById('category').value;
    const status = document.getElementById('status').value;
    
    const params = new URLSearchParams();
    if (search) params.append('search', search);
    if (category) params.append('category', category);
    if (status) params.append('status', status);
    
    window.location.href = '{{ route("admin.subcategories.index") }}?' + params.toString();
}

function clearFilters() {
    window.location.href = '{{ route("admin.subcategories.index") }}';
}

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
                location.reload();
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

function exportSubcategories() {
    // Implement export functionality
    alert('Export functionality will be implemented');
}
</script>
@endsection