@extends('layouts.app')

@section('title', 'Verify OTP - K-Glow')

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
                Enter Verification Code
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                We sent a 6-digit code to<br>
                <span class="font-medium text-gray-900">
                    {{ $type === 'email' ? $user->email : $user->phone }}
                </span>
            </p>
        </div>

        <!-- OTP Input Form -->
        <div class="bg-white shadow-lg rounded-lg p-8">
            <form id="otpForm" class="space-y-6">
                @csrf
                <input type="hidden" name="type" value="{{ $type }}">

                <!-- OTP Input Boxes -->
                <div class="flex justify-center gap-2">
                    <input type="text" maxlength="1" class="otp-input w-12 h-14 text-center text-2xl font-semibold border-2 border-gray-300 rounded-lg focus:border-[var(--brand-orange)] focus:ring-[var(--brand-orange)] focus:outline-none transition-colors" data-index="0" autocomplete="off">
                    <input type="text" maxlength="1" class="otp-input w-12 h-14 text-center text-2xl font-semibold border-2 border-gray-300 rounded-lg focus:border-[var(--brand-orange)] focus:ring-[var(--brand-orange)] focus:outline-none transition-colors" data-index="1" autocomplete="off">
                    <input type="text" maxlength="1" class="otp-input w-12 h-14 text-center text-2xl font-semibold border-2 border-gray-300 rounded-lg focus:border-[var(--brand-orange)] focus:ring-[var(--brand-orange)] focus:outline-none transition-colors" data-index="2" autocomplete="off">
                    <input type="text" maxlength="1" class="otp-input w-12 h-14 text-center text-2xl font-semibold border-2 border-gray-300 rounded-lg focus:border-[var(--brand-orange)] focus:ring-[var(--brand-orange)] focus:outline-none transition-colors" data-index="3" autocomplete="off">
                    <input type="text" maxlength="1" class="otp-input w-12 h-14 text-center text-2xl font-semibold border-2 border-gray-300 rounded-lg focus:border-[var(--brand-orange)] focus:ring-[var(--brand-orange)] focus:outline-none transition-colors" data-index="4" autocomplete="off">
                    <input type="text" maxlength="1" class="otp-input w-12 h-14 text-center text-2xl font-semibold border-2 border-gray-300 rounded-lg focus:border-[var(--brand-orange)] focus:ring-[var(--brand-orange)] focus:outline-none transition-colors" data-index="5" autocomplete="off">
                </div>

                <!-- Error Message -->
                <div id="errorMessage" class="hidden">
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                        <span id="errorText"></span>
                    </div>
                </div>

                <!-- Success Message -->
                <div id="successMessage" class="hidden">
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
                        <span id="successText"></span>
                    </div>
                </div>

                <!-- Verify Button -->
                <button type="submit" id="verifyBtn"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-[var(--brand-orange)] hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--brand-orange)] transition-colors">
                    Verify Code
                </button>

                <!-- Resend Code -->
                <div class="text-center">
                    <p class="text-sm text-gray-600">
                        Didn't receive the code?
                        <button type="button" id="resendBtn" class="font-medium text-[var(--brand-orange)] hover:text-orange-700">
                            Resend Code
                        </button>
                    </p>
                    <p id="countdown" class="text-xs text-gray-500 mt-1"></p>
                </div>
            </form>

            <!-- Timer -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-500">
                    Code expires in <span id="timer" class="font-semibold text-gray-900">10:00</span>
                </p>
            </div>
        </div>

        <!-- Back Button -->
        <div class="text-center">
            <a href="{{ route('verification.method') }}" class="text-sm text-gray-600 hover:text-gray-900">
                ← Back to verification methods
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const otpInputs = document.querySelectorAll('.otp-input');
    const verifyBtn = document.getElementById('verifyBtn');
    const resendBtn = document.getElementById('resendBtn');
    const errorMessage = document.getElementById('errorMessage');
    const successMessage = document.getElementById('successMessage');
    const errorText = document.getElementById('errorText');
    const successText = document.getElementById('successText');
    const timerElement = document.getElementById('timer');
    const countdownElement = document.getElementById('countdown');
    
    let timerSeconds = 600; // 10 minutes
    let resendCountdown = 0;
    let canResend = true;

    // OTP Input Handling
    otpInputs.forEach((input, index) => {
        input.addEventListener('input', function(e) {
            const value = e.target.value;
            
            // Only allow numbers
            if (!/^\d$/.test(value)) {
                e.target.value = '';
                return;
            }

            // Move to next input
            if (value && index < otpInputs.length - 1) {
                otpInputs[index + 1].focus();
            }

            // Auto-submit when all fields are filled
            if (index === otpInputs.length - 1 && value) {
                submitOTP();
            }
        });

        input.addEventListener('keydown', function(e) {
            // Move to previous input on backspace
            if (e.key === 'Backspace' && !e.target.value && index > 0) {
                otpInputs[index - 1].focus();
            }
        });

        input.addEventListener('paste', function(e) {
            e.preventDefault();
            const pasteData = e.clipboardData.getData('text').trim();
            
            if (/^\d{6}$/.test(pasteData)) {
                otpInputs.forEach((input, i) => {
                    input.value = pasteData[i];
                });
                submitOTP();
            }
        });
    });

    // Timer
    const timerInterval = setInterval(() => {
        timerSeconds--;
        const minutes = Math.floor(timerSeconds / 60);
        const seconds = timerSeconds % 60;
        timerElement.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;

        if (timerSeconds <= 0) {
            clearInterval(timerInterval);
            showError('Code has expired. Please request a new one.');
            verifyBtn.disabled = true;
        }
    }, 1000);

    // Resend countdown
    function startResendCountdown() {
        canResend = false;
        resendCountdown = 60;
        resendBtn.disabled = true;
        resendBtn.classList.add('opacity-50', 'cursor-not-allowed');

        const countdownInterval = setInterval(() => {
            resendCountdown--;
            countdownElement.textContent = `You can resend code in ${resendCountdown}s`;

            if (resendCountdown <= 0) {
                clearInterval(countdownInterval);
                canResend = true;
                resendBtn.disabled = false;
                resendBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                countdownElement.textContent = '';
            }
        }, 1000);
    }

    // Form submission
    function submitOTP() {
        const otp = Array.from(otpInputs).map(input => input.value).join('');
        
        if (otp.length !== 6) {
            showError('Please enter all 6 digits');
            return;
        }

        // Show loading
        verifyBtn.disabled = true;
        verifyBtn.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';

        fetch('{{ route("verification.verify") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                otp: otp,
                type: '{{ $type }}'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccess(data.message);
                
                if (data.fully_verified) {
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1500);
                } else if (data.next_step) {
                    setTimeout(() => {
                        window.location.href = '{{ url("/verify") }}/' + data.next_step;
                    }, 1500);
                }
            } else {
                showError(data.message + (data.attempts_left ? ` (${data.attempts_left} attempts left)` : ''));
                otpInputs.forEach(input => input.value = '');
                otpInputs[0].focus();
                verifyBtn.disabled = false;
                verifyBtn.textContent = 'Verify Code';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('An error occurred. Please try again.');
            verifyBtn.disabled = false;
            verifyBtn.textContent = 'Verify Code';
        });
    }

    document.getElementById('otpForm').addEventListener('submit', function(e) {
        e.preventDefault();
        submitOTP();
    });

    // Resend OTP
    resendBtn.addEventListener('click', function() {
        if (!canResend) return;

        this.disabled = true;
        this.textContent = 'Sending...';

        fetch('{{ route("verification.resend") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                type: '{{ $type }}'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccess('Code resent successfully!');
                timerSeconds = 600; // Reset timer
                startResendCountdown();
            } else {
                showError(data.message);
                this.disabled = false;
                this.textContent = 'Resend Code';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('Failed to resend code. Please try again.');
            this.disabled = false;
            this.textContent = 'Resend Code';
        });
    });

    function showError(message) {
        errorText.textContent = message;
        errorMessage.classList.remove('hidden');
        successMessage.classList.add('hidden');
        
        setTimeout(() => {
            errorMessage.classList.add('hidden');
        }, 5000);
    }

    function showSuccess(message) {
        successText.textContent = message;
        successMessage.classList.remove('hidden');
        errorMessage.classList.add('hidden');
    }

    // Focus first input on load
    otpInputs[0].focus();
});
</script>
@endsection


