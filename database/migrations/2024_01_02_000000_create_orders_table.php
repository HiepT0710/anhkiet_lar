<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('code')->unique();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email')->nullable();
            $table->string('customer_address');
            $table->text('note')->nullable();
            $table->unsignedBigInteger('total_amount');
            $table->string('payment_method')->default('vnpay');
            $table->string('payment_status')->default('pending'); // pending | paid | failed | cancelled
            $table->string('status')->default('pending'); // pending | processing | completed | cancelled
            $table->string('vnp_transaction_no')->nullable();
            $table->string('vnp_response_code')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};


