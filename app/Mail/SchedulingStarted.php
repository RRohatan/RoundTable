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

class SchedulingStarted extends Mailable
{
    use Queueable, SerializesModels;

    // Propiedades públicas para pasar datos a la vista
    public $event;
    public $participant;

    /**
     * Create a new message instance.
     */
    public function __construct(Event $event, User $participant)
    {
        $this->event = $event;
        $this->participant = $participant;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '¡El agendamiento ha comenzado para ' . $this->event->name . '!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Apunta a la vista que crearemos en el paso 2
        return new Content(
            markdown: 'emails.scheduling-started',
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
