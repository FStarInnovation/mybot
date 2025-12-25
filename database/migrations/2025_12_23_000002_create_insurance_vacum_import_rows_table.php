<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('insurance_vacum_import_rows', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->uuid('batch_id');
            $table->string('source');
            $table->date('source_date');

            $table->unsignedInteger('row_index');
            $table->jsonb('data');
            $table->char('row_hash', 64);

            $table->timestampTz('imported_at')->useCurrent();
            $table->text('error')->nullable();

            $table->index(['batch_id'], 'insurance_vacum_import_batch_idx');
            $table->index(['source', 'source_date'], 'insurance_vacum_import_source_date_idx');
            $table->unique(['batch_id', 'row_index'], 'insurance_vacum_import_batch_row_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('insurance_vacum_import_rows');
    }
};
