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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // notification type (order, payment, shipping, etc.)
            $table->string('title'); // notification title
            $table->text('message'); // notification message
            $table->json('data')->nullable(); // additional data (order details, etc.)
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // target user
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('cascade'); // related order
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade'); // related product
            $table->enum('channel', ['email', 'sms', 'push', 'in_app'])->default('in_app'); // notification channel
            $table->boolean('is_read')->default(false); // read status
            $table->timestamp('read_at')->nullable(); // when it was read
            $table->boolean('is_sent')->default(false); // if notification was sent
            $table->timestamp('sent_at')->nullable(); // when it was sent
            $table->boolean('is_important')->default(false); // priority notification
            $table->timestamp('expires_at')->nullable(); // notification expiry
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['user_id', 'is_read']);
            $table->index(['type', 'is_sent']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
