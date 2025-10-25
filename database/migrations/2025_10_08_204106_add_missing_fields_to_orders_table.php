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
        Schema::table('orders', function (Blueprint $table) {
            // Add missing fields for payment management
            $table->string('order_status')->default('pending')->after('status');
            $table->string('customer_name')->nullable()->after('user_id');
            $table->string('customer_email')->nullable()->after('customer_name');
            $table->string('customer_phone')->nullable()->after('customer_email');
            $table->string('city')->nullable()->after('billing_address');
            $table->string('state')->nullable()->after('city');
            $table->string('postal_code')->nullable()->after('state');
            $table->string('transaction_id')->nullable()->after('payment_status');
            $table->string('payment_reference')->nullable()->after('transaction_id');
            $table->timestamp('payment_date')->nullable()->after('payment_reference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'order_status',
                'customer_name',
                'customer_email',
                'customer_phone',
                'city',
                'state',
                'postal_code',
                'transaction_id',
                'payment_reference',
                'payment_date'
            ]);
        });
    }
};