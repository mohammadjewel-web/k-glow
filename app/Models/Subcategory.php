<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
   use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'description',
        'image',
        'status',
        'nav',
        'featured',
        'sort_order',
    ];

    // Link back to parent category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Subcategory can have many products
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}