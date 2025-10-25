<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'logo',
        'website',
        'is_active',
        'nav',
        'featured',
        'meta_title',
        'meta_description',
    ];

    // Brand can have many products
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Accessor for is_active (in case we need to maintain compatibility)
    public function getIsActiveAttribute()
    {
        return $this->attributes['is_active'] ?? $this->attributes['status'] ?? true;
    }
}