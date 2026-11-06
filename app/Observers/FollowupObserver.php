<?php

namespace App\Observers;

use App\Mail\CotizacionAprobadaMail;
use App\Mail\CursoCoordinarMail;
use App\Mail\CursoEnProcesoMail;
use App\Mail\CursoFinalizadoMail;
use App\Mail\CursoMatriculadoMail;
use App\Mail\DjOtecMail;
use App\Mail\PorFacturarMail;
use App\Models\Event;
use App\Models\Followup;
use App\Models\User;
use App\Services\TicketUpdateNotifier;
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
        $changes = $followup->getDirty();
        $relevantChanges = array_diff_key($changes, array_flip(['updated_at']));

        if (!$this->hasMailTriggeringChanges($relevantChanges)) {
            return;
        }

        $event = Event::findOrFail($followup->event_id);
        $data = $followup;
        $data->changes = $relevantChanges;

        $myuser = auth()->user();
        if (!$myuser) {
            return;
        }

        $followup->load('ejecutivo');
        $assignedExecutive = $followup->ejecutivo;
        $actorEmail = $myuser->email ?? '';

        $followup->setRelation('event', $event);


        $notificationRecipients = collect([$assignedExecutive, $myuser])
            ->filter()
            ->unique('id')
            ->values();

        if ($notificationRecipients->isNotEmpty()) {
            Notification::make()
                ->title('Ticket actualizado')
                ->body("El ticket #{$followup->name} fue actualizado")
                ->icon('heroicon-m-pencil-square')
                ->iconColor('info')
                ->sendToDatabase($notificationRecipients);
        }

        // Las ediciones conservando el estado usan el correo de actualización.
        if (! $followup->isDirty('event_id')) {
            app(TicketUpdateNotifier::class)->schedule($followup, $relevantChanges);
            return;
        }

        $notifier = app(TicketUpdateNotifier::class);
        $mailClass = match ($event->name) {
            'Cotización Aprobada' => CotizacionAprobadaMail::class,
            'Coordinar Curso' => CursoCoordinarMail::class,
            'Matricular Curso' => CursoMatriculadoMail::class,
            'Curso en Proceso' => CursoEnProcesoMail::class,
            'Curso Finalizado' => CursoFinalizadoMail::class,
            'Generar DJ' => DjOtecMail::class,
            'Por Facturar' => PorFacturarMail::class,
            default => null,
        };

        if ($mailClass === null) {
            $notifier->schedule($followup, $relevantChanges);
            return;
        }

        $primaryEmails = $notifier->deliveryRecipients($this->responsibleEmails($followup), $actorEmail);
        $ccRecipients = $notifier->deliveryRecipients([$actorEmail, $assignedExecutive?->email], $actorEmail);
        $ccRecipients = array_values(array_udiff($ccRecipients, $primaryEmails, 'strcasecmp'));

        Mail::to($primaryEmails)->cc($ccRecipients)->send(new $mailClass($data, $myuser));
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

    /**
     * Determina si los cambios del ticket deben disparar los correos automáticos por estado.
     */
    private function hasMailTriggeringChanges(array $relevantChanges): bool
    {
        return !empty($relevantChanges);
    }

    /**
     * Responsables originales de los correos por estado. Las actualizaciones y
     * los documentos reutilizan esta misma regla, agregando actor y coordinadora.
     */
    public function responsibleEmails(Followup $followup): array
    {
        $emails = match ($followup->event?->name) {
            'Matricular Curso' => User::whereHas('roles', fn ($query) => $query->where('name', 'Soporte'))
                ->whereNotNull('email')->pluck('email')->all(),
            'Cotización Aprobada' => [config('mail.cotizacion_aprobada_to')],
            'Cotización actualizada', 'Coordinar Curso', 'Curso en Proceso',
            'Curso Finalizado', 'Generar DJ', 'Por Facturar' => [$followup->ejecutivo?->email],
            default => [],
        };

        return collect($emails)
            ->filter(fn ($email) => is_string($email) && filled(trim($email)))
            ->map(fn ($email) => trim($email))
            ->unique(fn ($email) => strtolower($email))
            ->values()->all();
    }
}
