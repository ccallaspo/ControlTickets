<?php

namespace App\Filament\Resources\ModalidadesResource\Pages;

use App\Filament\Resources\ModalidadesResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateModalidades extends CreateRecord
{
    protected static string $resource = ModalidadesResource::class;

    protected function getRedirectUrl(): string {
        return $this->getResource()::getUrl('index');
    }
}
