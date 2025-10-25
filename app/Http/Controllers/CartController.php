<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display the cart page
     */
    public function index()
    {
        $cart = session()->get('cart', []);
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

        // Get applied coupon and calculate discount
        $appliedCoupon = session()->get('applied_coupon');
        $discount = 0;

        if ($appliedCoupon) {
            $coupon = \App\Models\Coupon::find($appliedCoupon['id']);
            if ($coupon && $coupon->canBeUsedByUser(auth()->id())) {
                $discount = $coupon->calculateDiscount($subtotal, $cartItems);
            } else {
                // Remove invalid coupon
                session()->forget('applied_coupon');
            }
        }

        $total = $subtotal + $shipping + $tax - $discount;

        return view('frontend.cart', compact('cartItems', 'subtotal', 'shipping', 'tax', 'discount', 'total'));
    }

    /**
     * Add product to cart
     */
    public function add(Request $request)
    {
        $productId = $request->product_id;
        $quantity = $request->quantity ?? 1;
        $options = $request->options ?? [];

        $product = Product::findOrFail($productId);

        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                'name' => $product->name,
                'quantity' => $quantity,
                'price' => $product->discount_price ?? $product->price,
                'image' => $product->thumbnail,
                'options' => $options
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart',
            'cart_count' => $this->getCartCount(),
            'cart_total' => $this->getCartTotal()
        ]);
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request)
    {
        $productId = $request->product_id;
        $quantity = $request->quantity;

        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            if ($quantity <= 0) {
                unset($cart[$productId]);
            } else {
                $cart[$productId]['quantity'] = $quantity;
            }
            session()->put('cart', $cart);
        }

        return response()->json([
            'success' => true,
            'cart_count' => $this->getCartCount(),
            'cart_total' => $this->getCartTotal()
        ]);
    }

    /**
     * Remove item from cart
     */
    public function remove(Request $request)
    {
        $productId = $request->product_id;
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product removed from cart',
            'cart_count' => $this->getCartCount(),
            'cart_total' => $this->getCartTotal()
        ]);
    }

    /**
     * Clear entire cart
     */
    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('cart')->with('success', 'Cart cleared successfully');
    }

    /**
     * Get cart count
     */
    private function getCartCount()
    {
        $cart = session()->get('cart', []);
        return array_sum(array_column($cart, 'quantity'));
    }

    /**
     * Get cart total
     */
    private function getCartTotal()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['quantity'] * $item['price'];
        }
        return number_format($total, 2);
    }

    /**
     * Get cart data for API
     */
    public function getCart()
    {
        $cart = session()->get('cart', []);
        $cartItems = [];
        $subtotal = 0;

        foreach ($cart as $id => $details) {
            $product = Product::find($id);
            if ($product) {
                $cartItems[] = [
                    'id' => $id,
                    'name' => $product->name,
                    'thumbnail' => $product->thumbnail,
                    'sku' => $product->sku,
                    'quantity' => $details['quantity'],
                    'price' => number_format($details['price'], 2),
                    'total' => number_format($details['quantity'] * $details['price'], 2),
                    'options' => $details['options'] ?? []
                ];
                $subtotal += $details['quantity'] * $details['price'];
            }
        }

        // Get applied coupon
        $appliedCoupon = session()->get('applied_coupon');
        $discount = 0;
        $couponCode = null;

        if ($appliedCoupon) {
            $coupon = \App\Models\Coupon::where('code', $appliedCoupon['code'])->first();
            if ($coupon && $coupon->canBeUsedByUser(auth()->id())) {
                $discount = $coupon->calculateDiscount($subtotal, $cartItems);
                $couponCode = $coupon->code;
            } else {
                // Remove invalid coupon
                session()->forget('applied_coupon');
            }
        }

        // Calculate totals
        $shipping = 10.00; // Fixed shipping cost
        $tax = $subtotal * 0.1; // 10% tax
        $total = $subtotal + $shipping + $tax - $discount;

        return response()->json([
            'items' => $cartItems,
            'subtotal' => number_format($subtotal, 2),
            'shipping' => number_format($shipping, 2),
            'tax' => number_format($tax, 2),
            'discount' => number_format($discount, 2),
            'total' => number_format($total, 2),
            'cart_count' => $this->getCartCount(),
            'applied_coupon' => $appliedCoupon,
            'coupon_code' => $couponCode
        ]);
    }

    /**
     * Get cart items for API
     */
    public function getCartItems()
    {
        $cart = session()->get('cart', []);
        $cartItems = [];

        foreach ($cart as $id => $details) {
            $product = Product::find($id);
            if ($product) {
                $cartItems[] = [
                    'id' => $id,
                    'name' => $product->name,
                    'thumbnail' => $product->thumbnail,
                    'price' => $details['price'],
                    'quantity' => $details['quantity'],
                    'total' => $details['quantity'] * $details['price']
                ];
            }
        }

        return response()->json($cartItems);
    }

    /**
     * Get cart count for API
     */
    public function getCartCountApi()
    {
        return response()->json([
            'count' => $this->getCartCount()
        ]);
    }

    /**
     * Apply coupon to cart
     */
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string'
        ]);

        $coupon = \App\Models\Coupon::where('code', $request->code)
            ->active()
            ->public()
            ->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid coupon code'
            ], 404);
        }

        // Check if coupon is valid
        if ($coupon->isExpired()) {
            return response()->json([
                'success' => false,
                'message' => 'This coupon has expired'
            ], 400);
        }

        if ($coupon->isNotStarted()) {
            return response()->json([
                'success' => false,
                'message' => 'This coupon is not yet active'
            ], 400);
        }

        if ($coupon->isUsageLimitReached()) {
            return response()->json([
                'success' => false,
                'message' => 'This coupon has reached its usage limit'
            ], 400);
        }

        // Check user usage limit
        $userId = auth()->id();
        if ($userId && $coupon->usage_limit_per_user) {
            $userUsageCount = $coupon->usages()->where('user_id', $userId)->count();
            if ($userUsageCount >= $coupon->usage_limit_per_user) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already used this coupon the maximum number of times'
                ], 400);
            }
        }

        // Get cart data for validation
        $cart = session()->get('cart', []);
        $cartItems = [];
        $subtotal = 0;

        foreach ($cart as $id => $details) {
            $product = Product::find($id);
            if ($product) {
                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $details['quantity'],
                    'price' => $details['price']
                ];
                $subtotal += $details['quantity'] * $details['price'];
            }
        }

        // Check minimum amount
        if ($coupon->minimum_amount && $subtotal < $coupon->minimum_amount) {
            return response()->json([
                'success' => false,
                'message' => 'Minimum order amount of ৳' . $coupon->minimum_amount . ' required for this coupon'
            ], 400);
        }

        // Check product restrictions
        if (!$coupon->isApplicableToCart($cartItems)) {
            return response()->json([
                'success' => false,
                'message' => 'This coupon cannot be applied to your current cart items'
            ], 400);
        }

        // Calculate discount
        $discount = $coupon->calculateDiscount($subtotal, $cartItems);

        if ($discount <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'This coupon cannot be applied to your cart'
            ], 400);
        }

        // Store applied coupon in session
        session()->put('applied_coupon', [
            'id' => $coupon->id,
            'code' => $coupon->code,
            'name' => $coupon->name,
            'type' => $coupon->type,
            'value' => $coupon->value,
            'discount' => $discount
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Coupon applied successfully!',
            'coupon' => [
                'code' => $coupon->code,
                'name' => $coupon->name,
                'discount' => number_format($discount, 2)
            ]
        ]);
    }

    /**
     * Remove coupon from cart
     */
    public function removeCoupon()
    {
        session()->forget('applied_coupon');

        return response()->json([
            'success' => true,
            'message' => 'Coupon removed successfully!'
        ]);
    }
}
