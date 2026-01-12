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
        Schema::table('products', function (Blueprint $table) {
            $table->timestamp('sale_starts_at')->nullable()->after('sale_price');
            $table->timestamp('sale_ends_at')->nullable()->after('sale_starts_at');
            $table->boolean('is_flash_sale')->default(false)->after('featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['sale_starts_at', 'sale_ends_at', 'is_flash_sale']);
        });
    }
};

