<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Meeting;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Jobs\SendMeetingRequestEmail;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendMeetingConfirmedEmail;
class MeetingController extends Controller
{
    /**
     * Almacena una new solicitud de reunión.
     */
    public function store(Request $request)
    {
        // 1. Validar los datos que llegan del formulario
        $data = $request->validate([
            'event_id' => 'required|exists:events,id',
            'receiver_registration_id' => 'required|exists:registrations,id',
        ]);



        // 2. Obtener al solicitante (el usuario logueado)
        $user = Auth::user();

        // 3. Encontrar la inscripción del solicitante para ESTE evento
        $requesterRegistration = Registration::where('user_id', $user->id)
                                             ->where('event_id', $data['event_id'])
                                             ->first();

        if (!$requesterRegistration) {
            return back()->with('error', 'No se encontró tu inscripción para este evento.');
        }

        // 4. (Opcional) Verificar que no pida una cita consigo mismo
        if ($requesterRegistration->id == $data['receiver_registration_id']) {
            return back()->with('error', 'No puedes solicitar una reunión contigo mismo.');
        }

        // 5. (Opcional) Verificar que no exista ya una solicitud
        $existingMeeting = Meeting::where(function($query) use ($requesterRegistration, $data) {
                $query->where('requester_registration_id', $requesterRegistration->id)
                      ->where('receiver_registration_id', $data['receiver_registration_id']);
            })
            ->orWhere(function($query) use ($requesterRegistration, $data) {
                $query->where('requester_registration_id', $data['receiver_registration_id'])
                      ->where('receiver_registration_id', $requesterRegistration->id);
            })
            ->where('event_id', $data['event_id'])
            ->first();

        if ($existingMeeting) {
            return back()->with('error', 'Ya existe una solicitud de reunión con este participante.');
        }

        // 5.5. Validar límite de reuniones del OFERENTE

         // Primero, encontramos la inscripción del receptor
         $receiverRegistration = Registration::find($data['receiver_registration_id']);

         // Si la persona a la que le piden la cita es 'supplier' (oferente)...
         if ($receiverRegistration->role === 'supplier') {
             // Contamos sus reuniones (pendientes o confirmadas)
        $meetingCount = Meeting::where(function($query) use ($receiverRegistration) {
                $query->where('requester_registration_id', $receiverRegistration->id)
                      ->orWhere('receiver_registration_id', $receiverRegistration->id);
            })
            ->whereIn('status', ['pending', 'confirmed'])
            ->count();

        // Si ya tiene 2 o más, bloqueamos la solicitud
        if ($meetingCount >= 2) {
            return back()->with('error', 'Este oferente ya ha alcanzado su límite de reuniones.');
        }
    }


        // 6. Crear la reunión
        $meeting = Meeting::create([
            'event_id' => $data['event_id'],
            'requester_registration_id' => $requesterRegistration->id,
            'receiver_registration_id' => $data['receiver_registration_id'],
            'status' => 'pending',
        ]);

        // 7. (Futuro) Aquí se dispararía la notificación al receptor.

        try {
            $event = $meeting->event;
            $requesterUser = $requesterRegistration->participant; // (El que envía)

            // Necesitamos al usuario receptor
            $receiverUser = Registration::find($data['receiver_registration_id'])
                                        ->participant; // (El que recibe)

            SendMeetingRequestEmail::dispatch($event, $requesterUser, $receiverUser);

        } catch (\Exception $e) {
            // Registrar el error si el correo falla, pero no detener al usuario
            Log::error('Fallo al enviar correo de solicitud: ' . $e->getMessage());
        }

        // 8. Redirigir de vuelta con mensaje de éxito
        return back()->with('success', '¡Solicitud de reunión enviada exitosamente!');
    }

    /**
     * Muestra la lista de solicitudes de reunión RECIBIDAS.
     */
    public function index()
    {
        $user = Auth::user();

        // 1. Encontrar todas las inscripciones del usuario (por si está en varios eventos)
        $userRegistrationIds = $user->registrations()->pluck('id');

        // 2. Buscar todas las reuniones PENDIENTES dirigidas a este usuario
        $pendingMeetings = Meeting::whereIn('receiver_registration_id', $userRegistrationIds)
                                ->where('status', 'pending')
                                ->with('event', 'requester.participant.participantProfile') // Cargar datos del evento y del solicitante
                                ->get();

        // 3. (Opcional) Buscar reuniones ya confirmadas
        $confirmedMeetings = Meeting::where(function($query) use ($userRegistrationIds) {
                                    $query->whereIn('receiver_registration_id', $userRegistrationIds)
                                          ->orWhereIn('requester_registration_id', $userRegistrationIds);
                                })
                                ->where('status', 'confirmed')
                                ->with('event', 'requester.participant.participantProfile', 'receiver.participant.participantProfile')
                                ->get();

        return view('participant.meetings.index', [
            'pendingMeetings' => $pendingMeetings,
            'confirmedMeetings' => $confirmedMeetings,
        ]);
    }

    /**
     * Confirma (Acepta) una solicitud de reunión.
     */
   public function confirm(Meeting $meeting)
    {
        // 1. (Seguridad) Verificar que el usuario actual es el receptor
        $this->authorizeReceiver($meeting);

        // 2. Validar límite de 2 reuniones (si es oferente)
        $receiverRegistration = $meeting->receiver;
        if ($receiverRegistration->role === 'supplier') {
            $meetingCount = Meeting::where(function($query) use ($receiverRegistration) {
                    $query->where('requester_registration_id', $receiverRegistration->id)
                          ->orWhere('receiver_registration_id', $receiverRegistration->id);
                })
                ->where('status', 'confirmed')
                ->count();

            if ($meetingCount >= 2) {
                $meeting->update(['status' => 'rejected']);
                return back()->with('error', 'Has alcanzado tu límite de 2 reuniones. Esta solicitud ha sido rechazada.');
            }
        }

        // 3. ¡LA LÓGICA DE AGENDAMIENTO!
        // Encontrar el próximo horario libre para ambos
        $availableSlot = $this->findNextAvailableSlot($meeting->event, $meeting->requester, $meeting->receiver);

        // 4. Si no hay horarios libres
        if (!$availableSlot) {
            return back()->with('error', 'No se encontró un horario compatible disponible para ambos.');
        }

        // 5. Asignar el horario y una mesa
        $event = $meeting->event;
        $endTime = $availableSlot->copy()->addMinutes($event->meeting_duration_minutes);

        // Asignar un número de mesa (simple, basado en cuántas reuniones ya hay)
        $tableNumber = $event->meetings()->where('status', 'confirmed')->count() + 1;

        $meeting->update([
            'status' => 'confirmed',
            'scheduled_start_time' => $availableSlot,
            'scheduled_end_time' => $endTime,
            'assigned_table' => 'Mesa ' . $tableNumber,
        ]);

        // 6. Notificar al solicitante que su reunión fue aceptada
        try {
            $event = $meeting->event;
            $requesterUser = $meeting->requester->participant; // (El que envió la solicitud)
            $receiverUser = $meeting->receiver->participant;  // (El que aceptó)

            SendMeetingConfirmedEmail::dispatch($event, $meeting, $requesterUser, $receiverUser);

        } catch (\Exception $e) {
            Log::error('Fallo al enviar correo de confirmación: ' . $e->getMessage());
        }

        return back()->with('success', '¡Reunión confirmada! Se ha asignado un horario y una mesa.');
    }

    /**
     * Encuentra el próximo horario disponible para dos participantes.
     */

    private function findNextAvailableSlot(Event $event, Registration $requester, Registration $receiver)
    {
        // 1. Definir el rango de horarios del evento
        $eventStartTime = $event->date->copy()->setTimeFrom($event->start_time);
        $eventEndTime = $event->date->copy()->setTimeFrom($event->end_time);
        $duration = $event->meeting_duration_minutes;

        $potentialSlot = $eventStartTime->copy(); // Empezamos a buscar desde la hora de inicio

        // 2. Recorrer cada "slot" (franja horaria) del evento
        while ($potentialSlot->lt($eventEndTime)) {

            // 3. Revisar si el SOLICITANTE está ocupado en este slot
            $requesterBusy = Meeting::where('event_id', $event->id)
                ->where('status', 'confirmed')
                ->where('scheduled_start_time', $potentialSlot)
                ->where(function ($q) use ($requester) {
                    $q->where('requester_registration_id', $requester->id)
                      ->orWhere('receiver_registration_id', $requester->id);
                })
                ->exists(); // true si está ocupado

            // 4. Revisar si el RECEPTOR está ocupado en este slot
            $receiverBusy = Meeting::where('event_id', $event->id)
                ->where('status', 'confirmed')
                ->where('scheduled_start_time', $potentialSlot)
                ->where(function ($q) use ($receiver) {
                    $q->where('requester_registration_id', $receiver->id)
                      ->orWhere('receiver_registration_id', $receiver->id);
                })
                ->exists(); // true si está ocupado

            // 5. Si AMBOS están libres, ¡encontramos el horario!
            if (!$requesterBusy && !$receiverBusy) {
                return $potentialSlot;
            }

            // 6. Si no, pasar al siguiente slot
            $potentialSlot->addMinutes($duration);
        }

        // 7. Si el bucle termina, no hay horarios libres
        return null;
    }

    /**
     * Rechaza una solicitud de reunión.
     */
    public function reject(Meeting $meeting)
    {
        // 1. (Seguridad) Verificar que el usuario actual es el receptor
        $this->authorizeReceiver($meeting);

        $meeting->update([
            'status' => 'rejected',
        ]);

        // 2. (Futuro) Notificar al solicitante

        return back()->with('success', 'Reunión rechazada.');
    }



    /**
     * Muestra la agenda personal del participante (reuniones confirmadas).
     */
    public function myAgenda()
    {
        $user = Auth::user();

        // 1. Encontrar todas las inscripciones del usuario
        $userRegistrationIds = $user->registrations()->pluck('id');

        // 2. Buscar todas las reuniones confirmadas o en curso del usuario
        $myMeetings = Meeting::where(function($query) use ($userRegistrationIds) {
                                $query->whereIn('receiver_registration_id', $userRegistrationIds)
                                      ->orWhereIn('requester_registration_id', $userRegistrationIds);
                            })
                            ->whereIn('status', ['confirmed', 'in_progress', 'completed']) // Solo las que están en la agenda
                            ->with('event', 'requester.participant.participantProfile', 'receiver.participant.participantProfile')
                            ->orderBy('scheduled_start_time', 'asc') // Ordenar por hora
                            ->get();

        return view('participant.meetings.my-agenda', [
            'myMeetings' => $myMeetings,
        ]);
    }

    /**
     * Cambia el estado de una reunión a "en curso".
     */
    public function start(Meeting $meeting)
    {
        $this->authorizeMeetingAccess($meeting); // Seguridad
        $meeting->update(['status' => 'in_progress']);
        return back()->with('success', 'Reunión marcada como "En Curso".');
    }

    /**
     * Cambia el estado de una reunión a "completada".
     */
    public function complete(Meeting $meeting)
    {
        $this->authorizeMeetingAccess($meeting); // Seguridad
      // ... (dentro del método complete())
    $meeting->update(['status' => 'completed']);

    // ¡Redirigimos al usuario a la encuesta!
    return redirect()->route('participant.survey.show', $meeting)
                     ->with('success', '¡Reunión completada! Por favor, llena la encuesta.');
   }


    /**
     * Cancela una reunión programada.
     */
    public function cancel(Meeting $meeting)
    {
        $this->authorizeMeetingAccess($meeting); // Seguridad
        $meeting->update(['status' => 'cancelled']);
        return back()->with('success', 'Reunión cancelada.');
    }

    /**
     * Función de ayuda para verificar permisos
     * (Esta ya la deberías tener)
     */


    /**
     * AÑADE ESTA NUEVA FUNCIÓN DE SEGURIDAD
     * Verifica que el usuario actual sea parte de la reunión
     */
    private function authorizeMeetingAccess(Meeting $meeting)
    {
        $user = Auth::user();

        $isRequester = $user->registrations()
                            ->where('id', $meeting->requester_registration_id)
                            ->exists();

        $isReceiver = $user->registrations()
                           ->where('id', $meeting->receiver_registration_id)
                           ->exists();

        if (!$isRequester && !$isReceiver) {
            abort(403, 'No tienes permiso para gestionar esta reunión.');
        }
    }

    /**
     * Función de ayuda para verificar permisos
     */
    private function authorizeReceiver(Meeting $meeting)
    {
        $user = Auth::user();
        // Obtiene el ID de la inscripción del receptor
        $receiverRegistrationId = $meeting->receiver_registration_id;
        // Verifica que el usuario logueado sea dueño de esa inscripción
        if (!$user->registrations()->where('id', $receiverRegistrationId)->exists()) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }
    }


}
