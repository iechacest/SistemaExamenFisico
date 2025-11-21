<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('inicio'); // tu inicio.blade.php
    }

    public function login(Request $request)
    {
        // 1. Validar
        $request->validate([
            'usuario'   => 'required|string',
            'password'  => 'required|string',
        ]);

        // 2. Buscar usuario
        $usuario = Usuario::where('usuario', $request->usuario)->first();

        if (!$usuario || !Hash::check($request->password, $usuario->password)) {
            return back()->with('error', 'Usuario o contraseña incorrectos.')
                        ->withInput();
        }

        // 3. Guardar datos básicos en sesión
        session([
            'id_usu' => $usuario->id_usu,
            'nombre' => $usuario->nombres,
            'cargo'  => $usuario->cargo,
        ]);

        // 4. Redirigir según cargo
        if ($usuario->cargo == 0) {
            return redirect()->route('dash.admin');
        } else {
            return redirect()->route('dash.registrador');
        }
    }

    public function logout()
{
    session()->flush();
    return redirect('/inicio')->with('success', 'Sesión cerrada correctamente.');
}

}
