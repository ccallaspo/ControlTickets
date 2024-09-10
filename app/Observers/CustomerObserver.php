<?php

namespace App\Observers;

use App\Models\Customer;
use Filament\Notifications\Notification;

class CustomerObserver
{
    /**
     * Handle the Customer "created" event.
     */
    public function created(Customer $customer): void
    {
        $recipient = auth()->user();

        Notification::make()
            ->title('Nuevo cliente')
            ->body("Hemos registrado al cliente " . $customer->name)
            ->icon('heroicon-c-building-office')
            ->iconColor('success')
            ->sendToDatabase($recipient);
    }

    /**
     * Handle the Customer "updated" event.
     */
    public function updated(Customer $customer): void
    {
        //
    }

    /**
     * Handle the Customer "deleted" event.
     */
    public function deleted(Customer $customer): void
    {
        //
    }

    /**
     * Handle the Customer "restored" event.
     */
    public function restored(Customer $customer): void
    {
        //
    }

    /**
     * Handle the Customer "force deleted" event.
     */
    public function forceDeleted(Customer $customer): void
    {
        //
    }
}
