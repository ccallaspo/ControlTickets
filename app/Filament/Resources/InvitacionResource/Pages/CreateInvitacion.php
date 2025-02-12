<?php

namespace App\Filament\Resources\InvitacionResource\Pages;

use App\Filament\Resources\InvitacionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateInvitacion extends CreateRecord
{
    protected static string $resource = InvitacionResource::class;

    protected function getRedirectUrl(): string {
        return $this->getResource()::getUrl('index');
    }
}
