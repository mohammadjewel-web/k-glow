<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Subcategory;

class HairCareSubcategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get Hair Care category
        $haircare = Category::where('name', 'like', '%Hair Care%')
            ->orWhere('name', 'like', '%Haircare%')
            ->first();

        if (!$haircare) {
            echo "Hair Care category not found. Creating one...\n";
            $haircare = Category::create([
                'name' => 'Hair Care',
                'slug' => 'hair-care',
                'description' => 'Complete hair care solutions for healthy, beautiful hair',
                'status' => 1,
                'is_featured' => 1,
                'order' => 1,
            ]);
        }

        echo "Adding subcategories to: {$haircare->name} (ID: {$haircare->id})\n\n";

        // Hair Care subcategories
        $subcategories = [
            // Hair Types
            ['name' => 'Normal Hair', 'slug' => 'normal-hair', 'description' => 'Products for normal hair type', 'sort_order' => 0],
            ['name' => 'Oily Hair', 'slug' => 'oily-hair', 'description' => 'Oil control and balancing products', 'sort_order' => 1],
            ['name' => 'Dry Hair', 'slug' => 'dry-hair', 'description' => 'Deep moisturizing and nourishing solutions', 'sort_order' => 2],
            ['name' => 'Damaged Hair', 'slug' => 'damaged-hair', 'description' => 'Repair and restore damaged hair', 'sort_order' => 3],
            ['name' => 'Color-Treated Hair', 'slug' => 'color-treated-hair', 'description' => 'Protect and maintain hair color', 'sort_order' => 4],
            ['name' => 'Curly Hair', 'slug' => 'curly-hair', 'description' => 'Define and enhance natural curls', 'sort_order' => 5],
            
            // Hair Concerns
            ['name' => 'Hair Loss/Thinning', 'slug' => 'hair-loss', 'description' => 'Strengthen and prevent hair loss', 'sort_order' => 6],
            ['name' => 'Dandruff/Scalp Care', 'slug' => 'dandruff-scalp', 'description' => 'Eliminate dandruff and soothe scalp', 'sort_order' => 7],
            ['name' => 'Hair Growth/Volume', 'slug' => 'hair-growth', 'description' => 'Promote hair growth and add volume', 'sort_order' => 8],
            ['name' => 'Split Ends/Breakage', 'slug' => 'split-ends', 'description' => 'Repair split ends and prevent breakage', 'sort_order' => 9],
            ['name' => 'Frizz Control', 'slug' => 'frizz-control', 'description' => 'Smooth and tame frizzy hair', 'sort_order' => 10],
            ['name' => 'Scalp Treatment', 'slug' => 'scalp-treatment', 'description' => 'Healthy scalp for healthy hair', 'sort_order' => 11],
            
            // Product Types
            ['name' => 'Shampoo', 'slug' => 'shampoo', 'description' => 'Cleansing shampoos for all hair types', 'sort_order' => 12],
            ['name' => 'Conditioner', 'slug' => 'conditioner', 'description' => 'Nourishing and moisturizing conditioners', 'sort_order' => 13],
            ['name' => 'Hair Mask/Treatment', 'slug' => 'hair-mask', 'description' => 'Intensive deep conditioning treatments', 'sort_order' => 14],
            ['name' => 'Hair Oil/Serum', 'slug' => 'hair-oil-serum', 'description' => 'Nourishing oils and serums', 'sort_order' => 15],
            ['name' => 'Hair Styling', 'slug' => 'hair-styling', 'description' => 'Styling products and tools', 'sort_order' => 16],
            ['name' => 'Hair Color/Dye', 'slug' => 'hair-color', 'description' => 'Hair coloring and dyeing products', 'sort_order' => 17],
        ];

        foreach ($subcategories as $subcat) {
            // Check if already exists
            $exists = Subcategory::where('category_id', $haircare->id)
                ->where('slug', $subcat['slug'])
                ->first();

            if (!$exists) {
                Subcategory::create([
                    'category_id' => $haircare->id,
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
        echo "Total subcategories in {$haircare->name}: " . Subcategory::where('category_id', $haircare->id)->count() . "\n";
    }
}
