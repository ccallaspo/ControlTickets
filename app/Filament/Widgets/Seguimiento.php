<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\FollowupResource;
use App\Filament\Widgets\Concerns\HasFollowupTicketTable;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class Seguimiento extends BaseWidget
{
    use HasFollowupTicketTable;

    protected static bool $isDiscovered = false;

    protected static ?int $sort = 3;

    protected static ?string $heading = 'Últimos Tickets';

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                FollowupResource::getEloquentQuery()
                    ->with(['ejecutivo', 'event', 'cotizacion.customer', 'customer'])
            )
            ->defaultPaginationPageOption(10)
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->columns($this->followupTicketColumns())
            ->filters($this->followupTicketFilters())
            ->actions($this->followupTicketActions());
    }
}
