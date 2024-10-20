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
        
        $solicitante = auth()->user()->email;
        $facturador = 'Christian.lillo@otecproyecta.cl';
        $administrtivo = 'Maria.montilla@otecproyecta.cl';
        $soporte = 'soporte@otecproyecta.cl';
        $cotizador = 'contacto@otecproyecta.cl';


        if ($event->name == 'Cotización aprobada') {

            Mail::to($administrtivo)
                ->cc($solicitante)->send(new CotizacionAprobadaMail($data, $myuser));
        }

        if ($event->name == 'Curso agendado') {

            Mail::to($soporte)
                ->cc($solicitante)->send(new CursoAgendadoMail($data, $myuser));
        }

        if ($event->name == 'Curso matriculado') {

            Mail::to($administrtivo)
                ->cc($solicitante)->send(new CursoMatriculadoMail($data, $myuser));
        }

        if ($event->name == 'Curso finalizado') {

            Mail::to($cotizador)
            ->cc($soporte)->cc($solicitante)
            ->send(new CursoFinalizadoMail($data, $myuser));
        }

        if ($event->name == 'DJ OTEC generada') {
            
            Mail::to($administrtivo)
                ->cc($solicitante)->send(new DjOtecMail($data, $myuser));
        }

        if ($event->name == 'DJs generadas') {

            Mail::to($facturador)
                ->cc($solicitante)->send(new DjParticipanteMail($data, $myuser));
        }

        if ($event->name == 'Facturado') {

            Mail::to($cotizador)
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
