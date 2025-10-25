@extends('layouts.admin')

@section('title', 'System Settings')
@section('page-title', 'Settings')

@section('content')
<style>
    .btn-save-settings {
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
    
    .btn-save-settings:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.05);
        color: white;
        text-decoration: none;
    }
    
    .btn-reset-settings {
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
    
    .btn-reset-settings:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.05);
        color: white;
        text-decoration: none;
    }
    
    .settings-section {
        display: none;
    }
    
    .settings-section.active {
        display: block;
    }
    
    .settings-nav-item {
        transition: all 0.3s ease;
    }
    
    .settings-nav-item:hover {
        background-color: #f3f4f6;
        transform: translateX(4px);
    }
    
    .settings-nav-item.active {
        background-color: #fef3c7;
        border-left: 4px solid #f59e0b;
    }
</style>

<!-- Header Section -->
<div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl shadow-lg mb-8 p-6 text-white">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold mb-2">System Settings</h1>
            <p class="text-orange-100">Configure your application settings and preferences</p>
        </div>
        <div class="flex space-x-3">
            <button class="btn-save-settings" onclick="saveAllSettings()">
                <i class="fas fa-save mr-2"></i> Save All Settings
            </button>
            <button class="btn-reset-settings" onclick="resetAllSettings()">
                <i class="fas fa-undo mr-2"></i> Reset to Default
            </button>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
    <!-- Settings Navigation -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Settings Menu</h3>
            </div>
            <div class="p-0">
                <nav class="space-y-1 p-4">
                    <a href="#general" class="settings-nav-item active flex items-center px-4 py-3 text-sm font-medium text-gray-700 rounded-lg" onclick="showSettings('general')">
                        <i class="fas fa-cog mr-3 text-orange-500"></i>
                        General Settings
                    </a>
                    <a href="#store" class="settings-nav-item flex items-center px-4 py-3 text-sm font-medium text-gray-700 rounded-lg" onclick="showSettings('store')">
                        <i class="fas fa-store mr-3 text-blue-500"></i>
                        Store Information
                    </a>
                    <a href="#email" class="settings-nav-item flex items-center px-4 py-3 text-sm font-medium text-gray-700 rounded-lg" onclick="showSettings('email')">
                        <i class="fas fa-envelope mr-3 text-green-500"></i>
                        Email Settings
                    </a>
                    <a href="#payment" class="settings-nav-item flex items-center px-4 py-3 text-sm font-medium text-gray-700 rounded-lg" onclick="showSettings('payment')">
                        <i class="fas fa-credit-card mr-3 text-purple-500"></i>
                        Payment Settings
                    </a>
                    <a href="#shipping" class="settings-nav-item flex items-center px-4 py-3 text-sm font-medium text-gray-700 rounded-lg" onclick="showSettings('shipping')">
                        <i class="fas fa-truck mr-3 text-indigo-500"></i>
                        Shipping Settings
                    </a>
                    <a href="#seo" class="settings-nav-item flex items-center px-4 py-3 text-sm font-medium text-gray-700 rounded-lg" onclick="showSettings('seo')">
                        <i class="fas fa-search mr-3 text-pink-500"></i>
                        SEO Settings
                    </a>
                    <a href="#theme" class="settings-nav-item flex items-center px-4 py-3 text-sm font-medium text-gray-700 rounded-lg" onclick="showSettings('theme')">
                        <i class="fas fa-palette mr-3 text-yellow-500"></i>
                        Theme & Appearance
                    </a>
                    <a href="#verification" class="settings-nav-item flex items-center px-4 py-3 text-sm font-medium text-gray-700 rounded-lg" onclick="showSettings('verification')">
                        <i class="fas fa-check-circle mr-3 text-teal-500"></i>
                        Verification Settings
                    </a>
                    <a href="#security" class="settings-nav-item flex items-center px-4 py-3 text-sm font-medium text-gray-700 rounded-lg" onclick="showSettings('security')">
                        <i class="fas fa-shield-alt mr-3 text-red-500"></i>
                        Security Settings
                    </a>
                </nav>
            </div>
        </div>
    </div>

    <!-- Settings Content -->
    <div class="lg:col-span-3">
        <!-- General Settings -->
        <div id="general-settings" class="settings-section active">
            <div class="bg-white rounded-xl shadow-lg mb-6">
                <div class="bg-gradient-to-r from-orange-50 to-orange-100 px-6 py-4 border-b border-orange-200">
                    <h3 class="text-lg font-semibold text-orange-800">General Settings</h3>
                    <p class="text-sm text-orange-600">Basic application configuration</p>
                </div>
                <div class="p-6">
                    <form id="general-form">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Site Name</label>
                                <input type="text" name="site_name" value="K-Glow BD" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Site URL</label>
                                <input type="url" name="site_url" value="https://k-glowbd.com" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Admin Email</label>
                                <input type="email" name="admin_email" value="admin@k-glowbd.com" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Default Currency</label>
                                <select name="default_currency" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                                    <option value="BDT" selected>BDT (৳)</option>
                                    <option value="USD">USD ($)</option>
                                    <option value="EUR">EUR (€)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Timezone</label>
                                <select name="timezone" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                                    <option value="Asia/Dhaka" selected>Asia/Dhaka</option>
                                    <option value="UTC">UTC</option>
                                    <option value="America/New_York">America/New_York</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Language</label>
                                <select name="language" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                                    <option value="en" selected>English</option>
                                    <option value="bn">বাংলা</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Site Description</label>
                            <textarea name="site_description" rows="3" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                      placeholder="Enter your site description...">Premium beauty and skincare products in Bangladesh</textarea>
                        </div>
                        
                        <!-- Save Button -->
                        <div class="flex justify-end space-x-3 pt-6 mt-6 border-t border-gray-200">
                            <button type="button" onclick="saveSettings('general')" 
                                    class="px-6 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                                <i class="fas fa-save mr-2"></i>
                                Save General Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Store Information -->
        <div id="store-settings" class="settings-section">
            <div class="bg-white rounded-xl shadow-lg mb-6">
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-6 py-4 border-b border-blue-200">
                    <h3 class="text-lg font-semibold text-blue-800">Store Information</h3>
                    <p class="text-sm text-blue-600">Configure your store details</p>
                </div>
                <div class="p-6">
                    <form id="store-form">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Store Name</label>
                                <input type="text" name="store_name" value="K-Glow BD" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Store Phone</label>
                                <input type="tel" name="store_phone" value="+880 1234 567890" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Store Address</label>
                                <textarea name="store_address" rows="3" 
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                          placeholder="Enter your store address...">123 Business Street, Dhaka, Bangladesh</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Business Hours</label>
                                <input type="text" name="business_hours" value="9:00 AM - 6:00 PM (Sat-Thu)" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tax ID</label>
                                <input type="text" name="tax_id" value="TAX123456789" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>
                        
                        <!-- Save Button -->
                        <div class="flex justify-end space-x-3 pt-6 mt-6 border-t border-gray-200">
                            <button type="button" onclick="saveSettings('store')" 
                                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-save mr-2"></i>
                                Save Store Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Email Settings -->
        <div id="email-settings" class="settings-section">
            <div class="bg-white rounded-xl shadow-lg mb-6">
                <div class="bg-gradient-to-r from-green-50 to-green-100 px-6 py-4 border-b border-green-200">
                    <h3 class="text-lg font-semibold text-green-800">Email Settings</h3>
                    <p class="text-sm text-green-600">Configure email notifications and SMTP</p>
                </div>
                <div class="p-6">
                    <form id="email-form">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Host</label>
                                <input type="text" name="smtp_host" value="smtp.gmail.com" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Port</label>
                                <input type="number" name="smtp_port" value="587" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Username</label>
                                <input type="email" name="smtp_username" value="noreply@k-glowbd.com" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Password</label>
                                <input type="password" name="smtp_password" value="••••••••" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">From Name</label>
                                <input type="text" name="from_name" value="K-Glow BD" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">From Email</label>
                                <input type="email" name="from_email" value="noreply@k-glowbd.com" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            </div>
                        </div>
                        <div class="mt-6">
                            <div class="flex items-center space-x-4">
                                <label class="flex items-center">
                                    <input type="checkbox" name="email_notifications" checked class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                                    <span class="ml-2 text-sm text-gray-700">Enable email notifications</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="order_emails" checked class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                                    <span class="ml-2 text-sm text-gray-700">Send order confirmation emails</span>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Save Button -->
                        <div class="flex justify-end space-x-3 pt-6 mt-6 border-t border-gray-200">
                            <button type="button" onclick="saveSettings('email')" 
                                    class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                <i class="fas fa-save mr-2"></i>
                                Save Email Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Payment Settings -->
        <div id="payment-settings" class="settings-section">
            <div class="bg-white rounded-xl shadow-lg mb-6">
                <div class="bg-gradient-to-r from-purple-50 to-purple-100 px-6 py-4 border-b border-purple-200">
                    <h3 class="text-lg font-semibold text-purple-800">Payment Settings</h3>
                    <p class="text-sm text-purple-600">Configure payment gateways and methods</p>
                </div>
                <div class="p-6">
                    <form id="payment-form">
                        <div class="space-y-6">
                            <div>
                                <h4 class="text-md font-semibold text-gray-800 mb-4">Payment Methods</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                                        <input type="checkbox" name="payment_methods[]" value="cash_on_delivery" checked class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                                        <div class="ml-3">
                                            <div class="flex items-center">
                                                <i class="fas fa-money-bill-wave text-green-500 mr-2"></i>
                                                <span class="font-medium">Cash on Delivery</span>
                                            </div>
                                            <p class="text-sm text-gray-500">Pay when you receive</p>
                                        </div>
                                    </label>
                                    <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                                        <input type="checkbox" name="payment_methods[]" value="sslcommerz" checked class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                                        <div class="ml-3">
                                            <div class="flex items-center">
                                                <i class="fas fa-credit-card text-blue-500 mr-2"></i>
                                                <span class="font-medium">SSLCommerz</span>
                                            </div>
                                            <p class="text-sm text-gray-500">Card & Mobile payments</p>
                                        </div>
                                    </label>
                                    <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                                        <input type="checkbox" name="payment_methods[]" value="bkash" class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                                        <div class="ml-3">
                                            <div class="flex items-center">
                                                <i class="fas fa-mobile-alt text-pink-500 mr-2"></i>
                                                <span class="font-medium">bKash</span>
                                            </div>
                                            <p class="text-sm text-gray-500">Mobile payment</p>
                                        </div>
                                    </label>
                                    <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                                        <input type="checkbox" name="payment_methods[]" value="nagad" class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                                        <div class="ml-3">
                                            <div class="flex items-center">
                                                <i class="fas fa-mobile-alt text-green-500 mr-2"></i>
                                                <span class="font-medium">Nagad</span>
                                            </div>
                                            <p class="text-sm text-gray-500">Mobile payment</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="border-t pt-6">
                                <h4 class="text-md font-semibold text-gray-800 mb-4">SSLCommerz Configuration</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Store ID</label>
                                        <input type="text" name="sslcz_store_id" placeholder="Enter your SSLCommerz Store ID" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                        <p class="text-xs text-gray-500 mt-1">Get your Store ID from SSLCommerz dashboard</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Store Password</label>
                                        <input type="password" name="sslcz_store_password" placeholder="Enter your SSLCommerz Store Password" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                        <p class="text-xs text-gray-500 mt-1">Your SSLCommerz Store Password</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Environment</label>
                                        <select name="sslcz_testmode" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                            <option value="true" selected>Sandbox (Test Mode)</option>
                                            <option value="false">Live (Production)</option>
                                        </select>
                                        <p class="text-xs text-gray-500 mt-1">Use Sandbox for testing, Live for production</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">IPN URL</label>
                                        <input type="text" readonly value="{{ url('/ipn') }}" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-500">
                                        <p class="text-xs text-gray-500 mt-1">Set this URL in your SSLCommerz dashboard</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="border-t pt-6">
                                <h4 class="text-md font-semibold text-gray-800 mb-4">bKash Configuration</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">bKash Number</label>
                                        <input type="tel" name="bkash_number" value="+880 1234 567890" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">bKash Type</label>
                                        <select name="bkash_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                            <option value="personal">Personal</option>
                                            <option value="merchant" selected>Merchant</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Save Button -->
                        <div class="flex justify-end space-x-3 pt-6 mt-6 border-t border-gray-200">
                            <button type="button" onclick="saveSettings('payment')" 
                                    class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                                <i class="fas fa-save mr-2"></i>
                                Save Payment Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Shipping Settings -->
        <div id="shipping-settings" class="settings-section">
            <div class="bg-white rounded-xl shadow-lg mb-6">
                <div class="bg-gradient-to-r from-indigo-50 to-indigo-100 px-6 py-4 border-b border-indigo-200">
                    <h3 class="text-lg font-semibold text-indigo-800">Shipping Settings</h3>
                    <p class="text-sm text-indigo-600">Configure shipping methods and rates</p>
                </div>
                <div class="p-6">
                    <form id="shipping-form">
                        <div class="space-y-6">
                            <div>
                                <h4 class="text-md font-semibold text-gray-800 mb-4">Shipping Methods</h4>
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                        <div class="flex items-center">
                                            <input type="checkbox" name="shipping_methods[]" value="home_delivery" checked class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                            <div class="ml-3">
                                                <div class="flex items-center">
                                                    <i class="fas fa-truck text-indigo-500 mr-2"></i>
                                                    <span class="font-medium">Home Delivery</span>
                                                </div>
                                                <p class="text-sm text-gray-500">Delivery to your doorstep</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-lg font-semibold text-gray-900">৳50</span>
                                            <p class="text-sm text-gray-500">Per order</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                        <div class="flex items-center">
                                            <input type="checkbox" name="shipping_methods[]" value="pickup" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                            <div class="ml-3">
                                                <div class="flex items-center">
                                                    <i class="fas fa-store text-green-500 mr-2"></i>
                                                    <span class="font-medium">Store Pickup</span>
                                                </div>
                                                <p class="text-sm text-gray-500">Pick up from our store</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-lg font-semibold text-gray-900">Free</span>
                                            <p class="text-sm text-gray-500">No charge</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="border-t pt-6">
                                <h4 class="text-md font-semibold text-gray-800 mb-4">Delivery Areas</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Free Delivery Minimum</label>
                                        <input type="number" name="free_delivery_minimum" value="1000" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Delivery Time (Days)</label>
                                        <input type="number" name="delivery_time" value="3" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Save Button -->
                        <div class="flex justify-end space-x-3 pt-6 mt-6 border-t border-gray-200">
                            <button type="button" onclick="saveSettings('shipping')" 
                                    class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                                <i class="fas fa-save mr-2"></i>
                                Save Shipping Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- SEO Settings -->
        <div id="seo-settings" class="settings-section">
            <div class="bg-white rounded-xl shadow-lg mb-6">
                <div class="bg-gradient-to-r from-pink-50 to-pink-100 px-6 py-4 border-b border-pink-200">
                    <h3 class="text-lg font-semibold text-pink-800">SEO Settings</h3>
                    <p class="text-sm text-pink-600">Search engine optimization settings</p>
                </div>
                <div class="p-6">
                    <form id="seo-form">
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                                <input type="text" name="meta_title" value="K-Glow BD - Premium Beauty & Skincare Products" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                                <textarea name="meta_description" rows="3" 
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent"
                                          placeholder="Enter meta description...">Premium beauty and skincare products in Bangladesh. Shop the latest cosmetics, skincare, and beauty essentials.</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Meta Keywords</label>
                                <input type="text" name="meta_keywords" value="beauty, skincare, cosmetics, Bangladesh, makeup, skincare products" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent">
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Google Analytics ID</label>
                                    <input type="text" name="google_analytics" value="GA-XXXXXXXXX" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Facebook Pixel ID</label>
                                    <input type="text" name="facebook_pixel" value="FB-XXXXXXXXX" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Save Button -->
                        <div class="flex justify-end space-x-3 pt-6 mt-6 border-t border-gray-200">
                            <button type="button" onclick="saveSettings('seo')" 
                                    class="px-6 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700 transition-colors">
                                <i class="fas fa-save mr-2"></i>
                                Save SEO Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Theme & Appearance Settings -->
        <div id="theme-settings" class="settings-section">
            <div class="bg-white rounded-xl shadow-lg mb-6">
                <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 px-6 py-4 border-b border-yellow-200">
                    <h3 class="text-lg font-semibold text-yellow-800">
                        <i class="fas fa-palette mr-2"></i>
                        Theme & Appearance
                    </h3>
                    <p class="text-sm text-yellow-600">Customize your site's look and feel</p>
                </div>
                <div class="p-6">
                    <form id="theme-form">
                        
                        <!-- Brand Colors -->
                        <div class="mb-8">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-fill-drip mr-2 text-orange-500"></i>
                                Brand Colors
                            </h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Primary Color (Root Color)</label>
                                    <div class="flex items-center space-x-3">
                                        <input type="color" name="primary_color" value="#f36c21" 
                                               class="h-12 w-20 border border-gray-300 rounded cursor-pointer">
                                        <input type="text" name="primary_color_hex" value="#f36c21" 
                                               class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                                               placeholder="#f36c21">
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Main brand color (used for buttons, links, etc.)</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Secondary Color</label>
                                    <div class="flex items-center space-x-3">
                                        <input type="color" name="secondary_color" value="#0a0a0a" 
                                               class="h-12 w-20 border border-gray-300 rounded cursor-pointer">
                                        <input type="text" name="secondary_color_hex" value="#0a0a0a" 
                                               class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                                               placeholder="#0a0a0a">
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Secondary brand color</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Accent Color</label>
                                    <div class="flex items-center space-x-3">
                                        <input type="color" name="accent_color" value="#fef3c7" 
                                               class="h-12 w-20 border border-gray-300 rounded cursor-pointer">
                                        <input type="text" name="accent_color_hex" value="#fef3c7" 
                                               class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                                               placeholder="#fef3c7">
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Accent/highlight color</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Background Color</label>
                                    <div class="flex items-center space-x-3">
                                        <input type="color" name="background_color" value="#ffffff" 
                                               class="h-12 w-20 border border-gray-300 rounded cursor-pointer">
                                        <input type="text" name="background_color_hex" value="#ffffff" 
                                               class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                                               placeholder="#ffffff">
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Main background color</p>
                                </div>
                            </div>
                        </div>

                        <hr class="my-8 border-gray-200">

                        <!-- Text Colors -->
                        <div class="mb-8">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-font mr-2 text-blue-500"></i>
                                Text Colors
                            </h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Primary Text Color</label>
                                    <div class="flex items-center space-x-3">
                                        <input type="color" name="text_color" value="#1f2937" 
                                               class="h-12 w-20 border border-gray-300 rounded cursor-pointer">
                                        <input type="text" name="text_color_hex" value="#1f2937" 
                                               class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                                               placeholder="#1f2937">
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Main text color</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Heading Color</label>
                                    <div class="flex items-center space-x-3">
                                        <input type="color" name="heading_color" value="#111827" 
                                               class="h-12 w-20 border border-gray-300 rounded cursor-pointer">
                                        <input type="text" name="heading_color_hex" value="#111827" 
                                               class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                                               placeholder="#111827">
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Heading text color</p>
                                </div>
                            </div>
                        </div>

                        <hr class="my-8 border-gray-200">

                        <!-- Typography -->
                        <div class="mb-8">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-text-height mr-2 text-purple-500"></i>
                                Typography
                            </h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Primary Font</label>
                                    <select name="primary_font" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                                        <option value="Inter, sans-serif">Inter</option>
                                        <option value="Poppins, sans-serif">Poppins</option>
                                        <option value="Roboto, sans-serif">Roboto</option>
                                        <option value="Open Sans, sans-serif">Open Sans</option>
                                        <option value="Lato, sans-serif">Lato</option>
                                        <option value="Montserrat, sans-serif">Montserrat</option>
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">Font for body text</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Heading Font</label>
                                    <select name="heading_font" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                                        <option value="Poppins, sans-serif">Poppins</option>
                                        <option value="Inter, sans-serif">Inter</option>
                                        <option value="Playfair Display, serif">Playfair Display</option>
                                        <option value="Merriweather, serif">Merriweather</option>
                                        <option value="Raleway, sans-serif">Raleway</option>
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">Font for headings</p>
                                </div>
                            </div>
                        </div>

                        <hr class="my-8 border-gray-200">

                        <!-- Logo & Branding -->
                        <div class="mb-8">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-image mr-2 text-green-500"></i>
                                Logo & Branding
                            </h4>
                            
                            <div class="space-y-6">
                                <!-- Primary Logo -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Primary Logo</label>
                                    <div class="flex items-center space-x-4">
                                        <img id="logo_preview" src="{{ asset($logo ?? 'admin-assets/logo.png') }}" alt="Logo" class="h-16 w-auto border border-gray-300 rounded p-2">
                                        <div class="flex-1">
                                            <input type="file" id="logo_file" accept="image/*" class="hidden" onchange="uploadLogo('logo')">
                                            <button type="button" onclick="document.getElementById('logo_file').click()" 
                                                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                                                <i class="fas fa-upload mr-2"></i>
                                                Upload New Logo
                                            </button>
                                            <p class="text-xs text-gray-500 mt-1">Recommended: PNG, 200x60px</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- White Logo -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">White Logo (for dark backgrounds)</label>
                                    <div class="flex items-center space-x-4">
                                        <div class="bg-gray-900 p-3 rounded">
                                            <img id="white_logo_preview" src="{{ asset($whiteLogo ?? 'admin-assets/white-logo.png') }}" alt="White Logo" class="h-16 w-auto">
                                        </div>
                                        <div class="flex-1">
                                            <input type="file" id="white_logo_file" accept="image/*" class="hidden" onchange="uploadLogo('white_logo')">
                                            <button type="button" onclick="document.getElementById('white_logo_file').click()" 
                                                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                                                <i class="fas fa-upload mr-2"></i>
                                                Upload White Logo
                                            </button>
                                            <p class="text-xs text-gray-500 mt-1">Recommended: PNG with transparency, 200x60px</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Favicon -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Favicon</label>
                                    <div class="flex items-center space-x-4">
                                        <img id="favicon_preview" src="{{ asset($favicon ?? 'favicon.ico') }}" alt="Favicon" class="h-12 w-12 border border-gray-300 rounded">
                                        <div class="flex-1">
                                            <input type="file" id="favicon_file" accept="image/x-icon,image/png" class="hidden" onchange="uploadLogo('favicon')">
                                            <button type="button" onclick="document.getElementById('favicon_file').click()" 
                                                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                                                <i class="fas fa-upload mr-2"></i>
                                                Upload Favicon
                                            </button>
                                            <p class="text-xs text-gray-500 mt-1">Recommended: ICO or PNG, 32x32px or 64x64px</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-8 border-gray-200">

                        <!-- Color Preview -->
                        <div class="mb-8">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-eye mr-2 text-indigo-500"></i>
                                Color Preview
                            </h4>
                            
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div class="text-center">
                                    <div id="primary_preview" class="h-20 rounded-lg border border-gray-300" style="background-color: #f36c21;"></div>
                                    <p class="text-xs text-gray-600 mt-2">Primary</p>
                                </div>
                                <div class="text-center">
                                    <div id="secondary_preview" class="h-20 rounded-lg border border-gray-300" style="background-color: #0a0a0a;"></div>
                                    <p class="text-xs text-gray-600 mt-2">Secondary</p>
                                </div>
                                <div class="text-center">
                                    <div id="accent_preview" class="h-20 rounded-lg border border-gray-300" style="background-color: #fef3c7;"></div>
                                    <p class="text-xs text-gray-600 mt-2">Accent</p>
                                </div>
                                <div class="text-center">
                                    <div id="background_preview" class="h-20 rounded-lg border border-gray-300" style="background-color: #ffffff;"></div>
                                    <p class="text-xs text-gray-600 mt-2">Background</p>
                                </div>
                            </div>
                            
                            <!-- Sample UI Preview -->
                            <div class="mt-6 p-6 rounded-lg border-2" id="sample_preview" style="background-color: #ffffff;">
                                <h5 id="sample_heading" style="color: #111827;" class="text-xl font-bold mb-2">Sample Heading</h5>
                                <p id="sample_text" style="color: #1f2937;" class="mb-3">This is how your text will look with the selected colors.</p>
                                <button id="sample_button" class="px-4 py-2 text-white rounded-lg" style="background-color: #f36c21;">Sample Button</button>
                            </div>
                        </div>

                        <!-- Save Button -->
                        <div class="flex justify-end space-x-3 pt-4">
                            <button type="button" onclick="resetThemeSettings()" 
                                    class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                <i class="fas fa-undo mr-2"></i>
                                Reset
                            </button>
                            <button type="button" onclick="saveThemeSettings()" 
                                    class="px-6 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors">
                                <i class="fas fa-save mr-2"></i>
                                Save Theme Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Verification Settings -->
        <div id="verification-settings" class="settings-section">
            <div class="bg-white rounded-xl shadow-lg mb-6">
                <div class="bg-gradient-to-r from-teal-50 to-teal-100 px-6 py-4 border-b border-teal-200">
                    <h3 class="text-lg font-semibold text-teal-800">
                        <i class="fas fa-check-circle mr-2"></i>
                        Verification Settings
                    </h3>
                    <p class="text-sm text-teal-600">Configure OTP verification for customer registration</p>
                </div>
                <div class="p-6">
                    <form id="verification-form">
                        
                        <!-- Email Verification Settings -->
                        <div class="mb-8">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-lg font-semibold text-gray-800 flex items-center">
                                    <i class="fas fa-envelope mr-2 text-blue-500"></i>
                                    Email Verification
                                </h4>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="email_verification_enabled" class="sr-only peer" checked>
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    <span class="ml-3 text-sm font-medium text-gray-700">Enable</span>
                                </label>
                            </div>
                            
                            <div class="space-y-4 pl-8">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            OTP Code Length
                                        </label>
                                        <input type="number" name="email_otp_length" value="6" min="4" max="8"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                                        <p class="text-xs text-gray-500 mt-1">Number of digits in OTP code (4-8)</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            OTP Expiry Time (minutes)
                                        </label>
                                        <input type="number" name="email_otp_expiry" value="10" min="1" max="60"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                                        <p class="text-xs text-gray-500 mt-1">How long OTP remains valid (1-60 minutes)</p>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Maximum Attempts
                                        </label>
                                        <input type="number" name="email_max_attempts" value="5" min="3" max="10"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                                        <p class="text-xs text-gray-500 mt-1">Max incorrect attempts before blocking (3-10)</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Resend Cooldown (seconds)
                                        </label>
                                        <input type="number" name="email_resend_cooldown" value="60" min="30" max="300"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                                        <p class="text-xs text-gray-500 mt-1">Wait time before resending (30-300 seconds)</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-8 border-gray-200">

                        <!-- SMS Verification Settings -->
                        <div class="mb-8">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-lg font-semibold text-gray-800 flex items-center">
                                    <i class="fas fa-mobile-alt mr-2 text-green-500"></i>
                                    SMS Verification
                                </h4>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="sms_verification_enabled" class="sr-only peer" checked>
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                                    <span class="ml-3 text-sm font-medium text-gray-700">Enable</span>
                                </label>
                            </div>
                            
                            <div class="space-y-4 pl-8">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            OTP Code Length
                                        </label>
                                        <input type="number" name="sms_otp_length" value="6" min="4" max="8"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                                        <p class="text-xs text-gray-500 mt-1">Number of digits in OTP code (4-8)</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            OTP Expiry Time (minutes)
                                        </label>
                                        <input type="number" name="sms_otp_expiry" value="10" min="1" max="60"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                                        <p class="text-xs text-gray-500 mt-1">How long OTP remains valid (1-60 minutes)</p>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Maximum Attempts
                                        </label>
                                        <input type="number" name="sms_max_attempts" value="5" min="3" max="10"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                                        <p class="text-xs text-gray-500 mt-1">Max incorrect attempts before blocking (3-10)</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Resend Cooldown (seconds)
                                        </label>
                                        <input type="number" name="sms_resend_cooldown" value="60" min="30" max="300"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                                        <p class="text-xs text-gray-500 mt-1">Wait time before resending (30-300 seconds)</p>
                                    </div>
                                </div>
                                
                                <!-- SMS API Configuration -->
                                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                    <h5 class="text-sm font-semibold text-gray-800 mb-3">SMS API Configuration</h5>
                                    <div class="space-y-3">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">API Key</label>
                                            <input type="text" name="sms_api_key" 
                                                   value="iSGZnhYmaTSPMiDzx3sfs3BXumiUQqR4sY6coSgEdwU="
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Client ID</label>
                                            <input type="text" name="sms_client_id" 
                                                   value="4cd3f386-56d5-4e1d-8083-441713cad419"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Sender ID</label>
                                                <input type="text" name="sms_sender_id" 
                                                       value="8809601010905"
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">API URL</label>
                                                <input type="text" name="sms_api_url" 
                                                       value="http://103.69.149.50/api/v2/SendSMS"
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                                            </div>
                                        </div>
                                        <button type="button" onclick="testSMS()" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                            <i class="fas fa-paper-plane mr-2"></i>
                                            Test SMS Configuration
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-8 border-gray-200">

                        <!-- Both Verification Settings -->
                        <div class="mb-8">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-shield-alt mr-2 text-purple-500"></i>
                                Dual Verification Settings
                            </h4>
                            
                            <div class="space-y-4 pl-8">
                                <div class="flex items-center justify-between p-4 bg-purple-50 rounded-lg border border-purple-200">
                                    <div>
                                        <label class="text-sm font-medium text-gray-800">Allow Both Email & SMS Verification</label>
                                        <p class="text-xs text-gray-600 mt-1">Users can choose to verify via both methods for enhanced security</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="both_verification_enabled" class="sr-only peer" checked>
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                                    </label>
                                </div>
                                
                                <div class="flex items-center justify-between p-4 bg-red-50 rounded-lg border border-red-200">
                                    <div>
                                        <label class="text-sm font-medium text-gray-800">Require Both Verifications</label>
                                        <p class="text-xs text-gray-600 mt-1">Force users to verify both email and phone (not recommended)</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="both_verification_required" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-600"></div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <hr class="my-8 border-gray-200">

                        <!-- General Verification Settings -->
                        <div class="mb-8">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-cog mr-2 text-gray-500"></i>
                                General Settings
                            </h4>
                            
                            <div class="space-y-4 pl-8">
                                <div class="flex items-center justify-between p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                                    <div>
                                        <label class="text-sm font-medium text-gray-800">Allow Skip in Debug Mode</label>
                                        <p class="text-xs text-gray-600 mt-1">Show "Skip Verification" button when APP_DEBUG=true</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="skip_verification_in_debug" class="sr-only peer" checked>
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-yellow-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-yellow-600"></div>
                                    </label>
                                </div>
                                
                                <div class="flex items-center justify-between p-4 bg-blue-50 rounded-lg border border-blue-200">
                                    <div>
                                        <label class="text-sm font-medium text-gray-800">Require Verification for Checkout</label>
                                        <p class="text-xs text-gray-600 mt-1">Users must verify account before making purchases</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="verification_required_for_checkout" class="sr-only peer" checked>
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Save Button -->
                        <div class="flex justify-end space-x-3 pt-4">
                            <button type="button" onclick="resetVerificationSettings()" 
                                    class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                <i class="fas fa-undo mr-2"></i>
                                Reset
                            </button>
                            <button type="button" onclick="saveVerificationSettings()" 
                                    class="px-6 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors">
                                <i class="fas fa-save mr-2"></i>
                                Save Verification Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Security Settings -->
        <div id="security-settings" class="settings-section">
            <div class="bg-white rounded-xl shadow-lg mb-6">
                <div class="bg-gradient-to-r from-red-50 to-red-100 px-6 py-4 border-b border-red-200">
                    <h3 class="text-lg font-semibold text-red-800">Security Settings</h3>
                    <p class="text-sm text-red-600">Configure security and access controls</p>
                </div>
                <div class="p-6">
                    <form id="security-form">
                        <div class="space-y-6">
                            <div>
                                <h4 class="text-md font-semibold text-gray-800 mb-4">Password Policy</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Password Length</label>
                                        <input type="number" name="min_password_length" value="8" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Password Expiry (Days)</label>
                                        <input type="number" name="password_expiry" value="90" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="border-t pt-6">
                                <h4 class="text-md font-semibold text-gray-800 mb-4">Login Security</h4>
                                <div class="space-y-4">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="two_factor_auth" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                        <span class="ml-2 text-sm text-gray-700">Enable Two-Factor Authentication</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="login_notifications" checked class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                        <span class="ml-2 text-sm text-gray-700">Send login notifications</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="session_timeout" checked class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                        <span class="ml-2 text-sm text-gray-700">Enable session timeout</span>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="border-t pt-6">
                                <h4 class="text-md font-semibold text-gray-800 mb-4">Access Control</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Session Timeout (Minutes)</label>
                                        <input type="number" name="session_timeout_minutes" value="30" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Max Login Attempts</label>
                                        <input type="number" name="max_login_attempts" value="5" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Save Button -->
                        <div class="flex justify-end space-x-3 pt-6 mt-6 border-t border-gray-200">
                            <button type="button" onclick="saveSettings('security')" 
                                    class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                <i class="fas fa-save mr-2"></i>
                                Save Security Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Show settings section
function showSettings(section) {
    // Hide all sections
    document.querySelectorAll('.settings-section').forEach(sec => {
        sec.classList.remove('active');
    });
    
    // Remove active class from all nav items
    document.querySelectorAll('.settings-nav-item').forEach(item => {
        item.classList.remove('active');
    });
    
    // Show selected section
    document.getElementById(section + '-settings').classList.add('active');
    
    // Add active class to clicked nav item
    event.target.classList.add('active');
}

// Save specific settings section
function saveSettings(section) {
    const formId = section + '-form';
    const form = document.getElementById(formId);
    
    if (!form) {
        showNotification('Form not found', 'error');
        return;
    }
    
    const formData = new FormData(form);
    const data = {};
    
    // Collect all form data including checkboxes
    for (let [key, value] of formData.entries()) {
        data[key] = value;
    }
    
    // Handle unchecked checkboxes
    const checkboxes = form.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
        if (!checkbox.name.includes('[]')) { // Skip multi-value checkboxes
            if (!formData.has(checkbox.name)) {
                data[checkbox.name] = 'false';
            } else {
                data[checkbox.name] = 'true';
            }
        }
    });
    
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
    button.disabled = true;
    
    // Send AJAX request
    fetch('{{ route("admin.settings.update") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        button.innerHTML = originalText;
        button.disabled = false;
        
        if (data.success) {
            showNotification('Settings saved successfully!', 'success');
        } else {
            showNotification('Error: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        button.innerHTML = originalText;
        button.disabled = false;
        showNotification('Failed to save settings', 'error');
    });
}

// Save all settings
function saveAllSettings() {
    const forms = ['general-form', 'store-form', 'email-form', 'payment-form', 'shipping-form', 'seo-form', 'theme-form', 'verification-form', 'security-form'];
    const allData = {};
    
    forms.forEach(formId => {
        const form = document.getElementById(formId);
        if (form) {
            const formData = new FormData(form);
            for (let [key, value] of formData.entries()) {
                allData[key] = value;
            }
            
            // Handle unchecked checkboxes
            const checkboxes = form.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(checkbox => {
                if (!checkbox.name.includes('[]')) {
                    if (!formData.has(checkbox.name)) {
                        allData[checkbox.name] = 'false';
                    } else {
                        allData[checkbox.name] = 'true';
                    }
                }
            });
        }
    });
    
    // Show loading state
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
    button.disabled = true;
    
    // Send AJAX request
    fetch('{{ route("admin.settings.update") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(allData)
    })
    .then(response => response.json())
    .then(data => {
        button.innerHTML = originalText;
        button.disabled = false;
        
        if (data.success) {
            showNotification('All settings saved successfully!', 'success');
        } else {
            showNotification('Error: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        button.innerHTML = originalText;
        button.disabled = false;
        showNotification('Failed to save settings', 'error');
    });
}

// Reset all settings
function resetAllSettings() {
    if (confirm('Are you sure you want to reset ALL settings to default values? This will restore:\n\n- Site name to "K-Glow BD"\n- Primary color to #f36c21\n- Default logos\n- All email, payment, shipping settings\n- All verification settings\n\nThis action cannot be undone!')) {
        // Show loading state
        const button = event.target;
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Resetting...';
        button.disabled = true;
        
        // Send reset request
        fetch('{{ route("admin.settings.reset") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ group: 'all' })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('All settings reset to defaults!', 'success');
                // Reload page after 1 second
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                button.innerHTML = originalText;
                button.disabled = false;
                showNotification('Error: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            button.innerHTML = originalText;
            button.disabled = false;
            showNotification('Failed to reset settings', 'error');
        });
    }
}

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

// Save Verification Settings
function saveVerificationSettings() {
    const form = document.getElementById('verification-form');
    const formData = new FormData(form);
    const data = {};
    
    // Collect all form data including checkboxes
    for (let [key, value] of formData.entries()) {
        data[key] = value;
    }
    
    // Handle unchecked checkboxes
    const checkboxes = form.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
        if (!formData.has(checkbox.name)) {
            data[checkbox.name] = 'false';
        } else {
            data[checkbox.name] = 'true';
        }
    });
    
    // Show loading
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
    button.disabled = true;
    
    // Send AJAX request
    fetch('{{ route("admin.settings.verification.update") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        button.innerHTML = originalText;
        button.disabled = false;
        
        if (data.success) {
            showNotification('Verification settings saved successfully!', 'success');
        } else {
            showNotification('Error saving settings: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        button.innerHTML = originalText;
        button.disabled = false;
        showNotification('Failed to save settings. Please try again.', 'error');
    });
}

// Reset Verification Settings
function resetVerificationSettings() {
    if (confirm('Reset verification settings to default values?')) {
        const form = document.getElementById('verification-form');
        
        // Reset form to defaults
        form.querySelector('input[name="email_verification_enabled"]').checked = true;
        form.querySelector('input[name="email_otp_length"]').value = 6;
        form.querySelector('input[name="email_otp_expiry"]').value = 10;
        form.querySelector('input[name="email_max_attempts"]').value = 5;
        form.querySelector('input[name="email_resend_cooldown"]').value = 60;
        
        form.querySelector('input[name="sms_verification_enabled"]').checked = true;
        form.querySelector('input[name="sms_otp_length"]').value = 6;
        form.querySelector('input[name="sms_otp_expiry"]').value = 10;
        form.querySelector('input[name="sms_max_attempts"]').value = 5;
        form.querySelector('input[name="sms_resend_cooldown"]').value = 60;
        
        form.querySelector('input[name="both_verification_enabled"]').checked = true;
        form.querySelector('input[name="both_verification_required"]').checked = false;
        form.querySelector('input[name="skip_verification_in_debug"]').checked = true;
        form.querySelector('input[name="verification_required_for_checkout"]').checked = true;
        
        showNotification('Settings reset to defaults', 'info');
    }
}

// Test SMS Configuration
function testSMS() {
    const apiKey = document.querySelector('input[name="sms_api_key"]').value;
    const clientId = document.querySelector('input[name="sms_client_id"]').value;
    const senderId = document.querySelector('input[name="sms_sender_id"]').value;
    const apiUrl = document.querySelector('input[name="sms_api_url"]').value;
    
    // Prompt for test phone number
    const phone = prompt('Enter test phone number (e.g., 01712345678):');
    
    if (!phone) {
        return;
    }
    
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Sending Test SMS...';
    button.disabled = true;
    
    // Send test SMS
    fetch('{{ route("admin.settings.verification.test-sms") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            phone: phone,
            api_key: apiKey,
            client_id: clientId,
            sender_id: senderId,
            api_url: apiUrl
        })
    })
    .then(response => response.json())
    .then(data => {
        button.innerHTML = originalText;
        button.disabled = false;
        
        if (data.success) {
            showNotification('Test SMS sent successfully! Check phone: ' + phone, 'success');
        } else {
            showNotification('Error: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        button.innerHTML = originalText;
        button.disabled = false;
        showNotification('Failed to send test SMS. Check console for details.', 'error');
    });
}

// Save Theme Settings
function saveThemeSettings() {
    const form = document.getElementById('theme-form');
    const formData = new FormData(form);
    const data = {};
    
    // Collect form data
    for (let [key, value] of formData.entries()) {
        // Use color picker value, not hex input
        if (key.endsWith('_hex')) {
            const colorKey = key.replace('_hex', '');
            data[colorKey] = value;
        } else if (!key.includes('_hex')) {
            data[key] = value;
        }
    }
    
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
    button.disabled = true;
    
    fetch('{{ route("admin.settings.update") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        button.innerHTML = originalText;
        button.disabled = false;
        
        if (data.success) {
            showNotification('Theme settings saved successfully!', 'success');
        } else {
            showNotification('Error: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        button.innerHTML = originalText;
        button.disabled = false;
        showNotification('Failed to save theme settings', 'error');
    });
}

// Reset Theme Settings
function resetThemeSettings() {
    if (confirm('Reset theme settings to default colors?')) {
        document.querySelector('input[name="primary_color"]').value = '#f36c21';
        document.querySelector('input[name="primary_color_hex"]').value = '#f36c21';
        document.querySelector('input[name="secondary_color"]').value = '#0a0a0a';
        document.querySelector('input[name="secondary_color_hex"]').value = '#0a0a0a';
        document.querySelector('input[name="accent_color"]').value = '#fef3c7';
        document.querySelector('input[name="accent_color_hex"]').value = '#fef3c7';
        document.querySelector('input[name="background_color"]').value = '#ffffff';
        document.querySelector('input[name="background_color_hex"]').value = '#ffffff';
        document.querySelector('input[name="text_color"]').value = '#1f2937';
        document.querySelector('input[name="text_color_hex"]').value = '#1f2937';
        document.querySelector('input[name="heading_color"]').value = '#111827';
        document.querySelector('input[name="heading_color_hex"]').value = '#111827';
        
        updateColorPreviews();
        showNotification('Theme reset to defaults', 'info');
    }
}

// Upload Logo/Favicon
function uploadLogo(type) {
    const fileInput = document.getElementById(type + '_file');
    const file = fileInput.files[0];
    
    if (!file) return;
    
    const formData = new FormData();
    formData.append('file', file);
    formData.append('setting_key', type);
    
    fetch('{{ route("admin.settings.upload") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById(type + '_preview').src = data.file_url + '?t=' + new Date().getTime();
            showNotification('File uploaded successfully!', 'success');
        } else {
            showNotification('Error: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Failed to upload file', 'error');
    });
}

// Update color previews
function updateColorPreviews() {
    const primaryColor = document.querySelector('input[name="primary_color"]').value;
    const secondaryColor = document.querySelector('input[name="secondary_color"]').value;
    const accentColor = document.querySelector('input[name="accent_color"]').value;
    const backgroundColor = document.querySelector('input[name="background_color"]').value;
    const textColor = document.querySelector('input[name="text_color"]').value;
    const headingColor = document.querySelector('input[name="heading_color"]').value;
    
    // Update preview boxes
    document.getElementById('primary_preview').style.backgroundColor = primaryColor;
    document.getElementById('secondary_preview').style.backgroundColor = secondaryColor;
    document.getElementById('accent_preview').style.backgroundColor = accentColor;
    document.getElementById('background_preview').style.backgroundColor = backgroundColor;
    
    // Update sample UI
    document.getElementById('sample_preview').style.backgroundColor = backgroundColor;
    document.getElementById('sample_heading').style.color = headingColor;
    document.getElementById('sample_text').style.color = textColor;
    document.getElementById('sample_button').style.backgroundColor = primaryColor;
}

// Form validation
document.addEventListener('DOMContentLoaded', function() {
    // Add form validation
    const forms = document.querySelectorAll('form[id$="-form"]');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            // Add validation logic here
        });
    });
    
    // Load verification settings on page load
    loadVerificationSettings();
    
    // Load all settings
    loadAllSettings();
    
    // Add color picker sync with hex input
    const colorInputs = document.querySelectorAll('input[type="color"]');
    colorInputs.forEach(input => {
        input.addEventListener('input', function() {
            const hexInput = document.querySelector(`input[name="${this.name}_hex"]`);
            if (hexInput) {
                hexInput.value = this.value;
            }
            updateColorPreviews();
        });
    });
    
    // Sync hex input with color picker
    const hexInputs = document.querySelectorAll('input[name$="_hex"]');
    hexInputs.forEach(input => {
        input.addEventListener('input', function() {
            const colorKey = this.name.replace('_hex', '');
            const colorInput = document.querySelector(`input[name="${colorKey}"][type="color"]`);
            if (colorInput && /^#[0-9A-F]{6}$/i.test(this.value)) {
                colorInput.value = this.value;
                updateColorPreviews();
            }
        });
    });
});

// Load all settings from database
function loadAllSettings() {
    fetch('{{ route("admin.settings.get") }}')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.settings) {
                const settings = data.settings;
                
                // Populate all form fields
                Object.keys(settings).forEach(key => {
                    const input = document.querySelector(`[name="${key}"]`);
                    if (input) {
                        if (input.type === 'checkbox') {
                            input.checked = settings[key] === true || settings[key] === 'true' || settings[key] === '1';
                        } else if (input.type === 'color') {
                            input.value = settings[key];
                            const hexInput = document.querySelector(`[name="${key}_hex"]`);
                            if (hexInput) {
                                hexInput.value = settings[key];
                            }
                        } else {
                            input.value = settings[key];
                        }
                    }
                });
                
                // Update color previews after loading
                updateColorPreviews();
            }
        })
        .catch(error => {
            console.error('Error loading settings:', error);
        });
}

// Load verification settings from database
function loadVerificationSettings() {
    fetch('{{ route("admin.settings.verification.get") }}')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.settings) {
                const settings = data.settings;
                const form = document.getElementById('verification-form');
                
                // Populate form fields
                Object.keys(settings).forEach(key => {
                    const input = form.querySelector(`[name="${key}"]`);
                    if (input) {
                        if (input.type === 'checkbox') {
                            input.checked = settings[key] === true || settings[key] === 'true' || settings[key] === '1';
                        } else {
                            input.value = settings[key];
                        }
                    }
                });
            }
        })
        .catch(error => {
            console.error('Error loading verification settings:', error);
        });
}
</script>
@endsection