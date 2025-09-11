<?php

namespace App\Filament\Resources\FollowupResource\Pages;

use App\Filament\Resources\FollowupResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFollowup extends EditRecord
{
    protected static string $resource = FollowupResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make()
                ->color('primary')
                ->label('Ver')
                ->icon('heroicon-o-eye'),
            // Actions\DeleteAction::make(),
        ];
    }

    public function getHeading(): string
    {
        return 'Editar Ticket';
    }
}
