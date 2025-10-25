@extends('layouts.app')

@section('title', 'Payment Cancelled')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-6">
        <!-- Cancel Header -->
        <div class="text-center mb-8">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-yellow-100 mb-4">
                <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Payment Cancelled</h1>
            <p class="text-lg text-gray-600">Your payment was cancelled. No charges have been made.</p>
        </div>

        <!-- Information -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">What happened?</h2>
            <p class="text-gray-700 mb-4">You cancelled the payment process. Your order has been saved but no payment was processed.</p>
            <div class="bg-blue-50 rounded-lg p-4">
                <p class="text-blue-800">
                    <span class="font-medium">Good news:</span> No money has been charged to your account, and you can complete your order anytime.
                </p>
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
                    Complete Payment
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

        <!-- Additional Information -->
        <div class="mt-8 bg-green-50 rounded-lg p-6">
            <h3 class="text-lg font-medium text-green-900 mb-2">Your Cart is Safe</h3>
            <p class="text-green-800 mb-2">Don't worry, your items are still in your cart and ready for checkout.</p>
            <ul class="text-green-800 space-y-1">
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-green-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    Your selected items remain in your cart
                </li>
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-green-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    You can complete your order anytime
                </li>
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-green-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    No charges have been made to your account
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection



