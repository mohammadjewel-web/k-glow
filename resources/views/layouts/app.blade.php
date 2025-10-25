<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $siteName ?? 'K-Glow')</title>

    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @php
        use App\Models\Setting;
        $primaryColor = Setting::get('primary_color', '#f36c21');
        $textColor = Setting::get('text_color', '#1f2937');
        $headingColor = Setting::get('heading_color', '#111827');
        $backgroundColor = Setting::get('background_color', '#ffffff');
        $siteName = Setting::get('site_name', 'K-Glow');
        $favicon = Setting::get('favicon', 'favicon.ico');
        $logo = Setting::get('logo', 'admin-assets/logo.png');
        $whiteLogo = Setting::get('white_logo', 'admin-assets/white-logo.png');
    @endphp

    <!-- Custom Colors -->
    <script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    'brand-orange': '{{ $primaryColor }}',
                }
            }
        }
    }
    </script>
    <link rel="icon" href="{{ asset($favicon) }}" type="image/x-icon" />

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
    :root {
        --brand-orange: {{ $primaryColor }};
        --text-color: {{ $textColor }};
        --heading-color: {{ $headingColor }};
        --background-color: {{ $backgroundColor }};
    }

    .hero-dot {
        width: 8px;
        height: 8px;
        border-radius: 9999px;
    }
    
    body {
        color: var(--text-color);
        background-color: var(--background-color);
    }
    
    h1, h2, h3, h4, h5, h6 {
        color: var(--heading-color);
    }
    </style>
    <script src="https://unpkg.com/alpinejs" defer></script>
</head>

<body class="antialiased" style="color: {{ $textColor }}; background-color: #f9fafb;">


    <!-- Header -->
    <header class="glass fixed top-0 left-0 right-0 z-50 shadow-sm bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <div class="flex items-center gap-4">
                    <a href="{{ route('home') }}" class="flex items-center gap-3">
                        <img src="{{ asset($logo ?? 'admin-assets/logo.png') }}" alt="{{ $siteName ?? 'K-Glow' }}"
                            class="h-12 w-auto object-contain rounded" />
                    </a>
                </div>

                <!-- Search Bar -->
                <div class="flex-1 px-6 hidden md:block">
                    <div class="relative max-w-2xl mx-auto">
                        <form action="{{ route('shop') }}" method="GET" class="relative">
                            <input 
                                type="search" 
                                name="search"
                                placeholder="Search for products..."
                                value="{{ request('search') }}"
                                class="w-full border border-gray-300 rounded-full pl-12 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-[var(--brand-orange)] focus:border-transparent transition-all duration-200" 
                            />
                            <svg class="w-5 h-5 absolute left-4 top-3.5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z" />
                        </svg>
                            <button type="submit" class="absolute right-2 top-2 bottom-2 px-4 bg-[var(--brand-orange)] text-white rounded-full hover:bg-orange-600 transition-colors duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>

            <!-- Mobile Search Button -->
            <button class="md:hidden p-2 rounded-md hover:bg-gray-100" @click="$dispatch('toggle-mobile-menu')">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z" />
                </svg>
            </button>

            <!-- Mobile Menu Button -->
            <button class="lg:hidden md:block p-2 rounded-md hover:bg-gray-100" @click="$dispatch('toggle-mobile-menu')">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

                <div class="flex items-center gap-4">
                <!-- Comparison Button -->
                <button onclick="openComparisonModal()" class="relative px-3 py-2 rounded hover:bg-gray-100">
                    <svg class="w-6 h-6 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span id="comparisonCount"
                        class="absolute -top-1 -right-1 bg-green-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">0</span>
                </button>
                
                <!-- Wishlist Button -->
                @auth
                <a href="{{ route('customer.wishlist') }}" class="relative px-3 py-2 rounded hover:bg-gray-100">
                    <svg class="w-6 h-6 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    <span id="wishlistCount"
                        class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">0</span>
                </a>
                
                <!-- Notifications Dropdown -->
                <x-notification-dropdown />
                @endauth

                <!-- Cart Button -->
                <button onclick="openCartModal()" class="relative px-3 py-2 rounded hover:bg-gray-100">
                    <svg class="w-6 h-6 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 7h13l-2-7M16 21a1 1 0 11-2 0 1 1 0 012 0zm-8 0a1 1 0 11-2 0 1 1 0 012 0z" />
                    </svg>
                    <span id="cartCount"
                        class="absolute -top-1 -right-1 bg-orange-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">{{ session('cart') ? array_sum(array_column(session('cart'), 'quantity')) : 0 }}</span>
                </button>
                    
                    @auth
                        <!-- Authenticated User Menu -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-2 px-3 py-2 rounded hover:bg-gray-100">
                                @if(Auth::user()->avatar && file_exists(public_path('admin-assets/avatars/'.Auth::user()->avatar)))
                                    <img src="{{ asset('admin-assets/avatars/'.Auth::user()->avatar) }}" 
                                         class="w-8 h-8 rounded-full object-cover" alt="Profile">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-[var(--brand-orange)] text-white flex items-center justify-center font-semibold">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                @endif
                                <span class="hidden md:block">{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                    </button>
                            
                            <div x-show="open" @click.away="open = false" 
                                 class="absolute right-0 mt-2 w-48 bg-white shadow-lg rounded-lg overflow-hidden z-50">
                                <a href="{{ route('customer.dashboard') }}" class="block px-4 py-2 hover:bg-gray-100">My Dashboard</a>
                                <a href="{{ route('customer.orders') }}" class="block px-4 py-2 hover:bg-gray-100">My Orders</a>
                                <a href="{{ route('customer.profile') }}" class="block px-4 py-2 hover:bg-gray-100">My Profile</a>
                                @can('manage products')
                                    <a href="{{ route('admin.products.index') }}" class="block px-4 py-2 hover:bg-gray-100">Admin Panel</a>
                                @endcan
                                <form action="{{ route('logout') }}" method="POST" class="block">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100 text-red-600">Logout</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <!-- Guest User Buttons -->
                    <button id="loginBtn"
                        class="px-4 py-2 bg-[var(--brand-orange)] text-white rounded shadow hover:bg-orange-600">
                        Login
                    </button>
                    <button id="registerBtn" class="px-4 py-2 bg-gray-700 text-white rounded shadow hover:bg-gray-800">
                        Register
                    </button>
                    @endauth

                    <!-- Modal Overlay -->
                    <div id="modalOverlay" class="fixed inset-0 bg-black/40 hidden z-40"></div>

                    <!-- Right Side Modal -->
                    <div id="sideModal"
                        class="fixed top-0 right-0 w-96 max-w-full h-full bg-white shadow-lg transform translate-x-full transition-transform duration-300 z-50">
                        <div class="flex justify-between items-center p-4 border-b">
                            <h2 id="modalTitle" class="text-xl font-semibold">Login</h2>
                            <button id="closeModal" class="text-gray-600 hover:text-gray-900 text-2xl font-bold">
                                &times;
                            </button>
                        </div>

                        <div class="p-6">
                            <!-- Login Form -->
                            <form id="loginForm" method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="mb-4">
                                    <label class="block text-sm font-medium mb-1">Email</label>
                                    <input type="email" name="email" required
                                        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-orange-400"
                                        placeholder="Enter your email" value="{{ old('email') }}" />
                                    @error('email')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium mb-1">Password</label>
                                    <input type="password" name="password" required
                                        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-orange-400"
                                        placeholder="Enter your password" />
                                    @error('password')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="remember" class="rounded text-orange-500">
                                        <span class="ml-2 text-sm text-gray-600">Remember me</span>
                                    </label>
                                </div>
                                <button type="submit"
                                    class="w-full py-2 bg-[var(--brand-orange)] text-white rounded hover:bg-orange-600 transition">
                                    Login
                                </button>
                                <p class="mt-4 text-sm text-gray-500 text-center">
                                    Don't have an account?
                                    <span id="switchToRegister"
                                        class="text-[var(--brand-orange)] cursor-pointer hover:underline">Register</span>
                                </p>
                            </form>

                            <!-- Register Form -->
                            <form id="registerForm" method="POST" action="{{ route('register') }}" class="hidden">
                                @csrf
                                <div class="mb-4">
                                    <label class="block text-sm font-medium mb-1">Full Name</label>
                                    <input type="text" name="name" required
                                        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-orange-400"
                                        placeholder="Enter your name" value="{{ old('name') }}" />
                                    @error('name')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium mb-1">Email</label>
                                    <input type="email" name="email" required
                                        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-orange-400"
                                        placeholder="Enter your email" value="{{ old('email') }}" />
                                    @error('email')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium mb-1">Phone</label>
                                    <input type="text" name="phone" required
                                        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-orange-400"
                                        placeholder="Enter your phone" value="{{ old('phone') }}" />
                                    @error('phone')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium mb-1">Password</label>
                                    <input type="password" name="password" required
                                        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-orange-400"
                                        placeholder="Enter your password" />
                                    @error('password')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium mb-1">Confirm Password</label>
                                    <input type="password" name="password_confirmation" required
                                        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-orange-400"
                                        placeholder="Confirm your password" />
                                </div>
                                <button type="submit"
                                    class="w-full py-2 bg-[var(--brand-orange)] text-white rounded hover:bg-orange-600 transition">
                                    Register
                                </button>
                                <p class="mt-4 text-sm text-gray-500 text-center">
                                    Already have an account?
                                    <span id="switchToLogin"
                                        class="text-[var(--brand-orange)] cursor-pointer hover:underline">Login</span>
                                </p>
                            </form>
                        </div>
                    </div>
                    <button id="menuBtn" class="sm:hidden px-3 py-2">☰</button>
                </div>
            </div>
        </div>

        <!-- nav -->

        <nav class="border-t relative bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <ul class="flex gap-6 py-3 text-sm justify-center">
                    <!-- Dynamic Navigation based on nav field -->
                    @php
                        $navCategories = \App\Models\Category::where('status', 1)
                            ->where('nav', 1)
                            ->with(['subcategories' => function($query) {
                                $query->where('status', 1)->where('nav', 1);
                            }])
                            ->orderBy('name')
                            ->get();
                        
                        $navBrands = \App\Models\Brand::where('status', 1)
                            ->where('nav', 1)
                            ->orderBy('name')
                            ->get();
                    @endphp
                    
                    @foreach($navCategories as $category)
                        <li class="relative group">
                            <a href="{{ route('shop', ['categories' => [$category->id]]) }}" 
                               class="hover:text-[var(--brand-orange)] flex items-center gap-1">
                                {{ strtoupper($category->name) }}
                                @if($category->subcategories->count() > 0)
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M5.23 7.21a.75.75 0 011.06-.02L10 10.92l3.71-3.73a.75.75 0 111.08 1.04l-4.25 4.25a.75.75 0 01-1.06 0L5.25 8.23a.75.75 0 01-.02-1.06z" />
                                    </svg>
                                @endif
                            </a>
                            
                            @if($category->subcategories->count() > 0)
                                <!-- Subcategories Mega Menu -->
                                <div class="absolute left-1/2 transform -translate-x-1/2 top-full w-80 bg-white shadow-lg border border-gray-200 rounded-lg p-6 opacity-0 group-hover:opacity-100 invisible group-hover:visible transition-all duration-300 z-50">
                                    <div class="mb-3">
                                        <h3 class="font-bold text-gray-800 text-sm uppercase tracking-wide">{{ $category->name }}</h3>
                                        <div class="w-8 h-0.5 bg-[var(--brand-orange)] mt-1"></div>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 gap-2">
                                        @foreach($category->subcategories as $subcategory)
                                            <a href="{{ route('shop', ['categories' => [$category->id], 'subcategories' => [$subcategory->id]]) }}" 
                                               class="flex items-center justify-between px-3 py-2 hover:bg-gray-50 rounded-md text-sm transition-colors group/item">
                                                <span class="text-gray-700 group-hover/item:text-[var(--brand-orange)]">{{ $subcategory->name }}</span>
                                                <svg class="w-3 h-3 text-gray-400 group-hover/item:text-[var(--brand-orange)] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </a>
                                        @endforeach
                                    </div>
                                    
                                    <!-- View All Category Link -->
                                    <div class="mt-4 pt-3 border-t border-gray-100">
                                        <a href="{{ route('shop', ['categories' => [$category->id]]) }}" 
                                           class="flex items-center justify-center w-full px-3 py-2 bg-[var(--brand-orange)] text-white rounded-md text-sm font-medium hover:bg-orange-600 transition-colors">
                                            View All {{ $category->name }}
                                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </li>
                    @endforeach
                    
                    @if($navBrands->count() > 0)
                        <!-- Brands Dropdown -->
                    <li class="relative group">
                        <a href="#" class="hover:text-[var(--brand-orange)] flex items-center gap-1">
                                BRANDS
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M5.23 7.21a.75.75 0 011.06-.02L10 10.92l3.71-3.73a.75.75 0 111.08 1.04l-4.25 4.25a.75.75 0 01-1.06 0L5.25 8.23a.75.75 0 01-.02-1.06z" />
                            </svg>
                        </a>
                            <div class="absolute left-1/2 transform -translate-x-1/2 top-full w-64 bg-white shadow-lg border border-gray-200 rounded-lg p-6 opacity-0 group-hover:opacity-100 invisible group-hover:visible transition-all duration-300 z-50">
                                <div class="mb-3">
                                    <h3 class="font-bold text-gray-800 text-sm uppercase tracking-wide">Popular Brands</h3>
                                    <div class="w-8 h-0.5 bg-[var(--brand-orange)] mt-1"></div>
                                </div>
                                
                                <div class="grid grid-cols-1 gap-2">
                                    @foreach($navBrands as $brand)
                                        <a href="{{ route('shop', ['brands' => [$brand->id]]) }}" 
                                           class="flex items-center justify-between px-3 py-2 hover:bg-gray-50 rounded-md text-sm transition-colors group/item">
                                            <span class="text-gray-700 group-hover/item:text-[var(--brand-orange)]">{{ $brand->name }}</span>
                                            <svg class="w-3 h-3 text-gray-400 group-hover/item:text-[var(--brand-orange)] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                        </a>
                                    @endforeach
                                </div>
                                
                                <!-- View All Brands Link -->
                                <div class="mt-4 pt-3 border-t border-gray-100">
                                    <a href="{{ route('shop') }}" 
                                       class="flex items-center justify-center w-full px-3 py-2 bg-gray-100 text-gray-700 rounded-md text-sm font-medium hover:bg-gray-200 transition-colors">
                                        View All Brands
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                            </div>
                        </div>
                    </li>
                    @endif
                    
                    <!-- Static Menu Items -->
                    <li>
                        <a href="{{ route('shop') }}" class="hover:text-[var(--brand-orange)]">SHOP ALL</a>
                    </li>
                    <li>
                        <a href="{{ route('contact') }}" class="hover:text-[var(--brand-orange)]">CONTACT</a>
                    </li>
                    <li>
                        <a href="#" class="font-semibold hover:text-[var(--brand-orange)]">SPECIAL OFFER</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <!-- Mobile Navigation Menu -->
    <div class="lg:hidden" x-data="{ open: false }" x-show="open" x-transition @toggle-mobile-menu.window="open = !open">
        <div class="bg-white shadow-lg border-t">
            <div class="px-4 py-2">
                <!-- Mobile Search -->
                <div class="relative mb-4">
                    <form action="{{ route('shop') }}" method="GET" class="relative">
                        <input 
                            type="search" 
                            name="search"
                            placeholder="Search for products..."
                            value="{{ request('search') }}"
                            class="w-full border border-gray-300 rounded-full pl-10 pr-12 py-2 focus:outline-none focus:ring-2 focus:ring-[var(--brand-orange)] focus:border-transparent transition-all duration-200" 
                        />
                        <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z" />
                        </svg>
                        <button type="submit" class="absolute right-1 top-1 bottom-1 px-3 bg-[var(--brand-orange)] text-white rounded-full hover:bg-orange-600 transition-colors duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z" />
                            </svg>
                        </button>
                    </form>
                </div>
                
                <!-- Mobile Menu Items -->
                <div class="space-y-1">
                    <a href="{{ route('home') }}" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded">Home</a>
                    <a href="{{ route('shop') }}" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded">Shop</a>
                    
                    <!-- Mobile Categories -->
                    <div x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center justify-between w-full px-3 py-2 text-gray-700 hover:bg-gray-100 rounded">
                            <span>Categories</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="open" class="pl-4 space-y-1">
                            @php
                                $mobileCategories = \App\Models\Category::where('status', 1)
                                    ->where('nav', 1)
                                    ->with(['subcategories' => function($query) {
                                        $query->where('status', 1)->where('nav', 1);
                                    }])
                                    ->withCount(['products' => function($query) {
                                        $query->where('status', 1);
                                    }])
                                    ->orderBy('name')
                                    ->get();
                            @endphp
                            
                            @foreach($mobileCategories as $category)
                                <div class="space-y-1">
                                    <!-- Category Link -->
                                    <a href="{{ route('shop', ['categories' => [$category->id]]) }}" 
                                       class="block px-3 py-2 text-sm font-medium text-gray-800 hover:bg-gray-100 rounded">
                                        {{ $category->name }} ({{ $category->products_count }})
                                    </a>
                                    
                                    <!-- Subcategories -->
                                    @if($category->subcategories->count() > 0)
                                        <div class="pl-4 space-y-1">
                                            @foreach($category->subcategories as $subcategory)
                                                <a href="{{ route('shop', ['categories' => [$category->id], 'subcategories' => [$subcategory->id]]) }}" 
                                                   class="block px-3 py-1 text-xs text-gray-600 hover:bg-gray-50 rounded">
                                                    • {{ $subcategory->name }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Mobile Brands -->
                    <div x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center justify-between w-full px-3 py-2 text-gray-700 hover:bg-gray-100 rounded">
                            <span>Brands</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="open" class="pl-4 space-y-1">
                            @php
                                $mobileBrands = \App\Models\Brand::where('status', 1)
                                    ->where('nav', 1)
                                    ->withCount(['products' => function($query) {
                                        $query->where('status', 1);
                                    }])
                                    ->orderBy('name')
                                    ->get();
                            @endphp
                            
                            @foreach($mobileBrands as $brand)
                                <a href="{{ route('shop', ['brands' => [$brand->id]]) }}" 
                                   class="block px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded">
                                    {{ $brand->name }} ({{ $brand->products_count }})
                                </a>
                            @endforeach
                        </div>
                    </div>
                    
                    <a href="{{ route('contact') }}" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded">Contact</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Cart Sidebar Modal -->
<div id="cartModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex justify-end h-full">
            <div class="bg-white w-full max-w-md h-full shadow-xl transform translate-x-full transition-transform duration-300 ease-in-out" id="cartSidebar">
                <div class="flex flex-col h-full">
                    <!-- Cart Header -->
                    <div class="flex items-center justify-between p-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-800">Shopping Cart</h2>
                        <button onclick="closeCartModal()" class="text-gray-500 hover:text-gray-700 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Cart Items -->
                    <div class="flex-1 overflow-y-auto p-4" id="cartItems">
                        <!-- Cart items will be populated by JavaScript -->
                    </div>
                    
                    <!-- Cart Footer -->
                    <div class="border-t border-gray-200 p-4" id="cartFooter">
                        <!-- Cart totals and actions will be populated by JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="fixed top-20 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50" id="successMessage">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="fixed top-20 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50" id="errorMessage">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="fixed top-20 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50" id="errorMessage">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Page Content -->
    <main class="pt-28">
        @yield('content')
    </main>

    <!-- Footer -->
    <!-- Footer Section -->
    <footer
        class="relative text-white pt-12 pb-6 px-6 md:px-16 bg-gradient-to-r from-[#0a0a0a] via-[{{ $primaryColor }}] to-[#0a0a0a]">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
            <!-- Logo and Social -->
            <div>
                <img src="{{ asset($whiteLogo ?? 'admin-assets/white-logo.png') }}" alt="{{ $siteName ?? 'K-Glow' }} Logo" class="w-60 mb-4" />
                <h4 class="text-lg font-semibold mb-2" style="color: {{ $primaryColor }};">Keep in Touch</h4>
                <div class="flex space-x-4 text-xl text-white/90">
                    <a href="#" class="hover:text-white transition-colors" aria-label="Facebook">🌐</a>
                    <a href="#" class="hover:text-white transition-colors" aria-label="Instagram">📸</a>
                    <a href="#" class="hover:text-white transition-colors" aria-label="YouTube">📺</a>
                    <a href="#" class="hover:text-white transition-colors" aria-label="TikTok">🎵</a>
                    <a href="#" class="hover:text-white transition-colors" aria-label="Telegram">💬</a>
                </div>
                <h4 class="text-lg font-semibold mt-6 mb-2" style="color: {{ $primaryColor }};">Payment Accepted</h4>
                <img src="https://koreanmartbd.com/wp-content/uploads/2023/10/Payment-Banner_Jul24_V1-02-2-scaled.png"
                    alt="Payment Methods" class="w-full max-w-xs" />
            </div>

            <!-- Top Categories -->
            <div>
                <h4 class="text-lg font-semibold mb-4 text-white">
                    Top Categories
                </h4>
                <ul class="space-y-2 text-sm">
                    <li class="hover:text-gray-200 transition-colors cursor-pointer">
                        Moisturizer
                    </li>
                    <li class="hover:text-gray-200 transition-colors cursor-pointer">
                        Serum
                    </li>
                    <li class="hover:text-gray-200 transition-colors cursor-pointer">
                        Essence
                    </li>
                    <li class="hover:text-gray-200 transition-colors cursor-pointer">
                        Toner
                    </li>
                    <li class="hover:text-gray-200 transition-colors cursor-pointer">
                        Cleanser
                    </li>
                    <li class="hover:text-gray-200 transition-colors cursor-pointer">
                        Cream
                    </li>
                </ul>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="text-lg font-semibold mb-4 text-white">Quick Links</h4>
                <ul class="space-y-2 text-sm">
                    <li class="hover:text-gray-200 transition-colors cursor-pointer">
                        Wholesale
                    </li>
                    <li class="hover:text-gray-200 transition-colors cursor-pointer">
                        Sponsors
                    </li>
                    <li class="hover:text-gray-200 transition-colors cursor-pointer">
                        Promo Codes
                    </li>
                    <li class="hover:text-gray-200 transition-colors cursor-pointer">
                        Join Group
                    </li>
                    <li class="hover:text-gray-200 transition-colors cursor-pointer">
                        Request Products
                    </li>
                    <li class="hover:text-gray-200 transition-colors cursor-pointer">
                        Request Dealership
                    </li>
                </ul>
            </div>

            <!-- Help -->
            <div>
                <h4 class="text-lg font-semibold mb-4 text-white">Help</h4>
                <ul class="space-y-2 text-sm">
                    <li>
                        <a href="{{ route('about-us') }}" class="hover:text-gray-200 transition-colors cursor-pointer">
                            About Us
                        </a>
                    </li>
                    <li class="hover:text-gray-200 transition-colors cursor-pointer">
                        Trade License
                    </li>
                    <li>
                        <a href="{{ route('terms-conditions') }}" class="hover:text-gray-200 transition-colors cursor-pointer">
                            Terms & Conditions
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('privacy-policy') }}" class="hover:text-gray-200 transition-colors cursor-pointer">
                            Privacy Policy
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('return-refund') }}" class="hover:text-gray-200 transition-colors cursor-pointer">
                            Return & Refund Policy
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('contact-us') }}" class="hover:text-gray-200 transition-colors cursor-pointer">
                            Contact Us
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="mt-8 text-center text-white/80 text-sm">
            © {{ date('Y') }} K-Glow. All Rights Reserved.
        </div>


    </footer>

    <!-- Authentication Modal JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginBtn = document.getElementById("loginBtn");
            const registerBtn = document.getElementById("registerBtn");
            const sideModal = document.getElementById("sideModal");
            const modalOverlay = document.getElementById("modalOverlay");
            const closeModal = document.getElementById("closeModal");
            const loginForm = document.getElementById("loginForm");
            const registerForm = document.getElementById("registerForm");
            const modalTitle = document.getElementById("modalTitle");
            const switchToRegister = document.getElementById("switchToRegister");
            const switchToLogin = document.getElementById("switchToLogin");

            function openModal(formType) {
                if (sideModal && modalOverlay) {
                    sideModal.classList.remove("translate-x-full");
                    modalOverlay.classList.remove("hidden");
                    document.body.classList.add("overflow-hidden");
                    if (formType === "login") {
                        if (loginForm) loginForm.classList.remove("hidden");
                        if (registerForm) registerForm.classList.add("hidden");
                        if (modalTitle) modalTitle.textContent = "Login";
                    } else {
                        if (registerForm) registerForm.classList.remove("hidden");
                        if (loginForm) loginForm.classList.add("hidden");
                        if (modalTitle) modalTitle.textContent = "Register";
                    }
                }
            }

            function closeModalFunc() {
                if (sideModal && modalOverlay) {
                    sideModal.classList.add("translate-x-full");
                    modalOverlay.classList.add("hidden");
                    document.body.classList.remove("overflow-hidden");
                }
            }

            // Only add event listeners if buttons exist (for guest users)
            if (loginBtn) loginBtn.addEventListener("click", () => openModal("login"));
            if (registerBtn) registerBtn.addEventListener("click", () => openModal("register"));
            if (closeModal) closeModal.addEventListener("click", closeModalFunc);
            if (modalOverlay) modalOverlay.addEventListener("click", closeModalFunc);

            if (switchToRegister) switchToRegister.addEventListener("click", () => openModal("register"));
            if (switchToLogin) switchToLogin.addEventListener("click", () => openModal("login"));

            // Auto-hide flash messages
            setTimeout(() => {
                const successMessage = document.getElementById('successMessage');
                const errorMessage = document.getElementById('errorMessage');
                if (successMessage) successMessage.style.display = 'none';
                if (errorMessage) errorMessage.style.display = 'none';
            }, 5000);
        });
    </script>

    <!-- Comparison Modal -->
    <div id="comparisonModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg max-w-6xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold">Product Comparison</h2>
                    <button onclick="closeComparisonModal()" class="text-gray-600 hover:text-gray-900 text-2xl font-bold">
                        &times;
                    </button>
                </div>
                <div id="comparisonContent">
                    <!-- Comparison content will be populated by JavaScript -->
                </div>
            </div>
        </div>
    </div>

    

    @stack('styles')
    @stack('scripts')

    <script>
    // Comparison functionality
    window.openComparisonModal = function() {
        const comparison = JSON.parse(localStorage.getItem('comparison')) || [];
        
        if (comparison.length === 0) {
            document.getElementById('comparisonContent').innerHTML = '<p class="text-center text-gray-500 py-8">No products in comparison list</p>';
        } else {
            // Fetch product details for comparison
            fetchComparisonProducts(comparison);
        }
        
        document.getElementById('comparisonModal').classList.remove('hidden');
    };

    window.closeComparisonModal = function() {
        document.getElementById('comparisonModal').classList.add('hidden');
    };

    function fetchComparisonProducts(productIds) {
        // This would typically fetch from your API
        // For now, we'll show a placeholder
        document.getElementById('comparisonContent').innerHTML = `
            <div class="text-center py-8">
                <p class="text-gray-500 mb-4">Comparing ${productIds.length} products...</p>
                <button onclick="clearComparison()" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                    Clear All
                </button>
            </div>
        `;
    }

    window.clearComparison = function() {
        localStorage.removeItem('comparison');
        updateComparisonCount();
        closeComparisonModal();
    };

    // Update comparison count
    function updateComparisonCount() {
        const comparison = JSON.parse(localStorage.getItem('comparison')) || [];
        const comparisonCount = document.getElementById('comparisonCount');
        if (comparisonCount) {
            comparisonCount.textContent = comparison.length;
        }
    }

    // Cart Modal functionality
    window.openCartModal = function() {
        console.log('Opening cart modal...');
        const modal = document.getElementById('cartModal');
        const sidebar = document.getElementById('cartSidebar');
        
        if (!modal) {
            console.error('Cart modal element not found');
            return;
        }
        
        if (!sidebar) {
            console.error('Cart sidebar element not found');
            return;
        }
        
        modal.classList.remove('hidden');
        // Trigger slide-in animation
        setTimeout(() => {
            sidebar.classList.remove('translate-x-full');
        }, 10);
        
        // Load cart items
        loadCartItems();
    };

    window.closeCartModal = function() {
        const modal = document.getElementById('cartModal');
        const sidebar = document.getElementById('cartSidebar');
        
        sidebar.classList.add('translate-x-full');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    };

    function loadCartItems() {
        console.log('Loading cart items...');
        fetch('/cart/data')
            .then(response => {
                console.log('Cart API response:', response);
                return response.json();
            })
            .then(data => {
                console.log('Cart data received:', data);
                displayCartItems(data);
            })
            .catch(error => {
                console.error('Error loading cart:', error);
                document.getElementById('cartItems').innerHTML = '<p class="text-center text-gray-500 py-8">Error loading cart items</p>';
            });
    }

    function displayCartItems(cartData) {
        console.log('Displaying cart items:', cartData);
        const cartItems = document.getElementById('cartItems');
        const cartFooter = document.getElementById('cartFooter');
        
        if (!cartData.items || cartData.items.length === 0) {
            console.log('Cart is empty');
            cartItems.innerHTML = `
                <div class="text-center py-8">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 7h13l-2-7M16 21a1 1 0 11-2 0 1 1 0 012 0zm-8 0a1 1 0 11-2 0 1 1 0 012 0z"></path>
                    </svg>
                    <p class="text-gray-500 text-lg">Your cart is empty</p>
                    <p class="text-gray-400 text-sm mt-2">Add some products to get started</p>
                </div>
            `;
            cartFooter.innerHTML = '';
            return;
        }

        // Display cart items
        let itemsHTML = '';
        cartData.items.forEach(item => {
            itemsHTML += `
                <div class="flex items-center space-x-4 py-4 border-b border-gray-100 last:border-b-0">
                    <div class="flex-shrink-0">
                        <img src="/admin-assets/products/${item.thumbnail}" alt="${item.name}" class="w-16 h-16 object-cover rounded-lg">
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-sm font-medium text-gray-900 truncate">${item.name}</h3>
                        <p class="text-sm text-gray-500">SKU: ${item.sku}</p>
                        <div class="flex items-center justify-between mt-2">
                            <div class="flex items-center space-x-2">
                                <button onclick="updateCartQuantity(${item.id}, ${item.quantity - 1})" class="w-6 h-6 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-50">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                    </svg>
                                </button>
                                <span class="text-sm font-medium w-8 text-center">${item.quantity}</span>
                                <button onclick="updateCartQuantity(${item.id}, ${item.quantity + 1})" class="w-6 h-6 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-50">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </button>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">৳${item.price}</p>
                                <button onclick="removeFromCart(${item.id})" class="text-red-500 hover:text-red-700 text-xs mt-1">
                                    Remove
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
        
        cartItems.innerHTML = itemsHTML;

        // Display cart footer
        cartFooter.innerHTML = `
            <div class="space-y-4">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Subtotal:</span>
                    <span class="font-medium">৳${cartData.subtotal}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Shipping:</span>
                    <span class="font-medium">৳${cartData.shipping}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Tax:</span>
                    <span class="font-medium">৳${cartData.tax}</span>
                </div>
                <div class="border-t border-gray-200 pt-2">
                    <div class="flex justify-between text-lg font-semibold">
                        <span>Total:</span>
                        <span>৳${cartData.total}</span>
                    </div>
                </div>
                <div class="space-y-2">
                    <a href="/cart" class="block w-full bg-gray-100 text-gray-800 text-center py-2 px-4 rounded-lg hover:bg-gray-200 transition-colors">
                        View Cart
                    </a>
                    <a href="/checkout" class="block w-full bg-[var(--brand-orange)] text-white text-center py-2 px-4 rounded-lg hover:bg-orange-600 transition-colors">
                        Checkout
                    </a>
                </div>
            </div>
        `;
    }

    function updateCartQuantity(productId, newQuantity) {
        console.log('Updating cart quantity:', productId, newQuantity);
        if (newQuantity < 1) {
            removeFromCart(productId);
            return;
        }

        fetch('/cart/update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: newQuantity
            })
        })
        .then(response => {
            console.log('Update response:', response);
            return response.json();
        })
        .then(data => {
            console.log('Update data:', data);
            if (data.success) {
                loadCartItems();
                updateCartCount();
            }
        })
        .catch(error => {
            console.error('Error updating cart:', error);
        });
    }

    function removeFromCart(productId) {
        console.log('Removing from cart:', productId);
        fetch('/cart/remove', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                product_id: productId
            })
        })
        .then(response => {
            console.log('Remove response:', response);
            return response.json();
        })
        .then(data => {
            console.log('Remove data:', data);
            if (data.success) {
                loadCartItems();
                updateCartCount();
            }
        })
        .catch(error => {
            console.error('Error removing from cart:', error);
        });
    }

    function updateCartCount() {
        fetch('/cart/count')
            .then(response => response.json())
            .then(data => {
                document.getElementById('cartCount').textContent = data.count;
            })
            .catch(error => {
                console.error('Error updating cart count:', error);
            });
    }

    // Close modal when clicking outside
    document.getElementById('cartModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeCartModal();
        }
    });

    // Wishlist functionality
    function updateWishlistCount() {
        @auth
        fetch('/customer/wishlist/count')
            .then(response => response.json())
            .then(data => {
                const wishlistCount = document.getElementById('wishlistCount');
                if (wishlistCount) {
                    wishlistCount.textContent = data.count;
                }
            })
            .catch(error => {
                console.error('Error updating wishlist count:', error);
            });
        @endauth
    }

    // Initialize wishlist count on page load
    updateWishlistCount();

    // Initialize comparison count on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateComparisonCount();
    });
    </script>

</body>

</html>