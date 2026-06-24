<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Si el usuario está logueado y su rol coincide con el que pedimos, lo dejamos pasar
        if (auth()->check() && auth()->user()->rol === $role) {
            return $next($request);
        }

        // Si no cumple, lo expulsamos con una pantalla de "Acceso Denegado"
        abort(403, 'Acceso denegado. No tienes permisos de Administrador para ver esta pantalla.');
    }
}
