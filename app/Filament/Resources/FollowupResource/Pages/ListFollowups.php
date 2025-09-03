<?php

namespace App\Filament\Resources\FollowupResource\Pages;

use App\Filament\Resources\FollowupResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\ExportAction;
use App\Filament\Exports\FollowupExporter; // Asegúrate de usar la nueva ruta

class ListFollowups extends ListRecords
{
    protected static string $resource = FollowupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Crear Ticket'),
            
            // Usa la nueva clase de exportador
            ExportAction::make()
                ->exporter(FollowupExporter::class),
        ];
    }
}