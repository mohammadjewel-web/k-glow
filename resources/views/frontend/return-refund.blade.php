@extends('layouts.app')

@section('title', 'Return & Refund Policy - K-Glow')

@section('content')
<!-- Hero Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-5xl mx-auto px-4 text-center">
        <h2 class="text-4xl font-bold text-[var(--brand-orange)] mb-4">
            Return & Refund Policy
        </h2>
        <p class="text-gray-600 text-md">
            We're here to make sure you're completely satisfied with your K-Glow
            experience.
        </p>
        <p class="text-sm text-gray-500 mt-2">
            Last updated: {{ date('F d, Y') }}
        </p>
    </div>
</section>

<!-- Policy Content -->
<section class="py-12">
    <div class="max-w-4xl mx-auto px-4 space-y-10 text-gray-700 text-sm leading-relaxed">
        <!-- 1. Overview -->
        <div>
            <h3 class="text-xl font-semibold text-[var(--brand-orange)] mb-2">
                1. Overview
            </h3>
            <p>
                At K-Glow, your satisfaction is our top priority. If you're not
                fully happy with your purchase, we offer hassle-free returns and
                refunds under the conditions outlined below.
            </p>
        </div>

        <!-- 2. Eligibility for Returns -->
        <div>
            <h3 class="text-xl font-semibold text-[var(--brand-orange)] mb-2">
                2. Eligibility for Returns
            </h3>
            <p>You may request a return if:</p>
            <ul class="list-disc list-inside mt-2 space-y-1">
                <li>
                    The item is unused, unopened, and in its original packaging
                </li>
                <li>The return request is made within 30 days of delivery</li>
                <li>You have the order number or proof of purchase</li>
                <li>The product is defective or damaged upon arrival</li>
                <li>The wrong item was shipped</li>
            </ul>
        </div>

        <!-- 3. Non-Returnable Items -->
        <div>
            <h3 class="text-xl font-semibold text-[var(--brand-orange)] mb-2">
                3. Non-Returnable Items
            </h3>
            <p>We do not accept returns on the following:</p>
            <ul class="list-disc list-inside mt-2 space-y-1">
                <li>Used, opened, or damaged items (unless defective)</li>
                <li>Gift cards or promotional items</li>
                <li>Items marked "Final Sale" or "Non-Returnable"</li>
                <li>Personalized or custom-made products</li>
                <li>Perishable goods</li>
                <li>Intimate or sanitary products</li>
            </ul>
        </div>

        <!-- 4. How to Request a Return -->
        <div>
            <h3 class="text-xl font-semibold text-[var(--brand-orange)] mb-2">
                4. How to Request a Return
            </h3>
            <p>
                To start a return, you can:
            </p>
            <ul class="list-disc list-inside mt-2 space-y-1">
                <li>Contact us at <a href="mailto:returns@k-glow.com" class="text-[var(--brand-orange)] underline">returns@k-glow.com</a></li>
                <li>Call us at <a href="tel:+880123456789" class="text-[var(--brand-orange)] underline">+880 123 456 789</a></li>
                <li>Use our online return form in your account dashboard</li>
                <li>Contact our customer support team</li>
            </ul>
            <p class="mt-2">
                Please include your order number and reason for return. Our team will guide
                you through the next steps.
            </p>
        </div>

        <!-- 5. Return Process -->
        <div>
            <h3 class="text-xl font-semibold text-[var(--brand-orange)] mb-2">
                5. Return Process
            </h3>
            <ol class="list-decimal list-inside space-y-1">
                <li>Contact us within 30 days of delivery</li>
                <li>Receive return authorization and instructions</li>
                <li>Package the item securely in original packaging</li>
                <li>Ship the item to our return address</li>
                <li>We'll inspect the returned item</li>
                <li>Process your refund within 5-7 business days</li>
            </ol>
        </div>

        <!-- 6. Refunds -->
        <div>
            <h3 class="text-xl font-semibold text-[var(--brand-orange)] mb-2">
                6. Refunds
            </h3>
            <p>
                Once we receive and inspect your returned item, we'll notify you
                of the refund status. Approved refunds will be processed to your
                original payment method within 5–7 business days.
            </p>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-4">
                <h4 class="font-semibold text-blue-800 mb-2">Refund Timeline</h4>
                <ul class="text-blue-700 space-y-1">
                    <li>• Credit Card: 5-7 business days</li>
                    <li>• Bank Transfer: 7-10 business days</li>
                    <li>• bKash/Nagad: 1-3 business days</li>
                    <li>• Cash on Delivery: Bank transfer within 10 business days</li>
                </ul>
            </div>
        </div>

        <!-- 7. Return Shipping -->
        <div>
            <h3 class="text-xl font-semibold text-[var(--brand-orange)] mb-2">
                7. Return Shipping
            </h3>
            <p>
                Return shipping costs depend on the reason for return:
            </p>
            <ul class="list-disc list-inside mt-2 space-y-1">
                <li><strong>Defective/Damaged/Wrong Item:</strong> We cover return shipping costs</li>
                <li><strong>Change of Mind:</strong> Customer is responsible for return shipping</li>
                <li><strong>Size/Color Exchange:</strong> Customer pays shipping, we pay for new item shipping</li>
            </ul>
        </div>

        <!-- 8. Exchanges -->
        <div>
            <h3 class="text-xl font-semibold text-[var(--brand-orange)] mb-2">
                8. Exchanges
            </h3>
            <p>
                We offer exchanges for different sizes, colors, or similar items
                of equal or greater value. If the new item costs more, you'll
                pay the difference. If it costs less, we'll refund the difference.
            </p>
            <p class="mt-2">
                Exchange requests must be made within 30 days of delivery and
                subject to item availability.
            </p>
        </div>

        <!-- 9. Late or Missing Refunds -->
        <div>
            <h3 class="text-xl font-semibold text-[var(--brand-orange)] mb-2">
                9. Late or Missing Refunds
            </h3>
            <p>If you haven't received your refund yet:</p>
            <ul class="list-disc list-inside mt-2 space-y-1">
                <li>Check your bank account again</li>
                <li>Contact your credit card company (it may take some time)</li>
                <li>Contact your bank (there is often some processing time)</li>
                <li>Check your bKash/Nagad account for mobile payments</li>
            </ul>
            <p class="mt-2">
                If you've done all this and still have not received your refund,
                please contact us at <a href="mailto:support@k-glow.com" class="text-[var(--brand-orange)] underline">support@k-glow.com</a>
            </p>
        </div>

        <!-- 10. Damaged or Defective Items -->
        <div>
            <h3 class="text-xl font-semibold text-[var(--brand-orange)] mb-2">
                10. Damaged or Defective Items
            </h3>
            <p>
                If you receive a damaged or defective item, please contact us
                immediately with photos of the damage. We'll arrange for a
                replacement or full refund at no cost to you.
            </p>
            <p class="mt-2">
                Please report damaged items within 48 hours of delivery for
                fastest resolution.
            </p>
        </div>

        <!-- 11. International Returns -->
        <div>
            <h3 class="text-xl font-semibold text-[var(--brand-orange)] mb-2">
                11. International Returns
            </h3>
            <p>
                Currently, we only ship within Bangladesh. If you're outside
                Bangladesh and received an order, please contact us for
                assistance with returns or exchanges.
            </p>
        </div>

        <!-- 12. Contact Information -->
        <div>
            <h3 class="text-xl font-semibold text-[var(--brand-orange)] mb-2">
                12. Contact Us for Returns
            </h3>
            <p>
                For any questions about returns or refunds, please contact us:
            </p>
            <ul class="list-disc list-inside mt-2 space-y-1">
                <li>Email: <a href="mailto:returns@k-glow.com" class="text-[var(--brand-orange)] underline">returns@k-glow.com</a></li>
                <li>Phone: <a href="tel:+880123456789" class="text-[var(--brand-orange)] underline">+880 123 456 789</a></li>
                <li>Support Hours: Mon-Fri, 9AM-5PM</li>
                <li>Address: Dhaka, Bangladesh</li>
            </ul>
        </div>
    </div>
</section>

<!-- Quick Actions -->
<section class="py-12 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4">
        <div class="bg-white rounded-lg p-8 shadow-sm">
            <h3 class="text-xl font-semibold text-[var(--brand-orange)] mb-4">
                Quick Actions
            </h3>
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-semibold mb-2">Start a Return</h4>
                    <p class="text-sm text-gray-600 mb-4">Ready to return an item? Use our convenient return process.</p>
                    <a href="{{ route('contact') }}" class="inline-flex items-center px-4 py-2 bg-[var(--brand-orange)] text-white rounded-lg hover:bg-orange-600 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                        </svg>
                        Start Return
                    </a>
                </div>
                <div>
                    <h4 class="font-semibold mb-2">Track Your Return</h4>
                    <p class="text-sm text-gray-600 mb-4">Already started a return? Check its status.</p>
                    <a href="{{ route('customer.orders') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Track Return
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection



