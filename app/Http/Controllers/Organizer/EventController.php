<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Meeting; // <-- AÑADE ESTE USE
use App\Models\MeetingSurvey; // <-- AÑADE ESTE USE
use App\Jobs\NotifyParticipantsSchedulingStarted;
class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('organizer.events.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validación de los campos
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date|after_or_equal:today',
            'location' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'meeting_duration_minutes' => 'required|integer|min:5',
            'supplier_limit' => 'required|integer|min:1',
            'registration_deadline' => 'required|date|before:date',
        ]);

        // 2. Añadir los datos que el sistema genera
        $validatedData['user_id'] = Auth::id(); // Asigna el ID del organizador logueado
        $validatedData['registration_link'] = Str::uuid(); // Genera un link único

        // 3. Crear el Evento
        Event::create($validatedData);

        // 4. Redirigir a una ruta (ej. el dashboard del organizador)
        // Reemplaza 'organizer.dashboard' si tienes una lista de eventos
        return redirect()->route('dashboard')
                         ->with('success', '¡Evento creado exitosamente!');
    }


    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        // 1. (Seguridad) Asegurarnos de que el organizador solo vea sus eventos
        if ($event->user_id !== Auth::id()) {
            abort(403);
        }

        // 2. Cargar todas las relaciones necesarias eficientemente
        $event->load('registrations', 'meetings.surveys');

        // 3. MÉTRICAS DE INSCRIPCIÓN
        $registrations = $event->registrations;
        $supplierCount = $registrations->where('role', 'supplier')->count();
        $buyerCount = $registrations->where('role', 'buyer')->count();

        // 4. MÉTRICAS DE REUNIONES
        $meetings = $event->meetings;
        $totalMeetings = $meetings->count();
        $confirmedMeetings = $meetings->where('status', 'confirmed')->count();
        $completedMeetings = $meetings->where('status', 'completed')->count();
        $pendingMeetings = $meetings->where('status', 'pending')->count();

        // 5. MÉTRICAS DE RESULTADOS (ENCUESTAS)
        // Obtenemos todas las encuestas de todas las reuniones de este evento
        $surveyResults = MeetingSurvey::whereIn('meeting_id', $meetings->pluck('id'))
                            ->get()
                            ->groupBy('result') // Agrupar por 'SaleOrPurchase', 'Alliance', etc.
                            ->map->count(); // Contar cuántos hay de cada uno

        return view('organizer.events.show', [
            'event' => $event,
            'supplierCount' => $supplierCount,
            'buyerCount' => $buyerCount,
            'totalMeetings' => $totalMeetings,
            'confirmedMeetings' => $confirmedMeetings,
            'completedMeetings' => $completedMeetings,
            'pendingMeetings' => $pendingMeetings,
            'surveyResults' => $surveyResults,
        ]);

    }

    public function edit(Event $event)
    {
        //
    }


    public function update(Request $request, Event $event)
    {
        //
    }

    public function destroy(Event $event)
    {
        //
    }


    public function updateStatus(Request $request, Event $event)
    {
       // 1. Validar el nuevo estado
        $validated = $request->validate([
            'status' => 'required|string|in:RegistrationOpen,SchedulingActive,InProgress,Finished',
        ]);

        // 2. Actualizar el evento
        $event->update([
            'status' => $validated['status']
        ]);

        // 3. ¡AQUÍ ESTÁ LA MAGIA!
        // Si el estado es 'SchedulingActive', disparamos el Job
        if ($validated['status'] === 'SchedulingActive') {
            NotifyParticipantsSchedulingStarted::dispatch($event);
        }

        return back()->with('success', '¡Estado del evento actualizado!');
    }
}
