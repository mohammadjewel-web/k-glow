<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Category extends Model
{
     use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'status',
        'nav',
        'featured',
    ];

    // A category can have many subcategories
    public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }

    // A category can have many products
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}