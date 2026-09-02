<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\FollowupResource;
use App\Filament\Widgets\Concerns\HasFollowupTicketTable;
use App\Models\Followup;
use Carbon\Carbon;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class CoursesToStart extends BaseWidget
{
    use HasFollowupTicketTable;

    protected static ?int $sort = 3;

    protected static ?string $heading = 'Cursos por Iniciar';

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $startDate = Carbon::now()->toDateString();
        $endDate = Carbon::now()->addDays(5)->toDateString();

        return $table
            ->query(
                FollowupResource::getEloquentQuery()
                    ->with(['ejecutivo', 'event', 'cotizacion.customer', 'customer'])
                    ->startingBetween($startDate, $endDate)
                    ->orderByRaw(
                        'LEAST(
                            COALESCE(DATE(f_star), \'9999-12-31\'),
                            COALESCE(CASE WHEN has_execution_data = 1 THEN DATE(exec_f_star) END, \'9999-12-31\')
                        ) ASC'
                    )
            )
            ->defaultPaginationPageOption(5)
            ->striped()
            ->columns($this->followupUpcomingCoursesColumns(
                $startDate,
                $endDate,
                fn (Followup $record) => $record->coursesStartingBetween($startDate, $endDate)
            ))
            ->filters($this->followupTicketFilters())
            ->actions($this->followupTicketActions());
    }
}
