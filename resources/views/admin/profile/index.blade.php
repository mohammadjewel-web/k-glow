@extends('layouts.admin')

@section('title', 'Admin Profile')
@section('page-title', 'My Profile')

@section('content')
<style>
    .btn-update-profile {
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
    
    .btn-update-profile:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.05);
        color: white;
        text-decoration: none;
    }
    
    .btn-change-password {
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
    
    .btn-change-password:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.05);
        color: white;
        text-decoration: none;
    }
    
    .profile-avatar {
        position: relative;
        display: inline-block;
    }
    
    .profile-avatar:hover .avatar-overlay {
        opacity: 1;
    }
    
    .avatar-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
        cursor: pointer;
    }
    
    .stat-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .stat-card-2 {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }
    
    .stat-card-3 {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
    }
    
    .stat-card-4 {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        color: white;
    }
</style>

<!-- Header Section -->
<div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl shadow-lg mb-8 p-6 text-white">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold mb-2">My Profile</h1>
            <p class="text-orange-100">Manage your account information and preferences</p>
        </div>
        <div class="flex space-x-3">
            <button class="btn-update-profile" onclick="updateProfile()">
                <i class="fas fa-save mr-2"></i> Update Profile
            </button>
            <button class="btn-change-password" onclick="changePassword()">
                <i class="fas fa-key mr-2"></i> Change Password
            </button>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Profile Information -->
    <div class="lg:col-span-2">
        <!-- Profile Details -->
        <div class="bg-white rounded-xl shadow-lg mb-6">
            <div class="bg-gradient-to-r from-orange-50 to-orange-100 px-6 py-4 border-b border-orange-200">
                <h3 class="text-lg font-semibold text-orange-800">Profile Information</h3>
                <p class="text-sm text-orange-600">Update your personal information</p>
            </div>
            <div class="p-6">
                <form id="profileForm" action="{{ route('admin.profile.update') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                            <input type="text" id="name" name="name" value="{{ Auth::user()->name }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                            <input type="email" id="email" name="email" value="{{ Auth::user()->email }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <input type="tel" id="phone" name="phone" value="{{ Auth::user()->phone ?? '' }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                            <input type="text" value="Administrator" readonly
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                            <input type="text" id="location" name="location" value="{{ Auth::user()->location ?? '' }}" placeholder="City, Country"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Website</label>
                            <input type="url" id="website" name="website" value="{{ Auth::user()->website ?? '' }}" placeholder="https://yourwebsite.com"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bio</label>
                        <textarea id="bio" name="bio" rows="3" placeholder="Tell us about yourself"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">{{ Auth::user()->bio ?? '' }}</textarea>
                    </div>
                </form>
            </div>
        </div>

        <!-- Change Password -->
        <div class="bg-white rounded-xl shadow-lg mb-6">
            <div class="bg-gradient-to-r from-red-50 to-red-100 px-6 py-4 border-b border-red-200">
                <h3 class="text-lg font-semibold text-red-800">Change Password</h3>
                <p class="text-sm text-red-600">Update your account password</p>
            </div>
            <div class="p-6">
                <form id="passwordForm" action="{{ route('admin.profile.password') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Current Password *</label>
                        <input type="password" id="current_password" name="current_password" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">New Password *</label>
                            <input type="password" id="new_password" name="new_password" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password *</label>
                            <input type="password" id="new_password_confirmation" name="new_password_confirmation" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Security Settings -->
        <div class="bg-white rounded-xl shadow-lg mb-6">
            <div class="bg-gradient-to-r from-purple-50 to-purple-100 px-6 py-4 border-b border-purple-200">
                <h3 class="text-lg font-semibold text-purple-800">Security Settings</h3>
                <p class="text-sm text-purple-600">Manage your account security</p>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-shield-alt text-purple-500 mr-3"></i>
                            <div>
                                <h4 class="font-medium text-gray-900">Two-Factor Authentication</h4>
                                <p class="text-sm text-gray-500">Add an extra layer of security</p>
                            </div>
                        </div>
                        <button class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors duration-200">
                            Enable
                        </button>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-bell text-blue-500 mr-3"></i>
                            <div>
                                <h4 class="font-medium text-gray-900">Login Notifications</h4>
                                <p class="text-sm text-gray-500">Get notified of new logins</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-clock text-green-500 mr-3"></i>
                            <div>
                                <h4 class="font-medium text-gray-900">Session Timeout</h4>
                                <p class="text-sm text-gray-500">Auto-logout after inactivity</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Sidebar -->
    <div class="lg:col-span-1">
        <!-- Profile Card -->
        <div class="bg-white rounded-xl shadow-lg mb-6 overflow-hidden">
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 p-6 text-white text-center">
                <div class="profile-avatar mb-4">
                    <img src="{{ Auth::user()->avatar ? asset('admin-assets/avatars/'.Auth::user()->avatar) : 'https://via.placeholder.com/150' }}" 
                         class="rounded-full w-24 h-24 object-cover mx-auto border-4 border-white shadow-lg" alt="Profile Picture">
                    <div class="avatar-overlay">
                        <i class="fas fa-camera text-white text-xl"></i>
                    </div>
                </div>
                <h3 class="text-xl font-bold mb-1">{{ Auth::user()->name }}</h3>
                <p class="text-orange-100 mb-2">{{ Auth::user()->email }}</p>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white bg-opacity-20">
                    <i class="fas fa-crown mr-1"></i>
                    Administrator
                </span>
            </div>
            <div class="p-6">
                <button onclick="changeAvatar()" class="w-full px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors duration-200">
                    <i class="fas fa-camera mr-2"></i> Change Avatar
                </button>
            </div>
        </div>

        <!-- Account Statistics -->
        <div class="bg-white rounded-xl shadow-lg mb-6">
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Account Statistics</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-4">
                    <div class="stat-card p-4 rounded-lg text-center">
                        <div class="text-2xl font-bold mb-1" id="totalOrders">0</div>
                        <div class="text-sm opacity-90">Orders Managed</div>
                    </div>
                    <div class="stat-card-2 p-4 rounded-lg text-center">
                        <div class="text-2xl font-bold mb-1" id="totalProducts">0</div>
                        <div class="text-sm opacity-90">Products Managed</div>
                    </div>
                    <div class="stat-card-3 p-4 rounded-lg text-center">
                        <div class="text-2xl font-bold mb-1" id="totalCustomers">0</div>
                        <div class="text-sm opacity-90">Customers</div>
                    </div>
                    <div class="stat-card-4 p-4 rounded-lg text-center">
                        <div class="text-2xl font-bold mb-1" id="totalRevenue">৳0</div>
                        <div class="text-sm opacity-90">Revenue</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-xl shadow-lg mb-6">
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Recent Activity</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-check text-green-600 text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Profile updated</p>
                            <p class="text-xs text-gray-500">2 hours ago</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-key text-blue-600 text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Password changed</p>
                            <p class="text-xs text-gray-500">1 day ago</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-sign-in-alt text-orange-600 text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Logged in</p>
                            <p class="text-xs text-gray-500">3 days ago</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-lg">
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Quick Actions</h3>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    <button class="w-full flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        <i class="fas fa-download mr-2"></i>
                        Export Data
                    </button>
                    <button class="w-full flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200">
                        <i class="fas fa-bell mr-2"></i>
                        Notification Settings
                    </button>
                    <button class="w-full flex items-center justify-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors duration-200">
                        <i class="fas fa-cog mr-2"></i>
                        Preferences
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Avatar Upload Modal -->
<div id="avatarModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Change Avatar</h3>
            </div>
            <div class="p-6">
                <div class="text-center mb-4">
                    <div class="w-24 h-24 bg-gray-100 rounded-full mx-auto mb-4 flex items-center justify-center">
                        <i class="fas fa-user text-gray-400 text-2xl"></i>
                    </div>
                    <p class="text-sm text-gray-600">Upload a new profile picture</p>
                </div>
                <form id="avatarForm" action="{{ route('admin.profile.avatar') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" id="avatarInput" name="avatar" accept="image/*" class="hidden">
                </form>
                <div class="flex space-x-3">
                    <button onclick="document.getElementById('avatarInput').click()" class="flex-1 px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors duration-200">
                        <i class="fas fa-upload mr-2"></i> Choose File
                    </button>
                    <button onclick="closeAvatarModal()" class="flex-1 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors duration-200">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Update profile function
function updateProfile() {
    const form = document.getElementById('profileForm');
    const formData = new FormData(form);
    
    // Show loading state
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Updating...';
    button.disabled = true;
    
    // Submit form via AJAX
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        button.innerHTML = originalText;
        button.disabled = false;
        
        if (data.success) {
            showNotification(data.message, 'success');
        } else {
            showNotification(data.message || 'An error occurred', 'error');
        }
    })
    .catch(error => {
        button.innerHTML = originalText;
        button.disabled = false;
        showNotification('An error occurred while updating profile', 'error');
        console.error('Error:', error);
    });
}

// Change password function
function changePassword() {
    const form = document.getElementById('passwordForm');
    const formData = new FormData(form);
    
    const newPassword = formData.get('new_password');
    const confirmPassword = formData.get('new_password_confirmation');
    
    if (newPassword !== confirmPassword) {
        showNotification('Passwords do not match!', 'error');
        return;
    }
    
    if (newPassword.length < 8) {
        showNotification('Password must be at least 8 characters long!', 'error');
        return;
    }
    
    // Show loading state
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Changing...';
    button.disabled = true;
    
    // Submit form via AJAX
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        button.innerHTML = originalText;
        button.disabled = false;
        
        if (data.success) {
            showNotification(data.message, 'success');
            form.reset();
        } else {
            showNotification(data.message || 'An error occurred', 'error');
        }
    })
    .catch(error => {
        button.innerHTML = originalText;
        button.disabled = false;
        showNotification('An error occurred while changing password', 'error');
        console.error('Error:', error);
    });
}

// Change avatar function
function changeAvatar() {
    document.getElementById('avatarModal').classList.remove('hidden');
}

// Close avatar modal
function closeAvatarModal() {
    document.getElementById('avatarModal').classList.add('hidden');
}

// Handle avatar upload
document.getElementById('avatarInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        // Validate file size (2MB max)
        if (file.size > 2 * 1024 * 1024) {
            showNotification('File size must be less than 2MB', 'error');
            return;
        }
        
        // Validate file type
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
        if (!allowedTypes.includes(file.type)) {
            showNotification('Please select a valid image file (JPEG, PNG, JPG, GIF)', 'error');
            return;
        }
        
        // Show loading state
        const modal = document.getElementById('avatarModal');
        const button = modal.querySelector('button[onclick*="avatarInput"]');
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Uploading...';
        button.disabled = true;
        
        // Create FormData and submit
        const form = document.getElementById('avatarForm');
        const formData = new FormData(form);
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            button.innerHTML = originalText;
            button.disabled = false;
            
            if (data.success) {
                // Update avatar preview
                const avatarImg = document.querySelector('.profile-avatar img');
                avatarImg.src = data.avatar_url;
                
                // Close modal
                closeAvatarModal();
                
                // Show success message
                showNotification(data.message, 'success');
            } else {
                showNotification(data.message || 'An error occurred', 'error');
            }
        })
        .catch(error => {
            button.innerHTML = originalText;
            button.disabled = false;
            showNotification('An error occurred while uploading avatar', 'error');
            console.error('Error:', error);
        });
    }
});

// Show notification
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg ${
        type === 'success' ? 'bg-green-500 text-white' : 
        type === 'error' ? 'bg-red-500 text-white' : 
        'bg-blue-500 text-white'
    }`;
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'times' : 'info'}-circle mr-2"></i>
            ${message}
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Load statistics
document.addEventListener('DOMContentLoaded', function() {
    // Load real statistics
    fetch('{{ route("admin.profile.stats") }}', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('totalOrders').textContent = data.total_orders || '0';
        document.getElementById('totalProducts').textContent = data.total_products || '0';
        document.getElementById('totalCustomers').textContent = data.total_customers || '0';
        document.getElementById('totalRevenue').textContent = '৳' + (data.total_revenue || '0');
    })
    .catch(error => {
        console.error('Error loading statistics:', error);
        // Fallback to default values
        document.getElementById('totalOrders').textContent = '0';
        document.getElementById('totalProducts').textContent = '0';
        document.getElementById('totalCustomers').textContent = '0';
        document.getElementById('totalRevenue').textContent = '৳0';
    });
});

// Close modal when clicking outside
document.getElementById('avatarModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeAvatarModal();
    }
});
</script>
@endsection


