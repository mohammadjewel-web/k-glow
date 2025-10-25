<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'value',
        'minimum_amount',
        'maximum_discount',
        'usage_limit',
        'used_count',
        'usage_limit_per_user',
        'applicable_products',
        'applicable_categories',
        'applicable_brands',
        'excluded_products',
        'starts_at',
        'expires_at',
        'is_active',
        'is_public',
    ];

    protected $casts = [
        'applicable_products' => 'array',
        'applicable_categories' => 'array',
        'applicable_brands' => 'array',
        'excluded_products' => 'array',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'is_public' => 'boolean',
        'value' => 'decimal:2',
        'minimum_amount' => 'decimal:2',
        'maximum_discount' => 'decimal:2',
    ];

    // Relationships
    public function usages()
    {
        return $this->hasMany(CouponUsage::class);
    }

    public function orders()
    {
        return $this->hasManyThrough(Order::class, CouponUsage::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeValid($query)
    {
        $now = Carbon::now();
        return $query->where('is_active', true)
                    ->where(function($q) use ($now) {
                        $q->whereNull('starts_at')
                          ->orWhere('starts_at', '<=', $now);
                    })
                    ->where(function($q) use ($now) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>=', $now);
                    });
    }

    public function scopeAvailable($query)
    {
        return $query->valid()->where(function($q) {
            $q->whereNull('usage_limit')
              ->orWhereRaw('used_count < usage_limit');
        });
    }

    // Methods
    public function isExpired()
    {
        if (!$this->expires_at) {
            return false;
        }
        return Carbon::now()->isAfter($this->expires_at);
    }

    public function isNotStarted()
    {
        if (!$this->starts_at) {
            return false;
        }
        return Carbon::now()->isBefore($this->starts_at);
    }

    public function isUsageLimitReached()
    {
        if (!$this->usage_limit) {
            return false;
        }
        return $this->used_count >= $this->usage_limit;
    }

    public function canBeUsedByUser($userId = null)
    {
        if (!$this->is_active || $this->isExpired() || $this->isNotStarted() || $this->isUsageLimitReached()) {
            return false;
        }

        if ($userId && $this->usage_limit_per_user) {
            $userUsageCount = $this->usages()->where('user_id', $userId)->count();
            if ($userUsageCount >= $this->usage_limit_per_user) {
                return false;
            }
        }

        return true;
    }

    public function calculateDiscount($orderAmount, $cartItems = [])
    {
        if (!$this->canBeUsedByUser()) {
            return 0;
        }

        // Check minimum amount
        if ($this->minimum_amount && $orderAmount < $this->minimum_amount) {
            return 0;
        }

        // Check product restrictions
        if (!$this->isApplicableToCart($cartItems)) {
            return 0;
        }

        $discount = 0;

        if ($this->type === 'percentage') {
            $discount = ($orderAmount * $this->value) / 100;
        } else {
            $discount = $this->value;
        }

        // Apply maximum discount limit
        if ($this->maximum_discount && $discount > $this->maximum_discount) {
            $discount = $this->maximum_discount;
        }

        // Don't exceed order amount
        return min($discount, $orderAmount);
    }

    public function isApplicableToCart($cartItems)
    {
        // If no restrictions, applicable to all
        if (!$this->applicable_products && !$this->applicable_categories && !$this->applicable_brands) {
            return true;
        }

        foreach ($cartItems as $item) {
            $product = $item['product'] ?? null;
            if (!$product) continue;

            // Check excluded products
            if ($this->excluded_products && in_array($product->id, $this->excluded_products)) {
                return false;
            }

            // Check applicable products
            if ($this->applicable_products && in_array($product->id, $this->applicable_products)) {
                return true;
            }

            // Check applicable categories
            if ($this->applicable_categories && in_array($product->category_id, $this->applicable_categories)) {
                return true;
            }

            // Check applicable brands
            if ($this->applicable_brands && in_array($product->brand_id, $this->applicable_brands)) {
                return true;
            }
        }

        // If we have restrictions but no items match, not applicable
        return !$this->applicable_products && !$this->applicable_categories && !$this->applicable_brands;
    }

    public function incrementUsage($userId = null, $orderId = null, $discountAmount = 0, $orderAmount = 0, $ipAddress = null)
    {
        $this->increment('used_count');

        // Record usage
        CouponUsage::create([
            'coupon_id' => $this->id,
            'user_id' => $userId,
            'order_id' => $orderId,
            'discount_amount' => $discountAmount,
            'order_amount' => $orderAmount,
            'ip_address' => $ipAddress,
        ]);
    }
}
