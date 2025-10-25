@extends('layouts.admin')

@section('title', 'Category Management')
@section('page-title', 'Categories')

@section('content')
<style>
    .btn-add-category {
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
    
    .btn-add-category:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.05);
        color: white;
        text-decoration: none;
    }
    
    .btn-export-category {
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
    
    .btn-export-category:hover {
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
            <h1 class="text-3xl font-bold mb-2">Category Management</h1>
            <p class="text-orange-100">Organize and manage your product categories</p>
        </div>
            <div class="flex space-x-3">
                <button class="btn-export-category" onclick="exportCategories()">
                    <i class="fas fa-download mr-2"></i> Export Categories
                </button>
                <a href="{{ route('admin.categories.create') }}" class="btn-add-category">
                    <i class="fas fa-plus mr-2"></i> Add Category
                </a>
            </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <div class="flex items-center">
            <div class="bg-blue-100 p-3 rounded-lg">
                <i class="fas fa-tags text-blue-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Categories</p>
                <p class="text-2xl font-bold text-gray-900">{{ $categories->total() }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <div class="flex items-center">
            <div class="bg-green-100 p-3 rounded-lg">
                <i class="fas fa-check-circle text-green-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Active Categories</p>
                <p class="text-2xl font-bold text-gray-900">{{ $categories->where('is_active', true)->count() }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <div class="flex items-center">
            <div class="bg-yellow-100 p-3 rounded-lg">
                <i class="fas fa-star text-yellow-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Featured Categories</p>
                <p class="text-2xl font-bold text-gray-900">{{ $categories->where('featured', true)->count() }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <div class="flex items-center">
            <div class="bg-purple-100 p-3 rounded-lg">
                <i class="fas fa-eye text-purple-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">In Navigation</p>
                <p class="text-2xl font-bold text-gray-900">{{ $categories->where('nav', true)->count() }}</p>
            </div>
        </div>
    </div>
    </div>

<!-- Filters Section -->
<div class="bg-white rounded-xl shadow-lg mb-8 border border-gray-200">
    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="bg-orange-100 p-2 rounded-lg mr-3">
                    <i class="fas fa-filter text-orange-600"></i>
                </div>
                <div>
                    <h6 class="text-lg font-semibold text-gray-800 mb-0">Advanced Filters</h6>
                    <p class="text-sm text-gray-600 mb-0">Filter categories by various criteria</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <span class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm font-medium">
                    {{ $categories->total() }} Total Categories
                </span>
                <button onclick="toggleFilters()" class="bg-orange-100 hover:bg-orange-200 text-orange-700 px-3 py-1 rounded-lg text-sm font-medium transition-all duration-300">
                    <i class="fas fa-chevron-down mr-1" id="filterToggleIcon"></i> Toggle Filters
        </button>
    </div>
        </div>
    </div>
    
    <div id="filtersContent" class="p-6">
        <form method="GET" action="{{ route('admin.categories.index') }}" id="filterForm">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500" 
                           placeholder="Search categories...">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Featured</label>
                    <select name="featured" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        <option value="">All Categories</option>
                        <option value="1" {{ request('featured') == '1' ? 'selected' : '' }}>Featured</option>
                        <option value="0" {{ request('featured') == '0' ? 'selected' : '' }}>Regular</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Navigation</label>
                    <select name="nav" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        <option value="">All Categories</option>
                        <option value="1" {{ request('nav') == '1' ? 'selected' : '' }}>In Navigation</option>
                        <option value="0" {{ request('nav') == '0' ? 'selected' : '' }}>Hidden</option>
                    </select>
                </div>
    </div>

            <div class="flex justify-between items-center">
                <div class="flex space-x-2">
                    <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg font-medium transition-all duration-300">
                        <i class="fas fa-search mr-2"></i> Apply Filters
                    </button>
                    <a href="{{ route('admin.categories.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition-all duration-300">
                        <i class="fas fa-times mr-2"></i> Clear Filters
                    </a>
                </div>
                <div class="text-sm text-gray-500">
                    Showing {{ $categories->count() }} of {{ $categories->total() }} categories
                </div>
            </div>
        </form>
    </div>
    </div>

    <!-- Categories Table -->
<div class="bg-white rounded-xl shadow-lg border border-gray-200">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Categories</h3>
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-500">{{ $categories->count() }} categories</span>
            </div>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subcategories</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Products</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Featured</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Navigation</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($categories as $category)
                <tr class="hover:bg-gray-50 transition-colors duration-200">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            @if($category->image)
                                <img src="{{ asset('admin-assets/categories/' . $category->image) }}" 
                                     class="w-12 h-12 rounded-lg object-cover mr-3 shadow-md" alt="Category"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="w-12 h-12 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center mr-3 shadow-md" style="display: none;">
                                    <i class="fas fa-tag text-gray-400"></i>
                                </div>
                            @else
                                <div class="w-12 h-12 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center mr-3 shadow-md">
                                    <i class="fas fa-tag text-gray-400"></i>
                                </div>
                            @endif
                            <div>
                                <div class="text-sm font-bold text-gray-900">{{ $category->name }}</div>
                                <div class="text-xs text-gray-500">ID: {{ $category->id }}</div>
                            </div>
                        </div>
                    </td>
                        <td class="px-6 py-4">
                        <div class="text-sm text-gray-900 max-w-xs truncate" title="{{ $category->description }}">
                            {{ $category->description ?? 'No description' }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $category->subcategories->count() }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            {{ $category->products->count() }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $category->featured ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $category->featured ? 'Featured' : 'Regular' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $category->nav ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $category->nav ? 'In Nav' : 'Hidden' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $category->created_at->format('M d, Y') }}
                        </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('admin.categories.show', $category->id) }}" 
                               class="text-blue-600 hover:text-blue-900 transition-colors duration-200" 
                               title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.categories.edit', $category->id) }}" 
                               class="text-yellow-600 hover:text-yellow-900 transition-colors duration-200" 
                               title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="{{ route('admin.subcategories.index', ['category' => $category->id]) }}" 
                               class="text-purple-600 hover:text-purple-900 transition-colors duration-200" 
                               title="Subcategories">
                                <i class="fas fa-tags"></i>
                            </a>
                            <button onclick="deleteCategory({{ $category->id }})" 
                                    class="text-red-600 hover:text-red-900 transition-colors duration-200" 
                                    title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-tags text-gray-400 text-4xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No categories found</h3>
                            <p class="text-gray-500 mb-4">Get started by creating your first category.</p>
                            <button onclick="createCategory()" class="btn-add-category" style="background: #f36c21; border: 1px solid #f36c21;">
                                <i class="fas fa-plus mr-2"></i> Add Category
                            </button>
                        </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($categories->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Showing {{ $categories->firstItem() }} to {{ $categories->lastItem() }} of {{ $categories->total() }} results
            </div>
            <div class="flex items-center space-x-2">
    {{ $categories->links() }}
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Create/Edit Category Modal -->
<div id="categoryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900" id="categoryModalTitle">Add Category</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeCategoryModal()">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <!-- Modal Body -->
            <form id="categoryForm" class="mt-4">
                
                <input type="hidden" id="categoryId" name="category_id">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="categoryName" class="block text-sm font-medium text-gray-700 mb-2">Category Name *</label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500" id="categoryName" name="name" required>
                    </div>
                    <div>
                        <label for="categorySlug" class="block text-sm font-medium text-gray-700 mb-2">Slug</label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500" id="categorySlug" name="slug" placeholder="auto-generated">
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="categoryDescription" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500" id="categoryDescription" name="description" rows="3"></textarea>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="categoryImage" class="block text-sm font-medium text-gray-700 mb-2">Image</label>
                        <input type="file" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500" id="categoryImage" name="image" accept="image/*">
                        <p class="text-xs text-gray-500 mt-1">Recommended size: 300x300px</p>
                    </div>
                    <div>
                        <label for="categoryIcon" class="block text-sm font-medium text-gray-700 mb-2">Icon</label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500" id="categoryIcon" name="icon" placeholder="fas fa-tag">
                        <p class="text-xs text-gray-500 mt-1">Font Awesome icon class</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label for="categoryStatus" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500" id="categoryStatus" name="is_active">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <div>
                        <label for="categoryFeatured" class="block text-sm font-medium text-gray-700 mb-2">Featured</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500" id="categoryFeatured" name="featured">
                            <option value="0">Regular</option>
                            <option value="1">Featured</option>
                        </select>
                    </div>
                    <div>
                        <label for="categoryNav" class="block text-sm font-medium text-gray-700 mb-2">Show in Navigation</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500" id="categoryNav" name="nav">
                            <option value="0">Hidden</option>
                            <option value="1">Show in Nav</option>
                        </select>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="categoryMetaTitle" class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500" id="categoryMetaTitle" name="meta_title">
                </div>
                
                <div class="mb-6">
                    <label for="categoryMetaDescription" class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500" id="categoryMetaDescription" name="meta_description" rows="2"></textarea>
</div>

                <!-- Modal Footer -->
                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors duration-200" onclick="closeCategoryModal()">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors duration-200">
                        Save Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Toggle filters function
function toggleFilters() {
    const content = document.getElementById('filtersContent');
    const icon = document.getElementById('filterToggleIcon');
    
    if (content.style.display === 'none') {
        content.style.display = 'block';
        icon.className = 'fas fa-chevron-up mr-1';
    } else {
        content.style.display = 'none';
        icon.className = 'fas fa-chevron-down mr-1';
    }
}

// Create category function
function createCategory() {
    document.getElementById('categoryModalTitle').textContent = 'Add Category';
    document.getElementById('categoryForm').reset();
    document.getElementById('categoryId').value = '';
    document.getElementById('categoryModal').classList.remove('hidden');
}

// Close category modal function
function closeCategoryModal() {
    document.getElementById('categoryModal').classList.add('hidden');
}

// Edit category function
function editCategory(categoryId) {
    // Fetch category data and populate form
    fetch(`/admin/categories/${categoryId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const category = data.category;
                document.getElementById('categoryModalTitle').textContent = 'Edit Category';
                document.getElementById('categoryId').value = category.id;
                document.getElementById('categoryName').value = category.name;
                document.getElementById('categorySlug').value = category.slug || '';
                document.getElementById('categoryDescription').value = category.description || '';
                document.getElementById('categoryIcon').value = category.icon || '';
                document.getElementById('categoryStatus').value = category.status ? '1' : '0';
                document.getElementById('categoryFeatured').value = category.featured ? '1' : '0';
                document.getElementById('categoryNav').value = category.nav ? '1' : '0';
                document.getElementById('categoryMetaTitle').value = category.meta_title || '';
                document.getElementById('categoryMetaDescription').value = category.meta_description || '';
                document.getElementById('categoryModal').classList.remove('hidden');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading category data');
        });
}

// View category function
function viewCategory(categoryId) {
    window.location.href = `/admin/categories/${categoryId}`;
}

// View subcategories function
function viewSubcategories(categoryId) {
    window.location.href = `/admin/subcategories?category=${categoryId}`;
}

// Delete category function
function deleteCategory(categoryId) {
    if (confirm('Are you sure you want to delete this category?')) {
        fetch(`/admin/categories/${categoryId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Category deleted successfully!');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting category');
        });
    }
}

// Export categories function
function exportCategories() {
    window.open('/admin/categories/export', '_blank');
}

// Category form submission
document.getElementById('categoryForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const categoryId = formData.get('category_id');
    const url = categoryId ? `/admin/categories/${categoryId}` : '/admin/categories';
    const method = categoryId ? 'PUT' : 'POST';
    
    fetch(url, {
        method: method,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Category saved successfully!');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error saving category');
    });
});

// Auto-generate slug from name
document.getElementById('categoryName').addEventListener('input', function() {
    const name = this.value;
    const slug = name.toLowerCase()
        .replace(/[^a-z0-9 -]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .trim('-');
    document.getElementById('categorySlug').value = slug;
});

// Close modal when clicking outside
document.getElementById('categoryModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeCategoryModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeCategoryModal();
    }
});
</script>
@endsection