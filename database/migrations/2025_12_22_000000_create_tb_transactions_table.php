<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('gateway')->nullable();
            $table->string('transaction_date')->nullable();
            $table->string('account_number')->nullable();
            $table->string('sub_account')->nullable();
            $table->unsignedBigInteger('amount_in')->default(0);
            $table->unsignedBigInteger('amount_out')->default(0);
            $table->unsignedBigInteger('accumulated')->default(0);
            $table->string('code')->nullable();
            $table->text('transaction_content')->nullable();
            $table->string('reference_number')->nullable();
            $table->text('body')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_transactions');
    }
};


