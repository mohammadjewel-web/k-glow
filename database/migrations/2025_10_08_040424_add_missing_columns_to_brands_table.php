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
        Schema::table('brands', function (Blueprint $table) {
            $table->text('description')->nullable()->after('slug');
            $table->string('website')->nullable()->after('description');
            $table->boolean('is_active')->default(true)->after('status');
            $table->string('meta_title')->nullable()->after('featured');
            $table->text('meta_description')->nullable()->after('meta_title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->dropColumn(['description', 'website', 'is_active', 'meta_title', 'meta_description']);
        });
    }
};
