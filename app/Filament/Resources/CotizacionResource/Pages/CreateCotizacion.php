<?php

namespace App\Filament\Resources\CotizacionResource\Pages;

use App\Filament\Resources\CotizacionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCotizacion extends CreateRecord
{
    protected static string $resource = CotizacionResource::class;
    
    public function getHeading(): string
    {
        return '  ';
    }

    protected function getRedirectUrl(): string {
        return $this->getResource()::getUrl('index');
    }
    
}
