<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\FollowupResource;
use App\Filament\Widgets\Concerns\HasFollowupTicketTable;
use App\Models\Followup;
use Carbon\Carbon;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class CoursesDjOverdue extends BaseWidget
{
    use HasFollowupTicketTable;

    protected static ?int $sort = 5;

    protected static ?string $heading = 'Generar DJ vencidos';

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $today = Carbon::now()->toDateString();

        return $table
            ->query(
                FollowupResource::getEloquentQuery()
                    ->with(['ejecutivo', 'event', 'cotizacion.customer', 'customer'])
                    ->djOverdue($today)
                    ->orderByRaw(
                        'LEAST(
                            COALESCE(DATE(f_end), \'9999-12-31\'),
                            COALESCE(CASE WHEN has_execution_data = 1 THEN DATE(exec_f_end) END, \'9999-12-31\')
                        ) ASC'
                    )
            )
            ->description('Cursos con fecha de término vencida, en estado Curso Finalizado o Generar DJ.')
            ->defaultPaginationPageOption(5)
            ->striped()
            ->columns($this->followupUpcomingCoursesColumns(
                $today,
                $today,
                fn (Followup $record) => $record->coursesEndedBefore($today)
            ))
            ->filters($this->followupTicketFilters())
            ->actions($this->followupTicketActions());
    }
}
