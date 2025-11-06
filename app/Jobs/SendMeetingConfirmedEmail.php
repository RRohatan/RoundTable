<?php

namespace App\Jobs;

use App\Mail\MeetingRequestConfirmed; // Importa el nuevo Mailable
use App\Models\Event;
use App\Models\Meeting;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendMeetingConfirmedEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $event;
    protected $meeting;
    protected $requester; // Quién SOLICITÓ (y recibirá el email)
    protected $receiver;  // Quién ACEPTÓ

    /**
     * Create a new job instance.
     */
    public function __construct(Event $event, Meeting $meeting, User $requester, User $receiver)
    {
        $this->event = $event;
        $this->meeting = $meeting;
        $this->requester = $requester;
        $this->receiver = $receiver;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Enviar el correo al SOLICITANTE
        if ($this->requester && $this->requester->email) {
            Mail::to($this->requester->email)
                ->queue(new MeetingRequestConfirmed($this->event, $this->meeting, $this->receiver));
        }
    }
}
