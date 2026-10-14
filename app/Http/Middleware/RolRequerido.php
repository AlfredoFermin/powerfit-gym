<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Control de acceso por rol (RF-12 / RNF-03).
 * Uso en rutas: RolRequerido::class . ':Administrador,Recepcionista'
 */
class RolRequerido
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $usuario = $request->user();

        if (! $usuario || ! $usuario->tieneRol(...$roles)) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        return $next($request);
    }
}
