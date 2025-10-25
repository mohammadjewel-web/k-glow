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
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('inventory_id')->constrained('inventory')->onDelete('cascade');
            $table->enum('type', ['in', 'out', 'adjustment', 'transfer', 'return', 'damage', 'expired']); // Movement type
            $table->integer('quantity'); // Positive for 'in', negative for 'out'
            $table->integer('previous_stock'); // Stock before movement
            $table->integer('new_stock'); // Stock after movement
            $table->string('reference_type')->nullable(); // order, purchase, adjustment, etc.
            $table->unsignedBigInteger('reference_id')->nullable(); // ID of the reference record
            $table->string('reason')->nullable(); // Reason for movement
            $table->text('notes')->nullable(); // Additional notes
            $table->decimal('unit_cost', 10, 2)->nullable(); // Cost per unit for this movement
            $table->decimal('total_cost', 10, 2)->nullable(); // Total cost of movement
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Who made the change
            $table->timestamp('movement_date')->useCurrent(); // When the movement occurred
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['product_id', 'type']);
            $table->index(['reference_type', 'reference_id']);
            $table->index(['movement_date']);
            $table->index(['user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
