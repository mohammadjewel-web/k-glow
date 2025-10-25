<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Coupon;
use Carbon\Carbon;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coupons = [
            [
                'code' => 'WELCOME10',
                'name' => 'Welcome Discount',
                'description' => 'Get 10% off on your first order',
                'type' => 'percentage',
                'value' => 10.00,
                'minimum_amount' => 100.00,
                'maximum_discount' => 50.00,
                'usage_limit' => 100,
                'usage_limit_per_user' => 1,
                'starts_at' => Carbon::now(),
                'expires_at' => Carbon::now()->addMonths(3),
                'is_active' => true,
                'is_public' => true,
            ],
            [
                'code' => 'SAVE20',
                'name' => 'Summer Sale',
                'description' => '20% off on all summer products',
                'type' => 'percentage',
                'value' => 20.00,
                'minimum_amount' => 200.00,
                'maximum_discount' => 100.00,
                'usage_limit' => 50,
                'usage_limit_per_user' => 2,
                'starts_at' => Carbon::now(),
                'expires_at' => Carbon::now()->addMonths(1),
                'is_active' => true,
                'is_public' => true,
            ],
            [
                'code' => 'FIXED50',
                'name' => 'Fixed Discount',
                'description' => 'Get ৳50 off on orders above ৳300',
                'type' => 'fixed',
                'value' => 50.00,
                'minimum_amount' => 300.00,
                'usage_limit' => 200,
                'usage_limit_per_user' => 3,
                'starts_at' => Carbon::now(),
                'expires_at' => Carbon::now()->addMonths(2),
                'is_active' => true,
                'is_public' => true,
            ],
            [
                'code' => 'VIP100',
                'name' => 'VIP Customer Discount',
                'description' => 'Special discount for VIP customers',
                'type' => 'fixed',
                'value' => 100.00,
                'minimum_amount' => 500.00,
                'usage_limit' => 25,
                'usage_limit_per_user' => 1,
                'starts_at' => Carbon::now(),
                'expires_at' => Carbon::now()->addMonths(6),
                'is_active' => true,
                'is_public' => false, // Private coupon
            ],
            [
                'code' => 'FLASH30',
                'name' => 'Flash Sale',
                'description' => '30% off for limited time',
                'type' => 'percentage',
                'value' => 30.00,
                'minimum_amount' => 150.00,
                'maximum_discount' => 200.00,
                'usage_limit' => 75,
                'usage_limit_per_user' => 1,
                'starts_at' => Carbon::now(),
                'expires_at' => Carbon::now()->addDays(7), // 7 days only
                'is_active' => true,
                'is_public' => true,
            ],
        ];

        foreach ($coupons as $couponData) {
            Coupon::create($couponData);
        }
    }
}
