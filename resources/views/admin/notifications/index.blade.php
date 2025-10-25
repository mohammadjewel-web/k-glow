@extends('layouts.admin')

@section('title', 'Notification Management')
@section('page-title', 'Notifications')

@section('content')
<style>
    .btn-add-notification {
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
    
    .btn-add-notification:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.05);
        color: white;
        text-decoration: none;
    }
    
    .btn-export-notification {
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
    
    .btn-export-notification:hover {
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
            <h1 class="text-3xl font-bold mb-2">Notification Management</h1>
            <p class="text-orange-100">Manage and send notifications to users</p>
        </div>
        <div class="flex space-x-3">
            <button class="btn-export-notification" onclick="exportNotifications()">
                <i class="fas fa-download mr-2"></i> Export Notifications
            </button>
            <button class="btn-add-notification" onclick="createNotification()">
                <i class="fas fa-plus mr-2"></i> Send Notification
            </button>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <div class="flex items-center">
            <div class="bg-blue-100 p-3 rounded-lg">
                <i class="fas fa-bell text-blue-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Notifications</p>
                <p class="text-2xl font-bold text-gray-900">{{ $notifications->total() }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <div class="flex items-center">
            <div class="bg-green-100 p-3 rounded-lg">
                <i class="fas fa-check-circle text-green-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Sent</p>
                <p class="text-2xl font-bold text-gray-900">{{ $notifications->where('is_sent', true)->count() }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <div class="flex items-center">
            <div class="bg-yellow-100 p-3 rounded-lg">
                <i class="fas fa-clock text-yellow-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Pending</p>
                <p class="text-2xl font-bold text-gray-900">{{ $notifications->where('is_sent', false)->count() }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <div class="flex items-center">
            <div class="bg-purple-100 p-3 rounded-lg">
                <i class="fas fa-envelope text-purple-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Unread</p>
                <p class="text-2xl font-bold text-gray-900">{{ $notifications->where('is_read', false)->count() }}</p>
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

    <form method="GET" action="{{ route('admin.notifications.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
            <div class="flex">
                <input type="text" id="search" name="search" placeholder="Search notifications..." 
                       class="flex-1 px-3 py-2 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                       value="{{ request('search') }}">
                <button type="submit" 
                        class="px-4 py-2 bg-orange-600 text-white rounded-r-lg hover:bg-orange-700 transition-colors duration-200">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
            <select id="type" name="type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                <option value="">All Types</option>
                <option value="order" {{ request('type') == 'order' ? 'selected' : '' }}>Order</option>
                <option value="payment" {{ request('type') == 'payment' ? 'selected' : '' }}>Payment</option>
                <option value="system" {{ request('type') == 'system' ? 'selected' : '' }}>System</option>
                <option value="promotion" {{ request('type') == 'promotion' ? 'selected' : '' }}>Promotion</option>
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
            <select id="status" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                <option value="">All Status</option>
                <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Read</option>
                <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Unread</option>
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Channel</label>
            <select id="channel" name="channel" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                <option value="">All Channels</option>
                <option value="email" {{ request('channel') == 'email' ? 'selected' : '' }}>Email</option>
                <option value="sms" {{ request('channel') == 'sms' ? 'selected' : '' }}>SMS</option>
                <option value="push" {{ request('channel') == 'push' ? 'selected' : '' }}>Push</option>
                <option value="in_app" {{ request('channel') == 'in_app' ? 'selected' : '' }}>In-App</option>
            </select>
        </div>
    </form>
</div>

<!-- Notifications Table -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-orange-600 focus:ring-orange-500">
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <i class="fas fa-bell mr-1"></i> Notification
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <i class="fas fa-user mr-1"></i> Recipient
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <i class="fas fa-tag mr-1"></i> Type
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <i class="fas fa-broadcast-tower mr-1"></i> Channel
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <i class="fas fa-toggle-on mr-1"></i> Status
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <i class="fas fa-calendar mr-1"></i> Date
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <i class="fas fa-cog mr-1"></i> Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($notifications as $notification)
                <tr class="hover:bg-gray-50 transition-colors duration-200 {{ $notification->is_read ? '' : 'bg-yellow-50' }}">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" class="notification-checkbox rounded border-gray-300 text-orange-600 focus:ring-orange-500" value="{{ $notification->id }}">
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                @if($notification->is_important)
                                    <i class="fas fa-exclamation-triangle text-red-500 text-lg"></i>
                                @else
                                    <i class="fas fa-bell text-gray-400 text-lg"></i>
                                @endif
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900">{{ $notification->title }}</div>
                                <div class="text-sm text-gray-500 mt-1">{{ Str::limit($notification->message, 100) }}</div>
                                @if($notification->is_important)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 mt-1">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>Important
                                    </span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($notification->user)
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-user text-gray-400 text-sm"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $notification->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $notification->user->email }}</div>
                                </div>
                            </div>
                        @else
                            <span class="text-gray-500">All Users</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ $notification->type == 'order' ? 'bg-blue-100 text-blue-800' : 
                               ($notification->type == 'payment' ? 'bg-green-100 text-green-800' : 
                               ($notification->type == 'system' ? 'bg-yellow-100 text-yellow-800' : 
                               ($notification->type == 'promotion' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800'))) 
                            }}">
                            <i class="fas fa-{{ $notification->type == 'order' ? 'shopping-cart' : 
                                ($notification->type == 'payment' ? 'credit-card' : 
                                ($notification->type == 'system' ? 'cog' : 
                                ($notification->type == 'promotion' ? 'gift' : 'bell'))) }} mr-1"></i>
                            {{ ucfirst($notification->type) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ $notification->channel == 'email' ? 'bg-blue-100 text-blue-800' : 
                               ($notification->channel == 'sms' ? 'bg-green-100 text-green-800' : 
                               ($notification->channel == 'push' ? 'bg-yellow-100 text-yellow-800' : 
                               ($notification->channel == 'in_app' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800'))) 
                            }}">
                            <i class="fas fa-{{ $notification->channel == 'email' ? 'envelope' : 
                                ($notification->channel == 'sms' ? 'sms' : 
                                ($notification->channel == 'push' ? 'mobile-alt' : 
                                ($notification->channel == 'in_app' ? 'bell' : 'bell'))) }} mr-1"></i>
                            {{ ucfirst($notification->channel) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex flex-col space-y-1">
                            @if($notification->is_sent)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-1"></i>Sent
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i>Pending
                                </span>
                            @endif
                            
                            @if($notification->is_read)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-eye mr-1"></i>Read
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <i class="fas fa-eye-slash mr-1"></i>Unread
                                </span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $notification->created_at->format('M d, Y g:i A') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center space-x-2">
                            <button onclick="viewNotification({{ $notification->id }})" 
                                    class="text-blue-600 hover:text-blue-900 transition-colors duration-200" 
                                    title="View">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button onclick="editNotification({{ $notification->id }})" 
                                    class="text-yellow-600 hover:text-yellow-900 transition-colors duration-200" 
                                    title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="deleteNotification({{ $notification->id }})" 
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
                        <div class="flex flex-col items-center">
                            <i class="fas fa-bell text-gray-400 text-4xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No notifications found</h3>
                            <p class="text-gray-500 mb-4">Get started by sending your first notification</p>
                            <button onclick="createNotification()" class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 transition-colors duration-200">
                                <i class="fas fa-plus mr-2"></i> Send Notification
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
            @if($notifications->previousPageUrl())
                <a href="{{ $notifications->previousPageUrl() }}" 
                   class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Previous
                </a>
            @else
                <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-400 bg-gray-100 cursor-not-allowed">
                    Previous
                </span>
            @endif
            
            @if($notifications->nextPageUrl())
                <a href="{{ $notifications->nextPageUrl() }}" 
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
                    <span class="font-medium">{{ $notifications->firstItem() ?? 0 }}</span>
                    to
                    <span class="font-medium">{{ $notifications->lastItem() ?? 0 }}</span>
                    of
                    <span class="font-medium">{{ $notifications->total() }}</span>
                    results
                </p>
            </div>
            <div>
                {{ $notifications->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Create Notification Modal -->
<div id="createNotificationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Send New Notification</h3>
                <button onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="createNotificationForm">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                        <input type="text" name="title" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Message *</label>
                        <textarea name="message" required rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"></textarea>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                            <select name="type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                                <option value="order">Order</option>
                                <option value="payment">Payment</option>
                                <option value="system">System</option>
                                <option value="promotion">Promotion</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Channel</label>
                            <select name="channel" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                                <option value="email">Email</option>
                                <option value="sms">SMS</option>
                                <option value="push">Push</option>
                                <option value="in_app">In-App</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="is_important" id="is_important" class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded">
                        <label for="is_important" class="ml-2 block text-sm text-gray-900">Mark as Important</label>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeCreateModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700">
                        Send Notification
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Create notification function
function createNotification() {
    document.getElementById('createNotificationModal').classList.remove('hidden');
}

// Close create modal
function closeCreateModal() {
    document.getElementById('createNotificationModal').classList.add('hidden');
}

// View notification function
function viewNotification(notificationId) {
    // Implementation for viewing notification details
    alert('View notification: ' + notificationId);
}

// Edit notification function
function editNotification(notificationId) {
    // Implementation for editing notification
    alert('Edit notification: ' + notificationId);
}

// Delete notification function
function deleteNotification(notificationId) {
    if (confirm('Are you sure you want to delete this notification?')) {
        fetch(`/admin/notifications/${notificationId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Notification deleted successfully!');
                location.reload();
            } else {
                alert('Error: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting notification');
        });
    }
}

// Export notifications function
function exportNotifications() {
    window.open('/admin/notifications/export', '_blank');
}

// Clear filters function
function clearFilters() {
    document.getElementById('search').value = '';
    document.getElementById('type').value = '';
    document.getElementById('status').value = '';
    document.getElementById('channel').value = '';
    
    // Reload page to show all notifications
    window.location.href = window.location.pathname;
}

// Filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const typeFilter = document.getElementById('type');
    const statusFilter = document.getElementById('status');
    const channelFilter = document.getElementById('channel');
    
    // Only add event listeners to dropdown filters (not search input)
    [typeFilter, statusFilter, channelFilter].forEach(element => {
        if (element) {
            element.addEventListener('change', function() {
                this.form.submit();
            });
        }
    });
    
    // Search input - only trigger on Enter key (no auto-reload)
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                this.form.submit();
            }
        });
    }
    
    // Close modal when clicking outside
    const createModal = document.getElementById('createNotificationModal');
    if (createModal) {
        createModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeCreateModal();
            }
        });
    }
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeCreateModal();
        }
    });
    
    // Form submission
    document.getElementById('createNotificationForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('/admin/notifications', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Notification sent successfully!');
                closeCreateModal();
                location.reload();
            } else {
                alert('Error: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error sending notification');
        });
    });
});
</script>
@endsection