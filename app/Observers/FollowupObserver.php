<?php

namespace App\Observers;

use App\Models\Event;
use App\Models\Followup;
use App\Models\Task;
use Filament\Notifications\Notification;

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
            ->body("Hemos registrado el ticket #".$followup->name)
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
        $myuser = auth()->user()->name;

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

        $n_proced = Task::where('name', 'Gestión administrativa')->firstOrFail();
        $n_event = Event::where('name', 'Crear agenda')->firstOrFail();
        
        if ($event->name == 'Cotización aprobada') {
            Followup::create([
                'active' => 'Si',
                'name' => $newName,
                'author' => $myuser,
                'referent' => $followup->referent,
                'task_id' => $n_proced->id,
                'event_id' => $n_event->id,
                'customer_id' => $followup->customer_id,
            ]);
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
