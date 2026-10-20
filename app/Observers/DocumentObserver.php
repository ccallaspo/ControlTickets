<?php

namespace App\Observers;

use App\Models\Document;
use App\Services\TicketUpdateNotifier;

class DocumentObserver
{
    public function saved(Document $document): void
    {
        if (! $document->wasRecentlyCreated && ! $document->wasChanged(['document_archive', 'typedocument_id'])) {
            return;
        }

        app(TicketUpdateNotifier::class)->scheduleDocument($document);
    }
}
