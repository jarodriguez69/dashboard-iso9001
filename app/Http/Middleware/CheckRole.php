<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    // Usamos ...$roles para que acepte múltiples roles separados por coma
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Verificamos si el rol del usuario está dentro de los roles permitidos
        if (auth()->check() && in_array(auth()->user()->rol, $roles)) {
            return $next($request);
        }

        abort(403, 'Acceso denegado. No tienes permisos para ver esta pantalla.');
    }
}
