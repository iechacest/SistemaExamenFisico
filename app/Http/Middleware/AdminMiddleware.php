<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
public function handle(Request $request, Closure $next): Response
    {
        // Verificar que haya sesión y que el cargo sea 1 (registrador)
        if (!session()->has('id_usu') || session('cargo') !== 0) {
            return redirect('/inicio')
                ->with('error', 'No tienes permiso para acceder al panel de administrador.');
        }
        return $next($request);
    }

}
