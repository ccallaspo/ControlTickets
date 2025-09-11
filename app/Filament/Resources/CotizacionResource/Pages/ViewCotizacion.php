<?php

namespace App\Filament\Resources\CotizacionResource\Pages;

use App\Filament\Resources\CotizacionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCotizacion extends ViewRecord
{
    protected static string $resource = CotizacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('Editar')
                ->color('warning')
                ->icon('heroicon-m-wrench-screwdriver'),
            Actions\Action::make('download')
                ->label('Descargar')
                ->url(fn($record) => route('pdf.download', $record->id))
                ->openUrlInNewTab(true)
        ];
    }

    public function getHeading(): string
    {
        return 'Ver Cotización';
    }
}
