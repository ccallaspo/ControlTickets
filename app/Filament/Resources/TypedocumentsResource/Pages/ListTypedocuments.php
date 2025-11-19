<?php

namespace App\Filament\Resources\TypedocumentsResource\Pages;

use App\Filament\Resources\TypedocumentsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTypedocuments extends ListRecords
{
    protected static string $resource = TypedocumentsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Tipo Documento')
                ->color('success')
                ->icon('heroicon-o-plus'),
        ];
    }
}
