<?php

namespace App\Filament\Resources\FollowupResource\Pages;

use App\Filament\Resources\FollowupResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateFollowup extends CreateRecord
{
    protected static string $resource = FollowupResource::class;

    public function getHeading(): string
    {
        return ' ';
    }

    protected function getRedirectUrl(): string {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return FollowupResource::mergeFinanciamientosFromForm($data, $this->form->getRawState());
    }
}
