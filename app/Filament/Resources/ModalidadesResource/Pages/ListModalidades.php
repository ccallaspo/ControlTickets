<?php

namespace App\Filament\Resources\ModalidadesResource\Pages;

use App\Filament\Resources\ModalidadesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListModalidades extends ListRecords
{
    protected static string $resource = ModalidadesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
