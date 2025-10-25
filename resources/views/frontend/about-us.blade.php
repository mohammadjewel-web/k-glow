@extends('layouts.app')

@section('title', 'About Us - K-Glow')

@section('content')
<!-- Hero Section -->
<section class="py-20" style="background-color: #fff3ec">
    <div class="max-w-5xl mx-auto px-4 text-center">
        <h2 class="text-4xl font-bold mb-6 text-[var(--brand-orange)]">
            Glow Up with Confidence
        </h2>
        <p class="text-lg text-gray-700 mb-4">
            At <strong>K-Glow</strong>, we're more than just an eCommerce store
            — we're a lifestyle brand that empowers individuals to look and feel
            their best every day. Whether you're shopping for skincare,
            wellness, or everyday essentials, we've got you covered.
        </p>
        <p class="text-md text-gray-600">
            Founded with the vision to make premium self-care accessible to
            everyone, K-Glow brings together quality, transparency, and
            affordability in one seamless shopping experience. Our goal is
            simple: to help you glow from the inside out.
        </p>
    </div>
</section>

<!-- Company Mission & Values -->
<section class="py-16 bg-white">
    <div class="max-w-6xl mx-auto px-4 grid md:grid-cols-2 gap-12">
        <!-- Mission -->
        <div>
            <h3 class="text-2xl font-semibold mb-4 text-[var(--brand-orange)]">
                Our Mission
            </h3>
            <p class="text-gray-600 leading-relaxed">
                At K-Glow, our mission is to elevate your everyday routines
                through trusted products and a seamless shopping experience. We
                believe self-care should never be a luxury — it should be part of
                your lifestyle.
            </p>
            <ul class="list-disc list-inside mt-4 text-gray-600 space-y-2">
                <li>
                    Delivering high-quality, dermatologist-approved skincare and
                    wellness products
                </li>
                <li>Providing fast, eco-conscious shipping for every order</li>
                <li>Offering responsive and compassionate customer support</li>
                <li>
                    Creating an inclusive community where everyone feels welcome
                    and supported
                </li>
            </ul>
        </div>

        <!-- Values -->
        <div>
            <h3 class="text-2xl font-semibold mb-4 text-[var(--brand-orange)]">
                Our Values
            </h3>
            <div class="space-y-4">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-[var(--brand-orange)] rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800">Quality First</h4>
                        <p class="text-gray-600 text-sm">We source only the finest products that meet our rigorous standards for safety and effectiveness.</p>
                    </div>
                </div>
                
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-[var(--brand-orange)] rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800">Transparency</h4>
                        <p class="text-gray-600 text-sm">We believe in honest communication about our products, ingredients, and business practices.</p>
                    </div>
                </div>
                
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-[var(--brand-orange)] rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800">Customer-Centric</h4>
                        <p class="text-gray-600 text-sm">Your satisfaction and well-being are at the heart of everything we do.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose K-Glow -->
<section class="py-16 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4">
        <div class="text-center mb-12">
            <h3 class="text-3xl font-bold mb-4 text-[var(--brand-orange)]">
                Why Choose K-Glow?
            </h3>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                We're committed to providing you with the best shopping experience and premium products that deliver real results.
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <!-- Fast Shipping -->
            <div class="text-center">
                <div class="w-16 h-16 bg-[var(--brand-orange)] rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <h4 class="text-xl font-semibold mb-2 text-gray-800">Fast & Free Shipping</h4>
                <p class="text-gray-600">Get your orders delivered quickly with our reliable shipping partners. Free shipping on orders over ৳1000.</p>
            </div>

            <!-- Quality Products -->
            <div class="text-center">
                <div class="w-16 h-16 bg-[var(--brand-orange)] rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h4 class="text-xl font-semibold mb-2 text-gray-800">Quality Guaranteed</h4>
                <p class="text-gray-600">All our products are carefully selected and tested to ensure they meet our high standards for quality and safety.</p>
            </div>

            <!-- Expert Support -->
            <div class="text-center">
                <div class="w-16 h-16 bg-[var(--brand-orange)] rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 2.25a9.75 9.75 0 100 19.5 9.75 9.75 0 000-19.5z"></path>
                    </svg>
                </div>
                <h4 class="text-xl font-semibold mb-2 text-gray-800">Expert Support</h4>
                <p class="text-gray-600">Our knowledgeable team is here to help you find the perfect products for your skincare and wellness needs.</p>
            </div>
        </div>
    </div>
</section>

<!-- Our Story -->
<section class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h3 class="text-3xl font-bold mb-8 text-[var(--brand-orange)]">
            Our Story
        </h3>
        <div class="prose prose-lg mx-auto text-gray-600">
            <p class="mb-6">
                K-Glow was born from a simple belief: everyone deserves access to premium skincare and wellness products that actually work. 
                Founded in 2024, we started as a small team passionate about Korean beauty and wellness trends.
            </p>
            <p class="mb-6">
                What began as a personal quest for better skincare has evolved into a comprehensive platform that serves thousands of customers 
                across Bangladesh. We've built relationships with trusted suppliers and brands to bring you authentic, high-quality products 
                at prices that won't break the bank.
            </p>
            <p>
                Today, K-Glow is more than just a store—it's a community of beauty enthusiasts who believe in the power of self-care and 
                the confidence that comes from looking and feeling your best. Join us on this journey to glowing skin and a healthier lifestyle.
            </p>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-16 bg-gradient-to-r from-[var(--brand-orange)] to-orange-600">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h3 class="text-3xl font-bold mb-4 text-white">
            Ready to Start Your Glow-Up Journey?
        </h3>
        <p class="text-lg text-white/90 mb-8">
            Discover our curated collection of premium skincare and wellness products designed to help you look and feel your absolute best.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('shop') }}" 
               class="inline-flex items-center px-8 py-3 bg-white text-[var(--brand-orange)] rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l-1 12H6L5 9z"></path>
                </svg>
                Shop Now
            </a>
            <a href="{{ route('contact') }}" 
               class="inline-flex items-center px-8 py-3 border-2 border-white text-white rounded-lg font-semibold hover:bg-white hover:text-[var(--brand-orange)] transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                Contact Us
            </a>
        </div>
    </div>
</section>
@endsection



