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
                    ->width('100px')
                    ->searchable()
                    ->sortable()
                    ->icon(
                        fn(Followup $record): ?string =>
                        !empty($record->event->icono) ? $record->event->icono : null
                    )
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'Cotización Enviada' => 'Cotización Enviada',
                        'Coordinar Curso' => 'Coordinar Curso',
                        'Matricular Curso' => 'Matricular Curso',
                        'Curso en Proceso' => 'Curso en Proceso',
                        'Curso Finalizado' => 'Curso Finalizado',
                        'Generar DJ' => 'Generar DJ',
                        'Por Facturar' => 'Por Facturar',
                        default => $state,
                    })
                    ->color('white')
                    ->extraAttributes(function (Followup $record) {
                        $color = $record->event?->description;
                        if (!$color) {
                            return [];
                        }
                        return [
                            'style' => "
                                background-color: {$color} !important;
                                border-color: {$color} !important;
                                color: #ffffff !important;
                                padding: 0.375rem 0.75rem !important;
                                border-radius: 0.375rem !important;
                                font-weight: 500 !important;
                                display: inline-flex !important;
                                align-items: center !important;
                                justify-content: center !important;
                                gap: 0.375rem !important;
                                white-space: nowrap !important;
                                text-align: center !important;
                                min-width: fit-content !important;
                            ",
                            'class' => 'custom-badge-colored'
                        ];
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
