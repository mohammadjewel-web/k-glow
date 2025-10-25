<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Cart;
use App\Library\SslCommerz\SslCommerzNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Initiate SSLCommerz payment
     */
    public function initiatePayment(Request $request)
    {
        try {
            // Validate request
            $request->validate([
                'order_id' => 'required|exists:orders,id',
                'payment_method' => 'required|in:sslcommerz,bkash,nagad,cash_on_delivery'
            ]);

            $order = Order::with(['orderItems.product', 'user'])->findOrFail($request->order_id);

            // Check if order belongs to authenticated user
            if (Auth::check() && $order->user_id !== Auth::id()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            // If cash on delivery, mark as paid and return success
            if ($request->payment_method === 'cash_on_delivery') {
                $order->update([
                    'payment_method' => 'cash_on_delivery',
                    'payment_status' => 'pending',
                    'order_status' => 'confirmed'
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Order confirmed. Payment will be collected on delivery.',
                    'redirect_url' => route('order.success', $order->id)
                ]);
            }

            // For SSLCommerz payment
            if ($request->payment_method === 'sslcommerz') {
                Log::info('Starting SSLCommerz payment initiation', [
                    'order_id' => $order->id,
                    'total_amount' => $order->total_amount,
                    'customer_name' => $order->customer_name
                ]);
                
                // Debug SSLCommerz configuration
                $config = config('sslcommerz');
                Log::info('SSLCommerz config:', [
                    'store_id' => $config['apiCredentials']['store_id'],
                    'store_password' => $config['apiCredentials']['store_password'] ? 'SET' : 'NOT SET',
                    'api_domain' => $config['apiDomain'],
                    'test_mode' => env('SSLCZ_TESTMODE')
                ]);
                
                $sslcommerz = new SslCommerzNotification();
                
                $post_data = array();
                $post_data['total_amount'] = $order->total_amount;
                $post_data['currency'] = "BDT";
                $post_data['tran_id'] = $order->id . '_' . time(); // Unique transaction ID
                $post_data['product_category'] = "goods"; // Required by SSLCommerz
                $post_data['shipping_method'] = "YES"; // Required by SSLCommerz
                $post_data['num_of_item'] = $order->orderItems->count(); // Required by SSLCommerz

                # CUSTOMER INFORMATION
                $post_data['cus_name'] = $order->customer_name;
                $post_data['cus_email'] = $order->customer_email;
                $post_data['cus_add1'] = $order->shipping_address;
                $post_data['cus_add2'] = "";
                $post_data['cus_city'] = $order->city;
                $post_data['cus_state'] = $order->state;
                $post_data['cus_postcode'] = $order->postal_code;
                $post_data['cus_country'] = "Bangladesh";
                $post_data['cus_phone'] = $order->customer_phone;
                $post_data['cus_fax'] = "";

                # SHIPMENT INFORMATION
                $post_data['ship_name'] = $order->customer_name;
                $post_data['ship_add1'] = $order->shipping_address;
                $post_data['ship_add2'] = "";
                $post_data['ship_city'] = $order->city; // Required when shipping_method is YES
                $post_data['ship_state'] = $order->state; // Required when shipping_method is YES
                $post_data['ship_postcode'] = $order->postal_code; // Required when shipping_method is YES
                $post_data['ship_country'] = "Bangladesh";

                # OPTIONAL PARAMETERS
                $post_data['value_a'] = $order->id; // Order ID
                $post_data['value_b'] = $request->payment_method;
                $post_data['value_c'] = Auth::id() ?? 'guest';
                $post_data['value_d'] = "";

                # CART PARAMETERS
                $post_data['cart'] = json_encode($order->orderItems->map(function($item) {
                    return [
                        'product' => $item->product->name,
                        'amount' => $item->price
                    ];
                })->toArray());
                $post_data['product_amount'] = $order->total_amount;
                $post_data['vat'] = "0";
                $post_data['discount_amount'] = $order->discount_amount ?? 0;
                $post_data['convenience_fee'] = "0";
                
                # PRODUCT NAME (Required by SSLCommerz)
                $productNames = $order->orderItems->map(function($item) {
                    return $item->product->name;
                })->toArray();
                $productNameString = implode(', ', $productNames);
                // Limit product name to 256 characters as per SSLCommerz requirement
                $post_data['product_name'] = strlen($productNameString) > 256 ? substr($productNameString, 0, 253) . '...' : $productNameString;
                
                # PRODUCT PROFILE (Required by SSLCommerz)
                $post_data['product_profile'] = "physical-goods";

                // Update order with payment method and transaction ID
                $order->update([
                    'payment_method' => 'sslcommerz',
                    'payment_status' => 'pending',
                    'transaction_id' => $post_data['tran_id']
                ]);

                // Initiate payment
                Log::info('SSLCommerz post_data:', $post_data);
                Log::info('SSLCommerz post_data count:', ['count' => count($post_data)]);
                
                $payment_response_json = $sslcommerz->makePayment($post_data, 'checkout', 'json');
                $payment_response = json_decode($payment_response_json, true);
                
                Log::info('SSLCommerz payment response:', ['response' => $payment_response]);
                Log::info('SSLCommerz raw response:', ['raw' => $payment_response_json]);

                if (isset($payment_response['status']) && $payment_response['status'] === 'success' && isset($payment_response['data'])) {
                    return response()->json([
                        'success' => true,
                        'payment_url' => $payment_response['data']
                    ]);
                } else {
                    Log::error('SSLCommerz payment initiation failed: ' . json_encode($payment_response));
                    return response()->json([
                        'success' => false,
                        'message' => 'Payment initiation failed: ' . ($payment_response['message'] ?? 'Unknown error')
                    ], 500);
                }
            }

            // For other payment methods (bKash, Nagad) - implement as needed
            return response()->json([
                'error' => 'Payment method not implemented yet'
            ], 400);

        } catch (\Exception $e) {
            Log::error('Payment initiation error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Payment initiation failed'
            ], 500);
        }
    }

    /**
     * Handle successful payment
     */
    public function success(Request $request)
    {
        try {
            // Debug the request data
            Log::info('SSLCommerz success callback received:', [
                'method' => $request->method(),
                'all_data' => $request->all(),
                'tran_id' => $request->input('tran_id'),
                'amount' => $request->input('amount'),
                'currency' => $request->input('currency'),
                'user_authenticated' => Auth::check(),
                'user_id' => Auth::id(),
                'session_id' => session()->getId()
            ]);
            
            $sslcommerz = new SslCommerzNotification();
            $tran_id = $request->input('tran_id');
            
            // Validate payment
            $order_validate = $sslcommerz->orderValidate($tran_id, $request->input('amount'), $request->input('currency'), $request->all());
            
            Log::info('SSLCommerz order validation result:', [
                'tran_id' => $tran_id,
                'amount' => $request->input('amount'),
                'currency' => $request->input('currency'),
                'validation_result' => $order_validate
            ]);
            
            if ($order_validate) {
                // Extract order ID from transaction ID
                $order_id = explode('_', $tran_id)[0];
                $order = Order::find($order_id);
                
                if ($order) {
                    $order->update([
                        'payment_status' => 'completed',
                        'order_status' => 'confirmed',
                        'payment_reference' => $request->input('bank_tran_id'),
                        'payment_date' => now()
                    ]);

                    // Clear user's cart if authenticated (without session dependency)
                    try {
                        if (Auth::check()) {
                            Cart::where('user_id', Auth::id())->delete();
                        }
                    } catch (\Exception $e) {
                        Log::info('Cart clearing failed (non-critical): ' . $e->getMessage());
                    }

                    // Debug session after order update
                    Log::info('After order update:', [
                        'user_authenticated' => Auth::check(),
                        'user_id' => Auth::id(),
                        'session_id' => session()->getId(),
                        'order_id' => $order->id
                    ]);
                    
                    // Store order info in session for the success page
                    session(['payment_success_order' => $order->id]);
                    
                    // Redirect to payment success page (no auth required)
                    return redirect()->route('payment.success.page')
                        ->with('success', 'Payment completed successfully! Order #' . $order->order_number);
                }
            }
            
            return redirect()->route('customer.dashboard')
                ->with('error', 'Payment validation failed');
                
        } catch (\Exception $e) {
            Log::error('Payment success error: ' . $e->getMessage());
            return redirect()->route('customer.dashboard')
                ->with('error', 'Payment processing error');
        }
    }

    /**
     * Handle failed payment
     */
    public function fail(Request $request)
    {
        $tran_id = $request->input('tran_id');
        $order_id = explode('_', $tran_id)[0];
        
        $order = Order::find($order_id);
        if ($order) {
            $order->update([
                'payment_status' => 'failed',
                'order_status' => 'cancelled'
            ]);
        }

        return redirect()->route('payment.fail.page')
            ->with('error', 'Payment failed. Please try again.');
    }

    /**
     * Handle cancelled payment
     */
    public function cancel(Request $request)
    {
        $tran_id = $request->input('tran_id');
        $order_id = explode('_', $tran_id)[0];
        
        $order = Order::find($order_id);
        if ($order) {
            $order->update([
                'payment_status' => 'cancelled',
                'order_status' => 'cancelled'
            ]);
        }

        return redirect()->route('payment.cancel.page')
            ->with('error', 'Payment was cancelled.');
    }

    /**
     * Handle IPN (Instant Payment Notification)
     */
    public function ipn(Request $request)
    {
        try {
            $sslcommerz = new SslCommerzNotification();
            
            if ($sslcommerz->ipnValidate($request->all())) {
                $tran_id = $request->input('tran_id');
                $order_id = explode('_', $tran_id)[0];
                $order = Order::find($order_id);
                
                if ($order && $request->input('status') === 'VALID') {
                    $order->update([
                        'payment_status' => 'completed',
                        'order_status' => 'confirmed',
                        'payment_reference' => $request->input('bank_tran_id'),
                        'payment_date' => now()
                    ]);
                }
                
                return response()->json(['status' => 'success']);
            }
            
            return response()->json(['status' => 'failed']);
            
        } catch (\Exception $e) {
            Log::error('IPN error: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * Get payment status
     */
    public function getStatus(Request $request)
    {
        $tran_id = $request->input('tran_id');
        $order_id = explode('_', $tran_id)[0];
        
        $order = Order::find($order_id);
        
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }
        
        return response()->json([
            'order_id' => $order->id,
            'payment_status' => $order->payment_status,
            'order_status' => $order->order_status,
            'total_amount' => $order->total_amount
        ]);
    }

    /**
     * Show payment success page (public - no auth required)
     */
    public function showSuccessPage(Request $request, $order = null)
    {
        $orderData = null;
        
        // If this is a POST request from SSLCommerz callback
        if ($request->isMethod('post')) {
            try {
                // Debug the request data
                Log::info('SSLCommerz success callback received in showSuccessPage:', [
                    'method' => $request->method(),
                    'all_data' => $request->all(),
                    'tran_id' => $request->input('tran_id'),
                    'amount' => $request->input('amount'),
                    'currency' => $request->input('currency'),
                ]);
                
                $sslcommerz = new SslCommerzNotification();
                $tran_id = $request->input('tran_id');
                
                // Validate payment
                $order_validate = $sslcommerz->orderValidate($tran_id, $request->input('amount'), $request->input('currency'), $request->all());
                
                Log::info('SSLCommerz order validation result in showSuccessPage:', [
                    'tran_id' => $tran_id,
                    'amount' => $request->input('amount'),
                    'currency' => $request->input('currency'),
                    'validation_result' => $order_validate
                ]);
                
                if ($order_validate) {
                    // Extract order ID from transaction ID
                    $order_id = explode('_', $tran_id)[0];
                    $orderData = Order::find($order_id);
                    
                    if ($orderData) {
                        $orderData->update([
                            'payment_status' => 'completed',
                            'order_status' => 'confirmed',
                            'payment_reference' => $request->input('bank_tran_id'),
                            'payment_date' => now()
                        ]);

                        // Clear user's cart if authenticated (without session dependency)
                        try {
                            if (Auth::check()) {
                                Cart::where('user_id', Auth::id())->delete();
                            }
                        } catch (\Exception $e) {
                            Log::info('Cart clearing failed (non-critical): ' . $e->getMessage());
                        }

                        Log::info('Order updated successfully in showSuccessPage:', [
                            'order_id' => $orderData->id,
                            'payment_status' => 'completed'
                        ]);
                    }
                }
            } catch (\Exception $e) {
                Log::error('Payment success processing error in showSuccessPage: ' . $e->getMessage());
            }
        }
        
        // If order data is still null, try to get from session
        if (!$orderData && session('payment_success_order')) {
            $orderData = Order::find(session('payment_success_order'));
            // Clear the session data
            session()->forget('payment_success_order');
        }
        
        return view('frontend.payment-success', compact('orderData'));
    }

    /**
     * Show payment fail page (public - no auth required)
     */
    public function showFailPage(Request $request)
    {
        // If this is a POST request from SSLCommerz callback
        if ($request->isMethod('post')) {
            try {
                // Debug the request data
                Log::info('SSLCommerz fail callback received:', [
                    'method' => $request->method(),
                    'all_data' => $request->all(),
                    'tran_id' => $request->input('tran_id'),
                ]);
                
                $tran_id = $request->input('tran_id');
                if ($tran_id) {
                    $order_id = explode('_', $tran_id)[0];
                    $order = Order::find($order_id);
                    
                    if ($order) {
                        $order->update([
                            'payment_status' => 'failed',
                            'order_status' => 'cancelled'
                        ]);
                        
                        Log::info('Order updated to failed status:', [
                            'order_id' => $order->id,
                            'payment_status' => 'failed'
                        ]);
                    }
                }
            } catch (\Exception $e) {
                Log::error('Payment fail processing error: ' . $e->getMessage());
            }
        }
        
        return view('frontend.payment-fail');
    }

    /**
     * Show payment cancel page (public - no auth required)
     */
    public function showCancelPage(Request $request)
    {
        // If this is a POST request from SSLCommerz callback
        if ($request->isMethod('post')) {
            try {
                // Debug the request data
                Log::info('SSLCommerz cancel callback received:', [
                    'method' => $request->method(),
                    'all_data' => $request->all(),
                    'tran_id' => $request->input('tran_id'),
                ]);
                
                $tran_id = $request->input('tran_id');
                if ($tran_id) {
                    $order_id = explode('_', $tran_id)[0];
                    $order = Order::find($order_id);
                    
                    if ($order) {
                        $order->update([
                            'payment_status' => 'cancelled',
                            'order_status' => 'cancelled'
                        ]);
                        
                        Log::info('Order updated to cancelled status:', [
                            'order_id' => $order->id,
                            'payment_status' => 'cancelled'
                        ]);
                    }
                }
            } catch (\Exception $e) {
                Log::error('Payment cancel processing error: ' . $e->getMessage());
            }
        }
        
        return view('frontend.payment-cancel');
    }
}
