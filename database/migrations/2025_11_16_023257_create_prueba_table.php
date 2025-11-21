<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prueba', function (Blueprint $table) {
            $table->increments('id_prueba'); // PK int autoincrement

            $table->text('observacion')->nullable();

            // nota_total de 0 a 999 (3 dígitos)
            $table->unsignedSmallInteger('nota_total')->nullable();

            // conclusión corta: "APROBADO", "REPROBADO", etc.
            $table->string('conclusion', 10)->nullable();

            // FK al postulante
            $table->unsignedInteger('id_postulante');

            $table->foreign('id_postulante')
                  ->references('id_postulante')
                  ->on('postulante')
                  ->onDelete('cascade'); // si borras postulante, se borran sus pruebas
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prueba');
    }
};
