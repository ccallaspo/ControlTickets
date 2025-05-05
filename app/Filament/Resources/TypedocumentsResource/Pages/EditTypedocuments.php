<?php

namespace App\Filament\Resources\TypedocumentsResource\Pages;

use App\Filament\Resources\TypedocumentsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTypedocuments extends EditRecord
{
    protected static string $resource = TypedocumentsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
        
    }

     public function getHeading(): string
    {
        return ' ';
    }
}
