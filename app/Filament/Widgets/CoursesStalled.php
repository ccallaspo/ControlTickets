<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\FollowupResource;
use App\Filament\Widgets\Concerns\HasFollowupTicketTable;
use App\Models\Followup;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class CoursesStalled extends BaseWidget
{
    use HasFollowupTicketTable;

    protected static ?int $sort = 6;

    protected static ?string $heading = 'Tickets estancados';

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                FollowupResource::getEloquentQuery()
                    ->with(['ejecutivo', 'event', 'cotizacion.customer', 'customer'])
                    ->stalled(7)
                    ->orderBy('updated_at')
            )
            ->description('Sin actualización hace 7 días o más, desde Cotización Aprobada hasta Por Facturar.')
            ->defaultPaginationPageOption(5)
            ->striped()
            ->columns($this->followupUpcomingCoursesColumns(
                '',
                '',
                fn (Followup $record) => $record->displayCourses(),
                showLastUpdate: true
            ))
            ->filters($this->followupTicketFilters())
            ->actions($this->followupTicketActions());
    }
}
