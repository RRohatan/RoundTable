<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
   public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. Verifica si el usuario está logueado
        // 2. Verifica si el rol del usuario es el que se requiere
        if (!Auth::check() || Auth::user()->role !== $role) {

            // Si no es, lo redirige al 'dashboard' (la ruta por defecto de Breeze)
            // Breeze se encargará de enviarlo al login si no está autenticado.
            return redirect('dashboard');
        }

        // Si el rol es correcto, deja que continúe
        return $next($request);
    }
}
