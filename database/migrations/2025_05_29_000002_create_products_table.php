<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('brand')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('original_price', 10, 2)->nullable();
            $table->decimal('discount', 5, 2)->nullable();
            $table->string('pharmacy')->nullable();
            $table->string('url')->unique();
            $table->text('description')->nullable();
            $table->json('embedding')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
