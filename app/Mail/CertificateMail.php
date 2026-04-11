<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Event;
use App\Models\Attendance; // Ajustado al nombre del modelo que creamos

class CertificateMail extends Mailable
{
    use Queueable, SerializesModels;

    // 1. Declaramos las propiedades públicas
    public Event $event;
    public Attendance $attendance;
    public string $pdfContent;

    /**
     * 2. Recibimos los datos al instanciar el correo
     */
    public function __construct(Event $event, Attendance $attendance, string $pdfContent)
    {
        $this->event = $event;
        $this->attendance = $attendance;
        $this->pdfContent = $pdfContent;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            // Un asunto más dinámico y profesional
            subject: 'Tu Certificado de Asistencia - ' . $this->event->title,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            // 3. Cambiamos 'view.name' por una vista real para el CUERPO del correo
            view: 'emails.certificate_body',
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
            // 4. Ahora $this->pdfContent y $this->event sí tienen los datos correctos
            Attachment::fromData(fn() => $this->pdfContent, 'Certificado_' . $this->event->title . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
