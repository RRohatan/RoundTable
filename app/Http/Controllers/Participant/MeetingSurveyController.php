<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Models\MeetingSurvey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeetingSurveyController extends Controller
{
    /**
     * Muestra el formulario de la encuesta post-reunión.
     */
    public function show(Meeting $meeting)
    {
        // 1. (Seguridad) Verificar que el usuario sea parte de la reunión
        $this->authorizeMeetingAccess($meeting);

        // 2. (Lógica) Verificar que la reunión esté 'completada'
        if ($meeting->status !== 'completed') {
            return redirect()->route('participant.meetings.myAgenda')->with('error', 'La reunión aún no ha finalizado.');
        }

        // 3. (Lógica) Verificar si ya llenó esta encuesta
        $existingSurvey = MeetingSurvey::where('meeting_id', $meeting->id)
                                        ->where('user_id', Auth::id())
                                        ->exists();

        if ($existingSurvey) {
            return redirect()->route('participant.meetings.myAgenda')->with('success', 'Ya has completado la encuesta para esta reunión.');
        }

        // 4. Mostrar la vista
        return view('participant.meetings.survey', [
            'meeting' => $meeting,
        ]);
    }

    /**
     * Almacena la respuesta de la encuesta.
     */
    public function store(Request $request, Meeting $meeting)
    {
        // 1. (Seguridad) Verificar que el usuario sea parte de la reunión
        $this->authorizeMeetingAccess($meeting);

        // 2. Validar la respuesta
        $data = $request->validate([
            'result' => 'required|string|in:SaleOrPurchase,Alliance,FollowUp,None',
        ]);

        // 3. Guardar la encuesta
        MeetingSurvey::create([
            'meeting_id' => $meeting->id,
            'user_id' => Auth::id(),
            'result' => $data['result'],
        ]);

        // 4. Redirigir de vuelta a la agenda
        return redirect()->route('participant.meetings.myAgenda')->with('success', '¡Gracias por tus comentarios!');
    }

    /**
     * Función de ayuda para verificar permisos
     */
    private function authorizeMeetingAccess(Meeting $meeting)
    {
        $user = Auth::user();
        $isRequester = $user->registrations()->where('id', $meeting->requester_registration_id)->exists();
        $isReceiver = $user->registrations()->where('id', $meeting->receiver_registration_id)->exists();

        if (!$isRequester && !$isReceiver) {
            abort(403, 'No tienes permiso para gestionar esta reunión.');
        }
    }
}
