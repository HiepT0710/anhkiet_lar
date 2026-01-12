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
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name')->nullable(); // Tên người đánh giá (nếu không đăng nhập)
            $table->string('email')->nullable(); // Email người đánh giá
            $table->integer('rating')->default(5); // Điểm đánh giá từ 1-5
            $table->text('comment')->nullable(); // Nội dung đánh giá
            $table->boolean('is_approved')->default(false); // Đã được duyệt chưa
            $table->timestamps();
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
