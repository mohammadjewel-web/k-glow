<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'subcategory_id',
        'brand_id',
        'description',
        'short_description',
        'price',
        'discount_price',
        'sku',
        'barcode',
        'stock',
        'weight',
        'dimensions',
        'colors',
        'sizes',
        'images',
        'material',
        'tags',
        'meta_title',
        'meta_description',
        'thumbnail',
        'status',
        'is_featured',
        'is_new',
        'is_best_seller',
        'is_flash_sale',
        'flash_sale_price',
        'flash_sale_start',
        'flash_sale_end',
        'views_count',
        'sold_count',
    ];

    protected $casts = [
        'sizes' => 'array',
        'flash_sale_start' => 'datetime',
        'flash_sale_end' => 'datetime',
        'status' => 'boolean',
        'is_featured' => 'boolean',
        'is_new' => 'boolean',
        'is_best_seller' => 'boolean',
        'is_flash_sale' => 'boolean',
    ];

    // Relationships
    public function category()
{
    return $this->belongsTo(Category::class);
}

public function subcategory()
{
    return $this->belongsTo(Subcategory::class);
}

public function brand()
{
    return $this->belongsTo(Brand::class);
}

// For multiple images
public function images()
{
    return $this->hasMany(ProductImage::class);
}

/**
 * Get the reviews for the product.
 */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function inventory()
    {
        return $this->hasOne(Inventory::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

/**
 * Get the approved reviews for the product.
 */
public function approvedReviews()
{
    return $this->hasMany(Review::class)->approved();
}

/**
 * Get the average rating for the product.
 */
public function getAverageRatingAttribute()
{
    return $this->approvedReviews()->avg('rating') ?? 0;
}

/**
 * Get the total reviews count for the product.
 */
public function getReviewsCountAttribute()
{
    return $this->approvedReviews()->count();
}

/**
 * Get the rating distribution for the product.
 */
public function getRatingDistributionAttribute()
{
    $distribution = [];
    for ($i = 1; $i <= 5; $i++) {
        $distribution[$i] = $this->approvedReviews()->where('rating', $i)->count();
    }
    return $distribution;
}
}