<footer class="site-footer bg-gray-900 text-white py-8 mt-10">
    <div class="container grid md:grid-cols-3 gap-6">
        <!-- About -->
        <div>
            <h3 class="font-bold text-lg mb-3">About K-Glow</h3>
            <p>Your trusted Korean beauty shop in Bangladesh.</p>
        </div>

        <!-- Quick Links -->
        <div>
            <h3 class="font-bold text-lg mb-3">Quick Links</h3>
            <ul>
                <li><a href="{{ route('shop') }}" class="hover:text-orange-500">Shop</a></li>
                <li><a href="{{ route('cart') }}" class="hover:text-orange-500">Cart</a></li>
                <li><a href="{{ route('contact') }}" class="hover:text-orange-500">Contact</a></li>
            </ul>
        </div>

        <!-- Contact -->
        <div>
            <h3 class="font-bold text-lg mb-3">Contact</h3>
            <p>Email: support@k-glow.com</p>
            <p>Phone: +880 123 456 789</p>
        </div>
    </div>

    <div class="text-center mt-6 text-gray-400">
        © {{ date('Y') }} K-Glow. All Rights Reserved.
    </div>
</footer>