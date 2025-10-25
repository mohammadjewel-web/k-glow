<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    @php
        use App\Models\Setting;
        $siteName = Setting::get('site_name', 'K-Glow');
        $primaryColor = Setting::get('primary_color', '#f36c21');
        $favicon = Setting::get('favicon', 'favicon.ico');
        $logo = Setting::get('logo', 'admin-assets/logo.png');
        $whiteLogo = Setting::get('white_logo', 'admin-assets/white-logo.png');
    @endphp
    
    <title>@yield('title', 'Admin Dashboard') - {{ $siteName }}</title>
    <link rel="icon" href="{{ asset($favicon) }}" type="image/x-icon" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --brand-orange: {{ $primaryColor }};
        }
        
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: linear-gradient(145deg, #f36c21 0%, #ff6b35 25%, #e55a1a 50%, #d44a0a 75%, #c23a00 100%);
            box-shadow: 4px 0 20px rgba(243, 108, 33, 0.3);
            transition: all 0.3s ease;
            z-index: 1000;
        }
        
        .sidebar.collapsed {
            width: 70px;
        }
        
        .main-content {
            margin-left: 250px;
            min-height: 100vh;
            background-color: #f8f9fc;
            transition: all 0.3s ease;
        }
        
        .main-content.expanded {
            margin-left: 70px;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
            position: relative;
            margin: 2px 8px;
            border-radius: 8px;
        }
        
        .nav-link:hover {
            background: linear-gradient(90deg, rgba(255, 255, 255, 0.15) 0%, rgba(255, 255, 255, 0.05) 100%);
            color: white;
            border-left-color: #ffffff;
            transform: translateX(4px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .nav-link.active {
            background: linear-gradient(90deg, rgba(255, 255, 255, 0.2) 0%, rgba(255, 255, 255, 0.1) 100%);
            color: white;
            border-left-color: #ffffff;
            transform: translateX(4px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }
        
        .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: linear-gradient(180deg, #ffffff 0%, rgba(255, 255, 255, 0.3) 100%);
            border-radius: 0 2px 2px 0;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .nav-link:hover::before,
        .nav-link.active::before {
            opacity: 1;
        }
        
        .nav-text {
            transition: opacity 0.3s ease;
        }
        
        .sidebar.collapsed .nav-text {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }
        
        .sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 0.75rem;
        }
        
        .sidebar.collapsed .nav-link i {
            margin-right: 0;
        }
        
        .sidebar.collapsed .nav-text {
            display: none !important;
        }
        
        .sidebar.collapsed .sidebar-icon-collapsed {
            display: block !important;
        }
        
        .sidebar-header {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }
        
        .sidebar-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent 0%, rgba(255, 255, 255, 0.3) 50%, transparent 100%);
        }
        
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: linear-gradient(135deg, #ffffff 0%, #f0f0f0 100%);
            color: #f36c21;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent 0%, rgba(255, 255, 255, 0.05) 50%, transparent 100%);
            pointer-events: none;
        }
        
        .nav-link i {
            transition: all 0.3s ease;
        }
        
        .nav-link:hover i {
            transform: scale(1.1);
        }
        
        /* Header Enhancements */
        .admin-toggle-btn {
            box-shadow: 0 4px 12px rgba(243, 108, 33, 0.3);
        }
        
        .admin-toggle-btn:hover {
            box-shadow: 0 6px 20px rgba(243, 108, 33, 0.4);
        }
        
        .admin-search-input:focus {
            box-shadow: 0 0 0 3px rgba(243, 108, 33, 0.1);
        }
        
        .admin-notification-btn:hover {
            box-shadow: 0 4px 12px rgba(243, 108, 33, 0.2);
        }
        
        .admin-user-btn:hover {
            box-shadow: 0 4px 12px rgba(243, 108, 33, 0.2);
        }
        
        .admin-notification-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: linear-gradient(135deg, #f36c21 0%, #e55a1a 100%);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(243, 108, 33, 0.4);
            border: 2px solid white;
        }
        
        .header-gradient {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 50%, #ffffff 100%);
        }
        
        .header-shadow {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }
    </style>
</head>
<body>
    <!-- Sidebar -->
        <nav class="sidebar" id="sidebar">
            <div class="sidebar-header h-16 flex items-center justify-center text-white font-bold text-xl relative px-4">
                <img src="{{ asset($whiteLogo) }}" alt="{{ $siteName }} Admin" class="h-10 w-auto object-contain nav-text">
                <i class="fas fa-store sidebar-icon-collapsed" style="display: none;"></i>
            </div>
        
        <div class="py-4">
            <!-- Dashboard -->
            <div class="mb-1">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt w-5 mr-3 text-center"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
            </div>
            
            <!-- Orders -->
            <div class="mb-1">
                <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                    <i class="fas fa-shopping-cart w-5 mr-3 text-center"></i>
                    <span class="nav-text">Orders</span>
                </a>
    </div>

            <!-- Products -->
            <div class="mb-1">
                <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
                    <i class="fas fa-box w-5 mr-3 text-center"></i>
                    <span class="nav-text">Products</span>
                </a>
            </div>
            
            <!-- Inventory -->
            <div class="mb-1">
                <a href="{{ route('admin.inventory.index') }}" class="nav-link {{ request()->routeIs('admin.inventory*') ? 'active' : '' }}">
                    <i class="fas fa-warehouse w-5 mr-3 text-center"></i>
                    <span class="nav-text">Inventory</span>
                </a>
        </div>

            <!-- Categories -->
            <div class="mb-1">
                <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
                    <i class="fas fa-tags w-5 mr-3 text-center"></i>
                    <span class="nav-text">Categories</span>
                </a>
            </div>
            
            <!-- Subcategories -->
            <div class="mb-1">
                <a href="{{ route('admin.subcategories.index') }}" class="nav-link {{ request()->routeIs('admin.subcategories*') ? 'active' : '' }}">
                    <i class="fas fa-layer-group w-5 mr-3 text-center"></i>
                    <span class="nav-text">Subcategories</span>
                </a>
            </div>
            
            <!-- Sliders -->
            <div class="mb-1">
                <a href="{{ route('admin.sliders.index') }}" class="nav-link {{ request()->routeIs('admin.sliders*') ? 'active' : '' }}">
                    <i class="fas fa-images w-5 mr-3 text-center"></i>
                    <span class="nav-text">Sliders</span>
                </a>
            </div>
            
            <!-- Slogans -->
            <div class="mb-1">
                <a href="{{ route('admin.slogans.index') }}" class="nav-link {{ request()->routeIs('admin.slogans*') ? 'active' : '' }}">
                    <i class="fas fa-quote-left w-5 mr-3 text-center"></i>
                    <span class="nav-text">Slogans</span>
                </a>
            </div>
            
            <!-- Brands -->
            <div class="mb-1">
                <a href="{{ route('admin.brands.index') }}" class="nav-link {{ request()->routeIs('admin.brands*') ? 'active' : '' }}">
                    <i class="fas fa-award w-5 mr-3 text-center"></i>
                    <span class="nav-text">Brands</span>
                </a>
            </div>

                <!-- Users -->
            <div class="mb-1">
                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                    <i class="fas fa-users w-5 mr-3 text-center"></i>
                    <span class="nav-text">Users</span>
                </a>
            </div>
            
            <!-- Coupons -->
            <div class="mb-1">
                <a href="{{ route('admin.coupons.index') }}" class="nav-link {{ request()->routeIs('admin.coupons*') ? 'active' : '' }}">
                    <i class="fas fa-ticket-alt w-5 mr-3 text-center"></i>
                    <span class="nav-text">Coupons</span>
                </a>
            </div>
            
            <!-- Notifications -->
            <div class="mb-1">
                <a href="{{ route('admin.notifications.index') }}" class="nav-link {{ request()->routeIs('admin.notifications*') ? 'active' : '' }}">
                    <i class="fas fa-bell w-5 mr-3 text-center"></i>
                    <span class="nav-text">Notifications</span>
                    <span class="notification-badge" id="notificationCount">0</span>
                </a>
            </div>
            
            <!-- Reports -->
            <div class="mb-1">
                <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar w-5 mr-3 text-center"></i>
                    <span class="nav-text">Reports</span>
                </a>
            </div>
            
            <!-- Settings -->
            <div class="mb-1">
                <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                    <i class="fas fa-cog w-5 mr-3 text-center"></i>
                    <span class="nav-text">Settings</span>
                </a>
            </div>
        </div>
        </nav>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Header -->
        <header class="h-16 header-gradient header-shadow sticky top-0 z-50 border-b border-gray-200">
            <div class="flex items-center justify-between px-6 h-full">
                <div class="flex items-center">
                    <button class="admin-toggle-btn bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white p-2 rounded-lg shadow-md transition-all duration-300 hover:shadow-lg transform hover:scale-105" onclick="toggleSidebar()">
                        <i class="fas fa-bars text-lg"></i>
                    </button>
                    <div class="ml-4">
                        <h4 class="text-xl font-bold text-gray-800 mb-0">@yield('page-title', 'Dashboard')</h4>
                        <p class="text-sm text-gray-500 mb-0">Welcome back, {{ Auth::user()->name }}</p>
                    </div>
            </div>

            <div class="flex items-center space-x-4">
                    <!-- Search Bar -->
                    <div class="relative hidden md:block">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" class="admin-search-input bg-gray-100 border-0 rounded-lg pl-10 pr-4 py-2 w-64 focus:ring-2 focus:ring-orange-500 focus:bg-white transition-all duration-300" placeholder="Search...">
                    </div>

                <!-- Notifications -->
                    <div class="relative">
                        <button class="admin-notification-btn bg-gradient-to-r from-gray-100 to-gray-200 hover:from-orange-100 hover:to-orange-200 text-gray-700 hover:text-orange-700 px-4 py-2 rounded-lg border border-gray-300 hover:border-orange-300 relative transition-all duration-300 hover:shadow-md transform hover:scale-105" onclick="toggleNotifications()">
                            <i class="fas fa-bell text-lg"></i>
                            <span class="admin-notification-badge" id="headerNotificationCount">0</span>
                    </button>
                        <div class="absolute top-full right-0 bg-white rounded-xl shadow-2xl min-w-80 z-50 hidden border border-gray-200" id="notificationDropdown">
                            <div class="p-4 border-b border-gray-200">
                                <h6 class="font-bold text-gray-800 text-lg">Recent Notifications</h6>
                                <p class="text-sm text-gray-500">Stay updated with latest activities</p>
                            </div>
                            <div class="p-3 max-h-80 overflow-y-auto" id="notificationList">
                                <!-- Notifications will be loaded here -->
                            </div>
                    </div>
                </div>

                    <!-- User Dropdown -->
                    <div class="relative">
                        <button class="admin-user-btn bg-gradient-to-r from-gray-100 to-gray-200 hover:from-orange-100 hover:to-orange-200 text-gray-700 hover:text-orange-700 px-4 py-2 rounded-lg border border-gray-300 hover:border-orange-300 flex items-center transition-all duration-300 hover:shadow-md transform hover:scale-105" onclick="toggleUserDropdown()">
                            <img src="{{ Auth::user()->avatar ? asset('admin-assets/avatars/'.Auth::user()->avatar) : 'https://via.placeholder.com/32' }}" 
                                 class="rounded-full mr-3 w-8 h-8 border-2 border-white shadow-md" alt="User">
                            <div class="text-left">
                                <div class="font-semibold text-sm">{{ Auth::user()->name }}</div>
                                <div class="text-xs text-gray-500">Administrator</div>
                        </div>
                            <i class="fas fa-chevron-down ml-3 text-sm"></i>
                    </button>
                        <div class="absolute top-full right-0 bg-white rounded-xl shadow-2xl min-w-56 z-50 hidden border border-gray-200" id="userDropdown">
                            <div class="p-4 border-b border-gray-200">
                                <div class="flex items-center">
                                    <img src="{{ Auth::user()->avatar ? asset('admin-assets/avatars/'.Auth::user()->avatar) : 'https://via.placeholder.com/32' }}" 
                                         class="rounded-full w-10 h-10 border-2 border-orange-200" alt="User">
                                    <div class="ml-3">
                                        <div class="font-semibold text-gray-800">{{ Auth::user()->name }}</div>
                                        <div class="text-sm text-gray-500">{{ Auth::user()->email }}</div>
                                    </div>
                                </div>
                            </div>
                            <a class="flex items-center px-4 py-3 text-gray-700 hover:bg-orange-50 hover:text-orange-700 transition-colors duration-200" href="{{ route('admin.profile.index') }}">
                                <i class="fas fa-user mr-3 text-orange-500"></i>Profile
                            </a>
                            <a class="flex items-center px-4 py-3 text-gray-700 hover:bg-orange-50 hover:text-orange-700 transition-colors duration-200" href="{{ route('admin.settings.index') }}">
                                <i class="fas fa-cog mr-3 text-orange-500"></i>Settings
                            </a>
                            <div class="border-t border-gray-200"></div>
                            <a class="flex items-center px-4 py-3 text-gray-700 hover:bg-red-50 hover:text-red-700 transition-colors duration-200" href="{{ route('logout') }}" 
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt mr-3 text-red-500"></i>Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="p-8">
            @yield('content')
        </div>
    </div>

    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    
    <script>
        // Toggle sidebar
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
        }
        
        // Toggle notifications dropdown
        function toggleNotifications() {
            const dropdown = document.getElementById('notificationDropdown');
            dropdown.classList.toggle('hidden');
            loadNotifications();
        }
        
        // Toggle user dropdown
        function toggleUserDropdown() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.classList.toggle('hidden');
        }
        
        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            const notificationDropdown = document.getElementById('notificationDropdown');
            const userDropdown = document.getElementById('userDropdown');
            
            if (!event.target.closest('.relative')) {
                notificationDropdown.classList.add('hidden');
                userDropdown.classList.add('hidden');
            }
        });
        
        // Load notifications
        function loadNotifications() {
            fetch('/api/notifications/recent')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateNotificationCount(data.unread_count);
                        displayNotifications(data.notifications);
                    }
                })
                .catch(error => console.error('Error loading notifications:', error));
        }
        
        // Update notification count
        function updateNotificationCount(count) {
            document.getElementById('notificationCount').textContent = count;
            document.getElementById('headerNotificationCount').textContent = count;
        }
        
        // Display notifications
        function displayNotifications(notifications) {
            const container = document.getElementById('notificationList');
            
            if (notifications.length === 0) {
                container.innerHTML = '<p class="text-muted mb-0">No notifications</p>';
                return;
            }
            
            container.innerHTML = notifications.map(notification => `
                <div class="d-flex align-items-start mb-2 p-2 ${!notification.is_read ? 'bg-light' : ''}">
                    <div class="flex-shrink-0">
                        <i class="fas fa-${getNotificationIcon(notification.type)} text-${getNotificationColor(notification.type)}"></i>
                    </div>
                    <div class="flex-grow-1 ml-2">
                        <div class="font-weight-bold">${notification.title}</div>
                        <div class="text-muted small">${notification.message}</div>
                        <div class="text-muted small">${notification.time_ago}</div>
                    </div>
                </div>
            `).join('');
        }
        
        // Get notification icon
        function getNotificationIcon(type) {
            const icons = {
                'order_confirmed': 'check-circle',
                'order_shipped': 'truck',
                'order_delivered': 'check',
                'low_stock_alert': 'exclamation-triangle',
                'out_of_stock_alert': 'times-circle',
                'payment_confirmed': 'credit-card'
            };
            return icons[type] || 'bell';
        }
        
        // Get notification color
        function getNotificationColor(type) {
            const colors = {
                'order_confirmed': 'success',
                'order_shipped': 'info',
                'order_delivered': 'success',
                'low_stock_alert': 'warning',
                'out_of_stock_alert': 'danger',
                'payment_confirmed': 'success'
            };
            return colors[type] || 'secondary';
        }
        
        // Load notifications on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadNotifications();
            
            // Refresh notifications every 30 seconds
            setInterval(loadNotifications, 30000);
        });
    </script>
</body>
</html>