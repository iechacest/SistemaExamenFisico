<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuario';      // porque no usamos "usuarios"
    protected $primaryKey = 'id_usu';  // PK personalizada

protected $fillable = [
    'usuario',
    'nombres',
    'apellido_pat',
    'apellido_mat',
    'password',
    'cargo'
];

}
