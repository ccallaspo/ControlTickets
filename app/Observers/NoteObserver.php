<?php

namespace App\Observers;

use App\Models\Followup;
use App\Models\Note;
use Filament\Notifications\Notification;

class NoteObserver
{
    /**
     * Handle the Note "created" event.
     */
    public function created(Note $note): void
    {
        $recipient = auth()->user();

        $followup = Followup::findOrFail($note->followup_id);

        Notification::make()
            ->title('Nueva Nota')
            ->body("Hemos registrado una nota en el ticket #" . $followup->name)
            ->icon('heroicon-s-clipboard-document-list')
            ->iconColor('success')
            ->sendToDatabase($recipient);
    }

    /**
     * Handle the Note "updated" event.
     */
    public function updated(Note $note): void
    {
        //
    }

    /**
     * Handle the Note "deleted" event.
     */
    public function deleted(Note $note): void
    {
        //
    }

    /**
     * Handle the Note "restored" event.
     */
    public function restored(Note $note): void
    {
        //
    }

    /**
     * Handle the Note "force deleted" event.
     */
    public function forceDeleted(Note $note): void
    {
        //
    }
}
