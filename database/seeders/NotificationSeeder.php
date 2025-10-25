<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some users and orders for sample notifications
        $users = User::take(5)->get();
        $orders = Order::take(3)->get();
        $products = Product::take(3)->get();

        if ($users->isEmpty()) {
            $this->command->info('No users found. Please run UserSeeder first.');
            return;
        }

        $notifications = [
            // Welcome notifications
            [
                'type' => 'welcome',
                'title' => 'Welcome to K-GLOW!',
                'message' => 'Welcome to K-GLOW! Start exploring our amazing products and enjoy your shopping experience.',
                'data' => [
                    'welcome_bonus' => 'Get 10% off your first order with code WELCOME10',
                    'explore_url' => route('shop'),
                ],
                'is_important' => true,
            ],
            
            // Order notifications
            [
                'type' => 'order_confirmed',
                'title' => 'Order Confirmed',
                'message' => 'Your order #ORD-001 has been confirmed and is being processed.',
                'data' => [
                    'order_number' => 'ORD-001',
                    'total_amount' => 150.00,
                    'status' => 'confirmed',
                ],
                'is_important' => true,
            ],
            
            [
                'type' => 'order_shipped',
                'title' => 'Order Shipped',
                'message' => 'Your order #ORD-002 has been shipped and is on its way to you.',
                'data' => [
                    'order_number' => 'ORD-002',
                    'tracking_number' => 'TRK123456789',
                    'estimated_delivery' => Carbon::now()->addDays(3)->format('Y-m-d'),
                ],
                'is_important' => true,
            ],
            
            [
                'type' => 'order_delivered',
                'title' => 'Order Delivered',
                'message' => 'Your order #ORD-003 has been delivered successfully.',
                'data' => [
                    'order_number' => 'ORD-003',
                    'delivery_date' => Carbon::now()->subDays(1)->format('Y-m-d'),
                ],
                'is_important' => true,
            ],
            
            // Product notifications
            [
                'type' => 'product_back_in_stock',
                'title' => 'Product Back in Stock',
                'message' => 'The product "Premium Wireless Headphones" is now back in stock!',
                'data' => [
                    'product_name' => 'Premium Wireless Headphones',
                    'product_url' => route('product.details', 'premium-wireless-headphones'),
                    'stock_quantity' => 25,
                ],
                'is_important' => false,
            ],
            
            [
                'type' => 'price_drop',
                'title' => 'Price Drop Alert',
                'message' => 'The price of "Smart Fitness Tracker" has dropped by 15%! New price: ৳2,550',
                'data' => [
                    'product_name' => 'Smart Fitness Tracker',
                    'product_url' => route('product.details', 'smart-fitness-tracker'),
                    'old_price' => 3000,
                    'new_price' => 2550,
                    'discount_percentage' => 15,
                ],
                'is_important' => false,
            ],
            
            // Payment notifications
            [
                'type' => 'payment_confirmed',
                'title' => 'Payment Confirmed',
                'message' => 'Payment for order #ORD-004 has been confirmed.',
                'data' => [
                    'order_number' => 'ORD-004',
                    'payment_method' => 'Credit Card',
                    'amount_paid' => 299.99,
                ],
                'is_important' => true,
            ],
            
            // System notifications
            [
                'type' => 'maintenance',
                'title' => 'Scheduled Maintenance',
                'message' => 'We will be performing scheduled maintenance on Sunday, 2:00 AM - 4:00 AM. Some features may be temporarily unavailable.',
                'data' => [
                    'start_time' => '2024-01-14 02:00:00',
                    'end_time' => '2024-01-14 04:00:00',
                    'maintenance_message' => 'Scheduled maintenance on Sunday, 2:00 AM - 4:00 AM',
                ],
                'is_important' => true,
            ],
            
            [
                'type' => 'promotional',
                'title' => 'Special Offer - 20% Off!',
                'message' => 'Get 20% off on all electronics this weekend! Use code ELECTRONICS20 at checkout.',
                'data' => [
                    'promotion_title' => 'Special Offer - 20% Off!',
                    'promotion_message' => 'Get 20% off on all electronics this weekend!',
                    'coupon_code' => 'ELECTRONICS20',
                ],
                'is_important' => false,
            ],
        ];

        // Create notifications for each user
        foreach ($users as $user) {
            foreach ($notifications as $index => $notificationData) {
                $isRead = $index % 3 === 0; // Make some notifications read
                $createdAt = Carbon::now()->subDays(rand(1, 30))->subHours(rand(0, 23));
                
                Notification::create([
                    'user_id' => $user->id,
                    'type' => $notificationData['type'],
                    'title' => $notificationData['title'],
                    'message' => $notificationData['message'],
                    'data' => $notificationData['data'],
                    'channel' => 'in_app',
                    'is_read' => $isRead,
                    'read_at' => $isRead ? $createdAt->addMinutes(rand(5, 60)) : null,
                    'is_important' => $notificationData['is_important'],
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            }
        }

        $this->command->info('Sample notifications created successfully!');
    }
}
