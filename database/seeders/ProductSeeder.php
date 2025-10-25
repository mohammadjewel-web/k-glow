<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create categories
        $skincareCategory = Category::firstOrCreate(
            ['name' => 'Skin Care'],
            [
                'slug' => 'skin-care',
                'description' => 'Korean skincare products',
                'status' => 1,
                'is_featured' => 1
            ]
        );

        $makeupCategory = Category::firstOrCreate(
            ['name' => 'Make Up'],
            [
                'slug' => 'make-up',
                'description' => 'Korean makeup products',
                'status' => 1,
                'is_featured' => 1
            ]
        );

        // Get or create brands
        $beautyOfJoseon = Brand::firstOrCreate(
            ['name' => 'Beauty of Joseon'],
            ['slug' => 'beauty-of-joseon', 'status' => 1]
        );

        $cosrx = Brand::firstOrCreate(
            ['name' => 'COSRX'],
            ['slug' => 'cosrx', 'status' => 1]
        );

        $tonymoly = Brand::firstOrCreate(
            ['name' => 'TONYMOLY'],
            ['slug' => 'tonymoly', 'status' => 1]
        );

        $axisY = Brand::firstOrCreate(
            ['name' => 'AXIS-Y'],
            ['slug' => 'axis-y', 'status' => 1]
        );

        // Sample products
        $products = [
            [
                'name' => 'Beauty of Joseon Rice + Probiotics Sunscreen - 50ml',
                'slug' => 'beauty-of-joseon-rice-probiotics-sunscreen',
                'sku' => 'BOJ-SPF-001',
                'description' => 'A lightweight, non-greasy sunscreen with rice and probiotics for daily protection.',
                'price' => 25.99,
                'discount_price' => 22.99,
                'category_id' => $skincareCategory->id,
                'brand_id' => $beautyOfJoseon->id,
                'thumbnail' => 'beauty-of-joseon-sunscreen.jpg',
                'is_featured' => 1,
                'is_new' => 0,
                'status' => 1,
                'stock' => 50
            ],
            [
                'name' => 'COSRX Advanced Snail 96 Mucin Power Essence - 100ml',
                'slug' => 'cosrx-advanced-snail-96-mucin-power-essence',
                'sku' => 'COSRX-SNAIL-001',
                'description' => 'Hydrating essence with 96% snail secretion filtrate for smooth, glowing skin.',
                'price' => 18.99,
                'discount_price' => null,
                'category_id' => $skincareCategory->id,
                'brand_id' => $cosrx->id,
                'thumbnail' => 'cosrx-snail-essence.jpg',
                'is_featured' => 1,
                'is_new' => 1,
                'status' => 1,
                'stock' => 30
            ],
            [
                'name' => 'TONYMOLY Red Retinol Radiance Whip Cleanser - 150ml',
                'slug' => 'tonymoly-red-retinol-radiance-whip-cleanser',
                'sku' => 'TONYMOLY-RETINOL-001',
                'description' => 'Gentle foaming cleanser with retinol for radiant, youthful skin.',
                'price' => 15.99,
                'discount_price' => 12.99,
                'category_id' => $skincareCategory->id,
                'brand_id' => $tonymoly->id,
                'thumbnail' => 'tonymoly-retinol-cleanser.jpg',
                'is_featured' => 0,
                'is_new' => 1,
                'status' => 1,
                'stock' => 25
            ],
            [
                'name' => 'AXIS-Y Dark Spot Correcting Glow Serum - 50ml',
                'slug' => 'axis-y-dark-spot-correcting-glow-serum',
                'sku' => 'AXISY-SERUM-001',
                'description' => 'Brightening serum that targets dark spots and evens skin tone.',
                'price' => 28.99,
                'discount_price' => null,
                'category_id' => $skincareCategory->id,
                'brand_id' => $axisY->id,
                'thumbnail' => 'axis-y-serum.jpg',
                'is_featured' => 1,
                'is_new' => 0,
                'status' => 1,
                'stock' => 40
            ],
            [
                'name' => 'COSRX Low pH Good Morning Gel Cleanser - 150ml',
                'slug' => 'cosrx-low-ph-good-morning-gel-cleanser',
                'sku' => 'COSRX-CLEANSER-001',
                'description' => 'Gentle morning cleanser with low pH to maintain skin balance.',
                'price' => 12.99,
                'discount_price' => null,
                'category_id' => $skincareCategory->id,
                'brand_id' => $cosrx->id,
                'thumbnail' => 'cosrx-cleanser.jpg',
                'is_featured' => 0,
                'is_new' => 1,
                'status' => 1,
                'stock' => 35
            ],
            [
                'name' => 'Beauty of Joseon Glow Deep Serum - 30ml',
                'slug' => 'beauty-of-joseon-glow-deep-serum',
                'sku' => 'BOJ-GLOW-001',
                'description' => 'Intensive glow serum with rice and niacinamide for radiant skin.',
                'price' => 22.99,
                'discount_price' => 19.99,
                'category_id' => $skincareCategory->id,
                'brand_id' => $beautyOfJoseon->id,
                'thumbnail' => 'beauty-of-joseon-serum.jpg',
                'is_featured' => 1,
                'is_new' => 1,
                'status' => 1,
                'stock' => 20
            ],
            [
                'name' => 'TONYMOLY Wonder Ceramide Mochi Toner - 500ml',
                'slug' => 'tonymoly-wonder-ceramide-mochi-toner',
                'sku' => 'TONYMOLY-TONER-001',
                'description' => 'Hydrating toner with ceramides for soft, supple skin.',
                'price' => 16.99,
                'discount_price' => null,
                'category_id' => $skincareCategory->id,
                'brand_id' => $tonymoly->id,
                'thumbnail' => 'tonymoly-toner.jpg',
                'is_featured' => 0,
                'is_new' => 0,
                'status' => 1,
                'stock' => 45
            ],
            [
                'name' => 'AXIS-Y Heartleaf My Type Calming Cream - 75ml',
                'slug' => 'axis-y-heartleaf-my-type-calming-cream',
                'sku' => 'AXISY-CREAM-001',
                'description' => 'Soothing cream with heartleaf extract for sensitive skin.',
                'price' => 24.99,
                'discount_price' => 21.99,
                'category_id' => $skincareCategory->id,
                'brand_id' => $axisY->id,
                'thumbnail' => 'axis-y-cream.jpg',
                'is_featured' => 1,
                'is_new' => 0,
                'status' => 1,
                'stock' => 15
            ]
        ];

        foreach ($products as $productData) {
            Product::updateOrCreate(
                ['sku' => $productData['sku']],
                $productData
            );
        }

        $this->command->info('Sample products created successfully!');
    }
}
