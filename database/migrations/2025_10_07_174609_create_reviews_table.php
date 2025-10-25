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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('rating')->unsigned(); // 1-5 stars
            $table->string('title')->nullable(); // Review title
            $table->text('comment')->nullable(); // Review comment
            $table->boolean('is_verified_purchase')->default(false); // Verified purchase badge
            $table->boolean('is_approved')->default(true); // Admin approval
            $table->boolean('is_featured')->default(false); // Featured review
            $table->json('helpful_votes')->nullable(); // Store user IDs who found helpful
            $table->timestamps();
            
            // Ensure a user can only review a product once
            $table->unique(['user_id', 'product_id']);
            
            // Index for performance
            $table->index(['product_id', 'is_approved']);
            $table->index(['user_id']);
            $table->index(['rating']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};