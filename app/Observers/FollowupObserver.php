<?php

namespace App\Observers;

use App\Mail\CotizacionAprobadaMail;
use App\Mail\CursoActualizacionMail;
use App\Mail\CursoAgendadoMail;
use App\Mail\CursoCoordinarMail;
use App\Mail\CursoEnProcesoMail;
use App\Mail\CursoFinalizadoMail;
use App\Mail\CursoMatriculadoMail;
use App\Mail\DjOtecMail;
use App\Mail\DjParticipanteMail;
use App\Mail\PorFacturarMail;
use App\Models\Event;
use App\Models\Followup;
use App\Models\Task;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;
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

        // La asignación de coordinadora se manejará con notificaciones propias más adelante.
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

        $followup->loadMissing('ejecutivo');
        $assignedExecutive = $followup->ejecutivo;
        $assignedExecutiveEmail = $assignedExecutive?->email;

        $isMatricularCurso = $event->name == 'Matricular Curso';

        if (!$isMatricularCurso && empty($assignedExecutiveEmail)) {
            return;
        }

        $ccRecipients = $this->buildCcRecipients($assignedExecutiveEmail, $myuser->email);

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


        if ($event->name == 'Cotización actualizada') {
            Mail::to($assignedExecutiveEmail)
                ->cc($ccRecipients)
                ->send(new CursoActualizacionMail($data, $myuser));
        }



        if ($event->name == 'Coordinar Curso') {
            Mail::to($assignedExecutiveEmail)
                ->cc($ccRecipients)
                ->send(new CursoCoordinarMail($data, $myuser));
        }


        if ($event->name == 'Matricular Curso') {
            $supportEmails = User::query()
                ->whereHas('roles', function ($query) {
                    $query->where('name', 'Soporte');
                })
                ->whereNotNull('email')
                ->pluck('email')
                ->unique()
                ->values()
                ->all();

            if (empty($supportEmails)) {
                return;
            }

            $matricularCc = $this->buildCcRecipientsFromMany($supportEmails, $myuser->email);

            Mail::to($supportEmails)
                ->cc($matricularCc)
                ->send(new CursoMatriculadoMail($data, $myuser));
        }

        if ($event->name == 'Curso Finalizado') {
            Mail::to($assignedExecutiveEmail)
                ->cc($ccRecipients)
                ->send(new CursoFinalizadoMail($data, $myuser));
        }

        if ($event->name == 'Generar DJ') {
            Mail::to($assignedExecutiveEmail)
                ->cc($ccRecipients)
                ->send(new DjOtecMail($data, $myuser));
        }


        if ($event->name == 'Por Facturar') {
            Mail::to($assignedExecutiveEmail)
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
     * Excluye ejecutivo_id (coordinadora), reservado para notificaciones personalizadas.
     */
    private function hasMailTriggeringChanges(array $relevantChanges): bool
    {
        $mailTriggeringChanges = array_diff_key(
            $relevantChanges,
            array_flip(['ejecutivo_id'])
        );

        return !empty($mailTriggeringChanges);
    }

    /**
     * Agrega en copia al usuario que actualiza, evitando duplicados.
     */
    private function buildCcRecipients(string $primaryEmail, string $actorEmail): array
    {
        return collect([$actorEmail])
            ->filter(fn(string $email) => !empty($primaryEmail) ? $email !== $primaryEmail : true)
            ->unique()
            ->values()
            ->all();
    }

    /**
     * Agrega en copia al usuario que actualiza si no está entre destinatarios.
     */
    private function buildCcRecipientsFromMany(array $primaryEmails, string $actorEmail): array
    {
        return collect([$actorEmail])
            ->filter(fn(string $email) => !in_array($email, $primaryEmails, true))
            ->unique()
            ->values()
            ->all();
    }
}
