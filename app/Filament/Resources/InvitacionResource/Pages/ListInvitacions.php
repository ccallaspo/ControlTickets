<?php

namespace App\Filament\Resources\InvitacionResource\Pages;

use App\Filament\Resources\InvitacionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInvitacions extends ListRecords
{
    protected static string $resource = InvitacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
