<?php

namespace App\Observers;

use App\Models\Calendar;

class CalendarObserver
{
    /**
     * Handle the Calendar "created" event.
     */
    public function created(Calendar $calendar): void
    {
        //
    }

    /**
     * Handle the Calendar "updated" event.
     */
    public function updated(Calendar $calendar): void
    {
        //
    }

    /**
     * Handle the Calendar "deleted" event.
     */
    public function deleted(Calendar $calendar): void
    {
        //
    }

    /**
     * Handle the Calendar "restored" event.
     */
    public function restored(Calendar $calendar): void
    {
        //
    }

    /**
     * Handle the Calendar "force deleted" event.
     */
    public function forceDeleted(Calendar $calendar): void
    {
        //
    }
}
