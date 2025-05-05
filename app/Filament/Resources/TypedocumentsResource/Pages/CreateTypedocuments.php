<?php

namespace App\Filament\Resources\TypedocumentsResource\Pages;

use App\Filament\Resources\TypedocumentsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTypedocuments extends CreateRecord
{
    protected static string $resource = TypedocumentsResource::class;
    

    protected function getRedirectUrl(): string {
        return $this->getResource()::getUrl('index');
    }   

    public function getHeading(): string
    {
        return ' ';
    }
}
