<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Support\Str;

class SubcategorySeeder extends Seeder
{
    public function run()
    {
        // Get categories
        $skincare = Category::where('name', 'Skincare')->first();
        $makeup = Category::where('name', 'Makeup')->first();
        $hairCare = Category::where('name', 'Hair Care')->first();
        $bodyCare = Category::where('name', 'Body Care')->first();

        // Skincare subcategories
        if ($skincare) {
            $skincareSubcategories = [
                ['name' => 'Moisturizers', 'description' => 'Hydrating creams and lotions'],
                ['name' => 'Serums', 'description' => 'Concentrated treatment products'],
                ['name' => 'Cleansers', 'description' => 'Face wash and cleansing products'],
                ['name' => 'Toners', 'description' => 'Skin balancing and refreshing products'],
                ['name' => 'Sunscreen', 'description' => 'UV protection products'],
                ['name' => 'Essences', 'description' => 'Lightweight hydrating products'],
                ['name' => 'Masks', 'description' => 'Treatment and pampering masks'],
                ['name' => 'Eye Care', 'description' => 'Eye creams and treatments'],
            ];

            foreach ($skincareSubcategories as $sub) {
                Subcategory::updateOrCreate(
                    [
                        'name' => $sub['name'],
                        'category_id' => $skincare->id
                    ],
                    [
                        'slug' => Str::slug($sub['name']),
                        'description' => $sub['description'],
                        'status' => 1,
                        'nav' => 1,
                        'featured' => 1
                    ]
                );
            }
        }

        // Makeup subcategories
        if ($makeup) {
            $makeupSubcategories = [
                ['name' => 'Foundation', 'description' => 'Base makeup products'],
                ['name' => 'Lipstick', 'description' => 'Lip color products'],
                ['name' => 'Eyeshadow', 'description' => 'Eye color products'],
                ['name' => 'Mascara', 'description' => 'Eyelash enhancement'],
                ['name' => 'Blush', 'description' => 'Cheek color products'],
                ['name' => 'Concealer', 'description' => 'Coverage products'],
                ['name' => 'Primer', 'description' => 'Makeup base products'],
                ['name' => 'Setting Spray', 'description' => 'Makeup finishing products'],
            ];

            foreach ($makeupSubcategories as $sub) {
                Subcategory::updateOrCreate(
                    [
                        'name' => $sub['name'],
                        'category_id' => $makeup->id
                    ],
                    [
                        'slug' => Str::slug($sub['name']),
                        'description' => $sub['description'],
                        'status' => 1,
                        'nav' => 1,
                        'featured' => 1
                    ]
                );
            }
        }

        // Hair Care subcategories
        if ($hairCare) {
            $hairSubcategories = [
                ['name' => 'Shampoo', 'description' => 'Hair cleansing products'],
                ['name' => 'Conditioner', 'description' => 'Hair conditioning products'],
                ['name' => 'Hair Masks', 'description' => 'Deep treatment products'],
                ['name' => 'Hair Oil', 'description' => 'Nourishing hair oils'],
                ['name' => 'Hair Serum', 'description' => 'Styling and treatment serums'],
                ['name' => 'Dry Shampoo', 'description' => 'Quick hair refresh products'],
                ['name' => 'Hair Styling', 'description' => 'Styling and finishing products'],
            ];

            foreach ($hairSubcategories as $sub) {
                Subcategory::updateOrCreate(
                    [
                        'name' => $sub['name'],
                        'category_id' => $hairCare->id
                    ],
                    [
                        'slug' => Str::slug($sub['name']),
                        'description' => $sub['description'],
                        'status' => 1,
                        'nav' => 1,
                        'featured' => 1
                    ]
                );
            }
        }

        // Body Care subcategories
        if ($bodyCare) {
            $bodySubcategories = [
                ['name' => 'Body Lotion', 'description' => 'Body moisturizing products'],
                ['name' => 'Body Wash', 'description' => 'Body cleansing products'],
                ['name' => 'Body Scrub', 'description' => 'Exfoliating products'],
                ['name' => 'Hand Cream', 'description' => 'Hand care products'],
                ['name' => 'Foot Care', 'description' => 'Foot treatment products'],
                ['name' => 'Body Oil', 'description' => 'Nourishing body oils'],
            ];

            foreach ($bodySubcategories as $sub) {
                Subcategory::updateOrCreate(
                    [
                        'name' => $sub['name'],
                        'category_id' => $bodyCare->id
                    ],
                    [
                        'slug' => Str::slug($sub['name']),
                        'description' => $sub['description'],
                        'status' => 1,
                        'nav' => 1,
                        'featured' => 1
                    ]
                );
            }
        }

        $this->command->info('Subcategories seeded successfully!');
    }
}
