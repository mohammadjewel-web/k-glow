<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'rating',
        'title',
        'comment',
        'is_verified_purchase',
        'is_approved',
        'is_featured',
        'helpful_votes'
    ];

    protected $casts = [
        'is_verified_purchase' => 'boolean',
        'is_approved' => 'boolean',
        'is_featured' => 'boolean',
        'helpful_votes' => 'array',
    ];

    /**
     * Get the user that wrote the review.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the product that was reviewed.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Scope for approved reviews only.
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope for featured reviews.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for verified purchases.
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified_purchase', true);
    }

    /**
     * Get the helpful votes count.
     */
    public function getHelpfulVotesCountAttribute()
    {
        return is_array($this->helpful_votes) ? count($this->helpful_votes) : 0;
    }

    /**
     * Check if a user has voted this review as helpful.
     */
    public function isHelpfulByUser($userId)
    {
        return is_array($this->helpful_votes) && in_array($userId, $this->helpful_votes);
    }

    /**
     * Add a helpful vote from a user.
     */
    public function addHelpfulVote($userId)
    {
        $votes = is_array($this->helpful_votes) ? $this->helpful_votes : [];
        if (!in_array($userId, $votes)) {
            $votes[] = $userId;
            $this->helpful_votes = $votes;
            $this->save();
        }
    }

    /**
     * Remove a helpful vote from a user.
     */
    public function removeHelpfulVote($userId)
    {
        $votes = is_array($this->helpful_votes) ? $this->helpful_votes : [];
        $votes = array_filter($votes, function($id) use ($userId) {
            return $id != $userId;
        });
        $this->helpful_votes = array_values($votes);
        $this->save();
    }

    /**
     * Get the rating as stars.
     */
    public function getStarsAttribute()
    {
        return str_repeat('★', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }

    /**
     * Get the rating percentage.
     */
    public function getRatingPercentageAttribute()
    {
        return ($this->rating / 5) * 100;
    }
}