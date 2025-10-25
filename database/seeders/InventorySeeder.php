<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\StockMovement;
use Carbon\Carbon;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();

        if ($products->isEmpty()) {
            $this->command->info('No products found. Please run ProductSeeder first.');
            return;
        }

        foreach ($products as $product) {
            // Skip if inventory already exists
            if ($product->inventory) {
                continue;
            }

            // Create inventory record
            $inventory = Inventory::createForProduct($product, [
                'current_stock' => rand(0, 100),
                'minimum_stock' => rand(5, 20),
                'maximum_stock' => rand(50, 200),
                'cost_price' => $product->price * 0.6, // 60% of selling price
                'selling_price' => $product->price,
                'sku' => 'SKU-' . str_pad($product->id, 6, '0', STR_PAD_LEFT),
                'barcode' => 'BC' . str_pad($product->id, 8, '0', STR_PAD_LEFT),
                'location' => 'Warehouse A',
                'track_stock' => true,
                'allow_backorder' => false,
            ]);

            // Create initial stock movement if stock > 0
            if ($inventory->current_stock > 0) {
                StockMovement::create([
                    'product_id' => $product->id,
                    'inventory_id' => $inventory->id,
                    'type' => 'in',
                    'quantity' => $inventory->current_stock,
                    'previous_stock' => 0,
                    'new_stock' => $inventory->current_stock,
                    'reference_type' => 'initial_stock',
                    'reason' => 'Initial stock setup',
                    'unit_cost' => $inventory->cost_price,
                    'total_cost' => $inventory->cost_price * $inventory->current_stock,
                    'user_id' => 1, // Admin user
                    'movement_date' => Carbon::now()->subDays(rand(1, 30)),
                ]);
            }

            // Create some random stock movements for variety
            $this->createRandomMovements($inventory);
        }

        $this->command->info('Inventory records created successfully!');
    }

    private function createRandomMovements(Inventory $inventory)
    {
        $movementCount = rand(2, 8);
        
        for ($i = 0; $i < $movementCount; $i++) {
            $types = ['in', 'out', 'adjustment'];
            $type = $types[array_rand($types)];
            $quantity = rand(1, 20);
            
            if ($type === 'out') {
                $quantity = -$quantity;
            }
            
            $previousStock = $inventory->current_stock;
            $newStock = max(0, $previousStock + $quantity);
            
            // Update inventory
            $inventory->update([
                'current_stock' => $newStock,
                'available_stock' => $newStock - $inventory->reserved_stock,
                'last_sold_at' => $type === 'out' ? Carbon::now()->subDays(rand(1, 30)) : $inventory->last_sold_at,
                'last_restocked_at' => $type === 'in' ? Carbon::now()->subDays(rand(1, 30)) : $inventory->last_restocked_at,
            ]);
            
            // Create movement record
            StockMovement::create([
                'product_id' => $inventory->product_id,
                'inventory_id' => $inventory->id,
                'type' => $type,
                'quantity' => $quantity,
                'previous_stock' => $previousStock,
                'new_stock' => $newStock,
                'reference_type' => 'random_movement',
                'reason' => $this->getRandomReason($type),
                'unit_cost' => $inventory->cost_price,
                'total_cost' => $inventory->cost_price * abs($quantity),
                'user_id' => 1,
                'movement_date' => Carbon::now()->subDays(rand(1, 30))->subHours(rand(0, 23)),
            ]);
        }
    }

    private function getRandomReason(string $type): string
    {
        $reasons = [
            'in' => ['Stock received', 'Purchase order', 'Return from customer', 'Inventory adjustment'],
            'out' => ['Sale', 'Damaged goods', 'Return to supplier', 'Inventory adjustment'],
            'adjustment' => ['Stock count correction', 'Damaged goods write-off', 'Inventory audit', 'System adjustment']
        ];

        $typeReasons = $reasons[$type] ?? ['Manual adjustment'];
        return $typeReasons[array_rand($typeReasons)];
    }
}
