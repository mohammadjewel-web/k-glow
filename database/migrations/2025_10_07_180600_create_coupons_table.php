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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Coupon code (e.g., SAVE20, WELCOME10)
            $table->string('name'); // Display name (e.g., "20% Off Summer Sale")
            $table->text('description')->nullable(); // Description of the coupon
            $table->enum('type', ['percentage', 'fixed']); // Discount type
            $table->decimal('value', 10, 2); // Discount value (percentage or fixed amount)
            $table->decimal('minimum_amount', 10, 2)->nullable(); // Minimum order amount
            $table->decimal('maximum_discount', 10, 2)->nullable(); // Maximum discount amount
            $table->integer('usage_limit')->nullable(); // Total usage limit
            $table->integer('used_count')->default(0); // How many times used
            $table->integer('usage_limit_per_user')->nullable(); // Usage limit per user
            $table->json('applicable_products')->nullable(); // Specific products (null = all products)
            $table->json('applicable_categories')->nullable(); // Specific categories
            $table->json('applicable_brands')->nullable(); // Specific brands
            $table->json('excluded_products')->nullable(); // Excluded products
            $table->dateTime('starts_at')->nullable(); // Start date
            $table->dateTime('expires_at')->nullable(); // Expiry date
            $table->boolean('is_active')->default(true); // Active status
            $table->boolean('is_public')->default(true); // Public visibility
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
