<?php

namespace App\Http\Middleware;

use App\Models\Event;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckEventStatus
{
    /**
     * Revisa si el evento está en la etapa correcta (ej. 'SchedulingActive').
     */
    public function handle(Request $request, Closure $next, string $status): Response
    {
        // Obtiene el 'event' de la ruta (funciona por el Model Binding)
        $event = $request->route('event');

        // Si el estado del evento NO es el que requerimos
        if ($event->status !== $status) {
            // Redirige al panel con un error
            return redirect()->route('dashboard')
                ->with('error', 'El agendamiento para este evento no está activo.');
        }

        // Si el estado es correcto, continúa
        return $next($request);
    }
}
