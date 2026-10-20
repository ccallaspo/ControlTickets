<?php

namespace App\Services;

use App\Mail\CursoActualizacionMail;
use App\Models\Followup;
use App\Models\Document;
use App\Observers\FollowupObserver;
use Illuminate\Support\Facades\Mail;

class TicketUpdateNotifier
{
    private array $pending = [];

    private bool $registered = false;

    public function schedule(Followup $ticket, array $changes, array $documents = []): void
    {
        $actor = auth()->user();
        if (! $actor || ($changes === [] && $documents === [])) {
            return;
        }

        // Capturar los originales antes de que Eloquent los sincronice.
        $originals = [];
        foreach ($changes as $field => $value) {
            $originals[$field] = $ticket->getOriginal($field);
        }

        $enqueue = function () use ($ticket, $changes, $documents, $originals, $actor): void {
            $key = ($ticket->getKey() ?? spl_object_id($ticket)).':'.$actor->getAuthIdentifier();
            $existing = $this->pending[$key] ?? ['changes' => [], 'originals' => [], 'documents' => []];
            $this->pending[$key] = [
                'ticket' => $ticket,
                'actor' => $actor,
                'changes' => array_replace($existing['changes'], $changes),
                'originals' => $existing['originals'] + $originals,
                'documents' => array_replace($existing['documents'], $documents),
            ];

            if (! $this->registered) {
                $this->registered = true;
                app()->terminating(fn () => $this->flush());
            }
        };

        $connection = $ticket->getConnection();
        if ($connection->transactionLevel() > 0) {
            $connection->afterCommit($enqueue);
        } else {
            $enqueue();
        }
    }

    public function scheduleDocument(Document $document): void
    {
        if (! auth()->check() || ! ($ticket = $document->followup)) {
            return;
        }

        $this->schedule($ticket, [], [($document->getKey() ?? spl_object_id($document)) => [
            'label' => $document->wasRecentlyCreated ? 'Documento agregado' : 'Documento actualizado',
            'type' => $document->typedocument?->name ?? 'Documento',
            'file' => basename($document->document_archive ?? ''),
        ]]);
    }

    public function flush(): void
    {
        $pending = $this->pending;
        $this->pending = [];
        foreach ($pending as $update) {
            $this->send($update['ticket'], $update['changes'], $update['originals'], array_values($update['documents']), $update['actor']);
        }
    }

    public function send(Followup $ticket, array $changes, array $originals = [], array $documents = [], $actor = null): void
    {
        $actor ??= auth()->user();

        if (! $actor || ($changes === [] && $documents === [])) {
            return;
        }

        // Recargar la coordinadora: puede haber cambiado en esta misma edición.
        $ticket->load('ejecutivo', 'event', 'note');
        $recipients = $this->recipients($ticket, $actor->email);

        if ($recipients === []) {
            return;
        }

        $ticket->changes = $changes;
        Mail::to($recipients)->send(new CursoActualizacionMail($ticket, $actor, $originals, $documents));
    }

    public function recipients(Followup $ticket, ?string $actorEmail): array
    {
        $responsibleEmails = app(FollowupObserver::class)->responsibleEmails($ticket);

        return collect([$actorEmail, $ticket->ejecutivo?->email, ...$responsibleEmails])
            ->filter(fn ($email) => is_string($email) && filled(trim($email)))
            ->map(fn ($email) => trim($email))
            ->unique(fn ($email) => strtolower($email))
            ->values()
            ->all();
    }
}
