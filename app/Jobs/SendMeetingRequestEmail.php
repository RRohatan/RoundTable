<?php

namespace App\Jobs;

use App\Mail\NewMeetingRequest; // Importa el nuevo Mailable
use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendMeetingRequestEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $event;
    protected $requester; // Quién envía
    protected $receiver;  // Quién recibe

    /**
     * Create a new job instance.
     */
    public function __construct(Event $event, User $requester, User $receiver)
    {
        $this->event = $event;
        $this->requester = $requester;
        $this->receiver = $receiver;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Enviar el correo al RECEPTOR
        if ($this->receiver && $this->receiver->email) {
            Mail::to($this->receiver->email)
                ->queue(new NewMeetingRequest($this->event, $this->requester));
        }
    }
}
