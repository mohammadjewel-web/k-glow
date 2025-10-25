<header class="site-header">
    <div class="container flex justify-between items-center py-4">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="logo">
            <img src="{{ asset('frontend/assets/images/logo.png') }}" alt="K-Glow" class="h-12">
        </a>

        <!-- Navigation -->
        <nav class="hidden md:flex space-x-6">
            <a href="{{ route('home') }}" class="hover:text-orange-500">Home</a>
            <a href="{{ route('shop') }}" class="hover:text-orange-500">Shop</a>
            <a href="{{ route('cart') }}" class="hover:text-orange-500">Cart</a>
            <a href="{{ route('contact') }}" class="hover:text-orange-500">Contact</a>
        </nav>

        <!-- Right Icons -->
        <div class="flex items-center space-x-4">
            <a href="{{ route('cart') }}" class="relative">
                <img src="{{ asset('frontend/assets/icons/cart.svg') }}" class="w-6 h-6" alt="Cart">
                <span class="absolute -top-2 -right-2 bg-orange-500 text-white text-xs px-1 rounded-full">0</span>
            </a>
            <a href="{{ route('login') }}">
                <img src="{{ asset('frontend/assets/icons/user.svg') }}" class="w-6 h-6" alt="Login">
            </a>
        </div>
    </div>
</header>