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
        Schema::create('farmacias', function (Blueprint $table) {
            $table->id();
            $table->string('establecimiento_id')->unique();
            $table->string('establecimiento_nombre');
            $table->string('localidad_id')->nullable();
            $table->string('localidad_nombre')->nullable();
            $table->string('provincia_id')->nullable();
            $table->string('provincia_nombre')->nullable();
            $table->string('departamento_id')->nullable();
            $table->string('departamento_nombre')->nullable();
            $table->string('codloc')->nullable();
            $table->string('codent')->nullable();
            $table->string('origen_financiamiento')->nullable();
            $table->string('tipologia_id')->nullable();
            $table->string('tipologia_sigla')->nullable();
            $table->string('tipologia_nombre')->nullable();
            $table->string('cp', 10)->nullable();
            $table->string('domicilio')->nullable();
            $table->string('sitio_web')->nullable();
            $table->timestamps();

            $table->index(['provincia_id', 'localidad_id']);
            $table->index(['localidad_nombre']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farmacias');
    }
};
