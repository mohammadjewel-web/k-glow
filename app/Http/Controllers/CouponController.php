<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\CouponUsage;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class CouponController extends Controller
{
    /**
     * Display a listing of coupons (Admin).
     */
    public function index()
    {
        $coupons = Coupon::with('usages')->latest()->paginate(15);
        return view('admin.coupons.index', compact('coupons'));
    }

    /**
     * Show the form for creating a new coupon (Admin).
     */
    public function create()
    {
        return view('admin.coupons.create');
    }

    /**
     * Store a newly created coupon (Admin).
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:50|unique:coupons,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'minimum_amount' => 'nullable|numeric|min:0',
            'maximum_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'usage_limit_per_user' => 'nullable|integer|min:1',
            'applicable_products' => 'nullable|array',
            'applicable_categories' => 'nullable|array',
            'applicable_brands' => 'nullable|array',
            'excluded_products' => 'nullable|array',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:starts_at',
            'is_active' => 'boolean',
            'is_public' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $coupon = Coupon::create($request->all());

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon created successfully!');
    }

    /**
     * Display the specified coupon (Admin).
     */
    public function show(Coupon $coupon)
    {
        $coupon->load('usages.user', 'usages.order');
        return view('admin.coupons.show', compact('coupon'));
    }

    /**
     * Show the form for editing the specified coupon (Admin).
     */
    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    /**
     * Update the specified coupon (Admin).
     */
    public function update(Request $request, Coupon $coupon)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'minimum_amount' => 'nullable|numeric|min:0',
            'maximum_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'usage_limit_per_user' => 'nullable|integer|min:1',
            'applicable_products' => 'nullable|array',
            'applicable_categories' => 'nullable|array',
            'applicable_brands' => 'nullable|array',
            'excluded_products' => 'nullable|array',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:starts_at',
            'is_active' => 'boolean',
            'is_public' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $coupon->update($request->all());

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon updated successfully!');
    }

    /**
     * Remove the specified coupon (Admin).
     */
    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon deleted successfully!');
    }

    /**
     * Validate and apply coupon (Public API).
     */
    public function validateCoupon(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $coupon = Coupon::where('code', $request->code)
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
        $userId = Auth::id();
        if ($userId && $coupon->usage_limit_per_user) {
            $userUsageCount = $coupon->usages()->where('user_id', $userId)->count();
            if ($userUsageCount >= $coupon->usage_limit_per_user) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already used this coupon the maximum number of times'
                ], 400);
            }
        }

        return response()->json([
            'success' => true,
            'coupon' => [
                'id' => $coupon->id,
                'code' => $coupon->code,
                'name' => $coupon->name,
                'type' => $coupon->type,
                'value' => $coupon->value,
                'minimum_amount' => $coupon->minimum_amount,
                'maximum_discount' => $coupon->maximum_discount,
                'description' => $coupon->description,
            ]
        ]);
    }

    /**
     * Calculate discount for cart (Public API).
     */
    public function calculateDiscount(Request $request): JsonResponse
    {
        $request->validate([
            'coupon_code' => 'required|string',
            'cart_items' => 'required|array',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $coupon = Coupon::where('code', $request->coupon_code)
            ->active()
            ->public()
            ->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid coupon code'
            ], 404);
        }

        // Get cart items with product details
        $cartItems = [];
        foreach ($request->cart_items as $item) {
            $product = \App\Models\Product::find($item['product_id']);
            if ($product) {
                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ];
            }
        }

        $discount = $coupon->calculateDiscount($request->subtotal, $cartItems);

        if ($discount <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'This coupon cannot be applied to your cart'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'discount' => $discount,
            'coupon' => [
                'id' => $coupon->id,
                'code' => $coupon->code,
                'name' => $coupon->name,
                'type' => $coupon->type,
                'value' => $coupon->value,
            ]
        ]);
    }

    /**
     * Get available coupons (Public API).
     */
    public function getAvailableCoupons(): JsonResponse
    {
        $coupons = Coupon::available()
            ->public()
            ->select(['id', 'code', 'name', 'description', 'type', 'value', 'minimum_amount', 'expires_at'])
            ->get();

        return response()->json([
            'success' => true,
            'coupons' => $coupons
        ]);
    }
}
