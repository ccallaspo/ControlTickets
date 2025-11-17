<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CursoActualizacionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $myuser;


    /**
     * Create a new message instance.
     */
    public function __construct($data, $myuser)
    {
        $this->data = $data;
        $this->myuser = $myuser;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Notificación - Actualización en Curso',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.curso_actualizado',
            with: [
                'data' => $this->data,
                'myuser' => $this->myuser,
            ],
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
