<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prueba extends Model
{
    protected $table = 'prueba';
    protected $primaryKey = 'id_prueba';
    public $timestamps = true;

    protected $fillable = [
        'observacion',
        'nota_total',
        'conclusion',
        'id_postulante',
    ];

    public function postulante()
    {
        return $this->belongsTo(Postulante::class, 'id_postulante', 'id_postulante');
    }

    public function evaluacion()
    {
        return $this->hasOne(Evaluacion::class, 'id_prueba', 'id_prueba');
    }
    

}
