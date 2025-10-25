@extends('layouts.admin')

@section('title', 'Slogan Management')
@section('page-title', 'Slogans')

@section('content')
<!-- Header Section -->
<div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl shadow-lg mb-8 p-6 text-white">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold mb-2">Homepage Slogans</h1>
            <p class="text-orange-100">Manage rotating slogans displayed on your homepage</p>
        </div>
        <div>
            <a href="{{ route('admin.slogans.create') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg font-medium transition-all duration-300 hover:scale-105">
                <i class="fas fa-plus mr-2"></i> Add New Slogan
            </a>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <div class="flex items-center">
            <div class="bg-blue-100 p-3 rounded-lg">
                <i class="fas fa-quote-left text-blue-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Slogans</p>
                <p class="text-2xl font-bold text-gray-900">{{ $slogans->count() }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <div class="flex items-center">
            <div class="bg-green-100 p-3 rounded-lg">
                <i class="fas fa-check-circle text-green-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Active Slogans</p>
                <p class="text-2xl font-bold text-gray-900">{{ $slogans->where('is_active', true)->count() }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <div class="flex items-center">
            <div class="bg-purple-100 p-3 rounded-lg">
                <i class="fas fa-sync text-purple-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Rotation</p>
                <p class="text-2xl font-bold text-gray-900">4s</p>
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

<!-- Slogans Table -->
<div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-bold text-gray-900">All Slogans</h2>
        <p class="text-sm text-gray-600 mt-1">Slogans will rotate automatically on the homepage every 4 seconds</p>
    </div>
    
    @if($slogans->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Order</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Slogan Text</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-medium text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($slogans as $slogan)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                {{ $slogan->order }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <i class="fas fa-quote-left text-orange-400 mr-3"></i>
                                <span class="font-medium text-gray-900">{{ $slogan->text }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <button onclick="toggleStatus({{ $slogan->id }})" 
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium transition-colors {{ $slogan->is_active ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}" 
                                    id="status-btn-{{ $slogan->id }}">
                                <i class="fas fa-{{ $slogan->is_active ? 'check' : 'times' }} mr-1"></i>
                                <span id="status-text-{{ $slogan->id }}">
                                    {{ $slogan->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </button>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route('admin.slogans.edit', $slogan) }}" 
                                   class="inline-flex items-center px-3 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors"
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.slogans.destroy', $slogan) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Are you sure you want to delete this slogan?')"
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
                <i class="fas fa-quote-left fa-2x text-gray-400"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No slogans found</h3>
            <p class="text-gray-600 mb-6">Create your first slogan to display on homepage</p>
            <a href="{{ route('admin.slogans.create') }}" class="inline-flex items-center px-6 py-3 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors">
                <i class="fas fa-plus mr-2"></i> Add New Slogan
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
            <h3 class="text-lg font-semibold text-blue-900 mb-2">How It Works</h3>
            <ul class="space-y-2 text-sm text-blue-800">
                <li class="flex items-start">
                    <i class="fas fa-check-circle mr-2 mt-1 text-blue-600"></i>
                    <span>Slogans rotate automatically on the homepage every 4 seconds with smooth animation</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle mr-2 mt-1 text-blue-600"></i>
                    <span>Only active slogans are displayed - toggle status to show/hide without deleting</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle mr-2 mt-1 text-blue-600"></i>
                    <span>Lower order numbers appear first - use 0, 1, 2, etc. to control sequence</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle mr-2 mt-1 text-blue-600"></i>
                    <span>Keep slogans short, compelling, and customer-focused for best impact</span>
                </li>
            </ul>
        </div>
    </div>
</div>

<script>
function toggleStatus(sloganId) {
    const btn = document.getElementById(`status-btn-${sloganId}`);
    const textSpan = document.getElementById(`status-text-${sloganId}`);
    const originalHTML = btn.innerHTML;
    
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Loading...';
    
    fetch(`/admin/slogans/${sloganId}/toggle`, {
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
            btn.innerHTML = `<i class="fas fa-${isActive ? 'check' : 'times'} mr-1"></i><span id="status-text-${sloganId}">${isActive ? 'Active' : 'Inactive'}</span>`;
            
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
