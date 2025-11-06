<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventDirectoryController extends Controller
{
    /**
     * Muestra el directorio de participantes para un evento.
     * (Oferentes ven Demandantes, Demandantes ven Oferentes)
     */
    public function index(Event $event)
    {
        // 1. Obtener al usuario actual
        $user = Auth::user();

        // 2. Encontrar la inscripción del usuario actual para ESTE evento
        $currentUserRegistration = $event->registrations()
                                        ->where('user_id', $user->id)
                                        ->first();

        // Si por alguna razón no está inscrito, no puede ver el directorio
        if (!$currentUserRegistration) {
            abort(403, 'No estás inscrito en este evento.');
        }

        // 3. Determinar qué rol buscar
        $roleToFind = ($currentUserRegistration->role === 'supplier') ? 'buyer' : 'supplier';
        $directoryTitle = ($roleToFind === 'supplier') ? 'Lista de Oferentes' : 'Lista de Demandantes';

        // 4. Buscar todas las inscripciones (con el perfil) que coincidan con el rol opuesto
        $participants = $event->registrations()
                            ->where('role', $roleToFind)
                            ->with('participant.participantProfile') // Carga el User y su Perfil
                            ->get();

        // 5. Pasar los datos a la vista
        return view('participant.event-directory', [
            'event' => $event,
            'participants' => $participants,
            'directoryTitle' => $directoryTitle,
        ]);
    }
}
