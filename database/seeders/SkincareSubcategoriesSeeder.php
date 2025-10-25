<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Subcategory;

class SkincareSubcategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get Skincare category (try both variations)
        $skincare = Category::where('name', 'like', '%Skincare%')
            ->orWhere('name', 'like', '%Skin Care%')
            ->first();

        if (!$skincare) {
            echo "Skincare category not found. Creating one...\n";
            $skincare = Category::create([
                'name' => 'Skincare',
                'slug' => 'skincare',
                'description' => 'Complete skincare solutions for all skin types and concerns',
                'status' => 1,
                'is_featured' => 1,
                'order' => 0,
            ]);
        }

        echo "Adding subcategories to: {$skincare->name} (ID: {$skincare->id})\n\n";

        // Skincare subcategories
        $subcategories = [
            // Skin Types
            ['name' => 'Normal Skin', 'slug' => 'normal-skin', 'description' => 'Products for normal skin type', 'sort_order' => 0],
            ['name' => 'Oily Skin', 'slug' => 'oily-skin', 'description' => 'Oil control and mattifying products', 'sort_order' => 1],
            ['name' => 'Dry Skin', 'slug' => 'dry-skin', 'description' => 'Hydrating and moisturizing solutions', 'sort_order' => 2],
            ['name' => 'Combination Skin', 'slug' => 'combination-skin', 'description' => 'Balanced care for mixed skin types', 'sort_order' => 3],
            ['name' => 'Sensitive Skin', 'slug' => 'sensitive-skin', 'description' => 'Gentle and soothing products', 'sort_order' => 4],
            ['name' => 'Damaged Skin', 'slug' => 'damaged-skin', 'description' => 'Repair and recovery treatments', 'sort_order' => 5],
            
            // Skin Concerns
            ['name' => 'Acne/Pimples Treatment', 'slug' => 'acne-treatment', 'description' => 'Clear skin and prevent breakouts', 'sort_order' => 6],
            ['name' => 'Large Pores/Scars Solution', 'slug' => 'pores-scars', 'description' => 'Minimize pores and reduce scars', 'sort_order' => 7],
            ['name' => 'Brightening Solution', 'slug' => 'brightening', 'description' => 'Illuminate and even skin tone', 'sort_order' => 8],
            ['name' => 'Redness/Rashes Solution', 'slug' => 'redness-rashes', 'description' => 'Calm and reduce inflammation', 'sort_order' => 9],
            ['name' => 'Anti-Aging/Wrinkle Care', 'slug' => 'anti-aging', 'description' => 'Reduce fine lines and wrinkles', 'sort_order' => 10],
            ['name' => 'Pigmentation/Melasma Solution', 'slug' => 'pigmentation', 'description' => 'Even out dark spots and discoloration', 'sort_order' => 11],
            ['name' => 'Oil & Sebum Control', 'slug' => 'oil-control', 'description' => 'Balance oil production', 'sort_order' => 12],
            ['name' => 'Spot & Blemish Treatment', 'slug' => 'spot-treatment', 'description' => 'Target specific imperfections', 'sort_order' => 13],
            ['name' => 'Sun Burn & Damage Control', 'slug' => 'sun-damage', 'description' => 'Repair and protect from UV damage', 'sort_order' => 14],
            ['name' => 'Dark Circles Treatment', 'slug' => 'dark-circles', 'description' => 'Brighten and refresh under eyes', 'sort_order' => 15],
            ['name' => 'Dehydration Solution', 'slug' => 'dehydration', 'description' => 'Deep hydration and moisture retention', 'sort_order' => 16],
            ['name' => 'Blackheads & Whiteheads Care', 'slug' => 'blackheads-whiteheads', 'description' => 'Deep cleansing and pore care', 'sort_order' => 17],
        ];

        foreach ($subcategories as $subcat) {
            // Check if already exists
            $exists = Subcategory::where('category_id', $skincare->id)
                ->where('slug', $subcat['slug'])
                ->first();

            if (!$exists) {
                Subcategory::create([
                    'category_id' => $skincare->id,
                    'name' => $subcat['name'],
                    'slug' => $subcat['slug'],
                    'description' => $subcat['description'],
                    'sort_order' => $subcat['sort_order'],
                    'status' => 1,
                    'featured' => 1,
                ]);
                echo "✓ Added: {$subcat['name']}\n";
            } else {
                echo "- Skipped (exists): {$subcat['name']}\n";
            }
        }

        echo "\n✅ Seeding complete!\n";
        echo "Total subcategories in {$skincare->name}: " . Subcategory::where('category_id', $skincare->id)->count() . "\n";
    }
}
