@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Notifications</h1>
            <p class="text-gray-600 mt-2">Stay updated with your orders and important information</p>
        </div>

        <!-- Filter Tabs -->
        <div class="mb-6">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8">
                    <button onclick="filterNotifications('all')" 
                            class="filter-tab py-2 px-1 border-b-2 font-medium text-sm active" 
                            data-filter="all">
                        All Notifications
                        <span id="allCount" class="ml-2 bg-gray-100 text-gray-900 py-0.5 px-2.5 rounded-full text-xs">0</span>
                    </button>
                    <button onclick="filterNotifications('unread')" 
                            class="filter-tab py-2 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-sm" 
                            data-filter="unread">
                        Unread
                        <span id="unreadCount" class="ml-2 bg-red-100 text-red-900 py-0.5 px-2.5 rounded-full text-xs">0</span>
                    </button>
                    <button onclick="filterNotifications('important')" 
                            class="filter-tab py-2 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-sm" 
                            data-filter="important">
                        Important
                        <span id="importantCount" class="ml-2 bg-orange-100 text-orange-900 py-0.5 px-2.5 rounded-full text-xs">0</span>
                    </button>
                </nav>
            </div>
        </div>

        <!-- Actions Bar -->
        <div class="mb-6 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <button onclick="markAllAsRead()" 
                        id="markAllReadBtn"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                        disabled>
                    Mark All as Read
                </button>
                <button onclick="deleteAllRead()" 
                        id="deleteAllReadBtn"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                        disabled>
                    Delete All Read
                </button>
            </div>
            
            <div class="flex items-center space-x-2">
                <select id="typeFilter" onchange="filterByType()" 
                        class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                    <option value="">All Types</option>
                    <option value="order_confirmed">Order Confirmed</option>
                    <option value="order_shipped">Order Shipped</option>
                    <option value="order_delivered">Order Delivered</option>
                    <option value="order_cancelled">Order Cancelled</option>
                    <option value="payment_confirmed">Payment Confirmed</option>
                    <option value="product_back_in_stock">Back in Stock</option>
                    <option value="price_drop">Price Drop</option>
                    <option value="welcome">Welcome</option>
                    <option value="maintenance">Maintenance</option>
                    <option value="promotional">Promotional</option>
                </select>
            </div>
        </div>

        <!-- Notifications List -->
        <div class="space-y-4">
            <!-- Loading State -->
            <div id="loadingState" class="text-center py-8">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-orange-500 mx-auto"></div>
                <p class="text-gray-500 mt-2">Loading notifications...</p>
            </div>

            <!-- Empty State -->
            <div id="emptyState" class="text-center py-12 hidden">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No notifications</h3>
                <p class="text-gray-500">You don't have any notifications yet.</p>
            </div>

            <!-- Notifications Container -->
            <div id="notificationsContainer" class="space-y-4">
                <!-- Notifications will be loaded here -->
            </div>

            <!-- Load More Button -->
            <div id="loadMoreContainer" class="text-center py-4 hidden">
                <button onclick="loadMoreNotifications()" 
                        id="loadMoreBtn"
                        class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    Load More
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.filter-tab.active {
    border-color: #f36c21;
    color: #f36c21;
}

.notification-item {
    transition: all 0.2s ease;
}

.notification-item:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.notification-unread {
    border-left: 4px solid #3b82f6;
    background-color: #f8fafc;
}

.notification-important {
    border-left: 4px solid #f59e0b;
    background-color: #fffbeb;
}
</style>

<script>
let currentPage = 1;
let currentFilter = 'all';
let currentType = '';
let hasMorePages = true;
let isLoading = false;

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    loadNotifications();
    updateCounts();
});

// Load notifications
async function loadNotifications(reset = false) {
    if (isLoading) return;
    
    isLoading = true;
    
    if (reset) {
        currentPage = 1;
        hasMorePages = true;
        document.getElementById('notificationsContainer').innerHTML = '';
    }
    
    const params = new URLSearchParams({
        page: currentPage,
        per_page: 10,
        ...(currentFilter !== 'all' && { [currentFilter]: true }),
        ...(currentType && { type: currentType })
    });
    
    try {
        const response = await fetch(`/api/notifications?${params}`);
        const data = await response.json();
        
        if (data.success) {
            if (reset) {
                document.getElementById('notificationsContainer').innerHTML = '';
            }
            
            if (data.notifications.length === 0 && currentPage === 1) {
                showEmptyState();
            } else {
                hideEmptyState();
                renderNotifications(data.notifications);
                
                // Update pagination
                hasMorePages = data.pagination.current_page < data.pagination.last_page;
                updateLoadMoreButton();
            }
            
            updateCounts(data);
        }
    } catch (error) {
        console.error('Error loading notifications:', error);
        showError('Failed to load notifications');
    } finally {
        isLoading = false;
        hideLoadingState();
    }
}

// Render notifications
function renderNotifications(notifications) {
    const container = document.getElementById('notificationsContainer');
    
    notifications.forEach(notification => {
        const notificationElement = createNotificationElement(notification);
        container.appendChild(notificationElement);
    });
}

// Create notification element
function createNotificationElement(notification) {
    const div = document.createElement('div');
    div.className = `notification-item bg-white rounded-lg border border-gray-200 p-4 cursor-pointer ${!notification.is_read ? 'notification-unread' : ''} ${notification.is_important ? 'notification-important' : ''}`;
    div.onclick = () => markAsRead(notification.id);
    
    const iconClass = getNotificationIconClass(notification.type);
    const icon = getNotificationIcon(notification.type);
    
    div.innerHTML = `
        <div class="flex items-start space-x-3">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 rounded-full flex items-center justify-center ${iconClass}">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="${icon}"></path>
                    </svg>
                </div>
            </div>
            
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-medium text-gray-900">${notification.title}</h3>
                    <div class="flex items-center space-x-2">
                        ${notification.is_important ? '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">Important</span>' : ''}
                        ${!notification.is_read ? '<div class="w-2 h-2 bg-blue-500 rounded-full"></div>' : ''}
                    </div>
                </div>
                
                <p class="text-sm text-gray-600 mt-1">${notification.message}</p>
                
                <div class="flex items-center justify-between mt-3">
                    <span class="text-xs text-gray-500">${notification.time_ago}</span>
                    <div class="flex items-center space-x-2">
                        <button onclick="event.stopPropagation(); deleteNotification(${notification.id})" 
                                class="text-red-600 hover:text-red-800 text-xs">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    return div;
}

// Filter notifications
function filterNotifications(filter) {
    currentFilter = filter;
    currentPage = 1;
    
    // Update active tab
    document.querySelectorAll('.filter-tab').forEach(tab => {
        tab.classList.remove('active', 'border-orange-500', 'text-orange-600');
        tab.classList.add('border-transparent', 'text-gray-500');
    });
    
    const activeTab = document.querySelector(`[data-filter="${filter}"]`);
    activeTab.classList.add('active', 'border-orange-500', 'text-orange-600');
    activeTab.classList.remove('border-transparent', 'text-gray-500');
    
    loadNotifications(true);
}

// Filter by type
function filterByType() {
    currentType = document.getElementById('typeFilter').value;
    currentPage = 1;
    loadNotifications(true);
}

// Mark as read
async function markAsRead(notificationId) {
    try {
        const response = await fetch(`/api/notifications/${notificationId}/read`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        if (data.success) {
            // Update UI
            const notificationElement = document.querySelector(`[onclick*="${notificationId}"]`);
            if (notificationElement) {
                notificationElement.classList.remove('notification-unread');
                notificationElement.querySelector('.bg-blue-500').remove();
            }
            updateCounts();
        }
    } catch (error) {
        console.error('Error marking notification as read:', error);
    }
}

// Mark all as read
async function markAllAsRead() {
    try {
        const response = await fetch('/api/notifications/read-all', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        if (data.success) {
            // Update UI
            document.querySelectorAll('.notification-unread').forEach(el => {
                el.classList.remove('notification-unread');
            });
            document.querySelectorAll('.bg-blue-500').forEach(el => {
                el.remove();
            });
            updateCounts();
        }
    } catch (error) {
        console.error('Error marking all notifications as read:', error);
    }
}

// Delete notification
async function deleteNotification(notificationId) {
    if (!confirm('Are you sure you want to delete this notification?')) return;
    
    try {
        const response = await fetch(`/api/notifications/${notificationId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        if (data.success) {
            // Remove from UI
            const notificationElement = document.querySelector(`[onclick*="${notificationId}"]`);
            if (notificationElement) {
                notificationElement.remove();
            }
            updateCounts();
        }
    } catch (error) {
        console.error('Error deleting notification:', error);
    }
}

// Delete all read notifications
async function deleteAllRead() {
    if (!confirm('Are you sure you want to delete all read notifications?')) return;
    
    // This would need to be implemented in the backend
    alert('This feature will be implemented in the backend');
}

// Load more notifications
function loadMoreNotifications() {
    if (hasMorePages && !isLoading) {
        currentPage++;
        loadNotifications();
    }
}

// Update counts
async function updateCounts(data = null) {
    if (!data) {
        try {
            const response = await fetch('/api/notifications/count');
            data = await response.json();
        } catch (error) {
            console.error('Error loading counts:', error);
            return;
        }
    }
    
    if (data.success) {
        document.getElementById('allCount').textContent = data.total_count || 0;
        document.getElementById('unreadCount').textContent = data.unread_count || 0;
        document.getElementById('importantCount').textContent = data.important_count || 0;
        
        // Update button states
        document.getElementById('markAllReadBtn').disabled = data.unread_count === 0;
        document.getElementById('deleteAllReadBtn').disabled = (data.total_count - data.unread_count) === 0;
    }
}

// Update load more button
function updateLoadMoreButton() {
    const container = document.getElementById('loadMoreContainer');
    const button = document.getElementById('loadMoreBtn');
    
    if (hasMorePages) {
        container.classList.remove('hidden');
        button.disabled = isLoading;
        button.textContent = isLoading ? 'Loading...' : 'Load More';
    } else {
        container.classList.add('hidden');
    }
}

// Show/hide states
function showLoadingState() {
    document.getElementById('loadingState').classList.remove('hidden');
    document.getElementById('notificationsContainer').classList.add('hidden');
    document.getElementById('emptyState').classList.add('hidden');
}

function hideLoadingState() {
    document.getElementById('loadingState').classList.add('hidden');
    document.getElementById('notificationsContainer').classList.remove('hidden');
}

function showEmptyState() {
    document.getElementById('emptyState').classList.remove('hidden');
    document.getElementById('notificationsContainer').classList.add('hidden');
}

function hideEmptyState() {
    document.getElementById('emptyState').classList.add('hidden');
    document.getElementById('notificationsContainer').classList.remove('hidden');
}

function showError(message) {
    alert(message);
}

// Notification icons and classes
function getNotificationIcon(type) {
    const icons = {
        'order_confirmed': 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
        'order_shipped': 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
        'order_delivered': 'M5 13l4 4L19 7',
        'order_cancelled': 'M6 18L18 6M6 6l12 12',
        'payment_confirmed': 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1',
        'product_back_in_stock': 'M5 13l4 4L19 7',
        'price_drop': 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6',
        'welcome': 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
        'maintenance': 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z',
        'promotional': 'M7 4V2a1 1 0 011-1h4a1 1 0 011 1v2m-5 0a2 2 0 00-2 2v10a2 2 0 002 2h6a2 2 0 002-2V6a2 2 0 00-2-2M9 8h6m-6 4h6m-6 4h4'
    };
    return icons[type] || icons['welcome'];
}

function getNotificationIconClass(type) {
    const classes = {
        'order_confirmed': 'bg-green-100 text-green-600',
        'order_shipped': 'bg-blue-100 text-blue-600',
        'order_delivered': 'bg-green-100 text-green-600',
        'order_cancelled': 'bg-red-100 text-red-600',
        'payment_confirmed': 'bg-green-100 text-green-600',
        'product_back_in_stock': 'bg-blue-100 text-blue-600',
        'price_drop': 'bg-orange-100 text-orange-600',
        'welcome': 'bg-purple-100 text-purple-600',
        'maintenance': 'bg-yellow-100 text-yellow-600',
        'promotional': 'bg-pink-100 text-pink-600'
    };
    return classes[type] || 'bg-gray-100 text-gray-600';
}
</script>
@endsection

