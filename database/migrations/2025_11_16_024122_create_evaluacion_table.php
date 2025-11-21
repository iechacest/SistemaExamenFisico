<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluacion', function (Blueprint $table) {
            $table->increments('id_eva'); // PK

            // --- Velocidad ---
            // Tiempo: lo guardamos como TIME (ej: 00:14:00)
            $table->time('velocidad')->nullable();
            $table->unsignedTinyInteger('nota_velocidad')->nullable();

            // --- Prueba de resistencia ---
            $table->time('prueba_resis')->nullable();
            $table->unsignedTinyInteger('nota_prueba')->nullable();

            // --- Barra ---
            $table->time('barra')->nullable();
            $table->unsignedTinyInteger('nota_barra')->nullable();

            // --- Capacidad abdominal ---
            $table->time('cap_abdominal')->nullable();
            $table->unsignedTinyInteger('nota_cap')->nullable();

            // --- Flexiones ---
            $table->time('flexiones')->nullable();
            $table->unsignedTinyInteger('nota_flexiones')->nullable();

            // --- Natación ---
            $table->time('natacion')->nullable();
            $table->unsignedTinyInteger('nota_natacion')->nullable();

            // Relación 1:1 (o 1:N) con prueba
            $table->unsignedInteger('id_prueba');

            $table->foreign('id_prueba')
                  ->references('id_prueba')
                  ->on('prueba')
                  ->onDelete('cascade');

            // Si quieres asegurar 1 evaluacion por prueba:
            // $table->unique('id_prueba');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluacion');
    }
};
