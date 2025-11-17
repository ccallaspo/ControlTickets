<?php

namespace App\Observers;

use App\Mail\CotizacionAprobadaMail;
use App\Mail\CursoActualizacionMail;
use App\Mail\CursoAgendadoMail;
use App\Mail\CursoCoordinarMail;
use App\Mail\CursoEnProcesoMail;
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
        // dd($data);

        $changes = $followup->getDirty();
        $relevantChanges = array_diff_key($changes, array_flip(['updated_at']));
        $data->changes = $relevantChanges;

        $myuser = auth()->user();

        $solicitante = auth()->user()->email;
        $facturador = 'Christian.lillo@otecproyecta.cl';
        $administrativo = 'coordinacion@otecproyecta.cl';
        $soporte = 'soporte@otecproyecta.cl';
        $cotizador = 'contacto@otecproyecta.cl';


        if ($event->name == 'Cotización actualizada') {

            $ccRecipients = [$cotizador,$solicitante,$administrativo,$soporte];
            Mail::to($solicitante)
               ->cc($ccRecipients)
                //  ->cc($solicitante)
                //  ->cc($administrativo)
                ->send(new CursoActualizacionMail($data, $myuser));
          //  dd($data);
        }



        if ($event->name == 'Coordinar Curso') {

            $ccRecipients = [$cotizador, $solicitante];
            Mail::to($administrativo)
                ->cc($ccRecipients)->send(new CursoCoordinarMail($data, $myuser));
        }


        if ($event->name == 'Matricular Curso') {
            $ccRecipients = [$cotizador, $solicitante];
            Mail::to($soporte)
                ->cc($cotizador)
                ->cc($ccRecipients)->send(new CursoMatriculadoMail($data, $myuser));
        }

        if ($event->name == 'Curso Finalizado') {
            $ccRecipients = [$soporte, $solicitante];
            Mail::to($cotizador)
                ->cc($ccRecipients)
                ->send(new CursoFinalizadoMail($data, $myuser));
        }

        if ($event->name == 'Generar DJ') {
            $ccRecipients = [$cotizador, $solicitante];
            Mail::to($administrativo)
                ->cc($ccRecipients)->send(new DjOtecMail($data, $myuser));
        }


        if ($event->name == 'Por Facturar') {
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
