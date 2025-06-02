<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Drop the index first
        DB::statement('DROP INDEX IF EXISTS memories_embedding_idx');
        
        // Change the vector dimension to 768
        DB::statement('ALTER TABLE memories ALTER COLUMN embedding TYPE vector(768)');
        
        // Recreate the index with the new dimension
        DB::statement('CREATE INDEX memories_embedding_idx ON memories USING ivfflat (embedding vector_cosine_ops) WITH (lists = 100)');
    }

    public function down()
    {
        // Drop the index
        DB::statement('DROP INDEX IF EXISTS memories_embedding_idx');
        
        // Revert back to 1536 dimension
        DB::statement('ALTER TABLE memories ALTER COLUMN embedding TYPE vector(1536)');
        
        // Recreate the index with original dimension
        DB::statement('CREATE INDEX memories_embedding_idx ON memories USING ivfflat (embedding vector_cosine_ops) WITH (lists = 100)');
    }
};
