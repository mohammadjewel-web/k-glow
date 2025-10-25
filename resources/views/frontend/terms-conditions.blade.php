@extends('layouts.app')

@section('title', 'Terms & Conditions - K-Glow')

@section('content')
<!-- Page Title -->
<section class="py-16 bg-gray-50">
    <div class="max-w-5xl mx-auto px-4 text-center">
        <h2 class="text-4xl font-bold text-[var(--brand-orange)] mb-4">
            Terms & Conditions
        </h2>
        <p class="text-gray-600 text-md">
            Please read these terms and conditions carefully before using our
            website.
        </p>
        <p class="text-sm text-gray-500 mt-2">
            Last updated: {{ date('F d, Y') }}
        </p>
    </div>
</section>

<!-- Terms Content -->
<section class="py-12">
    <div class="max-w-4xl mx-auto px-4 space-y-10 text-gray-700 text-sm leading-relaxed">
        <!-- 1. Introduction -->
        <div>
            <h3 class="text-xl font-semibold text-[var(--brand-orange)] mb-2">
                1. Introduction
            </h3>
            <p>
                Welcome to K-Glow. These Terms and Conditions govern your use of
                our website and services. By accessing or using our website, you
                agree to comply with and be bound by these terms.
            </p>
        </div>

        <!-- 2. Use of the Website -->
        <div>
            <h3 class="text-xl font-semibold text-[var(--brand-orange)] mb-2">
                2. Use of the Website
            </h3>
            <p>
                You must be at least 18 years old to use this website. You agree
                not to use our site for any unlawful purpose or in a way that may
                impair the performance, corrupt the content, or reduce the overall
                functionality of the website.
            </p>
            <p class="mt-2">
                You are responsible for maintaining the confidentiality of your
                account and password and for restricting access to your computer.
            </p>
        </div>

        <!-- 3. Products and Pricing -->
        <div>
            <h3 class="text-xl font-semibold text-[var(--brand-orange)] mb-2">
                3. Products and Pricing
            </h3>
            <p>
                All products and prices are subject to change without notice. We
                make every effort to display accurate product information, but
                errors may occasionally occur. We reserve the right to correct any
                errors and to cancel any orders placed at the incorrect price.
            </p>
            <ul class="list-disc list-inside mt-2 space-y-1">
                <li>All prices are in Bangladeshi Taka (BDT)</li>
                <li>Product availability is subject to stock levels</li>
                <li>We reserve the right to limit quantities</li>
                <li>Product images are for illustration purposes only</li>
            </ul>
        </div>

        <!-- 4. Shipping and Delivery -->
        <div>
            <h3 class="text-xl font-semibold text-[var(--brand-orange)] mb-2">
                4. Shipping & Delivery
            </h3>
            <p>
                Delivery times are estimates and not guaranteed. K-Glow is not
                responsible for delays caused by third-party shipping providers or
                events beyond our control.
            </p>
            <ul class="list-disc list-inside mt-2 space-y-1">
                <li>We ship within Bangladesh only</li>
                <li>Free shipping on orders over ৳1000</li>
                <li>Delivery time: 3-5 business days</li>
                <li>Risk of loss transfers to you upon delivery</li>
            </ul>
        </div>

        <!-- 5. Returns and Refunds -->
        <div>
            <h3 class="text-xl font-semibold text-[var(--brand-orange)] mb-2">
                5. Returns & Refunds
            </h3>
            <p>
                Please refer to our
                <a href="{{ route('return-refund') }}" class="text-[var(--brand-orange)] underline">
                    Returns Policy
                </a>
                for details on how to request a return or refund. Items must be
                returned in original condition within 30 days of delivery.
            </p>
        </div>

        <!-- 6. Payment Terms -->
        <div>
            <h3 class="text-xl font-semibold text-[var(--brand-orange)] mb-2">
                6. Payment Terms
            </h3>
            <p>
                We accept various payment methods including credit cards, bKash,
                Nagad, and cash on delivery. Payment is due at the time of order
                placement unless otherwise specified.
            </p>
            <ul class="list-disc list-inside mt-2 space-y-1">
                <li>SSLCommerz for secure card payments</li>
                <li>Mobile banking (bKash, Nagad)</li>
                <li>Cash on Delivery (COD) available</li>
                <li>All payments are processed securely</li>
            </ul>
        </div>

        <!-- 7. Intellectual Property -->
        <div>
            <h3 class="text-xl font-semibold text-[var(--brand-orange)] mb-2">
                7. Intellectual Property
            </h3>
            <p>
                All content on this website, including logos, images, text, and
                design elements, is the property of K-Glow and is protected by
                applicable copyright and trademark laws.
            </p>
            <p class="mt-2">
                You may not reproduce, distribute, or create derivative works
                without our express written permission.
            </p>
        </div>

        <!-- 8. User Accounts -->
        <div>
            <h3 class="text-xl font-semibold text-[var(--brand-orange)] mb-2">
                8. User Accounts
            </h3>
            <p>
                When you create an account with us, you must provide accurate and
                complete information. You are responsible for:
            </p>
            <ul class="list-disc list-inside mt-2 space-y-1">
                <li>Maintaining account security</li>
                <li>All activities under your account</li>
                <li>Updating account information</li>
                <li>Notifying us of any unauthorized use</li>
            </ul>
        </div>

        <!-- 9. Prohibited Uses -->
        <div>
            <h3 class="text-xl font-semibold text-[var(--brand-orange)] mb-2">
                9. Prohibited Uses
            </h3>
            <p>You may not use our website:</p>
            <ul class="list-disc list-inside mt-2 space-y-1">
                <li>For any unlawful purpose</li>
                <li>To violate any laws or regulations</li>
                <li>To infringe on others' rights</li>
                <li>To transmit harmful or malicious code</li>
                <li>To spam or send unsolicited communications</li>
                <li>To impersonate others</li>
            </ul>
        </div>

        <!-- 10. Limitation of Liability -->
        <div>
            <h3 class="text-xl font-semibold text-[var(--brand-orange)] mb-2">
                10. Limitation of Liability
            </h3>
            <p>
                To the fullest extent permitted by law, K-Glow shall not be liable
                for any indirect, incidental, special, consequential, or punitive
                damages, including but not limited to loss of profits, data, or
                other intangible losses.
            </p>
        </div>

        <!-- 11. Disclaimers -->
        <div>
            <h3 class="text-xl font-semibold text-[var(--brand-orange)] mb-2">
                11. Disclaimers
            </h3>
            <p>
                Our website and services are provided "as is" without warranties
                of any kind. We disclaim all warranties, express or implied,
                including but not limited to warranties of merchantability,
                fitness for a particular purpose, and non-infringement.
            </p>
        </div>

        <!-- 12. Privacy Policy -->
        <div>
            <h3 class="text-xl font-semibold text-[var(--brand-orange)] mb-2">
                12. Privacy Policy
            </h3>
            <p>
                Your privacy is important to us. Please review our
                <a href="{{ route('privacy-policy') }}" class="text-[var(--brand-orange)] underline">
                    Privacy Policy
                </a>
                to understand how we collect, use, and protect your information.
            </p>
        </div>

        <!-- 13. Changes to Terms -->
        <div>
            <h3 class="text-xl font-semibold text-[var(--brand-orange)] mb-2">
                13. Changes to Terms
            </h3>
            <p>
                We reserve the right to modify these terms at any time. Changes
                will be effective immediately upon posting. Your continued use of
                the website constitutes acceptance of the modified terms.
            </p>
        </div>

        <!-- 14. Governing Law -->
        <div>
            <h3 class="text-xl font-semibold text-[var(--brand-orange)] mb-2">
                14. Governing Law
            </h3>
            <p>
                These terms shall be governed by and construed in accordance with
                the laws of Bangladesh. Any disputes arising from these terms
                shall be subject to the exclusive jurisdiction of the courts of
                Bangladesh.
            </p>
        </div>

        <!-- 15. Severability -->
        <div>
            <h3 class="text-xl font-semibold text-[var(--brand-orange)] mb-2">
                15. Severability
            </h3>
            <p>
                If any provision of these terms is found to be unenforceable or
                invalid, the remaining provisions shall remain in full force and
                effect.
            </p>
        </div>

        <!-- 16. Contact Information -->
        <div>
            <h3 class="text-xl font-semibold text-[var(--brand-orange)] mb-2">
                16. Contact Information
            </h3>
            <p>
                If you have any questions about these Terms & Conditions, please
                contact us:
            </p>
            <ul class="list-disc list-inside mt-2 space-y-1">
                <li>Email: <a href="mailto:legal@k-glow.com" class="text-[var(--brand-orange)] underline">legal@k-glow.com</a></li>
                <li>Phone: <a href="tel:+880123456789" class="text-[var(--brand-orange)] underline">+880 123 456 789</a></li>
                <li>Address: Dhaka, Bangladesh</li>
                <li>Website: <a href="{{ route('home') }}" class="text-[var(--brand-orange)] underline">k-glow.com</a></li>
            </ul>
        </div>
    </div>
</section>

<!-- Agreement Section -->
<section class="py-12 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4">
        <div class="bg-white rounded-lg p-8 shadow-sm">
            <h3 class="text-xl font-semibold text-[var(--brand-orange)] mb-4">
                Agreement to Terms
            </h3>
            <p class="text-gray-700 mb-6">
                By using our website and services, you acknowledge that you have
                read, understood, and agree to be bound by these Terms & Conditions.
                If you do not agree to these terms, please do not use our website.
            </p>
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('home') }}" 
                   class="inline-flex items-center px-6 py-3 bg-[var(--brand-orange)] text-white rounded-lg hover:bg-orange-600 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Back to Home
                </a>
                <a href="{{ route('contact') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    Contact Us
                </a>
            </div>
        </div>
    </div>
</section>
@endsection



