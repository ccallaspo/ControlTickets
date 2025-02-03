<?php

namespace App\Filament\Widgets;

use App\Models\Followup;
use Carbon\Carbon;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class CoursesToEnd extends BaseWidget
{
    protected static ?int $sort = 5;
    protected static ?string $heading = 'Cursos por Finalizar'; 
    protected int | string | array $columnSpan = 'full';
    
   

    public function table(Table $table): Table
    {
        $startDate = Carbon::now()->toDateString(); // Fecha actual (YYYY-MM-DD)
        $endDate = Carbon::now()->addDays(5)->toDateString(); // Fecha 5 días después (YYYY-MM-DD)

        return $table
            ->query(
                Followup::query()
                    ->whereRaw('DATE(f_end) BETWEEN ? AND ?', [$startDate, $endDate]) // Filtra por fecha de finalización
            )
            ->columns([
                Tables\Columns\TextColumn::make('f_end')
                ->label('F. Finalización')
                ->date('d/m/Y')
                ->sortable(),
                Tables\Columns\TextColumn::make('referent')
                    ->label('Cotización')
                    ->sortable()
                    ->searchable(),
               
                Tables\Columns\TextColumn::make('name_course')
                    ->label('Nombre del Curso')
                    ->sortable(),
            ])
            ->defaultSort('f_end', 'asc')
            ->recordUrl(
                fn (Followup $record): string => url('admin/followups/' . $record->id . '/edit'), // Usando la ruta que funciona
                )
            ->defaultPaginationPageOption(5);

    }
}
