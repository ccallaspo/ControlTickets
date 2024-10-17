<?php

namespace App\Observers;

use App\Mail\CotizacionAprobadaMail;
use App\Mail\CursoAgendadoMail;
use App\Mail\CursoFinalizadoMail;
use App\Mail\CursoMatriculadoMail;
use App\Mail\DjOtecMail;
use App\Mail\DjParticipanteMail;
use App\Mail\PorFacturarMail;
use App\Models\Event;
use App\Models\Followup;
use App\Models\Task;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Mail;

class FollowupObserver
{
    /**
     * Handle the Followup "created" event.
     */
    public function created(Followup $followup): void
    {

        $recipient = auth()->user();
        Notification::make()
            ->title('Nuevo ticket')
            ->body("Hemos registrado el ticket #" . $followup->name)
            ->icon('heroicon-m-shield-check')
            ->iconColor('success')
            ->sendToDatabase($recipient);
    }

    /**
     * Handle the Followup "updated" event.
     */
    public function updated(Followup $followup): void
    {
        //data original
        $event = Event::findOrFail($followup->event_id);
        $data = $followup;
        $myuser = auth()->user();
        //dd($event->name);


        if ($event->name == 'Cotización aprobada') {

            // Correo a maria Jose
            $solicitante = auth()->user()->email;
            $recipientEmail = 'contacto@otecproyecta.cl';

            Mail::to($recipientEmail)
                ->cc($solicitante)->send(new CotizacionAprobadaMail($data, $myuser));
        }

        if ($event->name == 'Curso agendado') {

            //Correo a sebas
            $solicitante = auth()->user()->email;
            $recipientEmail = 'contacto@otecproyecta.cl';

            Mail::to($recipientEmail)
                ->cc($solicitante)->send(new CursoAgendadoMail($data, $myuser));
        }

        if ($event->name == 'Curso matriculado') {

            //Correo a Maria comienza seguimiento
            $solicitante = auth()->user()->email;
            $recipientEmail = 'contacto@otecproyecta.cl';

            Mail::to($recipientEmail)
                ->cc($solicitante)->send(new CursoMatriculadoMail($data, $myuser));
        }

        if ($event->name == 'Curso finalizado') {

            //Correo a yasna debe firmar DJO
            //Correo a sebas debe enviar informes
            $solicitante = auth()->user()->email;
            $recipientEmail = 'contacto@otecproyecta.cl';

            Mail::to($recipientEmail)
                ->cc($solicitante)->send(new CursoFinalizadoMail($data, $myuser));
        }

        if ($event->name == 'DJ OTEC generada') {
            
            //Correo a Maria Jose 
            $solicitante = auth()->user()->email;
            $recipientEmail = 'contacto@otecproyecta.cl';

            Mail::to($recipientEmail)
                ->cc($solicitante)->send(new DjOtecMail($data, $myuser));
        }

        if ($event->name == 'DJs generadas') {

            //Correo a Cristian
            $solicitante = auth()->user()->email;
            $recipientEmail = 'contacto@otecproyecta.cl';

            Mail::to($recipientEmail)
                ->cc($solicitante)->send(new DjParticipanteMail($data, $myuser));
        }

        if ($event->name == 'Por facturar') {

            $solicitante = auth()->user()->email;
            $recipientEmail = 'contacto@otecproyecta.cl';

            Mail::to($recipientEmail)
                ->cc($solicitante)->send(new PorFacturarMail($data, $myuser));
        }
    }

    /**
     * Handle the Followup "deleted" event.
     */
    public function deleted(Followup $followup): void
    {
        //
    }

    /**
     * Handle the Followup "restored" event.
     */
    public function restored(Followup $followup): void
    {
        //
    }

    /**
     * Handle the Followup "force deleted" event.
     */
    public function forceDeleted(Followup $followup): void
    {
        //
    }
}
