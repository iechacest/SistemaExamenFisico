<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
// ⬇️ TE FALTA ESTA LÍNEA
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('usuario')->insert([
            [
                'nombres'       => 'Admin',
                'apellido_pat'  => 'Principal',
                'apellido_mat'  => 'Sistema',
                'usuario'       => 'admin',
                'password'      => Hash::make('admin123'), // contraseña: admin123
                'cargo'         => 0, // administrador
            ],
            [
                'nombres'       => 'Registro',
                'apellido_pat'  => 'Cooper',
                'apellido_mat'  => 'Prueba',
                'usuario'       => 'registrador',
                'password'      => Hash::make('reg123'),   // contraseña: reg123
                'cargo'         => 1, // registrador
            ],
        ]);
    }
}
