<?php

namespace App\Observers;

use App\Models\Course;
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
    public function deleted(Course $course): void
    {
        //
    }

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
