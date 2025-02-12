<?php

namespace App\Filament\Resources\InvitacionResource\Pages;

use App\Filament\Resources\InvitacionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInvitacion extends EditRecord
{
    protected static string $resource = InvitacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string {
        return $this->getResource()::getUrl('index');
    }
}
