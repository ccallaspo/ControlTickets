<?php

namespace App\Filament\Resources\FollowupResource\Pages;

use App\Filament\Resources\FollowupResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewFollowup extends ViewRecord
{
    protected static string $resource = FollowupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function getHeading(): string
    {
        return 'Ver Ticket ';
    }
}
