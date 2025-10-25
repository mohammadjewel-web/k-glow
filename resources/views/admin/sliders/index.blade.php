@extends('layouts.admin')

@section('title', 'Slider Management')
@section('page-title', 'Sliders')

@section('content')
<style>
    .btn-add-slider {
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
    
    .btn-add-slider:hover {
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
            <h1 class="text-3xl font-bold mb-2">Homepage Sliders</h1>
            <p class="text-orange-100">Manage your homepage slider images and content</p>
        </div>
        <div>
            <a href="{{ route('admin.sliders.create') }}" class="btn-add-slider">
                <i class="fas fa-plus mr-2"></i> Add New Slider
            </a>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <div class="flex items-center">
            <div class="bg-blue-100 p-3 rounded-lg">
                <i class="fas fa-images text-blue-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Sliders</p>
                <p class="text-2xl font-bold text-gray-900">{{ $sliders->count() }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <div class="flex items-center">
            <div class="bg-green-100 p-3 rounded-lg">
                <i class="fas fa-check-circle text-green-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Active Sliders</p>
                <p class="text-2xl font-bold text-gray-900">{{ $sliders->where('is_active', true)->count() }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <div class="flex items-center">
            <div class="bg-orange-100 p-3 rounded-lg">
                <i class="fas fa-sort text-orange-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Display Order</p>
                <p class="text-2xl font-bold text-gray-900">{{ $sliders->count() > 0 ? $sliders->min('order') . ' - ' . $sliders->max('order') : '0' }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Success/Error Messages -->
@if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg flex items-center" role="alert">
        <i class="fas fa-check-circle mr-3 text-xl"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg flex items-center" role="alert">
        <i class="fas fa-exclamation-circle mr-3 text-xl"></i>
        <span>{{ session('error') }}</span>
    </div>
@endif

<!-- Sliders Table -->
<div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-bold text-gray-900">All Sliders</h2>
        <p class="text-sm text-gray-600 mt-1">Manage and organize your homepage sliders</p>
    </div>
    
    @if($sliders->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Order</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Image</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Title & Description</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Button</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-medium text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($sliders as $slider)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                {{ $slider->order }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <img src="{{ asset($slider->image) }}" 
                                 alt="{{ $slider->title }}" 
                                 class="h-16 w-28 object-cover rounded-lg shadow-sm border border-gray-200">
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                @if($slider->title)
                                <span class="font-semibold text-gray-900">{{ $slider->title }}</span>
                                @else
                                <span class="text-sm text-gray-400 italic">No title</span>
                                @endif
                                @if($slider->description)
                                <span class="text-sm text-gray-600 mt-1">{{ Str::limit($slider->description, 60) }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($slider->button_text)
                            <div class="flex flex-col">
                                <span class="text-sm font-medium text-gray-900">{{ $slider->button_text }}</span>
                                @if($slider->button_link)
                                <a href="{{ $slider->button_link }}" target="_blank" class="text-xs text-blue-600 hover:text-blue-800 mt-1 truncate max-w-xs">
                                    <i class="fas fa-external-link-alt mr-1"></i>{{ Str::limit($slider->button_link, 30) }}
                                </a>
                                @endif
                            </div>
                            @else
                            <span class="text-sm text-gray-400">No button</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <button onclick="toggleStatus({{ $slider->id }})" 
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium transition-colors {{ $slider->is_active ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}" 
                                    id="status-btn-{{ $slider->id }}">
                                <i class="fas fa-{{ $slider->is_active ? 'check' : 'times' }} mr-1"></i>
                                <span id="status-text-{{ $slider->id }}">
                                    {{ $slider->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </button>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route('admin.sliders.edit', $slider) }}" 
                                   class="inline-flex items-center px-3 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors"
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.sliders.destroy', $slider) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Are you sure you want to delete this slider?')"
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors"
                                            title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-16">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                <i class="fas fa-images fa-2x text-gray-400"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No sliders found</h3>
            <p class="text-gray-600 mb-6">Create your first homepage slider to get started</p>
            <a href="{{ route('admin.sliders.create') }}" class="inline-flex items-center px-6 py-3 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors">
                <i class="fas fa-plus mr-2"></i> Add New Slider
            </a>
        </div>
    @endif
</div>

<!-- Tips Card -->
<div class="mt-8 bg-blue-50 border border-blue-200 rounded-xl p-6">
    <div class="flex items-start">
        <div class="flex-shrink-0">
            <i class="fas fa-lightbulb text-blue-600 text-2xl"></i>
        </div>
        <div class="ml-4">
            <h3 class="text-lg font-semibold text-blue-900 mb-2">Pro Tips</h3>
            <ul class="space-y-2 text-sm text-blue-800">
                <li class="flex items-start">
                    <i class="fas fa-check-circle mr-2 mt-1 text-blue-600"></i>
                    <span>Use high-quality images with recommended size of 1920x600px for best results</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle mr-2 mt-1 text-blue-600"></i>
                    <span>Lower order numbers appear first - use 0, 1, 2, etc. to control sequence</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle mr-2 mt-1 text-blue-600"></i>
                    <span>Toggle status to show/hide sliders without deleting them</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle mr-2 mt-1 text-blue-600"></i>
                    <span>Add button text and link to create compelling call-to-action elements</span>
                </li>
            </ul>
        </div>
    </div>
</div>

<script>
function toggleStatus(sliderId) {
    const btn = document.getElementById(`status-btn-${sliderId}`);
    const textSpan = document.getElementById(`status-text-${sliderId}`);
    const originalHTML = btn.innerHTML;
    
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Loading...';
    
    fetch(`/admin/sliders/${sliderId}/toggle`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const isActive = data.is_active;
            btn.className = `inline-flex items-center px-3 py-1 rounded-full text-sm font-medium transition-colors ${isActive ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-gray-100 text-gray-800 hover:bg-gray-200'}`;
            btn.innerHTML = `<i class="fas fa-${isActive ? 'check' : 'times'} mr-1"></i><span id="status-text-${sliderId}">${isActive ? 'Active' : 'Inactive'}</span>`;
            
            // Show success message
            showNotification('Status updated successfully!', 'success');
        } else {
            btn.innerHTML = originalHTML;
            showNotification('Error: ' + data.message, 'error');
        }
        btn.disabled = false;
    })
    .catch(error => {
        btn.innerHTML = originalHTML;
        btn.disabled = false;
        showNotification('Failed to update status', 'error');
    });
}

function showNotification(message, type) {
    const bgColor = type === 'success' ? 'bg-green-100 border-green-500 text-green-700' : 'bg-red-100 border-red-500 text-red-700';
    const icon = type === 'success' ? 'check-circle' : 'exclamation-circle';
    
    const alert = document.createElement('div');
    alert.className = `${bgColor} border-l-4 p-4 mb-6 rounded-lg flex items-center fixed top-4 right-4 z-50 shadow-lg`;
    alert.innerHTML = `
        <i class="fas fa-${icon} mr-3 text-xl"></i>
        <span>${message}</span>
    `;
    
    document.body.appendChild(alert);
    
    setTimeout(() => {
        alert.remove();
    }, 3000);
}
</script>
@endsection
