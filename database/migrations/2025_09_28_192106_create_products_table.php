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
        Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->foreignId('category_id')->constrained()->onDelete('cascade');
                $table->foreignId('subcategory_id')->nullable()->constrained()->onDelete('set null');
                $table->foreignId('brand_id')->nullable()->constrained()->onDelete('set null');
                $table->text('description')->nullable();
                $table->text('short_description')->nullable();
                $table->decimal('price', 10, 2);
                $table->decimal('discount_price', 10, 2)->nullable();
                $table->string('sku')->nullable();
                $table->string('barcode')->nullable();
                $table->integer('stock')->default(0);
                $table->decimal('weight', 8, 2)->nullable();
                $table->string('dimensions')->nullable();
                $table->text('colors')->nullable()->change();
                $table->json('sizes')->nullable();
                $table->string('images')->nullable();
                $table->string('material')->nullable();
                $table->string('tags')->nullable();
                $table->string('meta_title')->nullable();
                $table->text('meta_description')->nullable();
                $table->string('thumbnail')->nullable();
                $table->boolean('status')->default(true);
                $table->boolean('is_featured')->default(false);
                $table->boolean('is_new')->default(false);
                $table->boolean('is_best_seller')->default(false);
                $table->boolean('is_flash_sale')->default(false);
                $table->decimal('flash_sale_price', 10, 2)->nullable();
                $table->timestamp('flash_sale_start')->nullable();
                $table->timestamp('flash_sale_end')->nullable();
                $table->integer('views_count')->default(0);
                $table->integer('sold_count')->default(0);
                $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};