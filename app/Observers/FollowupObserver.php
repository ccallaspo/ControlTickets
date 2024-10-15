<?php

namespace App\Observers;

use App\Mail\CotizacionAprobadaMail;
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
        $myuser = auth()->user()->name;
       // dd($myuser);


        if ($event->name == 'Cotización aprobada') {

            // $recipientEmail = 'contacto@otecproyecta.cl';s
            $recipientEmail = 'cafutrille@gmail.com';


            Mail::to($recipientEmail)->send(new CotizacionAprobadaMail($data,$myuser));
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
