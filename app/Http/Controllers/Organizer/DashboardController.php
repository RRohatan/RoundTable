<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Muestra el panel principal del organizador.
     */
    public function index()
    {
        // 1. Obtenemos al organizador autenticado
        $organizer = Auth::user();

        // 2. Buscamos sus eventos creados
        $events = $organizer->events()
                           ->orderBy('date', 'desc') // Ordenar por fecha
                           ->get();

        // 3. Pasamos los eventos a la vista
        return view('organizer.dashboard', [
            'events' => $events
        ]);
    }
}
