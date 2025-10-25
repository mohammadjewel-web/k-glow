@extends('layouts.app')

@section('title', 'Privacy Policy - K-Glow')

@section('content')
<!-- Hero Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-5xl mx-auto px-4 text-center">
        <h2 class="text-4xl font-bold text-[var(--brand-orange)] mb-4">
            Privacy Policy
        </h2>
        <p class="text-gray-600 text-md">
            We are committed to protecting your personal information and your
            right to privacy.
        </p>
        <p class="text-sm text-gray-500 mt-2">
            Last updated: {{ date('F d, Y') }}
        </p>
    </div>
</section>

<!-- Privacy Content -->
<section class="py-12">
    <div class="max-w-4xl mx-auto px-4 space-y-10 text-gray-700 text-sm leading-relaxed">
        <!-- 1. Introduction -->
        <div>
            <h3 class="text-xl font-semibold text-[var(--brand-orange)] mb-2">
                1. Introduction
            </h3>
            <p>
                Welcome to K-Glow. This Privacy Policy outlines how we collect,
                use, and protect your personal information when you visit our
                website or make a purchase.
            </p>
        </div>

        <!-- 2. Information We Collect -->
        <div>
            <h3 class="text-xl font-semibold text-[var(--brand-orange)] mb-2">
                2. Information We Collect
            </h3>
            <p>We may collect the following types of information:</p>
            <ul class="list-disc list-inside mt-2 space-y-1">
                <li>Personal Information (name, email, phone, address)</li>
                <li>
                    Payment Details (processed securely through third-party
                    gateways)
                </li>
                <li>Browsing behavior and usage data (cookies, analytics)</li>
                <li>Account information (profile data, preferences)</li>
                <li>Communication records (customer support interactions)</li>
            </ul>
        </div>

        <!-- 3. How We Use Your Information -->
        <div>
            <h3 class="text-xl font-semibold text-[var(--brand-orange)] mb-2">
                3. How We Use Your Information
            </h3>
            <p>We use your data to:</p>
            <ul class="list-disc list-inside mt-2 space-y-1">
                <li>Process and fulfill your orders</li>
                <li>Improve our website and services</li>
                <li>Send marketing emails (only if you opt-in)</li>
                <li>Provide customer support</li>
                <li>Prevent fraud and ensure security</li>
                <li>Comply with legal obligations</li>
            </ul>
        </div>

        <!-- 4. Sharing of Information -->
        <div>
            <h3 class="text-xl font-semibold text-[var(--brand-orange)] mb-2">
                4. Sharing of Information
            </h3>
            <p>
                We do not sell your personal data. We may share your information
                with trusted third parties such as:
            </p>
            <ul class="list-disc list-inside mt-2 space-y-1">
                <li>Payment processors (SSLCommerz, bKash, Nagad)</li>
                <li>Shipping carriers (for order delivery)</li>
                <li>Analytics providers (Google Analytics)</li>
                <li>Marketing platforms (with your consent)</li>
            </ul>
            <p class="mt-2">
                We only share information as necessary to operate our business
                and provide you with our services.
            </p>
        </div>

        <!-- 5. Cookies and Tracking -->
        <div>
            <h3 class="text-xl font-semibold text-[var(--brand-orange)] mb-2">
                5. Cookies & Tracking Technologies
            </h3>
            <p>
                We use cookies to enhance your experience, analyze site traffic,
                and personalize content. Types of cookies we use:
            </p>
            <ul class="list-disc list-inside mt-2 space-y-1">
                <li>Essential cookies (required for website functionality)</li>
                <li>Analytics cookies (to understand user behavior)</li>
                <li>Preference cookies (to remember your settings)</li>
                <li>Marketing cookies (to show relevant advertisements)</li>
            </ul>
            <p class="mt-2">
                You can disable cookies through your browser settings, but this
                may affect website functionality.
            </p>
        </div>

        <!-- 6. Data Security -->
        <div>
            <h3 class="text-xl font-semibold text-[var(--brand-orange)] mb-2">
                6. Data Security
            </h3>
            <p>
                We implement appropriate security measures to protect your
                personal data from unauthorized access or disclosure:
            </p>
            <ul class="list-disc list-inside mt-2 space-y-1">
                <li>SSL encryption for data transmission</li>
                <li>Secure payment processing</li>
                <li>Regular security audits</li>
                <li>Limited access to personal data</li>
                <li>Employee training on data protection</li>
            </ul>
            <p class="mt-2">
                However, no method of transmission over the internet is 100% secure.
            </p>
        </div>

        <!-- 7. Your Rights -->
        <div>
            <h3 class="text-xl font-semibold text-[var(--brand-orange)] mb-2">
                7. Your Rights
            </h3>
            <p>You have the right to:</p>
            <ul class="list-disc list-inside mt-2 space-y-1">
                <li>Access your personal data</li>
                <li>Correct inaccurate information</li>
                <li>Delete your account and data</li>
                <li>Opt-out of marketing communications</li>
                <li>Data portability</li>
                <li>Withdraw consent at any time</li>
            </ul>
            <p class="mt-2">
                To exercise these rights, please contact us at 
                <a href="mailto:privacy@k-glow.com" class="text-[var(--brand-orange)] underline">
                    privacy@k-glow.com
                </a>
            </p>
        </div>

        <!-- 8. Data Retention -->
        <div>
            <h3 class="text-xl font-semibold text-[var(--brand-orange)] mb-2">
                8. Data Retention
            </h3>
            <p>
                We retain your personal information for as long as necessary to:
            </p>
            <ul class="list-disc list-inside mt-2 space-y-1">
                <li>Provide our services</li>
                <li>Comply with legal obligations</li>
                <li>Resolve disputes</li>
                <li>Enforce our agreements</li>
            </ul>
            <p class="mt-2">
                Account data is typically retained for 3 years after account closure,
                unless required by law.
            </p>
        </div>

        <!-- 9. Children's Privacy -->
        <div>
            <h3 class="text-xl font-semibold text-[var(--brand-orange)] mb-2">
                9. Children's Privacy
            </h3>
            <p>
                Our services are not intended for children under 13 years of age.
                We do not knowingly collect personal information from children under 13.
                If you believe we have collected such information, please contact us immediately.
            </p>
        </div>

        <!-- 10. International Transfers -->
        <div>
            <h3 class="text-xl font-semibold text-[var(--brand-orange)] mb-2">
                10. International Data Transfers
            </h3>
            <p>
                Your information may be transferred to and processed in countries other
                than your own. We ensure appropriate safeguards are in place for such
                transfers in accordance with applicable data protection laws.
            </p>
        </div>

        <!-- 11. Changes to Privacy Policy -->
        <div>
            <h3 class="text-xl font-semibold text-[var(--brand-orange)] mb-2">
                11. Changes to This Privacy Policy
            </h3>
            <p>
                We may update this Privacy Policy from time to time. We will notify
                you of any changes by posting the new Privacy Policy on this page and
                updating the "Last updated" date.
            </p>
        </div>

        <!-- 12. Contact Information -->
        <div>
            <h3 class="text-xl font-semibold text-[var(--brand-orange)] mb-2">
                12. Contact Us
            </h3>
            <p>
                If you have any questions about this Privacy Policy, please contact us:
            </p>
            <ul class="list-disc list-inside mt-2 space-y-1">
                <li>Email: <a href="mailto:privacy@k-glow.com" class="text-[var(--brand-orange)] underline">privacy@k-glow.com</a></li>
                <li>Phone: <a href="tel:+880123456789" class="text-[var(--brand-orange)] underline">+880 123 456 789</a></li>
                <li>Address: Dhaka, Bangladesh</li>
            </ul>
        </div>
    </div>
</section>

<!-- Additional Resources -->
<section class="py-12 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4">
        <div class="bg-white rounded-lg p-8 shadow-sm">
            <h3 class="text-xl font-semibold text-[var(--brand-orange)] mb-4">
                Additional Resources
            </h3>
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-semibold mb-2">Related Policies</h4>
                    <ul class="space-y-1 text-sm">
                        <li><a href="{{ route('terms-conditions') }}" class="text-[var(--brand-orange)] underline">Terms & Conditions</a></li>
                        <li><a href="{{ route('return-refund') }}" class="text-[var(--brand-orange)] underline">Return & Refund Policy</a></li>
                        <li><a href="{{ route('contact') }}" class="text-[var(--brand-orange)] underline">Contact Us</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-2">Quick Actions</h4>
                    <ul class="space-y-1 text-sm">
                        <li><a href="{{ route('customer.profile') }}" class="text-[var(--brand-orange)] underline">Manage Account</a></li>
                        <li><a href="#" class="text-[var(--brand-orange)] underline">Update Preferences</a></li>
                        <li><a href="#" class="text-[var(--brand-orange)] underline">Download Data</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection



