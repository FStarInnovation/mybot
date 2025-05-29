<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('raw_products', function (Blueprint $table) {
            $table->bigIncrements('id');

            // core timestamps
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();

            // flexible raw data and metadata
            $table->json('data')->nullable();
            $table->json('metadata')->nullable();

            // basic extracted attributes (optional)
            $table->string('gtin')->nullable();
            $table->string('hashtag')->nullable();
            $table->string('price')->nullable();
            $table->decimal('price_num', 10, 2)->nullable();
            $table->string('timestamp')->nullable();
            $table->string('title')->nullable();
            $table->text('url')->nullable();

            // pipeline control fields
            $table->timestamp('processed_at')->nullable();
            $table->string('status')->default('pending');
        });

        // Add vector column (pgvector must be enabled in the database)
        DB::statement('ALTER TABLE raw_products ADD COLUMN embedding vector(768) NULL');
        DB::statement('CREATE INDEX raw_products_embedding_ivfflat ON raw_products USING ivfflat (embedding) WITH (lists = 100)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raw_products');
    }
};
