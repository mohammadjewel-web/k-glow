@extends('layouts.app')

@section('title', 'Contact Us - K-Glow')

@section('content')
<!-- Hero Section -->
<section class="bg-[var(--brand-orange)] py-20">
    <div class="max-w-4xl mx-auto text-center px-4">
        <h2 class="text-4xl font-bold text-white mb-4">
            Get in Touch
        </h2>
        <p class="text-lg max-w-2xl mx-auto text-white/90">
            Have a question, feedback, or just want to say hello? We'd love to
            hear from you. Fill out the form below or reach us via email or
            phone.
        </p>
    </div>
</section>

<!-- Contact Section -->
<section class="py-16 bg-white">
    <div class="max-w-6xl mx-auto px-4 grid md:grid-cols-2 gap-12">
        <!-- Contact Info -->
        <div>
            <h3 class="text-2xl font-semibold text-[var(--brand-orange)] mb-4">
                Contact Information
            </h3>
            <p class="text-gray-600 mb-6">
                Reach us directly or visit our support channels.
            </p>
            <ul class="space-y-4 text-gray-700 text-sm">
                <li>
                    <strong>Email:</strong>
                    <a href="mailto:support@k-glow.com" class="text-[var(--brand-orange)] underline">
                        support@k-glow.com
                    </a>
                </li>
                <li>
                    <strong>Phone:</strong>
                    <a href="tel:+880123456789" class="text-[var(--brand-orange)] underline">
                        +880 123 456 789
                    </a>
                </li>
                <li>
                    <strong>Address:</strong> 
                    Dhaka, Bangladesh
                </li>
                <li><strong>Business Hours:</strong> Mon–Fri, 9AM–5PM</li>
            </ul>
            
            <!-- Social Media Links -->
            <div class="mt-10">
                <h3 class="text-2xl font-semibold text-[var(--brand-orange)] mb-4">
                    Follow Us
                </h3>
                <div class="flex space-x-6 justify-center md:justify-start">
                    <a href="https://facebook.com/k-glow" target="_blank" rel="noopener" 
                       class="text-gray-600 hover:text-[var(--brand-orange)] transition-colors duration-300 text-3xl">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-8 h-8">
                            <path d="M22 12.07c0-5.52-4.48-10-10-10S2 6.55 2 12.07c0 5 3.66 9.12 8.44 9.88v-7H8.08v-3h2.36v-2.3c0-2.33 1.4-3.62 3.55-3.62 1.03 0 2.1.18 2.1.18v2.3h-1.18c-1.16 0-1.52.73-1.52 1.47V12h2.6l-.42 3h-2.18v7c4.78-.75 8.44-4.86 8.44-9.88z"/>
                        </svg>
                    </a>
                    <a href="https://instagram.com/k-glow" target="_blank" rel="noopener" 
                       class="text-gray-600 hover:text-[var(--brand-orange)] transition-colors duration-300 text-3xl">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-8 h-8">
                            <path d="M12 2.16c3.2 0 3.58.01 4.85.07 3.65.16 5.19 1.7 5.35 5.35.06 1.27.07 1.65.07 4.85s-.01 3.58-.07 4.85c-.16 3.65-1.7 5.19-5.35 5.35-1.27.06-1.65.07-4.85.07s-3.58-.01-4.85-.07c-3.65-.16-5.19-1.7-5.35-5.35-.06-1.27-.07-1.65-.07-4.85s.01-3.58.07-4.85c.16-3.65 1.7-5.19 5.35-5.35 1.27-.06 1.65-.07 4.85-.07zm0-2.16c-3.2 0-3.58.01-4.85.07C3.5.23 1.96 1.77 1.8 5.42 1.74 6.69 1.73 7.07 1.73 10.27s.01 3.58.07 4.85c.16 3.65 1.7 5.19 5.35 5.35 1.27.06 1.65.07 4.85.07s3.58-.01 4.85-.07c3.65-.16 5.19-1.7 5.35-5.35.06-1.27.07-1.65.07-4.85s-.01-3.58-.07-4.85c-.16-3.65-1.7-5.19-5.35-5.35-1.27-.06-1.65-.07-4.85-.07z"/>
                            <path d="M12 5.84c-3.43 0-6.16 2.73-6.16 6.16s2.73 6.16 6.16 6.16 6.16-2.73 6.16-6.16-2.73-6.16-6.16-6.16zm0 10.16c-2.2 0-4-1.8-4-4s1.8-4 4-4 4 1.8 4 4-1.8 4-4 4z"/>
                            <circle cx="18.42" cy="5.58" r="1.16"/>
                        </svg>
                    </a>
                    <a href="https://youtube.com/k-glow" target="_blank" rel="noopener" 
                       class="text-gray-600 hover:text-[var(--brand-orange)] transition-colors duration-300 text-3xl">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-8 h-8">
                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Contact Form -->
        <div>
            <h3 class="text-2xl font-semibold text-[var(--brand-orange)] mb-4">
                Send us a Message
            </h3>
            <form class="space-y-6" action="{{ route('contact.submit') }}" method="POST">
                @csrf
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Name *
                        </label>
                        <input type="text" id="name" name="name" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[var(--brand-orange)] focus:border-transparent"
                               value="{{ old('name') }}">
                        @error('name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email *
                        </label>
                        <input type="email" id="email" name="email" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[var(--brand-orange)] focus:border-transparent"
                               value="{{ old('email') }}">
                        @error('email')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                        Phone
                    </label>
                    <input type="tel" id="phone" name="phone"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[var(--brand-orange)] focus:border-transparent"
                           value="{{ old('phone') }}">
                    @error('phone')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                
                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                        Subject *
                    </label>
                    <select id="subject" name="subject" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[var(--brand-orange)] focus:border-transparent">
                        <option value="">Select a subject</option>
                        <option value="general" {{ old('subject') == 'general' ? 'selected' : '' }}>General Inquiry</option>
                        <option value="order" {{ old('subject') == 'order' ? 'selected' : '' }}>Order Support</option>
                        <option value="product" {{ old('subject') == 'product' ? 'selected' : '' }}>Product Question</option>
                        <option value="shipping" {{ old('subject') == 'shipping' ? 'selected' : '' }}>Shipping & Delivery</option>
                        <option value="return" {{ old('subject') == 'return' ? 'selected' : '' }}>Returns & Refunds</option>
                        <option value="feedback" {{ old('subject') == 'feedback' ? 'selected' : '' }}>Feedback</option>
                        <option value="other" {{ old('subject') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('subject')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                
                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                        Message *
                    </label>
                    <textarea id="message" name="message" rows="5" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[var(--brand-orange)] focus:border-transparent"
                              placeholder="Please describe your inquiry in detail...">{{ old('message') }}</textarea>
                    @error('message')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                
                <button type="submit"
                        class="w-full bg-[var(--brand-orange)] text-white py-3 px-6 rounded-lg font-semibold hover:bg-orange-600 transition-colors duration-200">
                    Send Message
                </button>
            </form>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4">
        <div class="text-center mb-12">
            <h3 class="text-3xl font-bold mb-4 text-[var(--brand-orange)]">
                Frequently Asked Questions
            </h3>
            <p class="text-lg text-gray-600">
                Quick answers to common questions about our products and services.
            </p>
        </div>
        
        <div class="space-y-6">
            <div class="bg-white rounded-lg p-6 shadow-sm">
                <h4 class="text-lg font-semibold mb-2 text-gray-800">How long does shipping take?</h4>
                <p class="text-gray-600">We typically process orders within 1-2 business days and deliver within 3-5 business days across Bangladesh.</p>
            </div>
            
            <div class="bg-white rounded-lg p-6 shadow-sm">
                <h4 class="text-lg font-semibold mb-2 text-gray-800">Do you offer international shipping?</h4>
                <p class="text-gray-600">Currently, we only ship within Bangladesh. We're working on expanding our shipping options.</p>
            </div>
            
            <div class="bg-white rounded-lg p-6 shadow-sm">
                <h4 class="text-lg font-semibold mb-2 text-gray-800">What is your return policy?</h4>
                <p class="text-gray-600">We offer a 30-day return policy for unopened products. Please check our <a href="{{ route('return-refund') }}" class="text-[var(--brand-orange)] underline">Return & Refund Policy</a> for details.</p>
            </div>
            
            <div class="bg-white rounded-lg p-6 shadow-sm">
                <h4 class="text-lg font-semibold mb-2 text-gray-800">Are your products authentic?</h4>
                <p class="text-gray-600">Yes, all our products are 100% authentic. We source directly from authorized distributors and brands.</p>
            </div>
            
            <div class="bg-white rounded-lg p-6 shadow-sm">
                <h4 class="text-lg font-semibold mb-2 text-gray-800">How can I track my order?</h4>
                <p class="text-gray-600">Once your order ships, you'll receive a tracking number via email. You can also check your order status in your account dashboard.</p>
            </div>
        </div>
    </div>
</section>
@endsection



