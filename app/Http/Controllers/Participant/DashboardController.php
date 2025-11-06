<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Muestra el panel principal del participante.
     */
    public function index()
    {
        // 1. Obtenemos al usuario autenticado
        $user = Auth::user();

        // 2. Buscamos sus inscripciones
        // Usamos 'with('event')' para cargar también los datos del evento
        // y evitar consultas N+1 (es más eficiente).
        $registrations = $user->registrations()->with('event')->get();

        // 3. Pasamos las inscripciones a la vista
        return view('participant.dashboard', [
            'registrations' => $registrations
        ]);
    }
}
