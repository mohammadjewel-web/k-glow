@extends('layouts.app')

@section('title', 'Payment Failed')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-6">
        <!-- Fail Header -->
        <div class="text-center mb-8">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Payment Failed</h1>
            <p class="text-lg text-gray-600">We're sorry, but your payment could not be processed.</p>
        </div>

        <!-- Error Information -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">What happened?</h2>
            <div class="space-y-4">
                <p class="text-gray-700">Your payment was not successful. This could be due to:</p>
                <ul class="list-disc list-inside text-gray-700 space-y-2 ml-4">
                    <li>Insufficient funds in your account</li>
                    <li>Card details entered incorrectly</li>
                    <li>Network connectivity issues</li>
                    <li>Bank security restrictions</li>
                </ul>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="text-center space-y-4">
            <div class="space-x-4">
                <a href="{{ route('checkout') }}" 
                   class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    Try Again
                </a>
                
                <a href="{{ route('customer.dashboard') }}" 
                   class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                    </svg>
                    Go to Dashboard
                </a>
            </div>
            
            <div>
                <a href="{{ route('shop') }}" 
                   class="text-primary-600 hover:text-primary-500 font-medium">
                    Continue Shopping
                </a>
            </div>
        </div>

        <!-- Help Information -->
        <div class="mt-8 bg-yellow-50 rounded-lg p-6">
            <h3 class="text-lg font-medium text-yellow-900 mb-2">Need Help?</h3>
            <p class="text-yellow-800 mb-4">If you continue to experience payment issues, please contact our support team.</p>
            <div class="space-y-2">
                <p class="text-yellow-800">
                    <span class="font-medium">Email:</span> support@k-glowbd.com
                </p>
                <p class="text-yellow-800">
                    <span class="font-medium">Phone:</span> +880-1234-567890
                </p>
            </div>
        </div>
    </div>
</div>
@endsection



