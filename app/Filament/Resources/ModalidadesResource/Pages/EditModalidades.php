<?php

namespace App\Filament\Resources\ModalidadesResource\Pages;

use App\Filament\Resources\ModalidadesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditModalidades extends EditRecord
{
    protected static string $resource = ModalidadesResource::class;

    protected function getRedirectUrl(): string {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
