<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('search_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('keyword');
            $table->unsignedInteger('search_count')->default(1);
            $table->timestamp('last_searched_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'keyword']);
            $table->index('last_searched_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('search_histories');
    }
};


