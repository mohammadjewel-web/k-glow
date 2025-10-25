@extends('layouts.app')

@section('title', 'Select Verification Method - K-Glow')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Header -->
        <div class="text-center">
            @php
                $logo = \App\Models\Setting::get('logo', 'admin-assets/logo.png');
                $siteName = \App\Models\Setting::get('site_name', 'K-Glow');
            @endphp
            <img src="{{ asset($logo) }}" alt="{{ $siteName }} Logo" class="mx-auto h-16 w-auto">
            <h2 class="mt-6 text-3xl font-bold text-gray-900">
                Verify Your Account
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Please select your preferred verification method to secure your account
            </p>
        </div>

        <!-- Verification Methods -->
        <div class="bg-white shadow-lg rounded-lg p-8">
            <div class="space-y-4">
                <!-- Email Verification -->
                <div class="border border-gray-200 rounded-lg p-6 hover:border-[var(--brand-orange)] hover:shadow-md transition-all cursor-pointer verification-method"
                     data-method="email">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <h3 class="text-lg font-semibold text-gray-900">Email Verification</h3>
                            <p class="mt-1 text-sm text-gray-600">
                                We'll send a 6-digit code to<br>
                                <span class="font-medium text-gray-900">{{ $user->email }}</span>
                            </p>
                            @if($user->isEmailVerified())
                                <span class="inline-flex items-center mt-2 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Verified
                                </span>
                            @endif
                        </div>
                        <div class="ml-4">
                            <input type="radio" name="verification_method" value="email" class="w-5 h-5 text-[var(--brand-orange)] focus:ring-[var(--brand-orange)]">
                        </div>
                    </div>
                </div>

                <!-- SMS Verification -->
                <div class="border border-gray-200 rounded-lg p-6 hover:border-[var(--brand-orange)] hover:shadow-md transition-all cursor-pointer verification-method"
                     data-method="sms">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <h3 class="text-lg font-semibold text-gray-900">SMS Verification</h3>
                            <p class="mt-1 text-sm text-gray-600">
                                We'll send a 6-digit code to<br>
                                <span class="font-medium text-gray-900">{{ $user->phone }}</span>
                            </p>
                            @if($user->isPhoneVerified())
                                <span class="inline-flex items-center mt-2 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Verified
                                </span>
                            @endif
                        </div>
                        <div class="ml-4">
                            <input type="radio" name="verification_method" value="sms" class="w-5 h-5 text-[var(--brand-orange)] focus:ring-[var(--brand-orange)]">
                        </div>
                    </div>
                </div>

                <!-- Both Verification -->
                <div class="border border-gray-200 rounded-lg p-6 hover:border-[var(--brand-orange)] hover:shadow-md transition-all cursor-pointer verification-method"
                     data-method="both">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <h3 class="text-lg font-semibold text-gray-900">Both (Recommended)</h3>
                            <p class="mt-1 text-sm text-gray-600">
                                Extra security with email and SMS verification
                            </p>
                            <span class="inline-flex items-center mt-2 px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Most Secure
                            </span>
                        </div>
                        <div class="ml-4">
                            <input type="radio" name="verification_method" value="both" class="w-5 h-5 text-[var(--brand-orange)] focus:ring-[var(--brand-orange)]">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Continue Button -->
            <div class="mt-8">
                <button id="continueBtn" disabled
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gray-300 cursor-not-allowed transition-colors">
                    Select a verification method to continue
                </button>
            </div>

            <!-- Skip for now (Development only) -->
            @if(config('app.debug'))
            <div class="mt-4 text-center">
                <a href="{{ route('verification.skip') }}" class="text-sm text-gray-500 hover:text-gray-700">
                    Skip verification (Development only)
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const methodCards = document.querySelectorAll('.verification-method');
    const continueBtn = document.getElementById('continueBtn');
    let selectedMethod = null;

    methodCards.forEach(card => {
        card.addEventListener('click', function() {
            // Remove selection from all cards
            methodCards.forEach(c => {
                c.classList.remove('border-[var(--brand-orange)]', 'bg-orange-50');
                c.querySelector('input[type="radio"]').checked = false;
            });

            // Add selection to clicked card
            this.classList.add('border-[var(--brand-orange)]', 'bg-orange-50');
            this.querySelector('input[type="radio"]').checked = true;
            selectedMethod = this.dataset.method;

            // Enable continue button
            continueBtn.disabled = false;
            continueBtn.classList.remove('bg-gray-300', 'cursor-not-allowed');
            continueBtn.classList.add('bg-[var(--brand-orange)]', 'hover:bg-orange-600', 'cursor-pointer');
            continueBtn.textContent = 'Continue';
        });
    });

    continueBtn.addEventListener('click', function() {
        if (!selectedMethod) return;

        // Show loading state
        this.disabled = true;
        this.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';

        // Send OTP
        fetch('{{ route("verification.send") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                method: selectedMethod
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Redirect to verification page
                const verifyType = selectedMethod === 'both' ? 'email' : selectedMethod;
                window.location.href = '{{ url("/verify") }}/' + verifyType;
            } else {
                alert('Error: ' + data.message);
                this.disabled = false;
                this.textContent = 'Continue';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
            this.disabled = false;
            this.textContent = 'Continue';
        });
    });
});
</script>
@endsection


