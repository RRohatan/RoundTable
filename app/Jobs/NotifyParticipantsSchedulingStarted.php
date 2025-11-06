<?php

namespace App\Jobs;

use App\Models\Event;
use App\Mail\SchedulingStarted; // <-- Importa el Mailable
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log; // <-- Para registrar
use Illuminate\Support\Facades\Mail; // <-- Importa Mail

class NotifyParticipantsSchedulingStarted implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $event;

    /**
     * Create a new job instance.
     */
    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // 1. Cargar las inscripciones con la info del participante (usuario)
        $registrations = $this->event->registrations()->with('participant')->get();

        Log::info("Iniciando envío de notificaciones para Evento ID: {$this->event->id}. Total participantes: {$registrations->count()}");

        // 2. Iterar sobre cada inscripción
        foreach ($registrations as $registration) {
            $participant = $registration->participant;

            // 3. Poner el correo en la cola para este participante
            if ($participant && $participant->email) {
                Mail::to($participant->email)
                    ->queue(new SchedulingStarted($this->event, $participant));
            }
        }
    }
}
