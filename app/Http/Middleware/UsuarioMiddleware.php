<?php

namespace App\Http\Middleware;

use Closure;

class UsuarioMiddleware
{
    public function handle($request, Closure $next)
    {
        if (!session()->has('id_usu')) {
            return redirect('/inicio')->with('error', 'Debes iniciar sesión.');
        }

        // cargo = 0 admin, 1 registrador
        $cargo = session('cargo');

        if (!in_array($cargo, [0, 1])) {
            return redirect('/inicio')->with('error', 'Acceso no autorizado.');
        }

        return $next($request);
    }
}
