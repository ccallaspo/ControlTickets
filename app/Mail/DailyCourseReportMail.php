<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DailyCourseReportMail extends Mailable
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
            subject: 'Reporte Diario de Cursos',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.report_course',
            with: [
                'coursesStartingToday' => $this->coursesStartingToday,
                'coursesEndingToday' => $this->coursesEndingToday,
            ]
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
