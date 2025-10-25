<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ReviewController extends Controller
{
    /**
     * Get reviews for a product.
     */
    public function index(Request $request, $productId): JsonResponse
    {
        $product = Product::findOrFail($productId);
        
        $reviews = $product->approvedReviews()
            ->with('user')
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'reviews' => $reviews->items(),
            'pagination' => [
                'current_page' => $reviews->currentPage(),
                'last_page' => $reviews->lastPage(),
                'per_page' => $reviews->perPage(),
                'total' => $reviews->total(),
            ],
            'product_stats' => [
                'average_rating' => round($product->average_rating, 1),
                'total_reviews' => $product->reviews_count,
                'rating_distribution' => $product->rating_distribution,
            ]
        ]);
    }

    /**
     * Store a new review.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'comment' => 'nullable|string|max:2000',
        ]);

        $userId = Auth::id();
        $productId = $request->product_id;

        // Check if user has already reviewed this product
        $existingReview = Review::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($existingReview) {
            return response()->json([
                'success' => false,
                'message' => 'You have already reviewed this product'
            ], 422);
        }

        // Check if user has purchased this product (for verified purchase badge)
        $hasPurchased = Auth::user()->orders()
            ->whereHas('items', function($query) use ($productId) {
                $query->where('product_id', $productId);
            })
            ->exists();

        try {
            // Create the review
            $review = Review::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'rating' => $request->rating,
                'title' => $request->title,
                'comment' => $request->comment,
                'is_verified_purchase' => $hasPurchased,
                'is_approved' => true, // Auto-approve for now
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Review submitted successfully',
                'review' => $review->load('user')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating review: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an existing review.
     */
    public function update(Request $request, $reviewId): JsonResponse
    {
        $review = Review::where('id', $reviewId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'comment' => 'nullable|string|max:2000',
        ]);

        $review->update([
            'rating' => $request->rating,
            'title' => $request->title,
            'comment' => $request->comment,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Review updated successfully',
            'review' => $review->load('user')
        ]);
    }

    /**
     * Delete a review.
     */
    public function destroy($reviewId): JsonResponse
    {
        $review = Review::where('id', $reviewId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $review->delete();

        return response()->json([
            'success' => true,
            'message' => 'Review deleted successfully'
        ]);
    }

    /**
     * Toggle helpful vote for a review.
     */
    public function toggleHelpful(Request $request, $reviewId): JsonResponse
    {
        $review = Review::findOrFail($reviewId);
        $userId = Auth::id();

        if ($review->isHelpfulByUser($userId)) {
            $review->removeHelpfulVote($userId);
            $action = 'removed';
        } else {
            $review->addHelpfulVote($userId);
            $action = 'added';
        }

        return response()->json([
            'success' => true,
            'message' => "Helpful vote {$action}",
            'helpful_count' => $review->helpful_votes_count,
            'is_helpful' => $review->isHelpfulByUser($userId)
        ]);
    }

    /**
     * Get user's reviews.
     */
    public function userReviews(): JsonResponse
    {
        $reviews = Auth::user()->reviews()
            ->with('product')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'reviews' => $reviews->items(),
            'pagination' => [
                'current_page' => $reviews->currentPage(),
                'last_page' => $reviews->lastPage(),
                'per_page' => $reviews->perPage(),
                'total' => $reviews->total(),
            ]
        ]);
    }

    /**
     * Check if user can review a product.
     */
    public function canReview(Request $request, $productId): JsonResponse
    {
        $userId = Auth::id();
        $product = Product::findOrFail($productId);

        $hasReviewed = Review::where('user_id', $userId)
            ->where('product_id', $productId)
            ->exists();

        $hasPurchased = Auth::user()->orders()
            ->whereHas('items', function($query) use ($productId) {
                $query->where('product_id', $productId);
            })
            ->exists();

        return response()->json([
            'can_review' => !$hasReviewed,
            'has_reviewed' => $hasReviewed,
            'has_purchased' => $hasPurchased,
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
            ]
        ]);
    }
}