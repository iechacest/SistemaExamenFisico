<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Postulante extends Model
{
    protected $table = 'postulante';
    protected $primaryKey = 'id_postulante';
    public $timestamps = false; // usamos hora_creacion, no created_at

    protected $fillable = [
        'apellido_paterno',
        'apellido_materno',
        'nombres',
        'ci',
        'sexo',
        'fecha_nac',
        'procedencia',
        'residencia',
        'numero_telefono',
        'correo',
        'contacto_emergencia',
        'nombre_contacto_emergencia',
        'seguro',
        'seguro_origen',
        'anio_postulacion',
        'id_registrador',
        'hora_creacion',
        'instituto',
    ];

    public function pruebas()
    {
        return $this->hasMany(Prueba::class, 'id_postulante', 'id_postulante');
    }
    // En el modelo Postulante.php
public function prueba()
{
    return $this->hasOne(Prueba::class, 'id_postulante');
}


    public function ultimaPrueba()
{
    return $this->hasOne(Prueba::class, 'id_postulante')->latestOfMany();
}
public function evaluacion()
{
    return $this->hasOneThrough(
        \App\Models\Evaluacion::class,
        \App\Models\Prueba::class,
        'id_postulante',   // clave en prueba hacia postulante
        'id_prueba',       // clave en evaluacion hacia prueba
        'id_postulante',   // clave local en postulante
        'id_prueba'        // clave local en prueba
    );
}



}
