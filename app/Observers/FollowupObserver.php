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
        $assignedExecutiveEmail = $assignedExecutive?->email;
        $actorEmail = $myuser->email ?? '';

        $followup->setRelation('event', $event);

        $isMatricularCurso = $event->name == 'Matricular Curso';
        $isCotizacionAprobada = $event->name == 'Cotización Aprobada';

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

        $responsibleEmails = $this->responsibleEmails($followup);
        $ccRecipients = $this->copyRecipients($followup, $actorEmail, $responsibleEmails);

        if ($isCotizacionAprobada) {
            $aprobadaTo = (string) config('mail.cotizacion_aprobada_to');

            if (filled($aprobadaTo)) {
                Mail::to($responsibleEmails)
                    ->cc($ccRecipients)
                    ->send(new CotizacionAprobadaMail($data, $myuser));
            }
        }

        if ($event->name == 'Cotización actualizada' && filled($assignedExecutiveEmail)) {
            app(TicketUpdateNotifier::class)->schedule($followup, $relevantChanges);
        }

        if ($event->name == 'Coordinar Curso' && filled($assignedExecutiveEmail)) {
            Mail::to($responsibleEmails)
                ->cc($ccRecipients)
                ->send(new CursoCoordinarMail($data, $myuser));
        }

        if ($isMatricularCurso) {
            $supportEmails = $responsibleEmails;

            if (empty($supportEmails)) {
                return;
            }

            Mail::to($supportEmails)
                ->cc($ccRecipients)
                ->send(new CursoMatriculadoMail($data, $myuser));
        }

        if ($event->name == 'Curso en Proceso' && filled($assignedExecutiveEmail)) {
            Mail::to($responsibleEmails)
                ->cc($ccRecipients)
                ->send(new CursoEnProcesoMail($data, $myuser));
        }

        if ($event->name == 'Curso Finalizado' && filled($assignedExecutiveEmail)) {
            Mail::to($responsibleEmails)
                ->cc($ccRecipients)
                ->send(new CursoFinalizadoMail($data, $myuser));
        }

        if ($event->name == 'Generar DJ' && filled($assignedExecutiveEmail)) {
            Mail::to($responsibleEmails)
                ->cc($ccRecipients)
                ->send(new DjOtecMail($data, $myuser));
        }

        if ($event->name == 'Por Facturar' && filled($assignedExecutiveEmail)) {
            Mail::to($responsibleEmails)
                ->cc($ccRecipients)
                ->send(new PorFacturarMail($data, $myuser));
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

    private function copyRecipients(Followup $followup, ?string $actorEmail, array $primaryEmails): array
    {
        $primaryEmails = array_map('strtolower', $primaryEmails);

        return collect([$actorEmail, $followup->ejecutivo?->email])
            ->filter(fn ($email) => is_string($email) && filled(trim($email)))
            ->map(fn ($email) => trim($email))
            ->unique(fn ($email) => strtolower($email))
            ->reject(fn ($email) => in_array(strtolower($email), $primaryEmails, true))
            ->values()->all();
    }
}
