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

    public function ultimaPrueba()
{
    return $this->hasOne(Prueba::class, 'id_postulante')->latestOfMany();
}

}
