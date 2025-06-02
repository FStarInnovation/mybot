<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Enable pgvector extension if not exists
        DB::statement('CREATE EXTENSION IF NOT EXISTS vector');
        
        Schema::create('memories', function (Blueprint $table) {
            $table->id();
            $table->text('content');
            $table->vector('embedding', 1536); // Assuming Jina Embeddings dimension
            $table->jsonb('metadata')->nullable();
            $table->string('session_id')->nullable()->index();
            $table->timestamps();
            
            // Index for similarity search
            $table->rawIndex(
                'embedding vector_cosine_ops',
                'memories_embedding_idx'
            )->algorithm('ivfflat');
        });
    }

    public function down()
    {
        Schema::dropIfExists('memories');
    }
};
