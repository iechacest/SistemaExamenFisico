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
        Schema::create('usuario', function (Blueprint $table) {
    $table->increments('id_usu');
    $table->string('nombres', 100);
    $table->string('apellido_pat', 100);
    $table->string('apellido_mat', 100)->nullable();

    $table->string('usuario', 50)->unique();  // login
    $table->string('password');               // contraseña encriptada

    $table->tinyInteger('cargo');             // 0 = admin, 1 = registrador
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario');
    }
};
