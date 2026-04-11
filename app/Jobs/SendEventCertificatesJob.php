<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Models\Event;
use App\Mail\CertificateMail;


class SendEventCertificatesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Event $event) {}

    public function handle(): void
    {
        // Traemos a todos los que asistieron
        $attendees = $this->event->attendances;

        foreach ($attendees as $attendee) {
            // Generamos el PDF para este asistente específico
            $pdf = Pdf::loadView('emails.certificate_pdf', [
                'event' => $this->event,
                'attendee' => $attendee
            ])->setPaper('a4', 'landscape');

            // Enviamos el correo con el PDF adjunto
            Mail::to($attendee->email)->send(new CertificateMail($this->event, $attendee, $pdf->output()));
        }
    }
}
