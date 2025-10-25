<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('current_stock')->default(0);
            $table->integer('reserved_stock')->default(0); // Stock reserved for pending orders
            $table->integer('available_stock')->default(0); // current_stock - reserved_stock
            $table->integer('minimum_stock')->default(0); // Low stock threshold
            $table->integer('maximum_stock')->nullable(); // Maximum stock level
            $table->decimal('cost_price', 10, 2)->nullable(); // Cost per unit
            $table->decimal('selling_price', 10, 2)->nullable(); // Selling price per unit
            $table->string('sku')->nullable(); // Product SKU
            $table->string('barcode')->nullable(); // Product barcode
            $table->string('location')->nullable(); // Warehouse location
            $table->text('notes')->nullable(); // Additional notes
            $table->boolean('track_stock')->default(true); // Whether to track stock for this product
            $table->boolean('allow_backorder')->default(false); // Allow orders when out of stock
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_restocked_at')->nullable();
            $table->timestamp('last_sold_at')->nullable();
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['product_id', 'is_active']);
            $table->index(['current_stock', 'minimum_stock']);
            $table->index(['sku']);
            $table->index(['barcode']);
            $table->unique('product_id'); // One inventory record per product
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory');
    }
};
