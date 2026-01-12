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
        Schema::create('auto_replies', function (Blueprint $table) {
            $table->id();
            $table->string('keywords'); // Từ khóa kích hoạt (phân tách bởi dấu phẩy)
            $table->text('response'); // Nội dung trả lời tự động
            $table->integer('priority')->default(0); // Độ ưu tiên (cao hơn sẽ được chọn trước)
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auto_replies');
    }
};

