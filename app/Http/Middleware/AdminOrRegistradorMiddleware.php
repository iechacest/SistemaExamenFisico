<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminOrRegistradorMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Debe estar logueado
        if (!session()->has('id_usu')) {
            return redirect('/inicio')
                ->with('error', 'Debes iniciar sesión.');
        }

        // cargo: 0 = admin, 1 = registrador
        $cargo = session('cargo');

        if ($cargo === 0 || $cargo === 1) {
            return $next($request);
        }

        return redirect('/inicio')
            ->with('error', 'No tienes permiso para acceder a esta sección.');
    }
}
