<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\NotificationService;
use App\Models\Inventory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Display the checkout page
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Your cart is empty');
        }

        $cartItems = [];
        $subtotal = 0;
        $shipping = 0;
        $tax = 0;
        $total = 0;

        foreach ($cart as $id => $details) {
            $product = Product::find($id);
            if ($product) {
                $cartItems[] = [
                    'id' => $id,
                    'product' => $product,
                    'quantity' => $details['quantity'],
                    'price' => $details['price'],
                    'total' => $details['quantity'] * $details['price'],
                    'options' => $details['options'] ?? []
                ];
                $subtotal += $details['quantity'] * $details['price'];
            }
        }

        // Calculate shipping (free over ৳50)
        if ($subtotal < 50) {
            $shipping = 5.99;
        }

        // Calculate tax (8%)
        $tax = $subtotal * 0.08;
        $total = $subtotal + $shipping + $tax;

        return view('frontend.checkout', compact('cartItems', 'subtotal', 'shipping', 'tax', 'total'));
    }

    /**
     * Process the checkout
     */
    public function process(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string|max:500',
            'billing_address' => 'required|string|max:500',
            'phone' => 'required|string|max:20',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'payment_method' => 'required|string|in:sslcommerz,cash_on_delivery,bkash,nagad',
            'notes' => 'nullable|string|max:1000'
        ]);

        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Your cart is empty');
        }

        DB::beginTransaction();
        
        try {
            // Calculate totals
            $subtotal = 0;
            $shipping = 0;
            $tax = 0;
            
            foreach ($cart as $id => $details) {
                $subtotal += $details['quantity'] * $details['price'];
            }
            
            // Calculate shipping (free over ৳50)
            if ($subtotal < 50) {
                $shipping = 5.99;
            }
            
            // Calculate tax (8%)
            $tax = $subtotal * 0.08;
            $total = $subtotal + $shipping + $tax;

            // Create order
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => Auth::id(),
                'status' => 'pending',
                'order_status' => 'pending',
                'subtotal' => $subtotal,
                'tax_amount' => $tax,
                'shipping_amount' => $shipping,
                'total_amount' => $total,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'shipping_address' => $request->shipping_address,
                'billing_address' => $request->billing_address,
                'customer_name' => Auth::user()->name,
                'customer_email' => Auth::user()->email,
                'customer_phone' => $request->phone,
                'phone' => $request->phone,
                'city' => $request->city,
                'state' => $request->state,
                'postal_code' => $request->postal_code,
                'notes' => $request->notes,
            ]);

            // Create order items and update inventory
            foreach ($cart as $id => $details) {
                $product = Product::find($id);
                if ($product) {
                    // Create order item
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'product_sku' => $product->sku,
                        'price' => $details['price'],
                        'quantity' => $details['quantity'],
                        'total' => $details['quantity'] * $details['price'],
                        'product_options' => $details['options'] ?? []
                    ]);

                    // Update inventory if product has inventory tracking
                    if ($product->inventory && $product->inventory->track_stock) {
                        $product->inventory->updateStock(
                            -$details['quantity'], // Negative for outbound
                            'out',
                            'Order #' . $order->order_number,
                            'order',
                            $order->id
                        );
                    }
                }
            }

            // Clear cart
            session()->forget('cart');

            // Send order confirmation notification
            NotificationService::sendOrderConfirmation($order);

            DB::commit();

            // If AJAX request, return JSON response
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'message' => 'Order created successfully'
                ]);
            }

            return redirect()->route('customer.order.details', $order->id)
                ->with('success', 'Order placed successfully! Order #' . $order->order_number);

        } catch (\Exception $e) {
            DB::rollback();
            
            // If AJAX request, return JSON error
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to place order. Please try again.'
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Failed to place order. Please try again.');
        }
    }

    /**
     * Display order success page
     */
    public function success(Order $order)
    {
        return view('frontend.order-success', compact('order'));
    }
}
