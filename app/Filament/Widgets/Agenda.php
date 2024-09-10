<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\CalendarResource;
use App\Models\Calendar;
use App\Models\User;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;


class Agenda extends BaseWidget
{
    protected static ?int $sort = 3;

    protected static ?string $heading = 'Últimas agendas';

    public function table(Table $table): Table
    {
        return $table
        ->query(CalendarResource::getEloquentQuery())
        ->defaultPaginationPageOption(5)
        ->defaultSort('created_at', 'desc')
        ->columns([
            Tables\Columns\TextColumn::make('title')
                    ->label('Asunto')
                    ->searchable(),
                    Tables\Columns\TextColumn::make('participants')
                    ->label('Participantes')
                    ->searchable()
                    ->getStateUsing(function ($record) {
                        // Asegúrate de que $record->participants sea una cadena
                        $participants = is_array($record->participants) ? implode(',', $record->participants) : $record->participants;
                
                        // Ahora explota la cadena de participantes
                        $participantIds = explode(',', $participants);
                        $names = User::whereIn('id', $participantIds)->pluck('name')->toArray();
                
                        return implode(', ', $names);
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Pendiente' => 'danger',
                        'En Proceso' => 'warning',
                        'Completado' => 'success',
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_star')
                    ->label('Fecha de Inicio')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_end')
                    ->label('Fecha de Cierre')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('author')
                    ->label('Creado por')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

        ])
        ->actions([

        ]);
    }

    
}
