<?php

namespace App\Mail;

use App\Models\Event;
use App\Models\Meeting;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MeetingRequestConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    public $event;
    public $meeting;
    public $receiver; // La persona que ACEPTÓ

    /**
     * Create a new message instance.
     */
    public function __construct(Event $event, Meeting $meeting, User $receiver)
    {
        $this->event = $event;
        $this->meeting = $meeting;
        $this->receiver = $receiver;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '¡Reunión Confirmada para ' . $this->event->name . '!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.meeting-request-confirmed',
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
