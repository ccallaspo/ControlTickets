<?php

namespace App\Observers;

use App\Models\Course;
use Exception;
use Filament\Notifications\Notification;

class CourseObserver
{
    /**
     * Handle the Course "created" event.
     */
    public function created(Course $course): void
    {
        $recipient = auth()->user();

        Notification::make()
            ->title('Nuevo curso')
            ->body("Hemos registrado el curso " . $course->name)
            ->icon('heroicon-o-academic-cap')
            ->iconColor('success')
            ->sendToDatabase($recipient);
    }

    /**
     * Handle the Course "updated" event.
     */
    public function updated(Course $course): void
    {
        //
    }

    /**
     * Handle the Course "deleted" event.
     */
        // public function deleting(Course $course): void
        // {
        //     if ($course->cotizacion()->exists()) {
        //         // Lanza una excepción con un mensaje personalizado
        //         throw new Exception('No se puede eliminar el curso porque tiene cotizaciones relacionadas.');
        //     }
        // }

    /**
     * Handle the Course "restored" event.
     */
    public function restored(Course $course): void
    {
        //
    }

    /**
     * Handle the Course "force deleted" event.
     */
    public function forceDeleted(Course $course): void
    {
        //
    }
}
