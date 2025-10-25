<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Display the user's wishlist.
     */
    public function index()
    {
        $wishlistItems = Auth::user()->wishlists()->with('product.category', 'product.brand')->get();
        
        return view('customer.wishlist', compact('wishlistItems'));
    }

    /**
     * Add a product to wishlist.
     */
    public function add(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $userId = Auth::id();
        $productId = $request->product_id;

        // Check if already in wishlist
        $existingWishlist = Wishlist::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($existingWishlist) {
            return response()->json([
                'success' => false,
                'message' => 'Product already in wishlist'
            ]);
        }

        // Add to wishlist
        Wishlist::create([
            'user_id' => $userId,
            'product_id' => $productId
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product added to wishlist'
        ]);
    }

    /**
     * Remove a product from wishlist.
     */
    public function remove(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $userId = Auth::id();
        $productId = $request->product_id;

        Wishlist::where('user_id', $userId)
            ->where('product_id', $productId)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product removed from wishlist'
        ]);
    }

    /**
     * Toggle wishlist status for a product.
     */
    public function toggle(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $userId = Auth::id();
        $productId = $request->product_id;

        $existingWishlist = Wishlist::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($existingWishlist) {
            $existingWishlist->delete();
            return response()->json([
                'success' => true,
                'message' => 'Product removed from wishlist',
                'in_wishlist' => false
            ]);
        } else {
            Wishlist::create([
                'user_id' => $userId,
                'product_id' => $productId
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Product added to wishlist',
                'in_wishlist' => true
            ]);
        }
    }

    /**
     * Check if a product is in wishlist.
     */
    public function check(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $userId = Auth::id();
        $productId = $request->product_id;

        $inWishlist = Wishlist::where('user_id', $userId)
            ->where('product_id', $productId)
            ->exists();

        return response()->json([
            'in_wishlist' => $inWishlist
        ]);
    }

    /**
     * Get wishlist count for the user.
     */
    public function count(): JsonResponse
    {
        $count = Auth::user()->wishlists()->count();
        
        return response()->json([
            'count' => $count
        ]);
    }
}