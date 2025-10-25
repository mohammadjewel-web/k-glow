@extends('layouts.admin')

@section('title', 'Brand Management')
@section('page-title', 'Brands')

@section('content')
<style>
    .btn-add-brand {
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
    
    .btn-add-brand:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.05);
        color: white;
        text-decoration: none;
    }
    
    .btn-export-brand {
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
    
    .btn-export-brand:hover {
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
            <h1 class="text-3xl font-bold mb-2">Brand Management</h1>
            <p class="text-orange-100">Organize and manage your product brands</p>
        </div>
        <div class="flex space-x-3">
            <button class="btn-export-brand" onclick="exportBrands()">
                <i class="fas fa-download mr-2"></i> Export Brands
            </button>
            <button class="btn-add-brand" onclick="createBrand()">
                <i class="fas fa-plus mr-2"></i> Add Brand
            </button>
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
                <p class="text-sm font-medium text-gray-600">Total Brands</p>
                <p class="text-2xl font-bold text-gray-900">{{ $brands->total() }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <div class="flex items-center">
            <div class="bg-green-100 p-3 rounded-lg">
                <i class="fas fa-check-circle text-green-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Active Brands</p>
                <p class="text-2xl font-bold text-gray-900">{{ $brands->where('is_active', true)->count() }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <div class="flex items-center">
            <div class="bg-yellow-100 p-3 rounded-lg">
                <i class="fas fa-star text-yellow-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Featured Brands</p>
                <p class="text-2xl font-bold text-gray-900">{{ $brands->where('featured', true)->count() }}</p>
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
                <p class="text-2xl font-bold text-gray-900">{{ $brands->where('nav', true)->count() }}</p>
            </div>
        </div>
    </div>
    </div>

<!-- Advanced Filters -->
<div class="bg-white rounded-xl shadow-lg mb-8 p-6">
    <div class="flex flex-wrap items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Advanced Filters</h3>
        <button onclick="clearFilters()" class="text-orange-600 hover:text-orange-800 text-sm font-medium">
            <i class="fas fa-times mr-1"></i> Clear All
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
            <div class="flex">
                <input type="text" id="searchInput" placeholder="Search brands..." 
                       class="flex-1 px-3 py-2 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                       value="{{ request('search') }}">
                <button type="button" onclick="applySearch()" 
                        class="px-4 py-2 bg-orange-600 text-white rounded-r-lg hover:bg-orange-700 transition-colors duration-200">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
            <select id="statusFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Featured</label>
            <select id="featuredFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                <option value="">All Brands</option>
                <option value="featured">Featured Only</option>
                <option value="regular">Regular Only</option>
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Navigation</label>
            <select id="navFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                <option value="">All Brands</option>
                <option value="nav">In Navigation</option>
                <option value="hidden">Hidden</option>
            </select>
        </div>
    </div>
    </div>

    <!-- Brands Table -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <i class="fas fa-image mr-1"></i> Logo
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <i class="fas fa-tag mr-1"></i> Brand
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <i class="fas fa-box mr-1"></i> Products
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <i class="fas fa-toggle-on mr-1"></i> Status
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <i class="fas fa-star mr-1"></i> Featured
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <i class="fas fa-eye mr-1"></i> Navigation
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <i class="fas fa-cog mr-1"></i> Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($brands as $brand)
                <tr class="hover:bg-gray-50 transition-colors duration-200">
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($brand->logo)
                            <img src="{{ asset('admin-assets/brands/' . $brand->logo) }}" 
                                 class="w-12 h-12 rounded-lg object-cover shadow-md" alt="Brand Logo"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="w-12 h-12 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center shadow-md" style="display: none;">
                                <i class="fas fa-image text-gray-400"></i>
                            </div>
                        @else
                            <div class="w-12 h-12 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center shadow-md">
                                <i class="fas fa-image text-gray-400"></i>
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $brand->name }}</div>
                                @if($brand->description)
                                    <div class="text-sm text-gray-500 truncate max-w-xs">{{ Str::limit($brand->description, 50) }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            <i class="fas fa-box mr-1"></i>
                            {{ $brand->products_count ?? 0 }} Products
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $brand->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            <i class="fas fa-{{ $brand->is_active ? 'check' : 'times' }} mr-1"></i>
                            {{ $brand->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $brand->featured ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800' }}">
                            <i class="fas fa-{{ $brand->featured ? 'star' : 'star-o' }} mr-1"></i>
                            {{ $brand->featured ? 'Featured' : 'Regular' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $brand->nav ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' }}">
                            <i class="fas fa-{{ $brand->nav ? 'eye' : 'eye-slash' }} mr-1"></i>
                            {{ $brand->nav ? 'In Nav' : 'Hidden' }}
                        </span>
                        </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.brands.show', $brand->id) }}" 
               class="text-blue-600 hover:text-blue-900 transition-colors duration-200" 
               title="View">
                <i class="fas fa-eye"></i>
            </a>
            <a href="{{ route('admin.brands.edit', $brand->id) }}" 
               class="text-yellow-600 hover:text-yellow-900 transition-colors duration-200" 
               title="Edit">
                <i class="fas fa-edit"></i>
            </a>
            <button onclick="deleteBrand({{ $brand->id }})" 
                    class="text-red-600 hover:text-red-900 transition-colors duration-200" 
                    title="Delete">
                <i class="fas fa-trash"></i>
            </button>
                        </div>
                        </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-tags text-gray-400 text-4xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No brands found</h3>
                            <p class="text-gray-500 mb-4">Get started by creating your first brand</p>
                            <button onclick="createBrand()" class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 transition-colors duration-200">
                                <i class="fas fa-plus mr-2"></i> Add Brand
                            </button>
                        </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
        <div class="flex-1 flex justify-between sm:hidden">
            @if($brands->previousPageUrl())
                <a href="{{ $brands->previousPageUrl() }}" 
                   class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Previous
                </a>
            @else
                <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-400 bg-gray-100 cursor-not-allowed">
                    Previous
                </span>
            @endif
            
            @if($brands->nextPageUrl())
                <a href="{{ $brands->nextPageUrl() }}" 
                   class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Next
                </a>
            @else
                <span class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-400 bg-gray-100 cursor-not-allowed">
                    Next
                </span>
            @endif
        </div>
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700">
                    Showing
                    <span class="font-medium">{{ $brands->firstItem() ?? 0 }}</span>
                    to
                    <span class="font-medium">{{ $brands->lastItem() ?? 0 }}</span>
                    of
                    <span class="font-medium">{{ $brands->total() }}</span>
                    results
                </p>
            </div>
            <div>
    {{ $brands->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Create/Edit Brand Modal -->
<div id="brandModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900" id="brandModalTitle">Add Brand</h3>
                <button onclick="closeBrandModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <!-- Modal Body -->
            <form id="brandForm" class="mt-4">
                <input type="hidden" id="brandId" name="brand_id">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="brandName" class="block text-sm font-medium text-gray-700 mb-2">Brand Name *</label>
                        <input type="text" id="brandName" name="name" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    </div>
                    <div>
                        <label for="brandSlug" class="block text-sm font-medium text-gray-700 mb-2">Slug</label>
                        <input type="text" id="brandSlug" name="slug" placeholder="auto-generated"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="brandDescription" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea id="brandDescription" name="description" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"></textarea>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="brandLogo" class="block text-sm font-medium text-gray-700 mb-2">Logo</label>
                        <input type="file" id="brandLogo" name="logo" accept="image/*"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        <p class="text-xs text-gray-500 mt-1">Recommended size: 200x100px</p>
                    </div>
                    <div>
                        <label for="brandWebsite" class="block text-sm font-medium text-gray-700 mb-2">Website</label>
                        <input type="url" id="brandWebsite" name="website" placeholder="https://brand-website.com"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label for="brandStatus" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select id="brandStatus" name="is_active"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <div>
                        <label for="brandFeatured" class="block text-sm font-medium text-gray-700 mb-2">Featured</label>
                        <select id="brandFeatured" name="featured"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                            <option value="0">Regular</option>
                            <option value="1">Featured</option>
                        </select>
                    </div>
                    <div>
                        <label for="brandNav" class="block text-sm font-medium text-gray-700 mb-2">Show in Navigation</label>
                        <select id="brandNav" name="nav"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                            <option value="0">Hidden</option>
                            <option value="1">Show in Nav</option>
                        </select>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="brandMetaTitle" class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                    <input type="text" id="brandMetaTitle" name="meta_title"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                </div>
                
                <div class="mb-6">
                    <label for="brandMetaDescription" class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                    <textarea id="brandMetaDescription" name="meta_description" rows="2"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"></textarea>
                </div>
                
                <!-- Modal Footer -->
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeBrandModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors duration-200">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors duration-200">
                        Save Brand
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Create brand function
function createBrand() {
    document.getElementById('brandModalTitle').textContent = 'Add Brand';
    document.getElementById('brandForm').reset();
    document.getElementById('brandId').value = '';
    document.getElementById('brandModal').classList.remove('hidden');
}

// Close brand modal function
function closeBrandModal() {
    document.getElementById('brandModal').classList.add('hidden');
}

// View brand function
function viewBrand(brandId) {
    window.location.href = `/admin/brands/${brandId}`;
}

// View products function
function viewProducts(brandId) {
    window.location.href = `/admin/products?brand=${brandId}`;
}

// Delete brand function
function deleteBrand(brandId) {
    if (confirm('Are you sure you want to delete this brand? This action cannot be undone.')) {
        fetch(`/admin/brands/${brandId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Brand deleted successfully!');
                location.reload();
            } else {
                alert('Error: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting brand');
        });
    }
}

// Export brands function
function exportBrands() {
    window.open('/admin/brands/export', '_blank');
}

// Brand form submission
document.getElementById('brandForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const brandId = formData.get('brand_id');
    const url = brandId ? `/admin/brands/${brandId}` : '/admin/brands';
    const method = brandId ? 'PUT' : 'POST';
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Saving...';
    submitBtn.disabled = true;
    
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
            alert('Brand saved successfully!');
            closeBrandModal();
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error saving brand');
    })
    .finally(() => {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    });
});

// Auto-generate slug from name
document.getElementById('brandName').addEventListener('input', function() {
    const name = this.value;
    const slug = name.toLowerCase()
        .replace(/[^a-z0-9 -]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .trim('-');
    document.getElementById('brandSlug').value = slug;
});

// Apply search function
function applySearch() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const featuredFilter = document.getElementById('featuredFilter');
    const navFilter = document.getElementById('navFilter');
    
    const params = new URLSearchParams();
    
    if (searchInput && searchInput.value) params.append('search', searchInput.value);
    if (statusFilter && statusFilter.value) params.append('status', statusFilter.value);
    if (featuredFilter && featuredFilter.value) params.append('featured', featuredFilter.value);
    if (navFilter && navFilter.value) params.append('nav', navFilter.value);
    
    const url = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
    window.location.href = url;
}

// Clear filters function
function clearFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('featuredFilter').value = '';
    document.getElementById('navFilter').value = '';
    
    // Reload page to show all brands
    window.location.href = window.location.pathname;
}

// Filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const featuredFilter = document.getElementById('featuredFilter');
    const navFilter = document.getElementById('navFilter');
    
    function applyFilters() {
        const params = new URLSearchParams();
        
        if (searchInput && searchInput.value) params.append('search', searchInput.value);
        if (statusFilter && statusFilter.value) params.append('status', statusFilter.value);
        if (featuredFilter && featuredFilter.value) params.append('featured', featuredFilter.value);
        if (navFilter && navFilter.value) params.append('nav', navFilter.value);
        
        const url = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
        window.location.href = url;
    }
    
    // Only add event listeners to dropdown filters (not search input)
    [statusFilter, featuredFilter, navFilter].forEach(element => {
        if (element) {
            element.addEventListener('change', applyFilters);
        }
    });
    
    // Search input with debounce - only trigger on Enter key or after 2 seconds of no typing
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(applyFilters, 2000); // Increased to 2 seconds
        });
        
        // Also trigger on Enter key
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                clearTimeout(searchTimeout);
                applyFilters();
            }
        });
    }
    
    // Close modal when clicking outside
    const brandModal = document.getElementById('brandModal');
    if (brandModal) {
        brandModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeBrandModal();
            }
        });
    }
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeBrandModal();
        }
    });
});
</script>
@endsection