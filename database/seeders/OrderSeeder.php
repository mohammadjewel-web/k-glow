<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Product;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get a regular user (not admin)
        $user = User::whereDoesntHave('roles', function($query) {
            $query->where('name', 'admin');
        })->first();

        if (!$user) {
            $this->command->info('No regular user found. Please create a user first.');
            return;
        }

        // Get some products
        $products = Product::take(5)->get();
        
        if ($products->isEmpty()) {
            $this->command->info('No products found. Please create some products first.');
            return;
        }

        // Create sample orders
        $orders = [
            [
                'status' => 'delivered',
                'subtotal' => 89.97,
                'tax_amount' => 8.00,
                'shipping_amount' => 5.99,
                'total_amount' => 103.96,
                'shipping_address' => '123 Main St, City, State 12345',
                'billing_address' => '123 Main St, City, State 12345',
                'phone' => $user->phone ?? '123-456-7890',
                'created_at' => now()->subDays(15),
                'delivered_at' => now()->subDays(5),
            ],
            [
                'status' => 'shipped',
                'subtotal' => 45.50,
                'tax_amount' => 4.00,
                'shipping_amount' => 5.99,
                'total_amount' => 55.49,
                'shipping_address' => '456 Oak Ave, City, State 12345',
                'billing_address' => '456 Oak Ave, City, State 12345',
                'phone' => $user->phone ?? '123-456-7890',
                'created_at' => now()->subDays(8),
                'shipped_at' => now()->subDays(3),
            ],
            [
                'status' => 'processing',
                'subtotal' => 67.99,
                'tax_amount' => 6.00,
                'shipping_amount' => 5.99,
                'total_amount' => 79.98,
                'shipping_address' => '789 Pine St, City, State 12345',
                'billing_address' => '789 Pine St, City, State 12345',
                'phone' => $user->phone ?? '123-456-7890',
                'created_at' => now()->subDays(3),
            ],
            [
                'status' => 'pending',
                'subtotal' => 23.99,
                'tax_amount' => 2.00,
                'shipping_amount' => 5.99,
                'total_amount' => 31.98,
                'shipping_address' => '321 Elm St, City, State 12345',
                'billing_address' => '321 Elm St, City, State 12345',
                'phone' => $user->phone ?? '123-456-7890',
                'created_at' => now()->subDays(1),
            ],
            [
                'status' => 'cancelled',
                'subtotal' => 34.99,
                'tax_amount' => 3.00,
                'shipping_amount' => 5.99,
                'total_amount' => 43.98,
                'shipping_address' => '654 Maple Dr, City, State 12345',
                'billing_address' => '654 Maple Dr, City, State 12345',
                'phone' => $user->phone ?? '123-456-7890',
                'created_at' => now()->subDays(10),
                'notes' => 'Cancelled by customer',
            ],
        ];

        foreach ($orders as $orderData) {
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => $user->id,
                'status' => $orderData['status'],
                'subtotal' => $orderData['subtotal'],
                'tax_amount' => $orderData['tax_amount'],
                'shipping_amount' => $orderData['shipping_amount'],
                'total_amount' => $orderData['total_amount'],
                'shipping_address' => $orderData['shipping_address'],
                'billing_address' => $orderData['billing_address'],
                'phone' => $orderData['phone'],
                'notes' => $orderData['notes'] ?? null,
                'shipped_at' => $orderData['shipped_at'] ?? null,
                'delivered_at' => $orderData['delivered_at'] ?? null,
                'created_at' => $orderData['created_at'],
                'updated_at' => $orderData['created_at'],
            ]);

            // Add 1-3 random products to each order
            $productCount = min(rand(1, 3), $products->count());
            $orderProducts = $products->random($productCount);
            foreach ($orderProducts as $product) {
                $quantity = rand(1, 3);
                $price = $product->discount_price ?? $product->price;
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'price' => $price,
                    'quantity' => $quantity,
                    'total' => $price * $quantity,
                    'product_options' => [
                        'size' => 'M',
                        'color' => 'Black'
                    ],
                ]);
            }
        }

        $this->command->info('Sample orders created successfully!');
    }
}
