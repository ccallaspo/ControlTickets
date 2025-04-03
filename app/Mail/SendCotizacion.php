<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendCotizacion extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $data; // Datos adicionales para la vista
    private $pdfPath; // Ruta del archivo PDF

    /**
     * Create a new message instance.
     *
     * @param mixed $data Datos adicionales para la vista.
     * @param string $pdfPath Ruta del archivo PDF generado.
     */
    public function __construct($data, $pdfPath)
    {
        $this->data = $data;
        $this->pdfPath = $pdfPath;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'OTEC Proyecta - Cotización #' . $this->data['cotizacion']->name, 
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.sendCotizacion',
            with: [
                'data' => $this->data
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromPath($this->pdfPath)
                ->as("Cotizacion_{$this->data['cotizacion']->name}.pdf") // Nombre del archivo adjunto
                ->withMime('application/pdf'), // Tipo MIME
        ];
    }
}
