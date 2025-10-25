<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Str;

class CategoryBrandSeeder extends Seeder
{
    public function run()
    {
        // Create Categories
        $categories = [
            [
                'name' => 'Skincare',
                'slug' => 'skincare',
                'description' => 'Complete skincare routine products for all skin types',
                'status' => 1,
            ],
            [
                'name' => 'Makeup',
                'slug' => 'makeup',
                'description' => 'Professional makeup products for every occasion',
                'status' => 1,
            ],
            [
                'name' => 'Hair Care',
                'slug' => 'hair-care',
                'description' => 'Hair care products for healthy and beautiful hair',
                'status' => 1,
            ],
            [
                'name' => 'Body Care',
                'slug' => 'body-care',
                'description' => 'Body care and hygiene products',
                'status' => 1,
            ],
            [
                'name' => 'Fragrance',
                'slug' => 'fragrance',
                'description' => 'Perfumes and body sprays for men and women',
                'status' => 1,
            ],
            [
                'name' => 'Men\'s Grooming',
                'slug' => 'mens-grooming',
                'description' => 'Grooming products specifically for men',
                'status' => 1,
            ],
            [
                'name' => 'Tools & Accessories',
                'slug' => 'tools-accessories',
                'description' => 'Beauty tools, brushes, and accessories',
                'status' => 1,
            ],
            [
                'name' => 'Natural & Organic',
                'slug' => 'natural-organic',
                'description' => 'Natural and organic beauty products',
                'status' => 1,
            ],
            [
                'name' => 'Anti-Aging',
                'slug' => 'anti-aging',
                'description' => 'Anti-aging skincare and treatments',
                'status' => 1,
            ],
            [
                'name' => 'Sunscreen & Protection',
                'slug' => 'sunscreen-protection',
                'description' => 'Sun protection and UV care products',
                'status' => 1,
            ],
        ];

        foreach ($categories as $categoryData) {
            Category::updateOrCreate(
                ['slug' => $categoryData['slug']],
                $categoryData
            );
        }

        // Create Brands
        $brands = [
            [
                'name' => 'Beauty of Joseon',
                'slug' => 'beauty-of-joseon',
                'status' => 1,
            ],
            [
                'name' => 'COSRX',
                'slug' => 'cosrx',
                'status' => 1,
            ],
            [
                'name' => 'TONYMOLY',
                'slug' => 'tonymoly',
                'status' => 1,
            ],
            [
                'name' => 'AXIS-Y',
                'slug' => 'axis-y',
                'status' => 1,
            ],
            [
                'name' => 'The Ordinary',
                'slug' => 'the-ordinary',
                'status' => 1,
            ],
            [
                'name' => 'CeraVe',
                'slug' => 'cerave',
                'status' => 1,
            ],
            [
                'name' => 'Neutrogena',
                'slug' => 'neutrogena',
                'status' => 1,
            ],
            [
                'name' => 'Olay',
                'slug' => 'olay',
                'status' => 1,
            ],
            [
                'name' => 'L\'Oréal Paris',
                'slug' => 'loreal-paris',
                'status' => 1,
            ],
            [
                'name' => 'Maybelline',
                'slug' => 'maybelline',
                'status' => 1,
            ],
            [
                'name' => 'Revlon',
                'slug' => 'revlon',
                'status' => 1,
            ],
            [
                'name' => 'MAC',
                'slug' => 'mac',
                'status' => 1,
            ],
            [
                'name' => 'NARS',
                'slug' => 'nars',
                'status' => 1,
            ],
            [
                'name' => 'Fenty Beauty',
                'slug' => 'fenty-beauty',
                'status' => 1,
            ],
            [
                'name' => 'Glossier',
                'slug' => 'glossier',
                'status' => 1,
            ],
        ];

        foreach ($brands as $brandData) {
            Brand::updateOrCreate(
                ['slug' => $brandData['slug']],
                $brandData
            );
        }

        $this->command->info('Categories and Brands seeded successfully!');
    }
}
