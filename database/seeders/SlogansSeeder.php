<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Slogan;

class SlogansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $slogans = [
            ['text' => 'Natural Beauty, Touch Of Science', 'order' => 0, 'is_active' => true],
            ['text' => 'Discover the best K-Beauty products curated just for you!', 'order' => 1, 'is_active' => true],
            ['text' => 'Top skincare, makeup & beauty tools at your fingertips!', 'order' => 2, 'is_active' => true],
            ['text' => 'Special offers every week — Don\'t miss out!', 'order' => 3, 'is_active' => true],
            ['text' => 'Your favorite K-Beauty brands, all in one place!', 'order' => 4, 'is_active' => true],
        ];

        foreach ($slogans as $slogan) {
            Slogan::create($slogan);
        }
    }
}
