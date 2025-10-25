<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Subcategory;

class NavigationSeeder extends Seeder
{
    public function run()
    {
        // Set some categories as navigation items
        $navCategories = ['Skincare', 'Makeup', 'Hair Care', 'Body Care'];
        foreach ($navCategories as $categoryName) {
            Category::where('name', $categoryName)->update([
                'nav' => true,
                'featured' => true
            ]);
        }
        
        // Set some brands as navigation items
        $navBrands = ['Beauty of Joseon', 'COSRX', 'TONYMOLY', 'The Ordinary', 'CeraVe'];
        foreach ($navBrands as $brandName) {
            Brand::where('name', $brandName)->update([
                'nav' => true,
                'featured' => true
            ]);
        }
        
        // Set some subcategories as navigation items
        $navSubcategories = [
            'Moisturizers' => 'Skincare',
            'Serums' => 'Skincare',
            'Cleansers' => 'Skincare',
            'Foundation' => 'Makeup',
            'Lipstick' => 'Makeup',
            'Shampoo' => 'Hair Care',
            'Conditioner' => 'Hair Care'
        ];
        
        foreach ($navSubcategories as $subName => $parentName) {
            $category = Category::where('name', $parentName)->first();
            if ($category) {
                Subcategory::where('name', $subName)
                    ->where('category_id', $category->id)
                    ->update([
                        'nav' => true,
                        'featured' => true
                    ]);
            }
        }
        
        $this->command->info('Navigation items set successfully!');
    }
}
