<div class="relative" x-data="notificationDropdown()">
    <!-- Notification Bell -->
    <button @click="toggleDropdown()" class="relative p-2 text-gray-600 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-orange-500 rounded-full">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg>
        
        <!-- Notification Badge -->
        <span x-show="unreadCount > 0" 
              x-text="unreadCount" 
              class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center"
              :class="{ 'animate-pulse': unreadCount > 0 }">
        </span>
    </button>

    <!-- Dropdown Panel -->
    <div x-show="isOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         @click.away="closeDropdown()"
         class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
        
        <!-- Header -->
        <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
                <div class="flex items-center space-x-2">
                    <span x-show="unreadCount > 0" 
                          x-text="unreadCount + ' unread'" 
                          class="text-xs text-red-600 font-medium">
                    </span>
                    <button @click="markAllAsRead()" 
                            x-show="unreadCount > 0"
                            class="text-xs text-blue-600 hover:text-blue-800">
                        Mark all read
                    </button>
                </div>
            </div>
        </div>

        <!-- Notifications List -->
        <div class="max-h-96 overflow-y-auto">
            <div x-show="loading" class="p-4 text-center">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-orange-500 mx-auto"></div>
                <p class="text-sm text-gray-500 mt-2">Loading notifications...</p>
            </div>

            <div x-show="!loading && notifications.length === 0" class="p-4 text-center">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                <p class="text-sm text-gray-500">No notifications yet</p>
            </div>

            <div x-show="!loading && notifications.length > 0" class="divide-y divide-gray-200">
                <template x-for="notification in notifications" :key="notification.id">
                    <div class="p-4 hover:bg-gray-50 cursor-pointer" 
                         :class="{ 'bg-blue-50 border-l-4 border-blue-400': !notification.is_read }"
                         @click="markAsRead(notification.id)">
                        
                        <!-- Notification Content -->
                        <div class="flex items-start space-x-3">
                            <!-- Icon -->
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center"
                                     :class="getNotificationIconClass(notification.type)">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path :d="getNotificationIcon(notification.type)"></path>
                                    </svg>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900" x-text="notification.title"></p>
                                <p class="text-sm text-gray-600 mt-1" x-text="notification.message"></p>
                                
                                <!-- Time and Status -->
                                <div class="flex items-center justify-between mt-2">
                                    <span class="text-xs text-gray-500" x-text="notification.time_ago"></span>
                                    <div class="flex items-center space-x-2">
                                        <span x-show="notification.is_important" 
                                              class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Important
                                        </span>
                                        <span x-show="!notification.is_read" 
                                              class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Footer -->
        <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
            <a href="/customer/notifications" 
               class="block text-center text-sm text-blue-600 hover:text-blue-800 font-medium">
                View all notifications
            </a>
        </div>
    </div>
</div>

<script>
function notificationDropdown() {
    return {
        isOpen: false,
        loading: false,
        notifications: [],
        unreadCount: 0,

        init() {
            this.loadNotifications();
            this.loadUnreadCount();
            
            // Refresh notifications every 30 seconds
            setInterval(() => {
                this.loadUnreadCount();
            }, 30000);
        },

        toggleDropdown() {
            this.isOpen = !this.isOpen;
            if (this.isOpen) {
                this.loadNotifications();
            }
        },

        closeDropdown() {
            this.isOpen = false;
        },

        async loadNotifications() {
            this.loading = true;
            try {
                const response = await fetch('/api/notifications/recent');
                const data = await response.json();
                
                if (data.success) {
                    this.notifications = data.notifications;
                    this.unreadCount = data.unread_count;
                }
            } catch (error) {
                console.error('Error loading notifications:', error);
            } finally {
                this.loading = false;
            }
        },

        async loadUnreadCount() {
            try {
                const response = await fetch('/api/notifications/count');
                const data = await response.json();
                
                if (data.success) {
                    this.unreadCount = data.unread_count;
                }
            } catch (error) {
                console.error('Error loading notification count:', error);
            }
        },

        async markAsRead(notificationId) {
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
                    // Update local state
                    const notification = this.notifications.find(n => n.id === notificationId);
                    if (notification) {
                        notification.is_read = true;
                    }
                    this.unreadCount = Math.max(0, this.unreadCount - 1);
                }
            } catch (error) {
                console.error('Error marking notification as read:', error);
            }
        },

        async markAllAsRead() {
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
                    // Update local state
                    this.notifications.forEach(notification => {
                        notification.is_read = true;
                    });
                    this.unreadCount = 0;
                }
            } catch (error) {
                console.error('Error marking all notifications as read:', error);
            }
        },

        getNotificationIcon(type) {
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
                'promotional': 'M7 4V2a1 1 0 011-1h4a1 1 0 011 1v2m-5 0h6m-6 0a2 2 0 00-2 2v10a2 2 0 002 2h6a2 2 0 002-2V6a2 2 0 00-2-2M9 8h6m-6 4h6m-6 4h4'
            };
            return icons[type] || icons['welcome'];
        },

        getNotificationIconClass(type) {
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
    }
}
</script>

