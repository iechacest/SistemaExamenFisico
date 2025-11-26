<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluacion extends Model
{
    protected $table = 'evaluacion';
    protected $primaryKey = 'id_eva';
    public $timestamps = false;

    protected $fillable = [
        'velocidad',
        'nota_velocidad',
        'prueba_resis',
        'nota_prueba',
        'barra',
        'nota_barra',
        'cap_abdominal',
        'nota_cap',
        'flexiones',
        'nota_flexiones',
        'natacion',
        'nota_natacion',
        'id_prueba',
    ];


// En el modelo Prueba.php
public function evaluacion()
{
    return $this->hasOne(Evaluacion::class, 'id_prueba');
}



}
