<?php

namespace App\Observers;

use App\Models\Cotizacion;
use App\Models\Course;
use App\Models\Event;
use App\Models\Followup;
use App\Models\Task;
use Filament\Notifications\Notification;

class CotizacionObserver
{

    public function created(Cotizacion $cotizacion): void
    {

        $myuser = auth()->user()->name;

        $course = Course::findOrFail($cotizacion->course_id);

        $prefix = 'SYC-';

        $latestName = Followup::where('name', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->value('name');

        $newNumber = 1;

        if ($latestName) {
            preg_match('/' . preg_quote($prefix, '/') . '(\d+)$/', $latestName, $matches);
            if (!empty($matches[1])) {
                $newNumber = intval($matches[1]) + 1;
            }
        }

        $newName = $prefix . $newNumber;

        
           // $proced = Task::where('name', 'Cotización con franquicia')->firstOrFail();
            //dd($proced);

            $event = Event::where('name', 'Cotización Enviada')
                ->firstOrFail();

            Followup::create([
                'active' => 'Si',
                'name' => $newName,
                'author' => $myuser,
                'referent' => $cotizacion->name,
                'task_id' => 1,
                'event_id' => $event->id,
                'customer_id' => $cotizacion->customer_id,
                'cotizacion_id' => $cotizacion->id,
                'cod_sence_course' => $course->cod_sence,
                'name_course' => $course->name,

            ]);
        

        $recipient = auth()->user();

        Notification::make()
            ->title('Creación de cotización')
            ->body("Hemos registrado la cotización #" . $cotizacion->name)
            ->icon('heroicon-o-document-currency-dollar')
            ->iconColor('success')
            ->sendToDatabase($recipient);
    }


    /**
     * Handle the Cotizacion "updated" event.
     */
    public function updated(Cotizacion $cotizacion): void
    {
        //
    }

    /**
     * Handle the Cotizacion "deleted" event.
     */
    public function deleted(Cotizacion $cotizacion): void
    {
        //
    }

    /**
     * Handle the Cotizacion "restored" event.
     */
    public function restored(Cotizacion $cotizacion): void
    {
        //
    }

    /**
     * Handle the Cotizacion "force deleted" event.
     */
    public function forceDeleted(Cotizacion $cotizacion): void
    {
        //
    }
}
