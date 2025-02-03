<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\FollowupResource;
use App\Models\Followup;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class Seguimiento extends BaseWidget
{
    protected static ?int $sort = 3;
    protected static ?string $heading = 'Últimos Tickets';
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(FollowupResource::getEloquentQuery())
            ->defaultPaginationPageOption(10)
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('SYC')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('referent')
                    ->label('Ref. Cotización')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('author')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Creado por')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('event.name')
                    ->label('Estado')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->icon(
                        fn(Followup $record): ?string =>
                        !empty($record->event->icono) ? $record->event->icono : null
                    )
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'Cotización enviada' => 'Cotización enviada',
                        'Cotización aprobada' => 'Cotización aprobada',
                        'Curso agendado' => 'Curso agendado',
                        'Curso matriculado' => 'Curso matriculado',
                        'Curso en proceso' => 'Curso en proceso',
                        'Curso finalizado' => 'Curso finalizado',
                        'DJ OTEC generada' => 'DJ OTEC generada',
                        'DJs generadas' => 'DJs generadas',
                        'Por facturar' => 'Por facturar',
                        default => $state,
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'Cotización enviada' => 'danger',
                        'Cotización aprobada' => 'success',
                        'Curso agendado' => 'primary',
                        'Curso matriculado' => 'info',
                        'Curso en proceso' => 'primary',
                        'Curso finalizado' => 'success',
                        'DJ OTEC generada' => 'success',
                        'DJs generadas' => 'success',
                        'Por facturar' => 'warning',
                        default => 'warning',
                    }),

                Tables\Columns\TextColumn::make('name_course')
                    ->label('Curso')
                    ->sortable()
                   // ->wrap()
                    ->size('sm')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Cliente')
                    ->wrap()
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('F. Creac.')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('F. Ult. Act.')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('event.name')
                    ->label('Estados')
                    ->indicator('Estado')
                    ->relationship('event', 'name')
            ])
            ->actions([
                Tables\Actions\Action::make('Modificar')
                    ->label('')
                    ->icon('heroicon-s-pencil-square')
                    ->url(fn(Followup $record): string => url('admin/followups/' . $record->id . '/edit')),
            ]);
    }
}
