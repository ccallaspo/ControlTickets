<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DailyOperationsReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $coursesStartingToday;
    public $coursesEndingToday;

    /**
     * Create a new message instance.
     */
    public function __construct($coursesStartingToday, $coursesEndingToday)
    {
        $this->coursesStartingToday = $coursesStartingToday;
        $this->coursesEndingToday = $coursesEndingToday;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            // CAMBIO 1: Asunto específico para operaciones
            subject: 'Reporte Diario de Cursos - Operaciones',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            // CAMBIO 2: Apunta a la nueva vista que creamos en el paso anterior
            view: 'mails.report_course_operations', 
            with: [
                'coursesStartingToday' => $this->coursesStartingToday,
                'coursesEndingToday' => $this->coursesEndingToday,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}