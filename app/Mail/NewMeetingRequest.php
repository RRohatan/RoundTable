<?php

namespace App\Mail;

use App\Models\Event;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewMeetingRequest extends Mailable
{
    use Queueable, SerializesModels;

    // Datos que necesita la plantilla
    public $event;
    public $requester; // La persona que ENVÍA la solicitud

    /**
     * Create a new message instance.
     */
    public function __construct(Event $event, User $requester)
    {
        $this->event = $event;
        $this->requester = $requester;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '¡Nueva solicitud de reunión para ' . $this->event->name . '!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Apunta a la vista que crearemos
        return new Content(
            markdown: 'emails.new-meeting-request',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
