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
        Schema::create('coupon_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('cascade');
            $table->decimal('discount_amount', 10, 2); // Actual discount applied
            $table->decimal('order_amount', 10, 2); // Order amount when coupon was used
            $table->string('ip_address')->nullable(); // Track by IP for guest users
            $table->timestamps();
            
            // Index for performance
            $table->index(['coupon_id', 'user_id']);
            $table->index(['coupon_id', 'ip_address']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupon_usages');
    }
};
