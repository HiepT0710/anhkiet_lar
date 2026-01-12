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
            // Tăng kích thước của subtotal_amount và discount_amount từ decimal(10,2) lên decimal(15,2)
            // để có thể lưu giá trị lớn hơn (tối đa 999,999,999,999,999.99)
            $table->decimal('subtotal_amount', 15, 2)->nullable()->change();
            $table->decimal('discount_amount', 15, 2)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Khôi phục lại kích thước cũ
            $table->decimal('subtotal_amount', 10, 2)->nullable()->change();
            $table->decimal('discount_amount', 10, 2)->default(0)->change();
        });
    }
};
