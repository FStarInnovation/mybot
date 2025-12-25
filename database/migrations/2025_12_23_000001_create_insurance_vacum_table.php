<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('insurance_vacum', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('source');
            $table->date('source_date');

            $table->text('droga')->nullable();
            $table->text('marca')->nullable();
            $table->text('presentacion')->nullable();
            $table->text('laboratorio')->nullable();

            $table->decimal('cobertura_pct', 6, 2)->nullable();
            $table->decimal('copago', 14, 2)->nullable();

            $table->timestamps();

            $table->unique([
                'source',
                'source_date',
                'droga',
                'marca',
                'presentacion',
                'laboratorio',
            ], 'insurance_vacum_unique_row');

            $table->index(['source', 'source_date'], 'insurance_vacum_source_date_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('insurance_vacum');
    }
};
