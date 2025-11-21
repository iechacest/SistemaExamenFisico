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
        Schema::create('postulante', function (Blueprint $table) {
            $table->increments('id_postulante'); // PK int autoincrement

            $table->string('apellido_paterno', 100);
            $table->string('apellido_materno', 100)->nullable();
            $table->string('nombres', 150);

            // CI como int (como tú pediste)
            $table->integer('ci');

            // 0 = hombre, 1 = mujer
            $table->tinyInteger('sexo');

            $table->date('fecha_nac');

            $table->string('procedencia', 150)->nullable();
            $table->string('residencia', 150)->nullable();

            $table->string('numero_telefono', 30)->nullable();
            $table->string('correo', 150)->nullable();

            $table->string('contacto_emergencia', 30)->nullable();         // tel del contacto
            $table->string('nombre_contacto_emergencia', 150)->nullable(); // nombre del contacto

            $table->string('seguro', 100)->nullable();        // nombre/ tipo de seguro
            $table->string('seguro_origen', 150)->nullable(); // de dónde es el seguro

            $table->integer('anio_postulacion'); // ej: 2025

            // Registrador que lo creó (relación con usuario.id_usu)
            $table->unsignedInteger('id_registrador');

            // Hora/fecha de creación del registro
            $table->dateTime('hora_creacion')->useCurrent();

            // 1 = COLMILAV, 2 = POLMILAE, 3 = EMMFAB
            $table->tinyInteger('instituto');

            // Clave foránea a usuario
            $table->foreign('id_registrador')
                  ->references('id_usu')
                  ->on('usuario');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('postulante');
    }
};
